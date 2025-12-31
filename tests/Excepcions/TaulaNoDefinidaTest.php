<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Excepcions;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Excepcions\TaulaNoDefinida;
use Throwable;
use TypeError;

#[CoversClass(TaulaNoDefinida::class)]
class TaulaNoDefinidaTest extends TestCase
{
    public function testConstructor(): void
    {
        $taulaNoDefinida = new TaulaNoDefinida('testModel');
        $this->assertInstanceOf(TaulaNoDefinida::class, $taulaNoDefinida);
        $this->assertInstanceOf(TypeError::class, $taulaNoDefinida);
        $this->assertInstanceOf(Throwable::class, $taulaNoDefinida);
    }
}
