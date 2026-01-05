<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Interfaces;

interface Model
{
    /**
     * @return string[]
     */
    public static function getClausPrimaries(): array;

    /**
     * @return array<string, string>
     */
    public static function getMappedColumns(): array;
}
