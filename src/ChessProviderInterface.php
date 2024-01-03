<?php

namespace PChess\ChessBundle;

use PChess\Chess\Chess;

interface ChessProviderInterface
{
    public function getChess(mixed $identifier = null, string $fen = null): Chess;
}
