<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use PChess\ChessBundle\SessionChessProvider;
use PChess\ChessBundle\SimpleChessProvider;
use PChess\ChessBundle\Twig\ChessExtension;
use PChess\ChessBundle\Twig\ChessRuntime;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->set(SessionChessProvider::class)
        ->arg('$requestStack', service('request_stack'))
        ->arg('$name', '%chess.session_name%')
    ;

    $services->set(SimpleChessProvider::class);

    $services
        ->set(ChessExtension::class)
        ->tag('twig.extension')
    ;

    $services
        ->set(ChessRuntime::class)
        ->arg('$output', abstract_arg('should be defined by Pass'))
        ->tag('twig.runtime')
    ;
};
