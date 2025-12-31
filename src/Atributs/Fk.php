<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Atributs;

use Sastreo\Ormeig\Interfaces\Model;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class Fk
{
    /**
     * @param class-string<Model> $model
     * @param string|null         $columna
     */
    public function __construct(
        public readonly string $model,
        public readonly ?string $columna = null,
    ) {
        $reflectionClass = new \ReflectionClass($model);
        if (!$reflectionClass->isSubclassOf(Model::class)) {
            throw new \Exception();
        }
    }
}
