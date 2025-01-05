<?php

namespace PChess\ChessBundle\Twig;

use PChess\Chess\Chess;
use PChess\ChessBundle\HtmlOutput;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ChessRuntime extends AbstractExtension
{
    public function __construct(private HtmlOutput $output)
    {
    }

    public function render(Chess $chess, ?string $from = null, mixed $identifier = null): string
    {
        return $this->output->render($chess, $from, $identifier);
    }
}
