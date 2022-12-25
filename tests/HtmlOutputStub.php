<?php

namespace PChess\ChessBundle\Tests;

use PChess\ChessBundle\HtmlOutput;

final class HtmlOutputStub extends HtmlOutput
{
    public function getStartUrl(string $from, mixed $identifier = null): string
    {
        return 'start/'.$from;
    }

    public function getEndUrl(string $from, string $to, mixed $identifier = null): string
    {
        return 'end/'.$from.'/'.$to;
    }

    public function getCancelUrl(mixed $identifier = null): string
    {
        return 'cancel';
    }

    public function getPromotionUrl(string $from, string $to, mixed $identifier = null): string
    {
        return 'promotion/'.$from.'/'.$to;
    }
}
