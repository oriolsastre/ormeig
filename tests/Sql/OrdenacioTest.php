<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Sql;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Atributs\Columna as ColumnaAtribut;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Columna;
use Sastreo\Ormeig\Enums\Ordenacio as OrdenacioEnum;
use Sastreo\Ormeig\Excepcions\ColumnaNoExisteix;
use Sastreo\Ormeig\Model;
use Sastreo\Ormeig\Sql\Ordenacio;
use Sastreo\Ormeig\Tests\Models\TestModelPk;

#[CoversClass(Ordenacio::class)]
#[UsesClass(Columna::class)]
#[UsesClass(ColumnaAtribut::class)]
#[UsesClass(Taula::class)]
#[UsesClass(ColumnaNoExisteix::class)]
#[UsesClass(Model::class)]
class OrdenacioTest extends TestCase
{
    #[Test]
    public function testConstructor(): void
    {
        $ordenacio = new Ordenacio(new Columna(TestModelPk::class, 'testId'), OrdenacioEnum::ASC);
        $this->assertInstanceOf(Ordenacio::class, $ordenacio);

        // Error
        $this->expectException(ColumnaNoExisteix::class);
        new Ordenacio(new Columna(TestModelPk::class, 'columnaInexistent'), OrdenacioEnum::ASC);
    }

    #[Test]
    public function testToSql(): void
    {
        $ordenacio = new Ordenacio(new Columna(TestModelPk::class, 'testId'), OrdenacioEnum::ASC);
        $this->assertEquals('test.testId ASC', $ordenacio->toSql());
    }
}
