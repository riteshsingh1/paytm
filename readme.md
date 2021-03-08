# paytm

[![Total Downloads][ico-downloads]][link-downloads]

Paytm Payment Gateway For Gratification

NOTE: This is not normal Paytm Payment Gateway. This is for transferring Payment to  Users Bank Account, From Paytm Payments Bank.
Paytm Business Account is needed.

## Installation

Via Composer

``` bash
composer require imritesh/paytm
```

## Usage
```php
(new Imritesh/Paytm())->purpose('INCENTIVE')->accountNumber()->ifsc()->withReferenceId()->paymentAmount(100)->later()->pay();
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email hello@imritesh.com instead of using the issue tracker.

## Credits

- [Ritesh Singh][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/imritesh/paytm.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/imritesh/paytm.svg?style=flat-square
[link-packagist]: https://packagist.org/packages/imritesh/paytm
[link-downloads]: https://packagist.org/packages/imritesh/paytm
[link-travis]: https://travis-ci.org/imritesh/paytm
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/imritesh
