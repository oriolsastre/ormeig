<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Excepcions;

class ClauPrimariaInvalida extends \ValueError
{
    /**
     * @param class-string    $model
     * @param mixed           $pK
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $model, mixed $pK, int $code = 0, ?\Throwable $previous = null)
    {
        $pKS = print_r($pK, true);
        $message = "La clau primària $pKS no es vàlida a la classe $model.";

        parent::__construct($message, $code, $previous);
    }
}
