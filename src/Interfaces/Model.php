<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Interfaces;

use Sastreo\Ormeig\Columna;

interface Model
{
    /**
     * @return Columna[]
     */
    public static function getClausPrimaries(): array;

    /**
     * @return array<string, string>
     */
    public static function getMappedColumns(): array;
}
