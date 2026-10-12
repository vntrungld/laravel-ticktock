# Changelog

All notable changes to `laravel-ticktock` are documented in this file.

## Unreleased

### Added
- Laravel 13 support, and CI coverage for PHP 8.3, 8.4 and 8.5
- `ticktock.enabled` config option (`TICKTOCK_ENABLED` env). When disabled, every timer call is a no-op
- Time between the last capture and the end of a timer is shown as an `(untracked)` row
- `@method` docblocks on the facade for IDE autocompletion

### Changed
- `illuminate/log` and `symfony/var-dumper` are now declared dependencies
- `ttl()` no longer logs an empty tree when no timer was started

## v1.1.0 - 2026-10-12

### Added
- `Ticktock::measure($label, callable $callback)`, which returns the callback's result
- `Ticktock::reset()`. The tree is also reset automatically at the start of each queued job and each Octane request
- Unfinished timers are rendered with a `(running)` marker
- Support for Laravel 5.5 – 12 on PHP 7.0+

### Fixed
- The `Ticktock` facade alias pointed to a wrongly cased class
- Calling `tt()`, `tte()` or `ttr()` without a started timer no longer causes a fatal error
- An exception between `tts()` and `tte()` no longer corrupts later trees
- Caller names are now resolved correctly for direct instances, global functions and top-level code

### Changed
- Durations use two decimals (`30.00ms` instead of `30ms`)
