<?php

namespace PChess\ChessBundle\Twig;

use PChess\ChessBundle\HtmlOutput;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ChessExtension extends AbstractExtension
{
    private HtmlOutput $output;

    public function __construct(HtmlOutput $output)
    {
        $this->output = $output;
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
