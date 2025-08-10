<?php

namespace Pantono\Cache\Adapter;

use PHPUnit\TextUI\Application;
use Pantono\Contracts\Application\Cache\ApplicationCacheInterface;
use Symfony\Component\Cache\Adapter\AbstractAdapter;

class SymfonyCacheAdapter implements ApplicationCacheInterface
{
    protected AbstractAdapter $adapter;

    public function __construct(AbstractAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->adapter->getItem($key);
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
