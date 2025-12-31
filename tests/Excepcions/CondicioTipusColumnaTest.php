<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Excepcions;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Excepcions\CondicioTipusColumna;

#[CoversClass(CondicioTipusColumna::class)]
class CondicioTipusColumnaTest extends TestCase
{
    #[Test]
    public function test(): void
    {
        $this->assertTrue(true);
    }
}
