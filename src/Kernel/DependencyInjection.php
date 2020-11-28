<?php

declare(strict_types=1);

namespace App\Kernel;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class DependencyInjection
{
    public static function getContainerBuilder(): ContainerBuilder
    {
        $containerBuilder = new ContainerBuilder();
        $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../../'));
        $loader->load('services.yaml');
        $containerBuilder->compile();

        return $containerBuilder;
    }
}
