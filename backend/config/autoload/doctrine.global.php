<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Configuration;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\ApcCache;

return [
    'dependencies' => [
        'factories' => [
            EntityManagerInterface::class => function (ContainerInterface $container) {
                $params = $container->get('config')['doctrine'];

                $isDevMode = $container->get('config')['debug'];

                $config = new Configuration();

                $cache = $isDevMode ? new ArrayCache() : new ApcCache();
                $config->setMetadataCacheImpl($cache);
                $config->setQueryCacheImpl($cache);
                $driverImpl = $config->newDefaultAnnotationDriver($params['entities']);
                $config->setMetadataDriverImpl($driverImpl);
                $config->setProxyDir($params['proxies']['dir']);
                $config->setProxyNamespace($params['proxies']['namespace']);
                $config->setAutoGenerateProxyClasses($isDevMode);

                return EntityManager::create($params['dbParams'], $config);
            },
        ],
    ],

    'doctrine' => [
        'migrations' => [
            'dir'       => 'src/Data/Migration',
            'namespace' => 'Api\Data\Migration',
        ],
        'entities'   => [
            'src/Model/',
        ],
        'proxies'    => [
            'dir'       => 'src/Data/Proxies',
            'namespace' => 'Api\Data\Proxies',
        ],
        'dbParams'   => [
            'driver'   => 'pdo_mysql',
            'user'     => 'root',
            'password' => 'example',
            'dbname'   => 'example',
            'host'     => 'db',
        ],
    ],
];