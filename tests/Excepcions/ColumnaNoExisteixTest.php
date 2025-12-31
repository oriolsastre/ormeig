<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Excepcions;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Excepcions\ColumnaNoExisteix;
use Throwable;
use ValueError;

#[CoversClass(ColumnaNoExisteix::class)]
class ColumnaNoExisteixTest extends TestCase
{
    public function testColumnaNoExisteix(): void
    {
        $ColumnaNoExisteix = new ColumnaNoExisteix("TestColumna", "TestModel");
        $this->assertInstanceOf(ColumnaNoExisteix::class, $ColumnaNoExisteix);
        $this->assertInstanceOf(ValueError::class, $ColumnaNoExisteix);
        $this->assertInstanceOf(Throwable::class, $ColumnaNoExisteix);
    }
}
