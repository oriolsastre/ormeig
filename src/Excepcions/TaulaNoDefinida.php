<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Excepcions;

use Sastreo\Ormeig\Interfaces\Model;
use TypeError;

class TaulaNoDefinida extends TypeError
{
    /**
     * @param class-string<Model> $model
     */
    public function __construct(string $model)
    {
        parent::__construct("El $model no té l'atribut de taula definit.");
    }
}
