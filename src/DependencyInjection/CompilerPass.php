<?php

namespace PChess\ChessBundle\DependencyInjection;

use PChess\ChessBundle\Twig\ChessExtension as TwigExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class CompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        /** @var string $service */
        $service = $container->getParameter('chess.output_service');
        $output = $container->getDefinition($service);
        $container->getDefinition(TwigExtension::class)->replaceArgument(0, $output);
    }
}
