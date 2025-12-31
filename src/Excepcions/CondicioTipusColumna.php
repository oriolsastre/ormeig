<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Excepcions;

use DateTime;
use Sastreo\Ormeig\Columna;
use Sastreo\Ormeig\Enums\Comparacio;
use Stringable;
use TypeError;
use function is_scalar, gettype;

class CondicioTipusColumna extends TypeError
{
    public function __construct(Columna|string|int|float|bool|DateTime|null $valor, Comparacio $comparacio)
    {
        $valorStr = $valor instanceof Columna ? "La columna" :  "El valor";
        if (is_scalar($valor) || $valor instanceof Stringable) {
            $valorStr .= " $valor";
        }
        $tipus = $valor instanceof Columna ? $valor->tipus : gettype($valor);
        parent::__construct("$valorStr de tipus $tipus no es compatible amb l'operador $comparacio->value.");
    }
}
