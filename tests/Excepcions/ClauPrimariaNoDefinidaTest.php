<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Excepcions;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Excepcions\ClauPrimariaNoDefinida;
use Throwable;
use ValueError;

#[CoversClass(ClauPrimariaNoDefinida::class)]
class ClauPrimariaNoDefinidaTest extends TestCase
{
    public function testClauPrimariaNoDefinida(): void
    {
        $clauPrimariaNoDefinida = new ClauPrimariaNoDefinida('TestModel');
        $this->assertInstanceOf(ClauPrimariaNoDefinida::class, $clauPrimariaNoDefinida);
        $this->assertInstanceOf(ValueError::class, $clauPrimariaNoDefinida);
        $this->assertInstanceOf(Throwable::class, $clauPrimariaNoDefinida);
    }
}
