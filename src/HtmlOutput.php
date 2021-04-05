<?php

namespace PChess\ChessBundle;

use PChess\Chess\Board;
use PChess\Chess\Chess;
use PChess\Chess\Output\HtmlOutput as ChessHtmlOutput;
use PChess\Chess\Output\Link;
use PChess\Chess\Piece;
use Symfony\Component\Routing\RouterInterface;

final class HtmlOutput extends ChessHtmlOutput
{
    private RouterInterface $router;

    private string $startRoute;

    private string $cancelRoute;

    private string $endRoute;

    private string $promotionRoute;

    public function __construct(RouterInterface $router, string $start, string $cancel, string $end, string $promotion)
    {
        $this->router = $router;
        $this->startRoute = $start;
        $this->cancelRoute = $cancel;
        $this->endRoute = $end;
        $this->promotionRoute = $promotion;
    }

    public function generateLinks(Chess $chess, ?string $from = null): array
    {
        $links = [];
        $allowedMoves = $this->getAllowedMoves($chess, $from);
        /** @var int $i */
        foreach ($chess->board as $i => $piece) {
            $url = null;
            $class = null;
            $san = Board::algebraic($i);
            if (null === $from) {
                // move not started
                if (null !== $piece && isset($allowedMoves[$san]) && $this->isTurn($chess, $piece)) {
                    $url = $this->router->generate($this->startRoute, ['from' => $san]);
                }
            } elseif ($from !== $san) {
                // move started
                if ($this->canMove($from, $i, $allowedMoves)) {
                    if (null !== $movingPiece = $chess->board[Board::SQUARES[$from]]) {
                        if ('p' === $movingPiece->getType() && (0 === Board::rank($i) || 7 === Board::rank($i))) {
                            $url = $this->router->generate($this->promotionRoute, ['from' => $from, 'to' => $san]);
                        } else {
                            $url = $this->router->generate($this->endRoute, ['from' => $from, 'to' => $san]);
                        }
                    }
                    $class = 'target';
                }
            } else {
                // restart move
                $url = $this->router->generate($this->cancelRoute);
                $class = 'current';
            }
            $links[$i] = new Link($class, $url);
        }

        return $links;
    }

    /**
     * @return array<string, array<int, string>>
     */
    private function getAllowedMoves(Chess $chess, ?string $from = null): array
    {
        $moves = $chess->moves($from ? Board::SQUARES[$from] : null);
        $return = [];
        foreach ($moves as $move) {
            $return[$move->from][] = (string) $move->san;
        }

        return $return;
    }

    private function isTurn(Chess $chess, Piece $piece): bool
    {
        return $piece->getColor() === $chess->turn;
    }

    /**
     * @param array<string, array> $allowedMoves
     */
    private function canMove(string $from, int $to, array $allowedMoves): bool
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
