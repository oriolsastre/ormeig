<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Models;

use Sastreo\Ormeig\Atributs\Columna;
use Sastreo\Ormeig\Atributs\Pk;
use Sastreo\Ormeig\Atributs\Taula;

#[Taula('usuari')]
class TestUsuari
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

    public function __construct(int $id = 0, string $nom = '')
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = '';
        $this->password = '';
    }

    public static function getMapping(): array
    {
        return [
            'userId' => 'id',
            'nom' => 'nom',
            'email' => 'email',
            'password' => 'password',
        ];
    }
}
