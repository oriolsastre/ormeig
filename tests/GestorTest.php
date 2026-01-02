<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Atributs\Columna;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Consulta;
use Sastreo\Ormeig\Gestor;
use Sastreo\Ormeig\Logic\OperadorLogic;
use Sastreo\Ormeig\Model;
use Sastreo\Ormeig\Ormeig;
use Sastreo\Ormeig\Tests\Mocks\MockFabrica;
use Sastreo\Ormeig\Tests\Models\TestModelPk;
use Sastreo\Ormeig\Tests\Models\TestUsuari;
use Sastreo\Ormeig\Tests\Seed\DatabaseSetUp;

#[CoversClass(Gestor::class)]
#[UsesClass(Taula::class)]
#[UsesClass(Columna::class)]
#[UsesClass(Consulta::class)]
#[UsesClass(Ormeig::class)]
#[UsesClass(OperadorLogic::class)]
#[UsesClass(Model::class)]
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

    #region FETCH
    #[Test]
    public function testTrobaTots(): void
    {
        $ormeig = $this->mockFabrica->realOrmeig();
        $gestor = $ormeig->getGestor(TestUsuari::class);
        $tots = $gestor->trobaTots();
        $this->assertIsArray($tots);
        // echo print_r($tots, true);
        $this->assertContainsOnlyInstancesOf(TestUsuari::class, $tots);
    }

    #endregion
    public static function setUpBeforeClass(): void
    {
        DatabaseSetUp::crearBaseDades();
        // echo "Creo";
        DatabaseSetUp::seedDatabase();
        // echo "Seed";
    }
}
