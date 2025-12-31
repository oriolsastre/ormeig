<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Sql;

use DateTime;
use Sastreo\Ormeig\Columna;
use Sastreo\Ormeig\Enums\Comparacio;
use Sastreo\Ormeig\Enums\Tipus;
use Sastreo\Ormeig\Excepcions\CondicioTipusColumna;
use Sastreo\Ormeig\Interfaces\ClausulaSql;
use Stringable;

class Condicio implements ClausulaSql
{
    /**
     * @param \Sastreo\Ormeig\Columna $columna
     * @param \Sastreo\Ormeig\Enums\Comparacio $comparacio
     */
    public function __construct(
        public Columna $columna,
        public Comparacio $comparacio,
        public Columna|string|int|float|bool|DateTime|null $valor
    ) {
        $type1 = $columna->tipus;
        $type2 = $valor instanceof Columna ? $valor->tipus : gettype($valor);

        // Tipus diferents a int i float amb >, <, >=, <=
        if (in_array($comparacio, [Comparacio::LT, Comparacio::LTE, Comparacio::GT, Comparacio::GTE])) {
            if (!in_array($type1, [Tipus::INT->value, 'int', Tipus::FLOAT->value, 'float'])) {
                throw new CondicioTipusColumna($columna, $comparacio);
            }
            if (!in_array($type2, [Tipus::INT->value, 'int', Tipus::FLOAT->value, 'float'])) {
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

    private function valorToString(Columna|string|int|float|bool|DateTime|null $valor): string
    {
        if (is_bool($valor)) {
            return $valor ? '1' : '0';
        } else  if (is_scalar($valor) || $valor instanceof Stringable) {
            return gettype($valor) === 'string' ? "'$valor'" : (string) $valor;
        } else if ($valor instanceof DateTime) {
            return $valor->format('Y-m-d H:i:s');
        } else {
            return "";
        }
    }
}
