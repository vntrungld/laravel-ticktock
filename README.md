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

## Usage

Use `tts` to start a timer for a block of code

Use `tte` to end a timer for a block of code

Use `tt` to capture the time of a block of code

Eg:

```php
tts('test 1')
    doSomething();tt('do something');
    doSomethingElse();tt('do something else');
tte()
```

Or you can use facade `Ticktock` to measure the time of your code.

```php
Ticktock::start('test 1')
    doSomething(); Ticktock::capture('do something');
    doSomethingElse(); Ticktock::capture('do something else');
Ticktock::end()
```

After that you can dump, dd or log this by using:
```php
ttd(); // dump
ttdd(); // dd
ttl(); // log
ttr(); // string

Ticktock::dump(); // dump
Ticktock::dd(); // dd
Ticktock::log(); // log
Ticktock::render(); // string
```

The output will be
```text
test 1 -- 30ms
├── do something -- 10ms
└── do something else -- 20ms
```

You can nest many levels as you want.

```php
tts('total');
    usleep(10 * 1000);tt('child1');
    usleep(20 * 1000);tt('child2');
    tts('child3');
        usleep(5 * 1000);tt('child3.1');
        usleep(7*1000);tt('child3.2');
        tts('child3.3');
            usleep(3 * 1000);tt('child3.3.1');
            usleep(4 * 1000);tt('child3.3.2');
        tte();
        usleep(18 * 1000);tt('child3.4');
        tts('child3.5');
            usleep(8 * 1000);tt('child3.5.1');
            usleep(9 * 1000);tt('child3.5.2');
        tte();
    tte();
    tts('child4');
        usleep(10 * 1000);tt('child4.1');
        usleep(15 * 1000);tt('child4.2');
        usleep(20 * 1000);tt('child4.3');
    tte();
tte();
ttl();
```

The output will be
```text
total -- 132ms
├── child1 -- 10ms
├── child2 -- 20ms
├── child3 -- 56ms
│   ├── child3.1 -- 5ms
│   ├── child3.2 -- 7ms
│   ├── child3.3 -- 8ms
│   │   ├── child3.3.1 -- 3ms
│   │   └── child3.3.2 -- 4ms
│   ├── child3.4 -- 18ms
│   └── child3.5 -- 18ms
│       ├── child3.5.1 -- 8ms
│       └── child3.5.2 -- 9ms
└── child4 -- 46ms
    ├── child4.1 -- 10ms
    ├── child4.2 -- 15ms
    └── child4.3 -- 20ms
```

## Testing
I have not written any tests yet. I will write them soon.

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
