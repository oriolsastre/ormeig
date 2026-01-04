<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Logic;

class LogicI extends OperadorLogic
{
    protected static string $logic = 'AND';
}
class LogicO extends OperadorLogic
{
    protected static string $logic = 'OR';
}
