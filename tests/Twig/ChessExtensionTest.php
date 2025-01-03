<?php

namespace PChess\ChessBundle\Tests\Twig;

use PChess\ChessBundle\Twig\ChessExtension;
use PHPUnit\Framework\TestCase;

final class ChessExtensionTest extends TestCase
{
    public function testFunctions(): void
    {
        $ext = new ChessExtension();
        self::assertIsArray($ext->getFunctions());
    }
}
