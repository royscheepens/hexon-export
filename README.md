# Hexon Export

[![Total Downloads](https://poser.pugx.org/royscheepens/hexon-export/downloads)](https://packagist.org/packages/royscheepens/hexon-export)
[![License](https://poser.pugx.org/royscheepens/hexon-export/license)](https://packagist.org/packages/royscheepens/hexon-export)
[![Latest Stable Version](https://poser.pugx.org/royscheepens/hexon-export/v/stable)](https://packagist.org/packages/royscheepens/hexon-export)
[![Monthly Downloads](https://poser.pugx.org/royscheepens/hexon-export/d/monthly)](https://packagist.org/packages/royscheepens/hexon-export)

> A Laravel Package to process imcremental XML exports from Hexon's Doorlinken Voorraad. 

Use this package when you want to process the incremental XML exports from Hexon's Doorlinken Voorraad product.

## Installation
Install with `composer`:

Laravel 5.4 and above
```
composer require royscheepens/hexon-export:^0.1.0
```

And add the service provider in `config/app.php`
```php
'providers' => [
    ........,
    RoyScheepens\HexonExport\HexonExportServiceProvider::class,
]
```

If you want to use the facade, add this to your facades in `config/app.php`
```php
'aliases' => [
    ........,
    'HexonExport' => RoyScheepens\HexonExport\HexonExportFacade::class,
]

```

To publish the configuration file and required migrations, run:
```
php artisan vendor:publish --provider="RoyScheepens\HexonExport\HexonExportServiceProvider"
```

## Configuration

todo

## Usage

todo

## Future Roadmap

The package currently only supports the 'incremental' XML export. Support for the 'bulk' export is something worth considering for the future. If you would like to contribute to the package, feel free to fork the repository and submit a pull request!