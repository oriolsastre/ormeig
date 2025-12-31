<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;
use Sastreo\Ormeig\Atributs\Columna as AtributsColumna;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Columna;
use Sastreo\Ormeig\Excepcions\ColumnaNoExisteix;
use Sastreo\Ormeig\Excepcions\TaulaNoDefinida;
use Sastreo\Ormeig\Tests\Models\TestModelPk;
use Sastreo\Ormeig\Tests\Models\TestModelTaulaNoDefinida;

#[CoversClass(Columna::class)]
#[UsesClass(AtributsColumna::class)]
#[UsesClass(Taula::class)]
#[UsesClass(ColumnaNoExisteix::class)]
#[UsesClass(TaulaNoDefinida::class)]
class ColumnaTest extends TestCase
{
    #[Test]
    public function testConstructor(): void
    {
        $columna = new Columna(TestModelPk::class, 'testId');
        $this->assertInstanceOf(Columna::class, $columna);
        $this->assertEquals(TestModelPk::class, $columna->model);
        $this->assertEquals('testId', $columna->columna);
        $this->assertEquals('testId', $columna->columnaSql);
        $this->assertEquals('int', $columna->tipus);
        $this->assertEquals('test.testId', (string) $columna);

        // Error
        $this->expectException(ColumnaNoExisteix::class);
        new Columna(TestModelPk::class, 'columnaInexistent');
    }

    public function testNomCanviat(): void
    {
        // Nom canviat a l'atribut, però ens referim pel nom a la classe.
        $columnaNom = new Columna(TestModelPk::class, 'testNom');
        $this->assertInstanceOf(Columna::class, $columnaNom);
        $this->assertEquals(TestModelPk::class, $columnaNom->model);
        $this->assertEquals('testNom', $columnaNom->columna);
        $this->assertEquals('test_nom', $columnaNom->columnaSql);
        $this->assertEquals('string', $columnaNom->tipus);
        $this->assertEquals('test.test_nom', (string) $columnaNom);

        // No ens hi podem referir pel nom canviat a l'atribut.
        $this->expectException(ColumnaNoExisteix::class);
        new Columna(TestModelPk::class, 'test_nom');
    }

    public function testErrorPropietatNoDefinidaComColumna(): void
    {
        $reflectModel = new ReflectionClass(TestModelPk::class);
        // Confirmem que la propietat noColumna existeix
        $reflectProperty = $reflectModel->getProperty('noColumna');
        $this->assertInstanceOf(ReflectionProperty::class, $reflectProperty);

        // L'error al intentar crear-ne una columna
        $this->expectException(ColumnaNoExisteix::class);
        new Columna(TestModelPk::class, 'noColumna');
    }

    public function testErrorTaulaNoDefinida(): void
    {
        $this->expectException(TaulaNoDefinida::class);
        new Columna(TestModelTaulaNoDefinida::class, 'testId');
    }
}
