<?php

namespace PChess\ChessBundle;

use PChess\Chess\Board;
use PChess\Chess\Chess;
use PChess\Chess\Output\HtmlOutput as ChessHtmlOutput;
use PChess\Chess\Output\Link;
use PChess\Chess\Piece;

abstract class HtmlOutput extends ChessHtmlOutput
{
    abstract public function getStartUrl(string $from, mixed $identifier = null): string;

    abstract public function getEndUrl(string $from, string $to, mixed $identifier = null): string;

    abstract public function getCancelUrl(mixed $identifier = null): string;

    abstract public function getPromotionUrl(string $from, string $to, mixed $identifier = null): string;

    public function generateLinks(Chess $chess, string $from = null, mixed $identifier = null): array
    {
        $links = [];
        $allowedMoves = self::getAllowedMoves($chess, $from);
        /** @var int $i */
        foreach ($chess->board as $i => $piece) {
            $url = null;
            $class = null;
            $san = Board::algebraic($i);
            if (null === $from) {
                // move not started
                if (null !== $piece && isset($allowedMoves[$san]) && self::isTurn($chess, $piece)) {
                    $url = $this->getStartUrl($san, $identifier);
                }
            } elseif ($from !== $san) {
                // move started
                if (self::canMove($from, $i, $allowedMoves)) {
                    if (null !== $movingPiece = $chess->board[Board::SQUARES[$from]]) {
                        if ('p' === $movingPiece->getType() && (0 === Board::rank($i) || 7 === Board::rank($i))) {
                            $url = $this->getPromotionUrl($from, $san, $identifier);
                        } else {
                            $url = $this->getEndUrl($from, $san, $identifier);
                        }
                    }
                    $class = 'target';
                }
            } else {
                // restart move
                $url = $this->getCancelUrl($identifier);
                $class = 'current';
            }
            $links[$i] = new Link($class, $url);
        }

        return $links;
    }

    /**
     * @return array<string, array<int, string>>
     */
    private static function getAllowedMoves(Chess $chess, string $from = null): array
    {
        $moves = $chess->moves($from ? Board::SQUARES[$from] : null);
        $return = [];
        foreach ($moves as $move) {
            $return[$move->from][] = (string) $move->san;
        }

        return $return;
    }

    private static function isTurn(Chess $chess, Piece $piece): bool
    {
        return $piece->getColor() === $chess->turn;
    }

    /**
     * @param array<string, array<int, string>> $allowedMoves Moves resulting from self::getAllowedMoves()
     */
    private static function canMove(string $from, int $to, array $allowedMoves): bool
    {
        $toSan = Board::algebraic($to);
        if (!isset($allowedMoves[$from])) {
            return false;
        }
        $cleanMoves = \array_map(static function (string $san) use ($from): string {
            $check = \substr($san, -1);
            $equalsPos = \strpos($san, '=');
            if ('+' === $check || '#' === $check) {
                $san = \substr($san, 0, -1);
            } elseif ('O-O-O' === $san) {
                $san = 'e1' === $from ? 'c1' : 'c8';
            } elseif ('O-O' === $san) {
                $san = 'e1' === $from ? 'g1' : 'g8';
            } elseif (false !== $equalsPos) {
                $san = \substr($san, 0, $equalsPos);
            }

            return \substr($san, -2);
        }, $allowedMoves[$from]);

        return \in_array($toSan, $cleanMoves, true);
    }
}
