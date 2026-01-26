<?php

namespace Pantono\Cache\Adapter;

use Pantono\Contracts\Application\Cache\ApplicationCacheInterface;
use Pantono\Contracts\Application\Cache\EphemeralCacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

class SymfonyCacheAdapter implements ApplicationCacheInterface, EphemeralCacheInterface
{
    protected TagAwareAdapter $adapter;

    public function __construct(AdapterInterface $adapter, ?AdapterInterface $tagAdapter = null)
    {
        $this->adapter = new TagAwareAdapter($adapter, $tagAdapter);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->adapter->getItem($key);
    }

    /**
     * @param string $key
     * @param callable $callback
     * @param array<string> $tags
     * @return mixed
     * @throws \Psr\Cache\CacheException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getCallback(string $key, callable $callback, array $tags = []): mixed
    {
        $item = $this->adapter->getItem($key);
        if (!$item->isHit()) {
            $data = $callback();
            $item->set($data);
            $item->tag($tags);
            $this->adapter->save($item);
        }
        return $item->get();
    }

    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        $item = $this->adapter->getItem($key);
        $item->set($value);
        $this->adapter->save($item);
        return true;
    }

    public function delete(string $key): bool
    {
        $this->adapter->deleteItem($key);
        return true;
    }

    public function clear(): bool
    {
        $this->adapter->clear();
        return true;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $items = [];
        foreach ($keys as $key) {
            if ($this->has($key)) {
                $items[$key] = $this->get($key, $default);
            }
        }
        return $items;
    }

    /**
     * @param iterable<string,mixed> $values
     * @param \DateInterval|int|null $ttl
     * @return bool
     * @throws InvalidArgumentException
     */
    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }
        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }
        return true;
    }

    public function has(string $key): bool
    {
        return $this->adapter->hasItem($key);
    }
}
