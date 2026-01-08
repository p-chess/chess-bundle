<?php

namespace PChess\ChessBundle\Tests\DependencyInjection;

use PChess\ChessBundle\DependencyInjection\ChessExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ChessExtensionTest extends TestCase
{
    public function testLoadSetParameters(): void
    {
        /** @var ContainerBuilder&\PHPUnit\Framework\MockObject\Stub $container */
        $container = $this->createStub(ContainerBuilder::class);
        $extension = new ChessExtension();
        $extension->load(['chess' => ['output_service' => 'foo']], $container);
        self::assertNotNull($extension);
    }
}
