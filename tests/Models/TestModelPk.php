<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Models;

use Sastreo\Ormeig\Atributs\Columna;
use Sastreo\Ormeig\Atributs\Pk;
use Sastreo\Ormeig\Atributs\Taula;

#[Taula('test')]
class TestModelPk
{
    #[Columna]
    #[Pk]
    public int $testId;

    #[Columna]
    public string $test;

    #[Columna(nom: 'test_nom')]
    public string $testNom;

    #[Columna]
    public float $testFloat;

    public string $noColumna;

    public static function getMapping(): array
    {
        return [
            'testId' => 'testId',
            'test' => 'test',
            'test_nom' => 'testNom',
            'testFloat' => 'testFloat',
        ];
    }
}
