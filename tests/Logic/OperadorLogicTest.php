<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Logic;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Atributs\Columna as ColumnaAtribut;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Columna;
use Sastreo\Ormeig\Enums\Comparacio;
use Sastreo\Ormeig\Logic\LogicI;
use Sastreo\Ormeig\Logic\LogicO;
use Sastreo\Ormeig\Logic\OperadorLogic;
use Sastreo\Ormeig\Model;
use Sastreo\Ormeig\Sql\Condicio;
use Sastreo\Ormeig\Tests\Models\TestModelPk;

#[CoversClass(OperadorLogic::class)]
#[CoversClass(LogicI::class)]
#[CoversClass(LogicO::class)]
#[UsesClass(Columna::class)]
#[UsesClass(Condicio::class)]
#[UsesClass(ColumnaAtribut::class)]
#[UsesClass(Taula::class)]
#[UsesClass(Model::class)]
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
        $cond1 = new Condicio(TestModelPk::testId(), Comparacio::EQ, 2);
        $cond2 = new Condicio(TestModelPk::testNom(), Comparacio::EQ, 'Nom del test');

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
}
