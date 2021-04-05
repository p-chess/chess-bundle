<?php

namespace PChess\ChessBundle;

use PChess\Chess\Board;
use PChess\Chess\Chess;
use PChess\Chess\Validation;
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

    public function getChess(?string $fen = null): Chess
    {
        $chess = $this->getSession()->has($this->name) ? $this->getSession()->get($this->name) : new Chess();
        if (null !== $fen) {
            if (false === $chess->load($fen)) {
                $error = Validation::validateFen($fen);
                throw new \InvalidArgumentException('Invalid FEN! '.$error['error']);
            }
            $this->save($chess);
        }

        return $chess;
    }

    public function restart(): void
    {
        $this->getSession()->remove($this->name);
    }

    public function save(Chess $chess): void
    {
        $this->getSession()->set($this->name, $chess);
    }

    public function reverse(): void
    {
        $chess = $this->getChess();
        $chess->board->reverse();
        $this->save($chess);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function getAllowedMoves(Chess $chess, ?string $from = null): array
    {
        $moves = $chess->moves($from ? Board::SQUARES[$from] : null);
        $return = [];
        foreach ($moves as $move) {
            $return[$move->from][] = (string) $move->san;
        }

        return $return;
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
