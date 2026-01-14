<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Models;

use Sastreo\Ormeig\Atributs\Columna;
use Sastreo\Ormeig\Atributs\Pk;
use Sastreo\Ormeig\Atributs\Taula;

#[Taula]
class TestModelMultiplePk
{
    #[Columna]
    #[Pk]
    private int $entitat;
    #[Columna]
    #[Pk]
    private int $usuari;
}
