# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

`vntrungld/laravel-ticktock` is a Laravel package: a hierarchical execution timer for debugging. Users drop `tts()` / `tt()` / `tte()` (or the `Ticktock` facade) into their code and get a tree-style timing report via dump, dd, log or a string.

## Commands

```bash
composer install
composer test                                   # vendor/bin/phpunit
vendor/bin/phpunit --filter test_print          # single test
```

There is no lint or build step. CI (`.github/workflows/run-tests.yml`) runs the suite across a PHP/Laravel matrix from PHP 7.0 + Laravel 5.5 up to PHP 8.2 + Laravel 12, swapping in `illuminate/support` and `orchestra/testbench` versions with `composer require ... --no-update`.

## Compatibility constraints

Supported range: PHP `~7.0|~8.0`, Laravel 5.5–12, PHPUnit 6.5–11. Keep all code in `src/` and `tests/` valid on **PHP 7.0**:
- no `void`, nullable `?Type`, arrow functions, typed properties or `??=`
- don't use implicitly nullable typed params (`Clock $c = null`) either, because PHP 8.4 deprecates them; leave the param untyped instead
- guard newer functions (e.g. `hrtime()` in `Clock`)
- in tests, use only PHPUnit APIs that exist in 6.5. `TestCase::assertOutputContains()` wraps `assertStringContainsString`/`assertContains` for this reason
- `phpunit.xml.dist` is deliberately minimal so it parses on every PHPUnit version

## Architecture

- `TickTock` (singleton, registered in `TickTockServiceProvider`) holds a single `root` `Node` and a `cursors` stack of open nodes. `start()` pushes a node, which becomes the root when the stack is empty and a child of the top node otherwise. `capture()` adds a finished child to the top node. `end()` pops.
- Segments are contiguous laps: `Node::addChild()` starts a child at the previous sibling's end time, or at the parent's start time. Durations therefore come from capture points and not from per-call start timestamps.
- Node names come from the call stack. `TickTock::getCaller()` walks `debug_backtrace` to the first frame whose file is outside `src/` and is not Laravel's `Facade.php`. That frame's line becomes "Line N" for captures, and the next frame's `Class::function` becomes the name for starts. This is why calls through the helper, the facade or a direct instance all resolve to the user's code. Any new entry point in `src/` gets the same treatment automatically; anything outside `src/` that wraps these calls would shift the reported caller.
- Time comes from `Clock::now()`, in milliseconds (`hrtime` falling back to `microtime`). `TickTock` gets a `Clock` from the container, and `Node` inherits its clock from its parent.
- Robustness rules: calling `tt()`/`tte()`/`ttr()` without a running timer must be a no-op, never an error. `measure()` closes every timer it opened, using `try/finally`. The service provider calls `reset()` on `JobProcessing` and Octane `RequestReceived`, through the facade root.
- `src/helpers.php` (autoloaded via `composer.json` `files`) defines the short global helpers that proxy to the facade. The facade alias `Ticktock` is registered via `extra.laravel.aliases`. The class is `Facades\TickTock`, and the alias key and the class name differ in case on purpose. A test checks that every class listed in `extra.laravel` exists.

## Tests

`tests/TestCase.php` (Orchestra Testbench) binds `tests/FakeClock.php` as the `Clock`, so tests advance time with `$this->clock->advance($ms)` and assert the exact rendered tree. Don't use `usleep`-based assertions. For line numbers in expected output, capture `__LINE__` on the same line as the `tt()` call rather than hard-coding them.

