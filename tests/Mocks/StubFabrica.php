<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Mocks;

use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Enums\Comparacio;
use Sastreo\Ormeig\Sql\Condicio;

class StubFabrica extends TestCase
{
    public function __construct(string $name = 'StubFabrica')
    {
        parent::__construct($name);
    }

    public static function stubCondicio(Comparacio $comp = Comparacio::EQ): Condicio
    {
        $condicio = self::createStub(Condicio::class);
        $sql = 'id '.$comp->value.' 1';
        $condicio->method('toSql')->willReturn($sql);

        return $condicio;
    }
}
