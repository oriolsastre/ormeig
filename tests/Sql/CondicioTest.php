<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Sql;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Atributs\Columna as ColumnaAtribut;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Columna;
use Sastreo\Ormeig\Enums\Comparacio;
use Sastreo\Ormeig\Excepcions\ColumnaNoExisteix;
use Sastreo\Ormeig\Excepcions\CondicioTipusColumna;
use Sastreo\Ormeig\Model;
use Sastreo\Ormeig\Sql\Condicio;
use Sastreo\Ormeig\Tests\Models\TestModelPk;

#[CoversClass(Condicio::class)]
#[CoversClass(CondicioTipusColumna::class)]
#[UsesClass(Columna::class)]
#[UsesClass(ColumnaAtribut::class)]
#[UsesClass(Taula::class)]
#[UsesClass(Model::class)]
#[UsesClass(ColumnaNoExisteix::class)]
class CondicioTest extends TestCase
{
    #[Test]
    public function testConstructor(): void
    {
        $condicio = new Condicio(TestModelPk::testId(), Comparacio::EQ, 2);
        $this->assertInstanceOf(Condicio::class, $condicio);

        // Error
        $this->expectException(ColumnaNoExisteix::class);
        new Condicio(TestModelPk::columnaInexistent(), Comparacio::EQ, '2');
    }

    #region Comparatius
    #[Test]
    #[DataProvider('comparatiuProvider')]
    public function testOperadorsComparatius(Comparacio $comparacio): void
    {
        // Prova amb enter
        $condicio = new Condicio(TestModelPk::testId(), $comparacio, 5);
        $this->assertInstanceOf(Condicio::class, $condicio);
        $this->assertEquals("test.testId {$comparacio->value} 5", $condicio->toSql());

        // Prova amb float
        $condicio = new Condicio(TestModelPk::testFloat(), $comparacio, 5.5);
        $this->assertInstanceOf(Condicio::class, $condicio);
        $this->assertEquals("test.testFloat {$comparacio->value} 5.5", $condicio->toSql());
    }

    #[Test]
    #[DataProvider('comparatiuProvider')]
    public function testOperadorsComparatiusFallaColumna(Comparacio $comparacio): void
    {
        $this->expectException(CondicioTipusColumna::class);
        new Condicio(TestModelPk::testNom(), $comparacio, 5);
    }

    #[Test]
    #[DataProvider('comparatiuProvider')]
    public function testOperadorsComparatiusFallaValor(Comparacio $comparacio): void
    {
        $this->expectException(CondicioTipusColumna::class);
        new Condicio(TestModelPk::testId(), $comparacio, '5');
    }

    #endregion
    #region LIKE
    #[Test]
    public function testOperadorLike(): void
    {
        $condicio = new Condicio(TestModelPk::testNom(), Comparacio::LIKE, '%Value%');
        $this->assertInstanceOf(Condicio::class, $condicio);
        $this->assertEquals("test.test_nom LIKE '%Value%'", $condicio->toSql());
    }

    #[Test]
    public function testOperadorLikeFallaColumna(): void
    {
        $this->expectException(CondicioTipusColumna::class);
        new Condicio(TestModelPk::testId(), Comparacio::LIKE, '5');
    }

    #[Test]
    public function testOperadorLikeFallaValor(): void
    {
        $this->expectException(CondicioTipusColumna::class);
        new Condicio(TestModelPk::testNom(), Comparacio::LIKE, 1);
    }
    #endregion

    public static function comparatiuProvider(): array
    {
        return [
            'Més petit que <' => [Comparacio::LT],
            'Més petit o igual que <=' => [Comparacio::LTE],
            'Més gran que >' => [Comparacio::GT],
            'Més gran o igual que >=' => [Comparacio::GTE],
        ];
    }
}
