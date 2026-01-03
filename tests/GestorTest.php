<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Atributs\Columna;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Gestor;
use Sastreo\Ormeig\Tests\Mocks\MockFabrica;
use Sastreo\Ormeig\Tests\Models\TestModelPk;

#[CoversClass(Gestor::class)]
#[UsesClass(Taula::class)]
#[UsesClass(Columna::class)]
class GestorTest extends TestCase
{
    private MockFabrica $mockFabrica;

    public function __construct(string $name)
    {
        $this->mockFabrica = new MockFabrica($name);

        return parent::__construct($name);
    }

    #[Test]
    public function testConstructor(): void
    {
        $mockOrmeig = $this->mockFabrica->mockOrmeig();
        $gestor = new Gestor($mockOrmeig, TestModelPk::class);
        $this->assertInstanceOf(Gestor::class, $gestor);
        $this->assertEquals(TestModelPk::class, $gestor->getModel());
    }
}
