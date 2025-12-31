<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Interfaces;

use Sastreo\Ormeig\Sql\Condicio;

/**
 * @extends \ArrayAccess<int|string, OperadorLogic|Condicio>
 */
interface OperadorLogic extends \ArrayAccess
{
}
