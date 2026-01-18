<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Mocks;

use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Enums\Comparacio;
use Sastreo\Ormeig\Gestor;
use Sastreo\Ormeig\Ormeig;
use Sastreo\Ormeig\Sql\Condicio;
use Sastreo\Ormeig\Tests\Models\TestModelPk;

class StubFabrica extends TestCase
{
    public function __construct(string $name = 'StubFabrica')
    {
        parent::__construct($name);
    }

    public static function stubOrmeig(): Ormeig
    {
        $ormeigStub = self::createStub(Ormeig::class);
        $ormeigStub->method('getDbcnx')->willReturn(self::pdoStub());
        $ormeigStub->method('getGestor')->willReturn(self::stubGestor());

        return $ormeigStub;
    }

    public static function stubGestor(string $model = TestModelPk::class): Gestor
    {
        $gestorStub = self::createStub(Gestor::class);
        $gestorStub->method('getModel')->willReturn($model);

        return $gestorStub;
    }

    public static function stubCondicio(Comparacio $comp = Comparacio::EQ): Condicio
    {
        $condicio = self::createStub(Condicio::class);
        $sql = 'id '.$comp->value.' 1';
        $condicio->method('toSql')->willReturn($sql);

        return $condicio;
    }

    private static function pdoStub(): \PDO
    {
        return self::createStub(\PDO::class);
    }
}
