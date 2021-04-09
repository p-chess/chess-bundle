<?php

namespace PChess\ChessBundle\Tests;

use PChess\ChessBundle\ChessBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ChessBundleTest extends TestCase
{
    public function testBuild(): void
    {
        $container = $this->createMock(ContainerBuilder::class);
        $container->expects(self::once())->method('addCompilerPass');
        $bundle = new ChessBundle();
        $bundle->build($container);
    }

    public function testGetPath(): void
    {
        $bundle = new ChessBundle();
        self::assertStringContainsString('chess-bundle', $bundle->getPath());
    }
}
