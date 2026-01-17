<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesFunction;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Consulta;
use Sastreo\Ormeig\Enums\Consulta as EnumsConsulta;
use Sastreo\Ormeig\Logic\OperadorLogic;
use Sastreo\Ormeig\Tests\Mocks\StubFabrica;
use Sastreo\Ormeig\Tests\Models\TestModelMultiplePk;
use Sastreo\Ormeig\Tests\Models\TestModelPk;
use Sastreo\Ormeig\Tests\Models\TestUsuari;

#[CoversMethod(Consulta::class, 'getSql')]
#[UsesClass(Taula::class)]
#[UsesClass(Consulta::class)]
#[UsesClass(OperadorLogic::class)]
#[UsesFunction('Sastreo\Ormeig\classEsModel')]
class ConsultaGetSqlTest extends TestCase
{
    #region DELETE
    #[Test]
    public function getSqlDelete(): void
    {
        $consulta = $this->getConsulta(TestUsuari::class, EnumsConsulta::DELETE);
        $condicio = StubFabrica::stubCondicio();
        $consulta->condicio($condicio);
        $sql = $consulta->getSql();
        $this->assertIsString($sql);
        $this->assertEquals('DELETE FROM usuari WHERE (id = 1);', $sql);
    }

    #[Test]
    public function getSqlDeleteThrows(): void
    {
        $this->expectException(\Exception::class);
        $consulta = $this->getConsulta(TestModelMultiplePk::class, EnumsConsulta::DELETE);
        $sql = $consulta->getSql();
    }
    #endregion DELETE

    /**
     * @param class-string $model
     *
     * @return Consulta
     */
    protected function getConsulta(string $model = TestModelPk::class, EnumsConsulta $tipus = EnumsConsulta::SELECT): Consulta
    {
        return new Consulta($model, $tipus);
    }
}
