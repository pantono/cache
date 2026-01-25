<?php

namespace Pantono\Cache\Factory;

use Pantono\Contracts\Locator\FactoryInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Predis\Client;
use Pantono\Contracts\Locator\LocatorInterface;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Pantono\Cache\Adapter\SymfonyCacheAdapter;
use Pantono\Utilities\ApplicationHelper;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class EphemeralCacheFactory implements FactoryInterface
{
    public function createInstance(): SymfonyCacheAdapter
    {
        return new SymfonyCacheAdapter(new ArrayAdapter(60));
    }
}
