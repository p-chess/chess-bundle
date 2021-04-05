<?php

namespace PChess\ChessBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ChessBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
