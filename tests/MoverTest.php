<?php

use PChess\Chess\Chess;
use PChess\Chess\Entry;
use PChess\Chess\History;
use PChess\Chess\Move;
use PChess\Chess\Piece;
use PChess\ChessBundle\Mover;

final class MoverTest extends \PHPUnit\Framework\TestCase
{
    public function testGetAllowedMoves(): void
    {
        self::assertNotNull(Mover::getAllowedMoves(new Chess()));
    }

    public function testGetHistoryStringsAndEntries(): void
    {
        $entry = new Entry(
            new Move('w', 1, new Piece('p', 'w'), 100, 84),
            null,
            ['w' => 0, 'b' => 0],
            ['w' => 0, 'b' => 0],
            null,
            0,
            1,
        );
        $chess = new Chess(null, new History([$entry]));
        $strings = Mover::getHistoryStrings($chess);
        self::assertCount(1, $strings);
        $history = Mover::getHistoryEntries($strings);
        self::assertCount(1, $history->getEntries());
    }
}
