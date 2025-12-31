<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Excepcions;

use Sastreo\Ormeig\Interfaces\Model;

class ColumnaNoExisteix extends \ValueError
{
    /**
     * Summary of __construct.
     *
     * @param string              $columna
     * @param class-string<Model> $model
     * @param int                 $code
     * @param \Throwable|null     $previous
     */
    public function __construct(string $columna, string $model, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct("La columna $columna no existeix al model $model.", $code, $previous);
    }
}
