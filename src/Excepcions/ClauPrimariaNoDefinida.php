<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Excepcions;

use Sastreo\Ormeig\Interfaces\Model;

class ClauPrimariaNoDefinida extends \ValueError
{
    /**
     * @param class-string<Model> $model
     */
    public function __construct(string $model, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct("El model $model no té clau primària definida.", $code, $previous);
    }
}
