<?php

namespace PChess\ChessBundle;

use PChess\Chess\Chess;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SessionChessProvider implements ChessProviderInterface
{
    private RequestStack $requestStack;

    private ?SessionInterface $session = null;

    private string $name;

    public function __construct(RequestStack $requestStack, string $name)
    {
        $this->requestStack = $requestStack;
        $this->name = $name;
    }

    /**
     * @param mixed                                  $identifier
     * @param array<int, \PChess\Chess\History>|null $history
     */
    public function getChess($identifier = null, ?string $fen = null, ?array $history = null): Chess
    {
        $name = $this->name.$identifier;
        $chess = $this->getSession()->has($name) ? $this->getSession()->get($name) : new Chess($fen, $history);
        $this->save($chess, $identifier);

        return $chess;
    }

    /** @param mixed $identifier */
    public function restart($identifier = null): void
    {
        $this->getSession()->remove($this->name.$identifier);
    }

    /** @param mixed $identifier */
    public function save(Chess $chess, $identifier = null): void
    {
        $this->getSession()->set($this->name.$identifier, $chess);
    }

    /** @param mixed $identifier */
    public function reverse($identifier = null): void
    {
        $chess = $this->getChess($identifier);
        $chess->board->reverse();
        $this->save($chess, $identifier);
    }

    private function getSession(): SessionInterface
    {
        if (null === $this->session) {
            if (null === $request = $this->requestStack->getCurrentRequest()) {
                throw new \RuntimeException('Cannot find session.');
            }
            $this->session = $request->getSession();
        }

        return $this->session;
    }
}
