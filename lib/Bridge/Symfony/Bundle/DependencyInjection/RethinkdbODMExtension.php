<?php

/*
 * This file is part of the Sauron package.
 *
 * (c) Vincent Touzet <vincent.touzet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RethinkDB\ODM\Bridge\Symfony\Bundle\DependencyInjection;

use RethinkDB\ODM\Metadata\Loader\ArrayLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class RethinkdbODMExtension
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class RethinkdbODMExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $config = $this->processConfiguration(new Configuration(), $configs);

        $container->setParameter('rethinkdb_odm.connection.host', $config['connection']['host']);
        $container->setParameter('rethinkdb_odm.connection.port', $config['connection']['port']);
        $container->setParameter('rethinkdb_odm.connection.database', $config['connection']['database']);
        $container->setParameter('rethinkdb_odm.connection.user', $config['connection']['user']);
        $container->setParameter('rethinkdb_odm.connection.password', $config['connection']['password']);

        if (isset($config['mapping']['entities'])) {
            // configuration loaded from array
            $definition = new Definition(ArrayLoader::class, [$config['mapping']['entities']]);
            $container->setDefinition('rethinkdb.class_metadata.array_loader', $definition);

            $container->setAlias('rethinkdb_odm.class_metadata.loader', 'rethinkdb.class_metadata.array_loader');
        }

        $loader->load('services.yml');
    }
}
