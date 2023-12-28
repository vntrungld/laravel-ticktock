# Laravel Ticktock

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vntrungld/laravel-ticktock.svg?style=flat-square)](https://packagist.org/packages/vntrungld/laravel-ticktock)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/vntrungld/laravel-ticktock/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/vntrungld/laravel-ticktock/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/vntrungld/laravel-ticktock/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/vntrungld/laravel-ticktock/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/vntrungld/laravel-ticktock.svg?style=flat-square)](https://packagist.org/packages/vntrungld/laravel-ticktock)

## Installation

You can install the package via composer:

```bash
composer require vntrungld/laravel-ticktock
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Vntrungld\LaravelTicktock\TickTockServiceProvider"
```

## Usage

You can use `tick` and `tock` functions to measure the time of your code.

```php
tick('test 1')
// your first code here
tock('test 1')

tick('test 2')
// your second code here
tock('test 2')
```

Or you can use facade `Ticktock` to measure the time of your code.

```php
Ticktock::start('test 1')
// your first code here
Ticktock::end('test 1')

Ticktock::start('test 2')
// your second code here
Ticktock::end('test 2')
```

Then go to `laravel.log` if you use default log channel you will see something like:

```bash
[2021-08-05 10:00:00] local.DEBUG: [Ticktock] Process test 1 takes 123ms
[2021-08-05 10:00:01] local.DEBUG: [Ticktock] Process test 2 takes 321ms
```

You can also get the delta time by using `tock` or `Ticktock::end`.

```php
tick('test 1');
// your first code here
$delta = tock('test 1');
dump($delta); // 123

Ticktock::start('test 2');
// your second code here
$delta = Ticktock::end('test 2');
dump($delta); // 321
```


## Testing

```bash
phpunit
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Lam Duc Trung](https://github.com/vntrungld)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
