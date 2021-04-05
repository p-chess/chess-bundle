<?php

namespace PChess\ChessBundle\Tests;

use PChess\ChessBundle\ChessBundle;
use PHPUnit\Framework\TestCase;

final class ChessBundleTest extends TestCase
{
    public function testGetPath(): void
    {
        $bundle = new ChessBundle();
        self::assertStringContainsString('chess-bundle', $bundle->getPath());
    }
}
