<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Atributs;

use Attribute;
use Sastreo\Ormeig\Enums\Tipus;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Columna
{
    public readonly string|null $tipus;
    /**
     * @param string|null $nom
     * @param bool|null $unica
     */
    public function __construct(
        public readonly string|null $nom = null,
        public readonly bool|null $unica = null,
        Tipus|string|null $tipus = null
    ) {
        $this->tipus = $tipus instanceof Tipus ? $tipus->value : $tipus;
    }
}
