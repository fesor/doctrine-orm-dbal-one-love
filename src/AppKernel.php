<?php

namespace Fesor\SchemaExample;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles()
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
        ];
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {

    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $c->loadFromExtension('framework', [
            // this is just example, so I leave hardcoded values.
            'secret' => '2e8cc4ebf2b4ee9bc5171369e2155fa1f440d0a3f0ba0231ae0901ee899db3da',
        ]);

        $c->loadFromExtension('doctrine', [
            'dbal' => [
                'driver' => 'pdo_sqlite',
                'url' => 'sqlite:///somedb.sqlite',
                'schema_filter' => '~^(?!legacy_)~'
            ],
            'orm' => [
                'entity_managers' => [
                    'default' => [
                        'auto_mapping' => false,
                        'mappings' => [
                            'model' => [
                                'type' => 'annotation',
                                'dir' => __DIR__ . '/../src/Model',
                                'prefix' => 'Fesor\\SchemaExample\\Model',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function getLogDir()
    {
        return $this->rootDir.'/../var/logs';
    }

    public function getCacheDir()
    {
        return $this->rootDir.'/../var/cache/'.$this->environment;
    }
}