<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Atributs\Columna as ColumnaAtribut;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Columna;
use Sastreo\Ormeig\Excepcions\ClauPrimariaNoDefinida;
use Sastreo\Ormeig\Excepcions\TaulaNoDefinida;
use Sastreo\Ormeig\Tests\Models\TestModelNoPk;
use Sastreo\Ormeig\Tests\Models\TestModelPk;
use Sastreo\Ormeig\Tests\Models\TestUsuari;

use function Sastreo\Ormeig\classEsModel;
use function Sastreo\Ormeig\getClausPrimaries;
use function Sastreo\Ormeig\getMappedColumns;

#[CoversFunction('Sastreo\Ormeig\classEsModel')]
#[CoversFunction('Sastreo\Ormeig\getClausPrimaries')]
#[CoversFunction('Sastreo\Ormeig\getMappedColumns')]
#[UsesClass(Taula::class)]
#[UsesClass(Columna::class)]
#[UsesClass(ColumnaAtribut::class)]
#[UsesClass(TaulaNoDefinida::class)]
#[UsesClass(ClauPrimariaNoDefinida::class)]
class ModelTest extends TestCase
{
    #[Test]
    public function testClassEsModel(): void
    {
        $this->expectException(TaulaNoDefinida::class);
        classEsModel(\stdClass::class);
    }

    #[Test]
    public function testGetClausPrimaries(): void
    {
        $clausPrimaries = getClausPrimaries(TestUsuari::class);
        $this->assertIsArray($clausPrimaries);
        $this->assertContainsOnlyInstancesOf(Columna::class, $clausPrimaries);
        $this->assertCount(1, $clausPrimaries);
        $this->assertSame('usuari.userId', $clausPrimaries[0]->__toString());
    }

    #[Test]
    public function testGetClausPrimariesThrows(): void
    {
        $this->expectException(ClauPrimariaNoDefinida::class);
        getClausPrimaries(TestModelNoPk::class);
    }

    #[Test]
    public function testMappedColumns(): void
    {
        $columnsUsuari = getMappedColumns(TestUsuari::class);
        $this->assertIsArray($columnsUsuari);
        $this->assertSame(TestUsuari::getMapping(), $columnsUsuari);

        $columnsTest = getMappedColumns(TestModelPk::class);
        $this->assertIsArray($columnsTest);
        $this->assertSame(TestModelPk::getMapping(), $columnsTest);
        $this->assertArrayNotHasKey('noColumna', $columnsTest);
    }
}
