<?php

namespace PChess\ChessBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ChessExtension extends AbstractExtension
{
    /**
     * @codeCoverageIgnore
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('chess_render', [ChessRuntime::class, 'render'], ['is_safe' => ['html']]),
        ];
    }
}
