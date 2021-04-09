<?php

namespace PChess\ChessBundle;

use PChess\Chess\Chess;

interface ChessProviderInterface
{
    /** @param mixed $identifier */
    public function getChess($identifier = null, ?string $fen = null): Chess;
}
