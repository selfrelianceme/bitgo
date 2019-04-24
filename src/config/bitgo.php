<?php

return [
    /**
     * BitGo server for call api
     * Recommendation use local node.js server by BitGo Express
     */
    'bitgo_server'      => env('BITGO_SERVER','https://www.bitgo.com/api/v2'),

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

    /**
     * Wallet id in BitGo service for Dash
     * Seen in page put in coin -> Settings -> Wallet ID
     */
    'wallet_id_dash'     => env('BITGO_WALLET_ID_DASH','bitgo_wallet_id_dash'),

    /**
     * Wallet id in BitGo service for Litecoin
     * Seen in page put in coin -> Settings -> Wallet ID
     */
    'wallet_id_ltc'     => env('BITGO_WALLET_ID_LTC','bitgo_wallet_id_ltc'),

    /**
     * Wallet id in BitGo service for Stellar(XLM)
     * Seen in page put in coin -> Settings -> Wallet ID
     */
    'wallet_id_xlm'     => env('BITGO_WALLET_ID_XLM','bitgo_wallet_id_xlm'),

    /**
     * Wallet id in BitGo service for Zcash(ZEC)
     * Seen in page put in coin -> Settings -> Wallet ID
     */
    'wallet_id_zec'     => env('BITGO_WALLET_ID_ZEC','bitgo_wallet_id_zec'),

    /**
     * Wallet id in BitGo service for BitcoinCash(BCH)
     * Seen in page put in coin -> Settings -> Wallet ID
     */
    'wallet_id_bch'     => env('BITGO_WALLET_ID_BCH','bitgo_wallet_id_bch'),

];