<?php

namespace Tests\Feature;

use Selfreliance\BitGo\BitGo;
use Tests\TestCase;
use Config;
use Carbon\Carbon;
class PayeerHandlerPayments extends TestCase
{

    public function testBalance()
    {
        $bigo = new BitGo('http://localhost:3080/api/v2/');
        $balance = $bigo->balance('btc');
        dd($balance);
    }

    public function testForm(){
        $bigo = new BitGo();
        $res_form = $bigo->form(1, 0, 'btc');
        dd($res_form);
    }

    public function testListWalletWebhooks(){
        $bigo = new BitGo();
        $res_form = $bigo->listWalletWebhooks('btc');
        dd($res_form);
    }

    public function testSendMultiply(){
        $data_for_multi_send = [];
        $data_for_multi_send[] = [
            'id'   => 0,
            'data' => [
                '12mP76fakJuX9Rrv1xatwYTD74cMHFNC6c',
                10000
            ]
        ];
        $tmp = [];
        foreach($data_for_multi_send as $row){
            $tmp[] = $row['data'];
        }

        $bitgo = new BitGo();
        $res_form = $bitgo->send_multi($tmp, 'btc');
        dd($res_form);
    }

    public function testGetTransfer(){
        $bitgo = new BitGo();
        $resp = $bitgo->getInfoByTransferId('btc', '5c80fe26cbc78640085ab8772db8a3bb');
        dd($resp);
    }

    public function testListTransfers(){
        $bitgo = new BitGo();
        $resp = $bitgo->getListTransfersReceive('btc');
    }
}