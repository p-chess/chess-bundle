<?php

namespace PChess\ChessBundle\Tests\Twig;

use PChess\Chess\Chess;
use PChess\ChessBundle\HtmlOutput;
use PChess\ChessBundle\Twig\ChessRuntime;
use PHPUnit\Framework\TestCase;

final class ChessRuntimeTest extends TestCase
{
    public function testRender(): void
    {
        $output = $this->createMock(HtmlOutput::class);
        $runtime = new ChessRuntime($output);
        self::assertIsString($runtime->render(new Chess()));
    }
}
