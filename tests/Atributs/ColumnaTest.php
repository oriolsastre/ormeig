<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Atributs;

use Attribute;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Sastreo\Ormeig\Atributs\Columna;
use Sastreo\Ormeig\Tests\Models\TestModelPk;

#[CoversClass(Columna::class)]
class ColumnaTest extends TestCase
{
    #[Test]
    public function testColumna(): void
    {
        $columna = new Columna('nom');
        $this->assertInstanceOf(Columna::class, $columna);
        $this->assertSame('nom', $columna->nom);
        $this->assertNull($columna->unica);
    }

    #[Test]
    public function isAttribute(): void
    {
        $columna = new Columna();
        $reflectColumna = new ReflectionClass($columna);
        $attr = $reflectColumna->getAttributes();
        $this->assertCount(1, $attr);
        $this->assertInstanceOf(Attribute::class, $attr[0]->newInstance());

        $testModel = new TestModelPk();
        $reflectTestModel = new ReflectionClass($testModel);
        $attr = $reflectTestModel->getProperty('testNom')->getAttributes(Columna::class);
        $this->assertCount(1, $attr);
        $this->assertInstanceOf(Columna::class, $attr[0]->newInstance());
        $this->assertEquals(Attribute::TARGET_PROPERTY, $attr[0]->getTarget());
    }
}
