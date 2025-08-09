<?php

namespace Pantono\Cache\Factory;

use Pantono\Contracts\Locator\FactoryInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Pantono\Utilities\ApplicationHelper;

class FilesystemCacheFactory implements FactoryInterface
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function createInstance(): FilesystemAdapter
    {
        $path = realpath(str_starts_with($this->path, '/') ? $this->path : ApplicationHelper::getApplicationRoot() . '/' . $this->path);
        if ($path === false) {
            throw new \RuntimeException('Cache directory ' . $this->path . ' is not accessible');
        }
        return new FilesystemAdapter('', 3600, $path);
    }
}
