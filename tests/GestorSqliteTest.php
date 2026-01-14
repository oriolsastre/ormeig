<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesFunction;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Atributs\Columna as ColumnaAtribut;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Columna;
use Sastreo\Ormeig\Consulta;
use Sastreo\Ormeig\Enums\Driver;
use Sastreo\Ormeig\Gestor;
use Sastreo\Ormeig\Logic\OperadorLogic;
use Sastreo\Ormeig\Ormeig;
use Sastreo\Ormeig\Sql\Condicio;
use Sastreo\Ormeig\Tests\Mocks\MockFabrica;
use Sastreo\Ormeig\Tests\Models\TestUsuari;
use Sastreo\Ormeig\Tests\Seed\DatabaseSetUp;

#[CoversClass(Gestor::class)]
#[UsesClass(Taula::class)]
#[UsesClass(ColumnaAtribut::class)]
#[UsesClass(Columna::class)]
#[UsesClass(Consulta::class)]
#[UsesClass(Ormeig::class)]
#[UsesClass(OperadorLogic::class)]
#[UsesClass(Condicio::class)]
#[UsesFunction('Sastreo\Ormeig\classEsModel')]
#[UsesFunction('Sastreo\Ormeig\getClausPrimaries')]
#[UsesFunction('Sastreo\Ormeig\clausPrimariesValides')]
#[UsesFunction('Sastreo\Ormeig\getMappedColumns')]
class GestorSqliteTest extends TestCase implements GestorDatabaseTestInterface
{
    private MockFabrica $mockFabrica;

    public function __construct(string $name)
    {
        $this->mockFabrica = new MockFabrica($name);

        return parent::__construct($name);
    }

    #[Test]
    public function testTrobaTots(): void
    {
        $ormeig = $this->mockFabrica->realOrmeig();
        $gestor = $ormeig->getGestor(TestUsuari::class);
        $tots = $gestor->trobaTots();
        $this->assertIsArray($tots);
        $this->assertContainsOnlyInstancesOf(TestUsuari::class, $tots);

        $raskolnikov = false;
        $annaKarenina = false;
        $reflectiveName = new \ReflectionProperty(TestUsuari::class, 'nom');
        $reflectiveEmail = new \ReflectionProperty(TestUsuari::class, 'email');
        foreach ($tots as $usuari) {
            if ($reflectiveName->getValue($usuari) === 'Raskolnikov') {
                $raskolnikov = true;
            }
            if ($reflectiveEmail->getValue($usuari) === 'anna.karenina@ormeig.cat') {
                $annaKarenina = true;
            }
        }

        $this->assertTrue($raskolnikov && $annaKarenina);
    }

    #[Test]
    public function testTrobaPerId(): void
    {
        $ormeig = $this->mockFabrica->realOrmeig();
        $gestor = $ormeig->getGestor(TestUsuari::class);
        $usuari = $gestor->trobaPerId(1);
        $this->assertInstanceOf(TestUsuari::class, $usuari);

        $reflectiveName = new \ReflectionProperty(TestUsuari::class, 'nom');
        $reflectiveId = new \ReflectionProperty(TestUsuari::class, 'id');
        $this->assertEquals(1, $reflectiveId->getValue($usuari));
        $this->assertEquals('Raskolnikov', $reflectiveName->getValue($usuari));
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
