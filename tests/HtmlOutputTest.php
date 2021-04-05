<?php

namespace PChess\ChessBundle\Tests;

use PChess\Chess\Chess;
use PChess\ChessBundle\HtmlOutput;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;

final class HtmlOutputTest extends TestCase
{
    public function testRenderWithoutFrom(): void
    {
        $routing = $this->createMock(RouterInterface::class);
        $output = new HtmlOutput($routing, 'start', 'cancel', 'end', 'promotion');
        self::assertNotNull($output->render(new Chess()));
    }

    public function testRenderWithFrom(): void
    {
        $routing = $this->createMock(RouterInterface::class);
        $output = new HtmlOutput($routing, 'start', 'cancel', 'end', 'promotion');
        self::assertNotNull($output->render(new Chess(), 'e2'));
    }

    public function testRenderPromotion(): void
    {
        $routing = $this->createMock(RouterInterface::class);
        $output = new HtmlOutput($routing, 'start', 'cancel', 'end', 'promotion');
        $chess = new Chess('rnbqkbnr/pp1ppppp/4P3/8/8/3P1N2/PpP2PPP/RNBQKB1R b KQkq - 0 5');
        self::assertNotNull($output->render($chess, 'b2'));
    }

    public function testRenderCastlingShort(): void
    {
        $routing = $this->createMock(RouterInterface::class);
        $output = new HtmlOutput($routing, 'start', 'cancel', 'end', 'promotion');
        $chess = new Chess('rn1qkbnr/pppb1ppp/3p4/4p3/4P3/5N2/PPPPBPPP/RNBQK2R w KQkq - 2 4');
        self::assertNotNull($output->render($chess, 'e1'));
    }

    public function testRenderCastlingLong(): void
    {
        $routing = $this->createMock(RouterInterface::class);
        $output = new HtmlOutput($routing, 'start', 'cancel', 'end', 'promotion');
        $chess = new Chess('rn1qk2r/pppbbppp/4pn2/3p4/3P4/2N1P3/PPPBQPPP/R3KBNR w KQkq - 3 6');
        self::assertNotNull($output->render($chess, 'e1'));
    }

    public function testRenderCheck(): void
    {
        $routing = $this->createMock(RouterInterface::class);
        $output = new HtmlOutput($routing, 'start', 'cancel', 'end', 'promotion');
        $chess = new Chess('rnbqkbnr/ppppp1pp/5p2/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq - 0 2');
        self::assertNotNull($output->render($chess, 'd1'));
    }
}
