<?php

namespace PChess\ChessBundle;

use PChess\Chess\Chess;

final class SimpleChessProvider implements ChessProviderInterface
{
    public function getChess(?string $fen = null): Chess
    {
        $chess = new Chess();
        if ((null !== $fen) && false === $chess->load($fen)) {
            throw new \InvalidArgumentException('Invalid FEN!');
        }

        return $chess;
    }
}
