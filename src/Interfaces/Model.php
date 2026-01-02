<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Interfaces;

interface Model
{
    /**
     * @return array<string, string>
     */
    public static function getMappedColumns(): array;
}
