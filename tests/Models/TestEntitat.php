<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Models;

use Sastreo\Ormeig\Atributs\Columna;
use Sastreo\Ormeig\Atributs\Pk;
use Sastreo\Ormeig\Atributs\Taula;

// Taula amb mateix nom que la classe
#[Taula]
class TestEntitat
{
    #[Columna]
    #[Pk]
    public int $testEntitatId;
}
