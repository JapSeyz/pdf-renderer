# PDF Renderer

## Installation

You can install the package via composer:

```bash
composer require japseyz/pdf-renderer
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="pdf-renderer-config"
```

This is the contents of the published config file:

```php
return [
    // Possible values are:
    // 'browsershot', 'api2pdf'
    'service' => 'browsershot'
];
```

## Usage

```php
 $renderer = PDFRenderer::view(
    'documents.invoice',
    [
        'client'          => $client,
    ]
);

return response($renderer->render(), 200, [
    'Content-Type'        => 'application/pdf',
    'Content-Disposition' => 'attachment; filename="invoice.pdf"',
]);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jesper Jacobsen](https://github.com/JapSeyz)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
