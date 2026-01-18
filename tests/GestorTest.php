<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesFunction;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Atributs\Columna as ColumnaAtribut;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Columna;
use Sastreo\Ormeig\Gestor;
use Sastreo\Ormeig\Tests\Mocks\MockFabrica;
use Sastreo\Ormeig\Tests\Models\TestEntitat;
use Sastreo\Ormeig\Tests\Models\TestModelMultiplePk;
use Sastreo\Ormeig\Tests\Models\TestModelPk;
use Sastreo\Ormeig\Tests\Models\TestUsuari;

#[CoversClass(Gestor::class)]
#[UsesClass(Taula::class)]
#[UsesClass(ColumnaAtribut::class)]
#[UsesClass(Columna::class)]
#[UsesFunction('Sastreo\Ormeig\classEsModel')]
#[UsesFunction('Sastreo\Ormeig\getClausPrimaries')]
#[UsesFunction('Sastreo\Ormeig\getValorColumnaModel')]
class GestorTest extends TestCase
{
    private MockFabrica $mockFabrica;

    public function __construct(string $name)
    {
        $this->mockFabrica = new MockFabrica($name);

        return parent::__construct($name);
    }

    #[Test]
    public function testConstructor(): void
    {
        $mockOrmeig = $this->mockFabrica->mockOrmeig();
        $gestor = new Gestor($mockOrmeig, TestModelPk::class);
        $this->assertInstanceOf(Gestor::class, $gestor);
        $this->assertEquals(TestModelPk::class, $gestor->getModel());
    }

    #region getTaula
    #[Test]
    public function testGetTaulaNoDefinida(): void
    {
        $mockOrmeig = $this->mockFabrica->mockOrmeig();
        $gestor = new Gestor($mockOrmeig, TestEntitat::class);
        $this->assertIsString($gestor->getTaula());
        $this->assertEquals('TestEntitat', $gestor->getTaula());
    }

    #[Test]
    public function testGetTaulaDefinida(): void
    {
        $mockOrmeig = $this->mockFabrica->mockOrmeig();
        $gestor = new Gestor($mockOrmeig, TestUsuari::class);
        $this->assertIsString($gestor->getTaula());
        $this->assertEquals('usuari', $gestor->getTaula());
    }
    #endregion

    #[Test]
    public function testEliminar(): void
    {
        $gestor = $this->getGestorMock(TestUsuari::class);
        $gestor->expects($this->once())->method('consulta');
        $gestor->expects($this->once())->method('condicio');

        $usuari = new TestUsuari(1);
        $this->assertNull($gestor->eliminar($usuari));
    }

    #[Test]
    public function testEliminarMultiplePk(): void
    {
        $gestor = $this->getGestorMock(TestModelMultiplePk::class);
        $gestor->expects($this->once())->method('consulta');
        $gestor->expects($this->exactly(2))->method('condicio');

        $this->assertNull($gestor->eliminar(new TestModelMultiplePk(1, 2)));
    }

    #[Test]
    public function testEliminarThrows(): void
    {
        $gestor = $this->getGestorMock(TestUsuari::class);
        $gestor->expects($this->exactly(0))->method('consulta');
        $gestor->expects($this->exactly(0))->method('condicio');

        $this->expectException(\TypeError::class);
        $incorrectObjectButTaula = new TestModelMultiplePk();
        $gestor->eliminar($incorrectObjectButTaula);
    }

    private function getGestorMock(string $model): Gestor|\PHPUnit\Framework\MockObject\MockObject
    {
        $gestor = $this->getMockBuilder(Gestor::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getModel', 'executaConsulta', 'consulta', 'condicio'])
            ->getMock();
        $gestor->method('getModel')->willReturn($model);
        $gestor->method('executaConsulta')->willReturn(null);

        return $gestor;
    }
}
