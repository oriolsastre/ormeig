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
use Sastreo\Ormeig\Enums\Driver;
use Sastreo\Ormeig\Gestor;
use Sastreo\Ormeig\Logic\OperadorLogic;
use Sastreo\Ormeig\Model;
use Sastreo\Ormeig\Ormeig;
use Sastreo\Ormeig\Tests\Mocks\MockFabrica;
use Sastreo\Ormeig\Tests\Models\TestUsuari;
use Sastreo\Ormeig\Tests\Seed\DatabaseSetUp;

#[CoversClass(Gestor::class)]
#[UsesClass(Taula::class)]
#[UsesClass(Columna::class)]
#[UsesClass(Consulta::class)]
#[UsesClass(Ormeig::class)]
#[UsesClass(OperadorLogic::class)]
#[UsesClass(Model::class)]
class GestorSqliteTest extends TestCase implements GestorDatabaseTestInterface
{
    private MockFabrica $mockFabrica;

    public function __construct(string $name)
    {
        $this->mockFabrica = new MockFabrica($name);

        return parent::__construct($name);
    }

    #[Test]
    public function testTrobatTots(): void
    {
        $ormeig = $this->mockFabrica->realOrmeig();
        $gestor = $ormeig->getGestor(TestUsuari::class);
        $tots = $gestor->trobaTots();
        $this->assertIsArray($tots);
        $this->assertContainsOnlyInstancesOf(TestUsuari::class, $tots);
    }

    protected function setUp(): void
    {
        DatabaseSetUp::seedDatabase();
    }

    public static function setUpBeforeClass(): void
    {
        DatabaseSetUp::crearBaseDades(Driver::SQLITE);
    }
}
