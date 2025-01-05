<?php

namespace PChess\ChessBundle;

use PChess\Chess\Board;
use PChess\Chess\Chess;
use PChess\Chess\Entry;
use PChess\Chess\History;
use PChess\Chess\Move;
use PChess\Chess\Piece;

final class Mover
{
    /**
     * Get an array of moves with starting squares as keys.
     *
     * @return array<string, array<int, string>>
     */
    public static function getAllowedMoves(Chess $chess, ?string $from = null): array
    {
        $moves = $chess->moves($from ? Board::SQUARES[$from] : null);
        $return = [];
        foreach ($moves as $move) {
            $return[$move->from][] = (string) $move->san;
        }

        return $return;
    }

    /**
     * Transform history entries into simplified strings.
     * Useful to store entries in a simple_array Doctrine field.
     *
     * @return array<int, string>
     */
    public static function getHistoryStrings(Chess $chess): array
    {
        return \array_map(
            static fn (Entry $entry): string => self::entryToString($entry),
            $chess->getHistory()->getEntries()
        );
    }

    /**
     * Revert the results of self::getHistoryStrings().
     *
     * @param array<int, string> $strings
     */
    public static function getHistoryEntries(array $strings): History
    {
        return new History(\array_map(
            static fn (string $entry): Entry => self::stringToEntry($entry),
            $strings
        ));
    }

    /**
     * Get a string from an Entry.
     */
    private static function entryToString(Entry $entry): string
    {
        $parts = [
            $entry->move->turn,
            $entry->move->flags,
            $entry->move->piece->getType(),
            $entry->move->piece->getColor(),
            $entry->move->fromSquare,
            $entry->move->toSquare,
            $entry->move->captured,
            $entry->move->promotion,
            $entry->move->san,
            \substr((string) $entry->position, 1, -1),
            \reset($entry->kings),
            \end($entry->kings),
            \reset($entry->castling),
            \end($entry->castling),
            $entry->epSquare,
            $entry->halfMoves,
            $entry->moveNumber,
        ];

        return \implode('|', $parts);
    }

    /**
     * Re-build Entry object from a string built using self::entryToString().
     */
    private static function stringToEntry(string $string): Entry
    {
        $parts = \explode('|', $string);
        $piece = new Piece($parts[2], $parts[3]);
        $captured = '' === $parts[6] ? null : $parts[6];
        $promotion = '' === $parts[7] ? null : $parts[7];
        $epSquare = '' === $parts[14] ? null : (int) $parts[14];
        $move = new Move($parts[0], (int) $parts[1], $piece, (int) $parts[4], (int) $parts[5], $captured, $promotion);
        $move->san = $parts[8];

        return new Entry(
            $move,
            '"'.$parts[9].'"',
            ['w' => (int) $parts[10], 'b' => (int) $parts[11]],
            ['w' => (int) $parts[12], 'b' => (int) $parts[13]],
            $epSquare,
            (int) $parts[15],
            (int) $parts[16]
        );
    }
}
