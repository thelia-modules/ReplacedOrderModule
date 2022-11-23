# Replaced Order Module

This module allows to replace payment module id or delivery module id from order.

So you can delete the module after.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is ReplacedOrderModule.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/replaced-order-module-module:~1.0
```

## Usage

This module add a selector in the config where you can choose the delivery or payment module you want to replace