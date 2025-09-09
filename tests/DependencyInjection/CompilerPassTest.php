<?php

namespace PChess\ChessBundle\Tests\DependencyInjection;

use PChess\ChessBundle\DependencyInjection\CompilerPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class CompilerPassTest extends TestCase
{
    public function testProcess(): void
    {
        /** @var Definition&\PHPUnit\Framework\MockObject\MockObject $definition */
        $definition = $this->createMock(Definition::class);
        $definition->expects(self::once())->method('replaceArgument');
        /** @var ContainerBuilder&\PHPUnit\Framework\MockObject\MockObject $container */
        $container = $this->createMock(ContainerBuilder::class);
        $container->expects(self::once())->method('getParameter')->with('chess.output_service')->willReturn('foo');
        $container->method('getDefinition')->willReturn($definition);
        $pass = new CompilerPass();
        $pass->process($container);
    }
}
