<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Atributs;

use function Sastreo\Ormeig\classEsModel;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class Fk
{
    /**
     * @param class-string $model
     * @param string|null  $columna
     */
    public function __construct(
        public readonly string $model,
        public readonly ?string $columna = null,
    ) {
        classEsModel($this->model);
    }
}
