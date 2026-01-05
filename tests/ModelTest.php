<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Atributs\Columna;
use Sastreo\Ormeig\Model;
use Sastreo\Ormeig\Tests\Models\TestModelPk;
use Sastreo\Ormeig\Tests\Models\TestUsuari;

#[CoversClass(Model::class)]
#[UsesClass(Columna::class)]
class ModelTest extends TestCase
{
    #[Test]
    public function testMappedColumns(): void
    {
        $columnsUsuari = TestUsuari::getMappedColumns();
        $this->assertIsArray($columnsUsuari);
        $this->assertSame(TestUsuari::getMapping(), $columnsUsuari);

        $columnsTest = TestModelPk::getMappedColumns();
        $this->assertIsArray($columnsTest);
        $this->assertSame(TestModelPk::getMapping(), $columnsTest);
        $this->assertArrayNotHasKey('noColumna', $columnsTest);
    }
}
