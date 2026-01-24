<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Atributs\Columna as ColumnaAtribut;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Columna;
use Sastreo\Ormeig\Excepcions\ClauPrimariaInvalida;
use Sastreo\Ormeig\Excepcions\ClauPrimariaNoDefinida;
use Sastreo\Ormeig\Excepcions\ColumnaNoExisteix;
use Sastreo\Ormeig\Excepcions\TaulaNoDefinida;
use Sastreo\Ormeig\Model;
use Sastreo\Ormeig\Tests\Models\TestEntitat;
use Sastreo\Ormeig\Tests\Models\TestModelMultiplePk;
use Sastreo\Ormeig\Tests\Models\TestModelNoPk;
use Sastreo\Ormeig\Tests\Models\TestModelPk;
use Sastreo\Ormeig\Tests\Models\TestUsuari;

#[CoversClass(Model::class)]
#[UsesClass(Taula::class)]
#[UsesClass(Columna::class)]
#[UsesClass(ColumnaAtribut::class)]
#[UsesClass(TaulaNoDefinida::class)]
#[UsesClass(ClauPrimariaNoDefinida::class)]
#[UsesClass(ClauPrimariaInvalida::class)]
#[UsesClass(ColumnaNoExisteix::class)]
class ModelTest extends TestCase
{
    #[Test]
    public function testClassEsModel(): void
    {
        $this->expectException(TaulaNoDefinida::class);
        Model::classEsModel(\stdClass::class);
    }

    #[Test]
    public function testGetClausPrimaries(): void
    {
        $clausPrimaries = Model::getClausPrimaries(TestUsuari::class);
        $this->assertIsArray($clausPrimaries);
        $this->assertContainsOnlyInstancesOf(Columna::class, $clausPrimaries);
        $this->assertCount(1, $clausPrimaries);
        $this->assertSame('usuari.userId', $clausPrimaries[0]->__toString());
    }

    #[Test]
    public function testGetClausPrimariesThrows(): void
    {
        $this->expectException(ClauPrimariaNoDefinida::class);
        Model::getClausPrimaries(TestModelNoPk::class);
    }

    #[Test]
    #[DataProvider('clausPrimariesProvider')]
    public function testClausPrimariesValides(string $class, mixed $ids, array $expected): void
    {
        $idsResult = Model::clausPrimariesValides($class, $ids);
        $this->assertIsArray($idsResult);
        $this->assertSame($expected, $idsResult);
    }

    #[Test]
    public function testClausPrimariesValidesThrows(): void
    {
        // Clau primària invàlida
        $this->expectException(ClauPrimariaInvalida::class);
        Model::clausPrimariesValides(TestUsuari::class, new \stdClass());
    }

    #[Test]
    public function testClausPrimariesValidesThrowsMultiple(): void
    {
        // Falten claus primàries
        $this->expectException(ClauPrimariaInvalida::class);
        Model::clausPrimariesValides(TestModelMultiplePk::class, 1);
    }

    #[Test]
    public function testClausPrimariesValidesThrowsCampsIncorrectesSimple(): void
    {
        // Claus primàries incorrectes
        $this->expectException(ClauPrimariaInvalida::class);
        Model::clausPrimariesValides(TestUsuari::class, ['noColumna' => 1]);
    }

    #[Test]
    public function testClausPrimariesValidesThrowsCampsNoPkSimple(): void
    {
        // Claus primàries incorrectes
        $this->expectException(ClauPrimariaInvalida::class);
        Model::clausPrimariesValides(TestUsuari::class, ['nom' => 1]);
    }

    #[Test]
    public function testMappedColumns(): void
    {
        $columnsUsuari = Model::getMappedColumns(TestUsuari::class);
        $this->assertIsArray($columnsUsuari);
        $this->assertSame(TestUsuari::getMapping(), $columnsUsuari);

        $columnsTest = Model::getMappedColumns(TestModelPk::class);
        $this->assertIsArray($columnsTest);
        $this->assertSame(TestModelPk::getMapping(), $columnsTest);
        $this->assertArrayNotHasKey('noColumna', $columnsTest);
    }

    #[Test]
    public function testGetValorColumnaModelPublic(): void
    {
        $entitat = new TestEntitat();
        $entitat->testEntitatId = 1;
        $columna = new Columna(TestEntitat::class, 'testEntitatId');
        $this->assertSame(1, Model::getValorColumnaModel($entitat, $columna));
    }

    #[Test]
    public function testGetValorColumnaModelPrivate(): void
    {
        $usuari = new TestUsuari(2);
        $columna = new Columna(TestUsuari::class, 'id');
        $this->assertSame(2, Model::getValorColumnaModel($usuari, $columna));
    }

    public static function clausPrimariesProvider(): array
    {
        return [
            'Id simple' => [TestUsuari::class, 1, ['id' => 1]],
            'Id simple en array' => [TestUsuari::class, ['id' => 1], ['id' => 1]],
            'Id múltiple en array' => [TestModelMultiplePk::class, ['entitat' => 5, 'usuari' => 1], ['entitat' => 5, 'usuari' => 1]],
        ];
    }
}
