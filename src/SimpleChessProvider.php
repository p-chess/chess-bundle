<?php

namespace PChess\ChessBundle;

use PChess\Chess\Chess;

final class SimpleChessProvider implements ChessProviderInterface
{
    public function getChess($identifier = null, ?string $fen = null): Chess
    {
        return new Chess($fen);
    }
}
