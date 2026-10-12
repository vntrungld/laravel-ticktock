# Laravel Ticktock

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vntrungld/laravel-ticktock.svg?style=flat-square)](https://packagist.org/packages/vntrungld/laravel-ticktock)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/vntrungld/laravel-ticktock/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/vntrungld/laravel-ticktock/actions?query=workflow%3A"Run+Tests"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/vntrungld/laravel-ticktock.svg?style=flat-square)](https://packagist.org/packages/vntrungld/laravel-ticktock)

A hierarchical code execution timer for Laravel. Measure and visualize the time spent in nested blocks of code with a tree-style output.

## Requirements

| Laravel | PHP       |
|---------|-----------|
| 5.5     | >= 7.0    |
| 5.6-5.8 | >= 7.1   |
| 6.x     | >= 7.2    |
| 7.x     | >= 7.2    |
| 8.x     | >= 7.3    |
| 9.x     | >= 8.0    |
| 10.x    | >= 8.1    |
| 11.x    | >= 8.2    |
| 12.x    | >= 8.2    |
| 13.x    | >= 8.3    |

## Installation

You can install the package via composer:

```bash
composer require vntrungld/laravel-ticktock
```

For Laravel 5.5+, the service provider and facade are auto-discovered. For older setups, add them manually to `config/app.php`:

```php
'providers' => [
    Vntrungld\LaravelTicktock\TickTockServiceProvider::class,
],

'aliases' => [
    'Ticktock' => Vntrungld\LaravelTicktock\Facades\TickTock::class,
],
```

## Usage

- `tts($label = null)` starts a timer named after the calling method (plus the optional label)
- `tt($label = null)` captures the time since the previous capture (or since the timer started), named after the line it is called on
- `tte()` ends the current timer and returns its duration in milliseconds

```php
class OrderController
{
    public function store()
    {
        tts('checkout');
        $this->validateCart(); tt('validate');
        $this->charge();       tt('charge');
        tte();
    }
}
```

Or use the `Ticktock` facade:

```php
Ticktock::start('checkout');
$this->validateCart(); Ticktock::capture('validate');
$this->charge();       Ticktock::capture('charge');
Ticktock::end();
```

After that you can dump, dd or log the tree:

```php
ttd();  // dump
ttdd(); // dd
ttl();  // log
ttr();  // string

Ticktock::dump();
Ticktock::dd();
Ticktock::log();
Ticktock::render();
```

The output will be

```text
App\Http\Controllers\OrderController::store - checkout -- 30.42ms
├── Line 7 - validate -- 10.13ms
└── Line 8 - charge -- 20.29ms
```

Timers nest: calling `tts()` inside a method that is already being timed adds a child node, so you can follow the time down through as many levels as you want.

```text
App\Services\Report::build -- 132.08ms
├── Line 12 -- 10.04ms
├── Line 13 -- 20.11ms
├── App\Services\Report::loadData -- 56.37ms
│   ├── Line 30 -- 5.02ms
│   ├── App\Services\Report::loadUsers -- 7.15ms
│   │   ├── Line 45 -- 3.06ms
│   │   └── Line 46 -- 4.09ms
│   └── Line 32 -- 44.20ms
└── App\Services\Report::render -- 45.56ms
    ├── Line 60 -- 10.03ms
    ├── Line 61 -- 15.21ms
    └── Line 62 -- 20.32ms
```

Timers that have not been ended yet are shown with a `(running)` marker. Calling `tt()`, `tte()` or `ttr()` without a started timer is a no-op.

### Exception-safe timing

`measure()` times a callback and always ends its timer (and any timer left open inside it), even when the callback throws:

```php
$users = Ticktock::measure('load users', function () {
    return User::with('roles')->get();
});
```

### Resetting

`Ticktock::reset()` discards the current tree. The tree is reset automatically at the start of every queued job and every Octane request, since those processes reuse the same instance.

## Testing

```bash
composer test
```

## Code Style

The code is formatted with [Laravel Pint](https://github.com/laravel/pint) (see `pint.json`). Pint needs PHP 8.1+, so it is not a dev dependency (the package still supports PHP 7.0). Install it globally and run:

```bash
composer global require laravel/pint
composer lint        # fix
composer lint:test   # check only
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
