<?php

return [
    /**
     * BitGo server for call api
     * Recommendation use local node.js server by BitGo Express
     */
    'bitgo_server'      => env('BITGO_SERVER','http://localhost:3080/api/v2/'),

    /**
     * Password for account in BitGo system
     */
    'wallet_passphrase' => env('BITGO_WALLET_PASSPHRASE','bitgo_wallet_passphrase'),

    /**
     * Token for call api
     * Seen in page https://www.bitgo.com/user/settings/options
     */
    'token'             => env('BITGO_TOKEN','bitgo_token'),

    /**
     * Wallet id in BitGo service
     * Seen in page put in coin -> Settings -> Wallet ID
     */
    'wallet_id_btc'     => env('BITGO_WALLET_ID_BTC','bitgo_wallet_id_btc'),
];