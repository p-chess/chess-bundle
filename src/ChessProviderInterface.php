<?php

namespace PChess\ChessBundle;

use PChess\Chess\Chess;

interface ChessProviderInterface
{
    public function getChess(?string $fen = null): Chess;
}
