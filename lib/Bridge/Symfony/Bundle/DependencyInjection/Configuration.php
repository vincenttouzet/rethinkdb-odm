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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('rethinkdb_odm');

        $root
            ->children()
                ->arrayNode('connection')
                    ->children()
                        ->scalarNode('host')->isRequired()->end()
                        ->scalarNode('port')->isRequired()->defaultValue(28015)->end()
                        ->scalarNode('database')->isRequired()->end()
                        ->scalarNode('user')->isRequired()->defaultValue('admin')->end()
                        ->scalarNode('password')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('mapping')
                    ->fixXmlConfig('entity', 'entities')
                    ->children()
                        ->arrayNode('entities')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('class')->end()
                                    ->scalarNode('table')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
