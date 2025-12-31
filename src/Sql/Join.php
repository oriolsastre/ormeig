<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Sql;

use Sastreo\Ormeig\Columna;
use Sastreo\Ormeig\Enums\Join as EnumsJoin;
use Sastreo\Ormeig\Interfaces\ClausulaSql;

class Join implements ClausulaSql
{
    /**
     * @param \Sastreo\Ormeig\Columna $columnaOrigen
     * @param \Sastreo\Ormeig\Columna $columnaDesti
     * @param \Sastreo\Ormeig\Enums\Join $join
     */
    public function __construct(
        public Columna $columnaOrigen,
        public Columna $columnaDesti,
        public EnumsJoin $join = EnumsJoin::INNER,
    ) {}
}
