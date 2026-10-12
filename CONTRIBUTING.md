# Contributing

Contributions are welcome via pull requests on [GitHub](https://github.com/vntrungld/laravel-ticktock).

- **Keep PHP 7.0 compatibility.** The package supports PHP 7.0+ and Laravel 5.5+, so avoid `void`, nullable (`?Type`) and typed-property syntax, arrow functions and `??=`.
- **Add tests.** Tests use a fake clock (`$this->clock->advance($ms)`) and assert the exact rendered output; avoid `usleep`-based timing assertions.
- **Follow the code style.** Run [Laravel Pint](https://github.com/laravel/pint) before submitting (`composer lint`).
- **One pull request per feature.** Document any behaviour change in `README.md` and `CHANGELOG.md`.

Run the test suite with:

```bash
composer test
```
