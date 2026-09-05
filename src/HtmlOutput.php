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

    public function generateLinks(Chess $chess, ?string $from = null, mixed $identifier = null): array
    {
        $links = [];
        $allowedMoves = Mover::getAllowedMoves($chess, $from);
        /** @var int $i */
        foreach ($chess->board as $i => $piece) {
            $url = null;
            $class = null;
            $unsafe = false;
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
                            // the promotion page just asks which piece to promote to: nothing changes yet
                            $url = $this->getPromotionUrl($from, $san, $identifier);
                        } else {
                            $url = $this->getEndUrl($from, $san, $identifier);
                            $unsafe = true;
                        }
                    }
                    $class = 'target';
                }
            } else {
                // restart move
                $url = $this->getCancelUrl($identifier);
                $class = 'current';
            }
            $links[$i] = new Link($class, $url, $unsafe);
        }

        return $links;
    }

    private static function isTurn(Chess $chess, Piece $piece): bool
    {
        return $piece->getColor() === $chess->turn;
    }

    /**
     * @param array<string, array<int, string>> $allowedMoves Moves resulting from Mover::getAllowedMoves()
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
