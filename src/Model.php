<?php

declare(strict_types=1);

namespace Sastreo\Ormeig;

use Sastreo\Ormeig\Interfaces\Model as ModelInterface;

abstract class Model implements ModelInterface
{
    public static function __callStatic(string $method, mixed $_): Columna
    {
        return new Columna(static::class, $method);
    }
}
