# BitGo for Laravel 5.*

Require this package with composer:
```
composer require selfreliance/bitgo
```
## Publish Config

```
php artisan vendor:publish --provider="Selfreliance\BitGo\BitGoServiceProvider"
```

## Use name module

```
use Selfreliance\BitGo\BitGo;
```

## Configuration

Add to **.env** file:

```
#BitGo_Settings
BITGO_SERVER=
BITGO_WALLET_PASSPHRASE=
BITGO_TOKEN=
BITGO_WALLET_ID_BTC=
BITGO_WALLET_ID_DASH=
BITGO_WALLET_ID_LTC=
BITGO_WALLET_ID_XLM=
BITGO_WALLET_ID_ZEC=
BITGO_WALLET_ID_BCH=
```