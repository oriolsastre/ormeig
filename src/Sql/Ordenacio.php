<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Sql;

use Sastreo\Ormeig\Columna;
use Sastreo\Ormeig\Enums\Ordenacio as EnumsOrdenacio;
use Sastreo\Ormeig\Interfaces\ClausulaSql;

class Ordenacio implements ClausulaSql
{
    /**
     * @param Columna $columna
     * @param EnumsOrdenacio $ordre
     */
    public function __construct(
        public Columna $columna,
        public EnumsOrdenacio $ordre
    ) {}

    public function toSql(): string
    {
        $model = $this->columna->taulaSql;
        $columna = $this->columna->columnaSql;
        $ordre = $this->ordre->value;
        return "$model.$columna $ordre";
    }
}
