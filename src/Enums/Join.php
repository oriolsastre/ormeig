<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Enums;

enum Join: string
{
    case INNER = 'INNER';
    case LEFT = 'LEFT';
    case LEFT_OUTER = 'LEFT OUTER';
    case RIGHT = 'RIGHT';
    case RIGHT_OUTER = 'RIGHT OUTER';
    case FULL = 'FULL';
}
