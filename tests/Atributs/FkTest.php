<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Atributs;

use Attribute;
use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Sastreo\Ormeig\Atributs\Fk;
use Sastreo\Ormeig\Tests\Models\TestModelPk;

#[CoversClass(Fk::class)]
class FkTest extends TestCase
{
    #[Test]
    public function testConstruct(): void
    {
        $columna = new Fk(TestModelPk::class, 'testId');
        $this->assertInstanceOf(Fk::class, $columna);

        $this->expectException(Exception::class);
        new Fk(TestCase::class, 'someColumn');
    }

    #[Test]
    public function isAttribute(): void
    {
        $fk = new Fk(TestModelPk::class, 'test_nom');
        $reflectFk = new ReflectionClass($fk);
        $attr = $reflectFk->getAttributes();
        $this->assertCount(1, $attr);
        $this->assertInstanceOf(Attribute::class, $attr[0]->newInstance());

        // $testModel = new TestModelPk();
        // $reflectTestModel = new ReflectionClass($testModel);
        // $attr = $reflectTestModel->getProperty('testId')->getAttributes(Fk::class);
        // $this->assertCount(1, $attr);
        // $this->assertInstanceOf(Fk::class, $attr[0]->newInstance());
        // $this->assertEquals(Attribute::TARGET_PROPERTY, $attr[0]->getTarget());
    }
}
