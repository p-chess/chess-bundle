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
                ->scalarNode('output_service')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('session_name')
                    ->cannotBeEmpty()
                    ->defaultValue('chess')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
