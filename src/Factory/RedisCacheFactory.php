<?php

namespace Pantono\Cache\Factory;

use Pantono\Contracts\Locator\FactoryInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Pantono\Utilities\ApplicationHelper;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Predis\Client;
use Pantono\Cache\Adapter\SymfonyCacheAdapter;

class RedisCacheFactory implements FactoryInterface
{
    private string $host;
    private string $port;

    public function __construct(string $host, string $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function createInstance(): SymfonyCacheAdapter
    {
        return new SymfonyCacheAdapter(new RedisAdapter(new Client([
            'scheme' => 'tcp',
            'host' => $this->host,
            'port' => $this->port
        ])));
    }
}
