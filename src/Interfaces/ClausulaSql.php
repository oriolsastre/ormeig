<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Interfaces;

interface ClausulaSql
{
    public function toSql(): string;
}
