<?php

namespace PChess\ChessBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('chess');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->scalarNode('session_name')
                    ->cannotBeEmpty()
                    ->defaultValue('chess')
                ->end()
                ->arrayNode('routes')
                    ->children()
                        ->scalarNode('start')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('cancel')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('end')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('promotion')
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
