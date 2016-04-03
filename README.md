# Laravel Tactician

[![Build Status](https://travis-ci.org/tillkruss/laravel-tactician.svg?branch=master)](https://travis-ci.org/tillkruss/laravel-tactician)
[![Latest Stable Version](https://poser.pugx.org/tillkruss/laravel-tactician/v/stable)](https://packagist.org/packages/tillkruss/laravel-tactician)
[![License](https://poser.pugx.org/tillkruss/laravel-tactician/license)](https://packagist.org/packages/tillkruss/laravel-tactician)

A flexible and easy to use implementation of the Tactician command bus for Laravel 5.

## Introduction

This package is a stand-alone command bus implementation of the PHP League’s [Tactician](http://tactician.thephpleague.com) command bus, it’s not a replacement for Laravel’s command bus or queue.

__Features:__

- Easy to use, configure and extend
- _Global_ and/or _per-command_ middleware stacks
- 3 kinds of command handler locators
- Database transaction middleware


## Installation

To get started, add this package to your `composer.json` file as a dependency:

```
composer require tillkruss/laravel-tactician
```

Next, open your `app` configuration file and add the `TacticianServiceProvider` to your list of `providers`:

```
TillKruss\LaravelTactician\TacticianServiceProvider,
```

After that, add the `tactician.php` configuration file by running:

```
php artisan vendor:publish
```


## Configuration

Please see [CONFIGURATION.md](CONFIGURATION.md) for more information on how to configure this package.


## Usage

...
- examples folder?
- USAGE.md?


## License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
