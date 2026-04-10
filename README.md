# phpnomad/symfony-cache-integration

[![Latest Version](https://img.shields.io/packagist/v/phpnomad/symfony-cache-integration.svg)](https://packagist.org/packages/phpnomad/symfony-cache-integration)
[![Total Downloads](https://img.shields.io/packagist/dt/phpnomad/symfony-cache-integration.svg)](https://packagist.org/packages/phpnomad/symfony-cache-integration)
[![PHP Version](https://img.shields.io/packagist/php-v/phpnomad/symfony-cache-integration.svg)](https://packagist.org/packages/phpnomad/symfony-cache-integration)
[![License](https://img.shields.io/packagist/l/phpnomad/symfony-cache-integration.svg)](https://packagist.org/packages/phpnomad/symfony-cache-integration)

`phpnomad/symfony-cache-integration` adapts Symfony's Cache component to `phpnomad/cache`'s `CacheStrategy` contract. It ships a single strategy, `SymfonyFileCache`, backed by Symfony's `FilesystemAdapter`. Your application code still depends only on the `CacheStrategy` interface, so the Symfony-specific wiring stays at the bootstrap layer and never leaks into the services that read and write cached values.

## Installation

```bash
composer require phpnomad/symfony-cache-integration
```

## What This Provides

- `SymfonyFileCache`, a `CacheStrategy` implementation backed by Symfony's `FilesystemAdapter`. It gives you a filesystem-backed persistent cache that satisfies the `phpnomad/cache` contract (`get`, `set`, `delete`, `exists`, `clear`) and throws `CachedItemNotFoundException` on a miss, so consumers can catch the exception and fall back to their source of truth.

## Requirements

- [`phpnomad/cache`](https://packagist.org/packages/phpnomad/cache) for the `CacheStrategy` interface and the `CachedItemNotFoundException` this package throws
- [`symfony/cache`](https://packagist.org/packages/symfony/cache) `^7.1`, the Symfony Cache component this package wraps
- PHP 8.2 or newer, inherited from `symfony/cache` `^7.1`

## Usage

Register `SymfonyFileCache` as the concrete implementation of `CacheStrategy` in one of your application's initializers. The container then resolves any service that type-hints `CacheStrategy` to the filesystem-backed adapter.

```php
<?php

namespace MyApp\Bootstrap;

use PHPNomad\Cache\Interfaces\CacheStrategy;
use PHPNomad\Loader\Interfaces\HasClassDefinitions;
use PHPNomad\Symfony\Component\CacheIntegration\Strategies\SymfonyFileCache;

final class AppInitializer implements HasClassDefinitions
{
    public function getClassDefinitions(): array
    {
        return [
            SymfonyFileCache::class => CacheStrategy::class,
        ];
    }
}
```

Pass `AppInitializer` to your `Bootstrapper` alongside the rest of your initializers and the binding takes effect when `load()` runs.

## Documentation

Full PHPNomad documentation lives at [phpnomad.com](https://phpnomad.com). For the underlying caching layer, see the [Symfony Cache component documentation](https://symfony.com/doc/current/components/cache.html).

## License

Licensed under the [MIT License](LICENSE.txt).
