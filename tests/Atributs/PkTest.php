<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Atributs;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Atributs\Pk;
use Sastreo\Ormeig\Tests\Models\TestModelPk;

#[CoversClass(Pk::class)]
class PkTest extends TestCase
{
    #[Test]
    public function testPk(): void
    {
        $columna = new Pk();
        $this->assertInstanceOf(Pk::class, $columna);
    }

    #[Test]
    public function isAttribute(): void
    {
        $pk = new Pk();
        $reflectPk = new \ReflectionClass($pk);
        $attr = $reflectPk->getAttributes();
        $this->assertCount(1, $attr);
        $this->assertInstanceOf(\Attribute::class, $attr[0]->newInstance());

        $testModel = new TestModelPk();
        $reflectTestModel = new \ReflectionClass($testModel);
        $attr = $reflectTestModel->getProperty('testId')->getAttributes(Pk::class);
        $this->assertCount(1, $attr);
        $this->assertInstanceOf(Pk::class, $attr[0]->newInstance());
        $this->assertEquals(\Attribute::TARGET_PROPERTY, $attr[0]->getTarget());
    }
}
