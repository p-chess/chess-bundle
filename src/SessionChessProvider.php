<?php

namespace PChess\ChessBundle;

use PChess\Chess\Chess;
use PChess\Chess\History;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SessionChessProvider implements ChessProviderInterface
{
    private ?SessionInterface $session = null;

    public function __construct(private RequestStack $requestStack, private string $name)
    {
    }

    public function getChess(mixed $identifier = null, ?string $fen = null, ?History $history = null): Chess
    {
        $name = $this->name.$identifier;
        $chess = $this->getSession()->has($name) ? $this->getSession()->get($name) : new Chess($fen, $history);
        $this->save($chess, $identifier);

        return $chess;
    }

    public function restart(mixed $identifier = null): void
    {
        $this->getSession()->remove($this->name.$identifier);
    }

    public function save(Chess $chess, mixed $identifier = null): void
    {
        $this->getSession()->set($this->name.$identifier, $chess);
    }

    public function reverse(mixed $identifier = null): void
    {
        $chess = $this->getChess($identifier);
        $chess->board->reverse();
        $this->save($chess, $identifier);
    }

    private function getSession(): SessionInterface
    {
        if (null === $this->session) {
            if (null === $request = $this->requestStack->getCurrentRequest()) {
                throw new \UnexpectedValueException('Cannot find session.');
            }
            $this->session = $request->getSession();
        }

        return $this->session;
    }
}
