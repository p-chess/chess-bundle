<?php

namespace PChess\ChessBundle\Twig;

use PChess\ChessBundle\HtmlOutput;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ChessExtension extends AbstractExtension
{
    public function __construct(private HtmlOutput $output)
    {
    }

    /**
     * @codeCoverageIgnore
     *
     * @return array<int, TwigFunction>
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('chess_render', [$this->output, 'render'], ['is_safe' => ['html']]),
        ];
    }
}
