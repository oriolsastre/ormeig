<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Models;

use Sastreo\Ormeig\Atributs\Columna;
use Sastreo\Ormeig\Atributs\Pk;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Model;

// Taula amb mateix nom que la classe
#[Taula]
class TestEntitat extends Model
{
    #[Columna]
    #[Pk]
    public int $testEntitatId;
}
