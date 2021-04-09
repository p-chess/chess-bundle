<?php

namespace PChess\ChessBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class ChessExtension extends Extension
{
    /**
     * @param array<string, mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('chess.output_service', $config['output_service']);
        $container->setParameter('chess.session_name', $config['session_name']);
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.xml');
    }
}
