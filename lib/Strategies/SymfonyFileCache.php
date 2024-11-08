<?php

namespace PHPNomad\Symfony\Component\CacheIntegration\Strategies;

use PHPNomad\Cache\Exceptions\CachedItemNotFoundException;
use PHPNomad\Cache\Interfaces\CacheStrategy;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class SymfonyFileCache implements CacheStrategy
{
    private FilesystemAdapter $cache;

    public function __construct()
    {
        $this->cache = new FilesystemAdapter();
    }

    /**
     * @inheritDoc
     */
    public function get(string $key)
    {
        $item = $this->cache->getItem($key);

        if (!$item->isHit()) {
            throw new CachedItemNotFoundException("Cache item not found: {$key}");
        }

        return $item->get();
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, $value, ?int $ttl): void
    {
        $item = $this->cache->getItem($key);
        $item->set($value);

        if ($ttl !== null) {
            $item->expiresAfter($ttl);
        }

        $this->cache->save($item);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): void
    {
        $this->cache->deleteItem($key);
    }

     /**
     * @inheritDoc
     */
    public function exists(string $key): bool
    {
        $item = $this->cache->getItem($key);
        return $item->isHit();
    }

     /**
     * @inheritDoc
     */
    public function clear(): void
    {
        $this->cache->clear();
    }
}
