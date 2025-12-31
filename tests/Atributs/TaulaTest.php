<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Atributs;

use Attribute;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Tests\Models\TestModelPk;

#[CoversClass(Taula::class)]
class TaulaTest extends TestCase
{
    #[Test]
    public function testTaula(): void
    {
        $taula = new Taula('nom');
        $this->assertInstanceOf(Taula::class, $taula);
        $this->assertSame('nom', $taula->nom);
    }

    #[Test]
    public function isAttribute(): void
    {
        $taula = new Taula();
        $reflectTaula = new ReflectionClass($taula);
        $attr = $reflectTaula->getAttributes();
        $this->assertCount(1, $attr);
        $this->assertInstanceOf(Attribute::class, $attr[0]->newInstance());

        $testModel = new TestModelPk();
        $reflectTestModel = new ReflectionClass($testModel);
        $attr = $reflectTestModel->getAttributes(Taula::class);
        $this->assertCount(1, $attr);
        $this->assertInstanceOf(Taula::class, $attr[0]->newInstance());
        $this->assertEquals(Attribute::TARGET_CLASS, $attr[0]->getTarget());
    }
}
