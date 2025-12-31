<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Enums;

enum Driver: string
{
    case SQLITE = 'sqlite';
    case MYSQL = 'mysql';
}
