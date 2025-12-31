<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Enums;

enum Comparacio: string
{
    case EQ = '=';
    case NEQ = '!=';
    case NULL = 'IS NULL';
    case GT = '>';
    case LT = '<';
    case GTE = '>=';
    case LTE = '<=';
    case LIKE = 'LIKE';
}
