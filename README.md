# Resource Picker for Filament

This package allows to pick items from other resources as an alternative for relationships (e.g. when you want to save related items in a json column).

## Installation

You can install the package via composer:

```bash
composer require wotz/filament-resource-picker
```

## Usage

```php
\Wotz\FilamentResourcePicker\Filament\Forms\Components\ResourcePickerInput::make('related_items');
```

## Documentation

For the full documentation, check [here](./docs/index.md).

## Testing

```bash
vendor/bin/pest
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Upgrading

Please see [UPGRADING](UPGRADING.md) for more information on how to upgrade to a new version.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

If you discover any security-related issues, please email info@whoownsthezebra.be instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
