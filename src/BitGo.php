<?php


namespace Selfreliance\BitGo;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use GuzzleHttp\Client;

class BitGo {
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
     * @var $memo description pay
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
     * @param string $unit
     *
     * @return string
     */
    public function balance($unit = "USD"){
        $resp = $this->call_api('GET',$unit,'balances',[],false);
        $points = 100000000;
        $after = 8;
        if($unit == 'XLM'){
            $points = 10000000;
            $after = 7;
        }

        $balance = number($resp->balanceString / $points,$after);
        $confirmedBalance = number($resp->confirmedBalanceString / $points,$after);
        $spendableBalance = number($resp->spendableBalanceString / $points,$after);

        return 'Balance '.$balance." | Confirmed ".$confirmedBalance.' | Spendable '.$spendableBalance;
    }

    /**
     * @param        $payment_id
     * @param        $sum
     * @param string $unit
     *
     * @return \stdClass
     */
    public function form($payment_id,$sum,$unit = 'USD'){
        $resp = $this->call_api('POST',$unit,'address',['label' => $payment_id.', '.$sum]);
        dd($resp);
        $PassData = new \stdClass();
        $PassData->address = $resp->address;
        $PassData->another_site = false;
        return $PassData;
    }

    /**
     * @param $address_and_amount
     * @param $unit
     */
    public function send_multi($address_and_amount,$unit){
        $recipients = [];
        foreach($address_and_amount as $row) {
            array_push($recipients,[
                'address' => $row[0],
                'amount'  => $row[1]
            ]);
        }
        $resp = $this->call_api('POST',$unit,'sendmany',[
            'recipients'       => $recipients,
            'walletPassphrase' => config()->get('bitgo.wallet_passphrase')
        ]);
        $PassData              = new \stdClass();
        $PassData->transaction = $resp->txid;
        $PassData->sending     = true;
        $PassData->add_info    = [
            "fee"       => number($resp->transfer->feeString/100000000, 8),
            "full_data" => $resp
        ];
        return $PassData;
    }

    /**
     * @param $unit
     *
     * @return mixed
     */
    public function addWalletWebhook($unit){
        $resp = $this->call_api('POST',$unit,'webhooks',[
            'type' => 'transaction',
            'url'  => 'https://devfutures.com'
        ]);
        return $resp;
    }

    public function getInfoByTransferId($unit, $transfer){
        $resp = $this->call_api('GET',$unit,'transfer/'.$transfer);
        return $resp;
    }

    public function getListTransfersReceive($unit){
        $resp = $this->call_api('GET',$unit,'transfer/', [
            'state' => 'confirmed',
            'type' => 'receive'
        ]);
        return $resp;
    }

    /**
     * @param $unit
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
     * @param array  $options
     * @param bool   $need_wallet_id
     *
     * @return mixed
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
        } catch(GuzzleException $e) {
            throw new \Exception($e->getMessage());
        }
    }
}