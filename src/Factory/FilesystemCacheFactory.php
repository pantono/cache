<?php

namespace Pantono\Cache\Factory;

use Pantono\Contracts\Locator\FactoryInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Pantono\Utilities\ApplicationHelper;
use Symfony\Component\Cache\Adapter\Psr16Adapter;
use Pantono\Cache\Adapter\SymfonyCacheAdapter;

class FilesystemCacheFactory implements FactoryInterface
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function createInstance(): SymfonyCacheAdapter
    {
        $path = realpath(str_starts_with($this->path, '/') ? $this->path : ApplicationHelper::getApplicationRoot() . '/' . $this->path);
        if ($path === false) {
            throw new \RuntimeException('Cache directory ' . $this->path . ' is not accessible');
        }
        return new SymfonyCacheAdapter(new FilesystemAdapter('', 3600, $path));
    }
}
