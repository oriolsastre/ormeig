<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Excepcions;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Excepcions\ClauPrimariaInvalida;
use Sastreo\Ormeig\Tests\Models\TestEntitat;

#[CoversClass(ClauPrimariaInvalida::class)]
class ClauPrimariaInvalidaTest extends TestCase
{
    public function testClauPrimariaInvalida(): void
    {
        $clauPrimariaInvalida = new ClauPrimariaInvalida(TestEntitat::class, 1);
        $this->assertInstanceOf(ClauPrimariaInvalida::class, $clauPrimariaInvalida);
        $this->assertInstanceOf(\ValueError::class, $clauPrimariaInvalida);
        $this->assertInstanceOf(\Throwable::class, $clauPrimariaInvalida);
    }
}
