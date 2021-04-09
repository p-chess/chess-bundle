<?php

namespace PChess\ChessBundle\Tests;

use PChess\ChessBundle\SimpleChessProvider;
use PHPUnit\Framework\TestCase;

final class SimpleChessProviderTest extends TestCase
{
    public function testGetChess(): void
    {
        $provider = new SimpleChessProvider();
        self::assertNotNull($provider->getChess());
    }

    public function testGetChessWithInvalidFen(): void
    {
        $provider = new SimpleChessProvider();
        $this->expectException(\InvalidArgumentException::class);
        $provider->getChess(null, 'invalid!');
    }
}
