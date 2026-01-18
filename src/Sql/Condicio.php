<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Sql;

use Sastreo\Ormeig\Columna;
use Sastreo\Ormeig\Enums\Comparacio;
use Sastreo\Ormeig\Enums\Tipus;
use Sastreo\Ormeig\Excepcions\CondicioTipusColumna;
use Sastreo\Ormeig\Interfaces\ClausulaSql;

class Condicio implements ClausulaSql
{
    /**
     * @param Columna    $columna
     * @param Comparacio $comparacio
     * @param mixed      $valor
     */
    public function __construct(
        public Columna $columna,
        public Comparacio $comparacio,
        public mixed $valor,
    ) {
        $type1 = $columna->tipus;
        $type2 = $valor instanceof Columna ? $valor->tipus : \gettype($valor);

        // Tipus diferents a int i float amb >, <, >=, <=
        if (\in_array($comparacio, [Comparacio::LT, Comparacio::LTE, Comparacio::GT, Comparacio::GTE])) {
            if (!\in_array($type1, [Tipus::INT->value, 'int', Tipus::FLOAT->value, 'float'])) {
                throw new CondicioTipusColumna($columna, $comparacio);
            }
            if (!\in_array($type2, [Tipus::INT->value, 'int', Tipus::FLOAT->value, 'float'])) {
                throw new CondicioTipusColumna($valor, $comparacio);
            }
        }
        // Tipus diferents a string amb LIKE
        if ($comparacio === Comparacio::LIKE) {
            if ($type1 !== Tipus::STRING->value) {
                throw new CondicioTipusColumna($columna, $comparacio);
            }
            if ($type2 !== Tipus::STRING->value) {
                throw new CondicioTipusColumna($valor, $comparacio);
            }
        }
    }

    public function toSql(): string
    {
        return "{$this->columna} {$this->comparacio->value} {$this->valorToString($this->valor)}";
    }

    private function valorToString(mixed $valor): string
    {
        if (\is_bool($valor)) {
            return $valor ? '1' : '0';
        } elseif (\is_scalar($valor) || $valor instanceof \Stringable) {
            return \gettype($valor) === 'string' ? "'$valor'" : (string) $valor;
        } elseif ($valor instanceof \DateTime) {
            return $valor->format('Y-m-d H:i:s');
        } else {
            return '';
        }
    }
}
