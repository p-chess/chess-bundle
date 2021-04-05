<?php

namespace PChess\ChessBundle\Tests\Twig;

use PChess\ChessBundle\HtmlOutput;
use PChess\ChessBundle\Twig\ChessExtension;
use PHPUnit\Framework\TestCase;

final class ChessExtensionTest extends TestCase
{
    public function testFunctions(): void
    {
        $output = $this->createMock(HtmlOutput::class);
        $ext = new ChessExtension($output);
        self::assertIsArray($ext->getFunctions());
    }
}
