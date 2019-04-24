<?php


namespace Selfreliance\BitGo;

class BitGo extends BitGoBase {
    /**
     * @param string $unit
     *
     * @return array|string
     * @throws \Exception
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

        return ['balance' => $balance, "confirmed" => $confirmedBalance, "spendable" => $spendableBalance];
    }

    /**
     * @param        $payment_id
     * @param        $sum
     * @param string $unit
     *
     * @return \stdClass
     * @throws \Exception
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
     * @return \stdClass
     * @throws \Exception
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
}