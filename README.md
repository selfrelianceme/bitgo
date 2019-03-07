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

Add `BITGO_SERVER`, `BITGO_WALLET_PASSPHRASE`, `BITGO_TOKEN` and `BITGO_WALLET_ID_BTC` to **.env** file:

```
#BitGo_Settings
BITGO_SERVER=
BITGO_WALLET_PASSPHRASE=
BITGO_TOKEN=
BITGO_WALLET_ID_BTC=
```