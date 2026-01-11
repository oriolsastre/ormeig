<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Excepcions;

class TaulaNoDefinida extends \TypeError
{
    /**
     * @param class-string $model
     */
    public function __construct(string $model)
    {
        parent::__construct("El $model no té l'atribut de taula definit.");
    }
}
