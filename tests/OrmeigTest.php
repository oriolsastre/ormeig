<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests;

use PDO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Atributs\Columna;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Enums\Driver;
use Sastreo\Ormeig\Gestor;
use Sastreo\Ormeig\Ormeig;
use Sastreo\Ormeig\Tests\Models\TestModelPk;

#[CoversClass(Ormeig::class)]
#[UsesClass(Gestor::class)]
#[UsesClass(Taula::class)]
#[UsesClass(Columna::class)]
class OrmeigTest extends TestCase
{
    #[Test]
    public function testSqliteMemory(): void
    {
        $ormeig = new Ormeig(
            driver: Driver::SQLITE,
            dbname: ':memory:',
        );
        $this->assertInstanceOf(Ormeig::class, $ormeig);
        $dbcnx = $ormeig->getDbcnx();
        $this->assertInstanceOf(PDO::class, $dbcnx);
    }
    #[Test]
    public function testBadConstructor(): void
    {
        $this->expectException(\Exception::class);
        new Ormeig(
            driver: Driver::MYSQL,
            dbname: 'test',
        );
    }
    #[Test]
    public function testGetGestor(): void
    {
        $ormeig = new Ormeig(
            driver: Driver::SQLITE,
            dbname: ':memory:',
        );


        $gestor = $ormeig->getGestor(TestModelPk::class);
        $this->assertInstanceOf(Gestor::class, $gestor);
    }
}
