<?php


namespace Selfreliance\BitGo;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use GuzzleHttp\Client;

class BitGoBase {
    /**
     * For make validations
     */
    use ValidatesRequests;

    /**
     * @var Client base Guzzle Client
     */
    public $client;

    /**
     * @var array header request
     */
    public $header;

    /**
     * @var $memo
     * description pay
     */
    protected $memo;

    /**
     * BitGo constructor.
     */
    public function __construct(){
        $this->client = new Client([
            'base_uri' => config()->get('bitgo.bitgo_server')
        ]);
        $this->header = [
            'Authorization' => 'Bearer '.config()->get('bitgo.token'),
            'Accept'        => 'application/x-www-form-urlencoded'
        ];
    }

    /**
     * @param $memo
     *
     * @return $this
     */
    public function memo($memo){
        $this->memo = $memo;
        return $this;
    }

    /**
     * @param $unit
     *
     * @return mixed
     * @throws \Exception
     */
    public function addWalletWebhook($unit){
        $resp = $this->call_api('POST',$unit,'webhooks',[
            'type' => 'transaction',
            'url'  => 'https://devfutures.com'
        ]);
        return $resp;
    }

    /**
     * @param $unit
     * @param $transfer
     * @return mixed
     * @throws \Exception
     */
    public function getInfoByTransferId($unit, $transfer){
        $resp = $this->call_api('GET',$unit,'transfer/'.$transfer);
        return $resp;
    }

    /**
     * @param $unit
     * @return mixed
     * @throws \Exception
     */
    public function getListTransfersReceive($unit){
        $resp = $this->call_api('GET',$unit,'transfer/', [
            'state' => 'confirmed',
            'type' => 'receive'
        ]);
        return $resp;
    }

    /**
     * @param $unit
     * @throws \Exception
     */
    public function listWalletWebhooks($unit){
        $resp = $this->call_api('GET',$unit,'webhooks');
        dd($resp);
    }

    public function getWebhookPayload(Request $request){
        \Log::info('WebHook',[$request->all()]);
    }

    public function validateIPNRequest(Request $request){
        return $this->check_transaction($request->all(),$request->server(),$request->headers);
    }

    function check_transaction(array $request,array $server,$headers = []){
        return true;
    }

    public function validateIPN(array $post_data,array $server_data){
        return true;
    }

    function cancel_payment(Request $request){

    }

    /**
     * @param string $method
     * @param string $unit
     * @param string $end_point
     * @param array $options
     * @param bool $need_wallet_id
     *
     * @return mixed
     * @throws \Exception
     */
    public function call_api(string $method,string $unit,string $end_point,array $options = [],$need_wallet_id = true){
        $unit = strtolower($unit);
        $wallet_id = config()->get('bitgo.wallet_id_'.$unit);
        try {
            $fields = ($method == 'GET') ? 'query' : 'json';
            $opt = [
                $fields    => $options,
                'headers' => $this->header
            ];
            $url_wallet_id = ($need_wallet_id) ? '/'.$wallet_id.'/' : '/';
            $url = $unit.'/wallet'.$url_wallet_id.$end_point;
            $response = $this->client->request($method,$url,$opt);
            $body = $response->getBody();

            $resp = json_decode($body->getContents());
            return $resp;
        }catch(RequestException $e) {
            $response = $e->getResponse();
            if($response){
                $responseBodyAsString = $response->getBody()->getContents();
                throw new \Exception($responseBodyAsString);
            }
            throw new \Exception($e->getMessage());
        } catch(GuzzleException $e) {
            throw new \Exception($e->getMessage());
        }
    }
}