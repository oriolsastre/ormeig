<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Atributs\Columna as ColumnaAtribut;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Columna;
use Sastreo\Ormeig\Excepcions\ClauPrimariaNoDefinida;
use Sastreo\Ormeig\Model;
use Sastreo\Ormeig\Tests\Models\TestModelPk;
use Sastreo\Ormeig\Tests\Models\TestUsuari;

#[CoversClass(Model::class)]
#[UsesClass(Taula::class)]
#[UsesClass(Columna::class)]
#[UsesClass(ColumnaAtribut::class)]
#[UsesClass(ClauPrimariaNoDefinida::class)]
class ModelTest extends TestCase
{
    #[Test]
    public function testGetClausPrimaries(): void
    {
        $clausPrimaries = TestUsuari::getClausPrimaries();
        $this->assertIsArray($clausPrimaries);
        $this->assertContainsOnlyInstancesOf(Columna::class, $clausPrimaries);
        $this->assertCount(1, $clausPrimaries);
        $this->assertSame('usuari.userId', $clausPrimaries[0]->__toString());
    }

    #[Test]
    public function testGetClausPrimariesThrows(): void
    {
        $this->expectException(ClauPrimariaNoDefinida::class);
        Model::getClausPrimaries();
    }

    #[Test]
    public function testMappedColumns(): void
    {
        $columnsUsuari = TestUsuari::getMappedColumns();
        $this->assertIsArray($columnsUsuari);
        $this->assertSame(TestUsuari::getMapping(), $columnsUsuari);

        $columnsTest = TestModelPk::getMappedColumns();
        $this->assertIsArray($columnsTest);
        $this->assertSame(TestModelPk::getMapping(), $columnsTest);
        $this->assertArrayNotHasKey('noColumna', $columnsTest);
    }
}
