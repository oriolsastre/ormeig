<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Atributs\Columna as ColumnaAtribut;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Columna;
use Sastreo\Ormeig\Consulta;
use Sastreo\Ormeig\Enums\Comparacio;
use Sastreo\Ormeig\Enums\Consulta as EnumsConsulta;
use Sastreo\Ormeig\Enums\Ordenacio as OrdenacioEnum;
use Sastreo\Ormeig\Excepcions\TaulaNoDefinida;
use Sastreo\Ormeig\Logic\OperadorLogic;
use Sastreo\Ormeig\Model;
use Sastreo\Ormeig\Sql\Condicio;
use Sastreo\Ormeig\Sql\Ordenacio;
use Sastreo\Ormeig\Tests\Mocks\MockFabrica;
use Sastreo\Ormeig\Tests\Models\TestModelPk;
use Sastreo\Ormeig\Tests\Models\TestModelTaulaNoDefinida;

#[CoversClass(Consulta::class)]
#[UsesClass(Columna::class)]
#[UsesClass(ColumnaAtribut::class)]
#[UsesClass(Condicio::class)]
#[UsesClass(Taula::class)]
#[UsesClass(OperadorLogic::class)]
#[UsesClass(Ordenacio::class)]
#[UsesClass(TaulaNoDefinida::class)]
#[UsesClass(Model::class)]
class ConsultaTest extends TestCase
{
    #[Test]
    public function testConstructor(): void
    {
        $mockFabrica = new MockFabrica();
        $mockGestor = $mockFabrica->mockGestor();
        $consulta = new Consulta($mockGestor->getModel());
        $this->assertInstanceOf(Consulta::class, $consulta);

        // Error taula no definida.
        $this->expectException(TaulaNoDefinida::class);
        new Consulta(TestModelTaulaNoDefinida::class);
    }

    #region SQL
    #[Test]
    public function testGetSqlSimple(): void
    {
        $consulta = $this->getConsulta('Test');
        $sql = $consulta->getSql();
        $this->assertIsString($sql);
        $this->assertStringStartsWith('SELECT * FROM test', $sql);
    }

    #[Test]
    public function testGetSqlWhere(): void
    {
        $consulta = $this->getConsulta();
        $condicio = new Condicio(new Columna(TestModelPk::class, 'testId'), Comparacio::EQ, 5);
        $consulta->condicio($condicio);
        $sql = $consulta->getSql();
        $this->assertIsString($sql);
        $this->assertStringStartsWith('SELECT * FROM test WHERE (test.testId = 5)', $sql);
    }

    #[Test]
    public function testGetSqlLimit(): void
    {
        // Sense definir
        $consulta = $this->getConsulta();
        $sql = $consulta->getSql();
        $this->assertIsString($sql);
        $this->assertEquals('SELECT * FROM test LIMIT 100;', $sql);

        // Sense Limit
        $consulta = $this->getConsulta();
        $consulta->limit(0);
        $sql = $consulta->getSql();
        $this->assertIsString($sql);
        $this->assertEquals('SELECT * FROM test;', $sql);

        // Limit
        $consulta = $this->getConsulta();
        $consulta->limit(10);
        $sql = $consulta->getSql();
        $this->assertIsString($sql);
        $this->assertEquals('SELECT * FROM test LIMIT 10;', $sql);
    }

    #[Test]
    public function testGetSqlOrderBy(): void
    {
        $consulta = $this->getConsulta(TestModelPk::class);
        $consulta->ordena(new Ordenacio(new Columna(TestModelPk::class, 'testId'), OrdenacioEnum::ASC))->limit(0);
        $sql = $consulta->getSql();
        $this->assertIsString($sql);
        $this->assertEquals('SELECT * FROM test ORDER BY test.testId ASC;', $sql);

        // Multiples ordres
        $consulta = $this->getConsulta(TestModelPk::class);
        $consulta->ordena(new Ordenacio(new Columna(TestModelPk::class, 'testId'), OrdenacioEnum::ASC))->ordena(new Ordenacio(new Columna(TestModelPk::class, 'testNom'), OrdenacioEnum::DESC))->limit(0);
        $sql = $consulta->getSql();
        $this->assertIsString($sql);
        $this->assertEquals('SELECT * FROM test ORDER BY test.testId ASC, test.test_nom DESC;', $sql);
    }

    #endregion SQL
    /**
     * @param class-string $model
     *
     * @return Consulta
     */
    private function getConsulta(string $model = TestModelPk::class, EnumsConsulta $tipus = EnumsConsulta::SELECT): Consulta
    {
        $mockFabrica = new MockFabrica($model);
        $gestor = $mockFabrica->mockGestor();

        return new Consulta($gestor->getModel(), $tipus);
    }
}
