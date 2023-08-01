# Replaced Order Module

This module allows to replace payment module id or delivery module id from order.
So you can delete the module after.

## Installation

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/replaced-order-module-module:~2.0
```

## Usage

This module add a selector in the config where you can choose the delivery or payment module you want to replace

You can also choose the delivery or payment module you want to replace with another delivery or payment module

Example : Colissimo(deprecated) -> ColissimoHomeDelivery