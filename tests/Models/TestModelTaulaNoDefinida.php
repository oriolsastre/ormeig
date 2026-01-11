<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Models;

use Sastreo\Ormeig\Atributs\Columna;
use Sastreo\Ormeig\Atributs\Pk;

class TestModelTaulaNoDefinida
{
    #[Columna]
    #[Pk]
    public int $testId;
}
