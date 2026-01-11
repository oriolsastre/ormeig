<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Logic;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesFunction;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Atributs\Columna as ColumnaAtribut;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Columna;
use Sastreo\Ormeig\Enums\Comparacio;
use Sastreo\Ormeig\Logic\LogicI;
use Sastreo\Ormeig\Logic\LogicO;
use Sastreo\Ormeig\Logic\OperadorLogic;
use Sastreo\Ormeig\Sql\Condicio;
use Sastreo\Ormeig\Tests\Mocks\StubFabrica;
use Sastreo\Ormeig\Tests\Models\TestModelPk;

#[CoversClass(OperadorLogic::class)]
#[CoversClass(LogicI::class)]
#[CoversClass(LogicO::class)]
#[UsesClass(Columna::class)]
#[UsesClass(Condicio::class)]
#[UsesClass(ColumnaAtribut::class)]
#[UsesClass(Taula::class)]
#[UsesFunction('Sastreo\Ormeig\classEsModel')]
class OperadorLogicTest extends TestCase
{
    #[Test]
    public function logicTest()
    {
        $logicI = new LogicI();
        $logicO = new LogicO();
        $this->assertInstanceOf(OperadorLogic::class, $logicI);
        $this->assertInstanceOf(LogicI::class, $logicI);
        $this->assertInstanceOf(OperadorLogic::class, $logicO);
        $this->assertInstanceOf(LogicO::class, $logicO);
    }

    #[Test]
    public function afegirCondicionsTest(): void
    {
        $cond1 = new Condicio(new Columna(TestModelPk::class, 'testId'), Comparacio::EQ, 2);
        $cond2 = new Condicio(new Columna(TestModelPk::class, 'testNom'), Comparacio::EQ, 'Nom del test');

        $logic = new LogicI($cond1, $cond2);
        $this->assertInstanceOf(OperadorLogic::class, $logic);
        foreach ($logic as $condicio) {
            $this->assertThat($condicio, $this->logicalXor(
                $this->isInstanceOf(Condicio::class),
                $this->isInstanceOf(OperadorLogic::class),
            ));
        }
    }

    #[Test]
    public function condicioFailTest(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        /* @suppress PHP0406 */
        new LogicO('testId', Comparacio::EQ, 2);
    }

    #[Test]
    #[DataProvider('operadorLogicProvider')]
    public function toSqlTest(string $class, array $condicions, string $expected): void
    {
        $logic = new $class(...$condicions);
        $operador = '';
        switch ($class) {
            case LogicI::class:
                $operador = 'AND';
                break;
            case LogicO::class:
                $operador = 'OR';
                break;
        }

        $trueExpected = str_replace('%s', $operador, $expected);

        $this->assertIsString($logic->toSql());
        $this->assertEquals($trueExpected, $logic->toSql());
    }

    public static function operadorLogicProvider(): array
    {
        $stubCondicioEq = StubFabrica::stubCondicio();
        $stubCondicioLt = StubFabrica::stubCondicio(Comparacio::LT);
        $stubCondicioGt = StubFabrica::stubCondicio(Comparacio::GT);
        $data = [];
        $subData = [
            '1 comparacio' => [[$stubCondicioEq], '(id = 1)'],
            '2 comparacions' => [[$stubCondicioEq, $stubCondicioLt], '(id = 1 %s id < 1)'],
            '3 comparacions' => [[$stubCondicioEq, $stubCondicioLt, $stubCondicioGt], '(id = 1 %s id < 1 %s id > 1)'],
            '1 operador lògic I' => [[new LogicI($stubCondicioEq, $stubCondicioLt)], '((id = 1 AND id < 1))'],
            '1 operador lògic O' => [[new LogicO($stubCondicioEq, $stubCondicioLt)], '((id = 1 OR id < 1))'],
            'comparacio i operador lògic I' => [[$stubCondicioEq, new LogicI($stubCondicioEq, $stubCondicioLt)], '(id = 1 %s (id = 1 AND id < 1))'],
            'comparacio i operador lògic O' => [[StubFabrica::stubCondicio(Comparacio::NEQ), new LogicO($stubCondicioEq, $stubCondicioLt)], '(id != 1 %s (id = 1 OR id < 1))'],
        ];
        $logics = ['Logic I' => LogicI::class, 'Logic O' => LogicO::class];
        foreach ($logics as $operadorLogic => $class) {
            foreach ($subData as $cas => $value) {
                $test = $operadorLogic.' amb '.$cas;
                $data[$test] = [$class, ...$value];
            }
        }

        return $data;
    }
}
