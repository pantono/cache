<?php

namespace Pantono\Cache\Factory;

use Pantono\Contracts\Locator\FactoryInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Predis\Client;
use Pantono\Contracts\Locator\LocatorInterface;
use Symfony\Component\Cache\Adapter\AbstractAdapter;

class ApplicationCacheFactory implements FactoryInterface
{
    private LocatorInterface $locator;
    private string $cacheType;

    public function __construct(LocatorInterface $locator, string $cacheType)
    {
        $this->locator = $locator;
        $this->cacheType = $cacheType;
    }

    public function createInstance(): AbstractAdapter
    {
        $type = $this->cacheType;
        if (strtolower($type) === 'redis' || $type === RedisAdapter::class || $type === RedisCacheFactory::class) {
            $type = FilesystemCacheFactory::class;
        }
        if (strtolower($type) == 'file' || $type === FilesystemCacheFactory::class) {
            $type = FilesystemCacheFactory::class;
        }
        $class = $this->locator->getClassAutoWire($type);
        if ($class) {
            return $class;
        }

        throw new \RuntimeException('Unable to load application cache with type: ' . $type);
    }
}
