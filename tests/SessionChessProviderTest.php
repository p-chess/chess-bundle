<?php

namespace PChess\ChessBundle\Tests;

use PChess\Chess\Board;
use PChess\Chess\Chess;
use PChess\ChessBundle\Mover;
use PChess\ChessBundle\SessionChessProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

final class SessionChessProviderTest extends TestCase
{
    public function testGetChess(): void
    {
        $provider = new SessionChessProvider(self::getStack(), 'chess');
        self::assertNotNull($provider->getChess());
    }

    public function testGetChessWithInvalidFen(): void
    {
        $provider = new SessionChessProvider(self::getStack(), 'chess');
        $this->expectException(\InvalidArgumentException::class);
        $provider->getChess(null, 'invalid!');
    }

    public function testGetChessWithValidFen(): void
    {
        $provider = new SessionChessProvider(self::getStack(), 'chess');
        $fen = 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1';
        $chess = $provider->getChess(null, $fen);
        self::assertEquals($fen, $chess->fen());
    }

    public function testRestart(): void
    {
        $provider = new SessionChessProvider(self::getStack(), 'chess');
        $chess = $provider->getChess();
        $chess->move('e4');
        $provider->save($chess);
        $provider->restart();
        self::assertEquals(Board::DEFAULT_POSITION, $provider->getChess()->fen());
    }

    public function testSave(): void
    {
        $stack = self::getStack();
        $provider = new SessionChessProvider($stack, 'chess');
        $chess = new Chess();
        $provider->save($chess);
        self::assertSame($chess, $stack->getCurrentRequest()->getSession()->get('chess'));
    }

    public function testReverse(): void
    {
        $provider = new SessionChessProvider(self::getStack(), 'chess');
        $provider->reverse();
        self::assertTrue($provider->getChess()->board->isReversed());
    }

    public function testGetAllowedMoves(): void
    {
        self::assertNotNull(Mover::getAllowedMoves(new Chess()));
    }

    public function testSessionException(): void
    {
        $this->expectException(\RuntimeException::class);
        $provider = new SessionChessProvider(new RequestStack(), 'chess');
        $provider->getChess();
    }

    private static function getStack(): RequestStack
    {
        $fakeRequest = Request::create('/');
        $fakeRequest->setSession(new Session(new MockArraySessionStorage()));
        $stack = new RequestStack();
        $stack->push($fakeRequest);

        return $stack;
    }
}
