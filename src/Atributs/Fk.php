<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Atributs;

use Attribute;
use Exception;
use ReflectionClass;
use Sastreo\Ormeig\Interfaces\Model;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Fk
{
    /**
     * @param class-string<Model> $model
     * @param string|null $columna
     */
    public function __construct(
        public readonly string $model,
        public readonly string|null $columna = null
    ) {
        $reflectionClass = new ReflectionClass($model);
        if (!$reflectionClass->isSubclassOf(Model::class)) {
            throw new Exception();
        }
    }
}
