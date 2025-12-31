<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Models;

use Sastreo\Ormeig\Atributs\Columna;
use Sastreo\Ormeig\Atributs\Pk;
use Sastreo\Ormeig\Model;

class TestModelTaulaNoDefinida extends Model
{
    #[Columna]
    #[Pk]
    public int $testId;
}
