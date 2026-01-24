<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Atributs;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Atributs\Fk;
use Sastreo\Ormeig\Excepcions\TaulaNoDefinida;
use Sastreo\Ormeig\Model;
use Sastreo\Ormeig\Tests\Models\TestModelPk;

#[CoversClass(Fk::class)]
#[UsesClass(TaulaNoDefinida::class)]
#[UsesClass(Model::class)]
class FkTest extends TestCase
{
    #[Test]
    public function testConstruct(): void
    {
        $columna = new Fk(TestModelPk::class, 'testId');
        $this->assertInstanceOf(Fk::class, $columna);

        $this->expectException(TaulaNoDefinida::class);
        new Fk(TestCase::class, 'someColumn');
    }

    #[Test]
    public function isAttribute(): void
    {
        $fk = new Fk(TestModelPk::class, 'test_nom');
        $reflectFk = new \ReflectionClass($fk);
        $attr = $reflectFk->getAttributes();
        $this->assertCount(1, $attr);
        $this->assertInstanceOf(\Attribute::class, $attr[0]->newInstance());

        // $testModel = new TestModelPk();
        // $reflectTestModel = new ReflectionClass($testModel);
        // $attr = $reflectTestModel->getProperty('testId')->getAttributes(Fk::class);
        // $this->assertCount(1, $attr);
        // $this->assertInstanceOf(Fk::class, $attr[0]->newInstance());
        // $this->assertEquals(Attribute::TARGET_PROPERTY, $attr[0]->getTarget());
    }
}
