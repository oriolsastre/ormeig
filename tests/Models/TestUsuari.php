<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Models;

use Sastreo\Ormeig\Atributs\Columna;
use Sastreo\Ormeig\Atributs\Pk;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Model;

#[Taula('usuari')]
class TestUsuari extends Model
{
    #[Columna('userId')]
    #[Pk]
    private int $id;
    #[Columna]
    private string $nom;
    #[Columna(unica: true)]
    private string $email;
    #[Columna]
    private string $password;
}
