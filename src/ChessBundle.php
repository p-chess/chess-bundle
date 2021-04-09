<?php

namespace PChess\ChessBundle;

use PChess\ChessBundle\DependencyInjection\CompilerPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ChessBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CompilerPass(), PassConfig::TYPE_BEFORE_REMOVING);
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
