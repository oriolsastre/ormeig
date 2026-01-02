<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Enums;

enum Consulta: string
{
    case SELECT = 'SELECT';
    case INSERT = 'INSERT';
    case UPDATE = 'UPDATE';
    case DELETE = 'DELETE';
}
