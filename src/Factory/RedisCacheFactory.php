<?php

namespace Pantono\Cache\Factory;

use Pantono\Contracts\Locator\FactoryInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Pantono\Utilities\ApplicationHelper;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Predis\Client;

class RedisCacheFactory implements FactoryInterface
{
    private string $host;
    private string $port;

    public function __construct(string $host, string $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function createInstance(): RedisAdapter
    {
        $client = new Client([
            'scheme' => 'tcp',
            'host' => $this->host,
            'port' => $this->port
        ]);
        return new RedisAdapter($client);
    }
}
