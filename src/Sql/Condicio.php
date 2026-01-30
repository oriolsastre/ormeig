<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Sql;

use Sastreo\Ormeig\Columna;
use Sastreo\Ormeig\Enums\Comparacio;
use Sastreo\Ormeig\Enums\Tipus;
use Sastreo\Ormeig\Excepcions\CondicioTipusColumna;
use Sastreo\Ormeig\Interfaces\ClausulaSql;
use Sastreo\Ormeig\Model;

class Condicio implements ClausulaSql
{
    public Columna $columna;

    /**
     * @param Columna|array{class-string, string} $columna
     * @param Comparacio                          $comparacio
     * @param mixed                               $valor
     */
    public function __construct(
        Columna|array $columna,
        public Comparacio $comparacio,
        public mixed $valor,
    ) {
        if (\is_array($columna)) {
            $this->columna = new Columna($columna[0], $columna[1]);
        } else {
            $this->columna = $columna;
        }
        $type1 = $this->columna->tipus;
        $type2 = $valor instanceof Columna ? $valor->tipus : \gettype($valor);

        // Tipus diferents a int i float amb >, <, >=, <=
        if (\in_array($comparacio, [Comparacio::LT, Comparacio::LTE, Comparacio::GT, Comparacio::GTE])) {
            if (!\in_array($type1, [Tipus::INT->value, 'int', Tipus::FLOAT->value, 'float'])) {
                throw new CondicioTipusColumna($this->columna, $comparacio);
            }
            if (!\in_array($type2, [Tipus::INT->value, 'int', Tipus::FLOAT->value, 'float'])) {
                throw new CondicioTipusColumna($valor, $comparacio);
            }
        }
        // Tipus diferents a string amb LIKE
        if ($comparacio === Comparacio::LIKE) {
            if ($type1 !== Tipus::STRING->value) {
                throw new CondicioTipusColumna($this->columna, $comparacio);
            }
            if ($type2 !== Tipus::STRING->value) {
                throw new CondicioTipusColumna($valor, $comparacio);
            }
        }
    }

    public function toSql(): string
    {
        $sql = "{$this->columna} {$this->comparacio->value}";
        if ($this->comparacio !== Comparacio::NULL) {
            $sql .= ' '.Model::valorToString($this->valor);
        }

        return $sql;
    }
}
