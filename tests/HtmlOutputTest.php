<?php

namespace PChess\ChessBundle\Tests;

use PChess\Chess\Chess;
use PHPUnit\Framework\TestCase;

final class HtmlOutputTest extends TestCase
{
    public function testRenderWithoutFrom(): void
    {
        $output = new HtmlOutputStub();
        $rendered = $output->render(new Chess());
        self::assertStringStartsWith('<table', $rendered, 'no move can end here, hence no form is needed');
        self::assertStringNotContainsString('<button', $rendered);
        self::assertStringContainsString('<a class="wp" href="start/e2"></a>', $rendered);
    }

    public function testRenderWithFrom(): void
    {
        $output = new HtmlOutputStub();
        $rendered = $output->render(new Chess(), 'e2');
        self::assertStringStartsWith('<form method="post">', $rendered);
        self::assertStringEndsWith('</form>', $rendered);
        self::assertStringContainsString('<input type="hidden" name="_token" value="a&amp;token">', $rendered);
        self::assertStringContainsString('<button type="submit" formaction="end/e2/e4"></button>', $rendered);
        // starting a move and cancelling it are plain links
        self::assertStringContainsString('<a class="wp" href="cancel"></a>', $rendered);
    }

    public function testRenderPromotionTargetIsALink(): void
    {
        $output = new HtmlOutputStub();
        $chess = new Chess('rnbqkbnr/pp1ppppp/4P3/8/8/3P1N2/PpP2PPP/RNBQKB1R b KQkq - 0 5');
        $rendered = $output->render($chess, 'b2');
        // choosing the promoted piece does not change the game yet
        self::assertStringStartsWith('<table', $rendered);
        self::assertStringContainsString('<a class="wr" href="promotion/b2/a1"></a>', $rendered);
    }

    public function testRenderPromotion(): void
    {
        $output = new HtmlOutputStub();
        $chess = new Chess('rnbqkbnr/pp1ppppp/4P3/8/8/3P1N2/PpP2PPP/RNBQKB1R b KQkq - 0 5');
        self::assertNotNull($output->render($chess, 'b2'));
    }

    public function testRenderCastlingShort(): void
    {
        $output = new HtmlOutputStub();
        $chess = new Chess('rn1qkbnr/pppb1ppp/3p4/4p3/4P3/5N2/PPPPBPPP/RNBQK2R w KQkq - 2 4');
        self::assertNotNull($output->render($chess, 'e1'));
    }

    public function testRenderCastlingLong(): void
    {
        $output = new HtmlOutputStub();
        $chess = new Chess('rn1qk2r/pppbbppp/4pn2/3p4/3P4/2N1P3/PPPBQPPP/R3KBNR w KQkq - 3 6');
        self::assertNotNull($output->render($chess, 'e1'));
    }

    public function testRenderCheck(): void
    {
        $output = new HtmlOutputStub();
        $chess = new Chess('rnbqkbnr/ppppp1pp/5p2/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq - 0 2');
        self::assertNotNull($output->render($chess, 'd1'));
    }
}
