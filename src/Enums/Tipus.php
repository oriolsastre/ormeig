<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Enums;

enum Tipus: string
{
    case INT = 'integer';
    case STRING = 'string';
    case FLOAT = 'double';
    case BOOL = 'boolean';
    case DATETIME = 'datetime';
}
