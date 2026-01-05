<?php

declare(strict_types=1);

namespace Sastreo\Ormeig;

use Sastreo\Ormeig\Atributs\Columna as ColumnaAtribut;
use Sastreo\Ormeig\Interfaces\Model as ModelInterface;

abstract class Model implements ModelInterface
{
    public static function __callStatic(string $method, mixed $_): Columna
    {
        return new Columna(static::class, $method);
    }

    /**
     * Retorna un array amb un mapeig entre el nom de la columnes a la taula i les propieta de la classe [columna => propietat].
     *
     * @return array<string, string>
     */
    public static function getMappedColumns(): array
    {
        $mappedColumns = [];
        $reflectionModel = new \ReflectionClass(static::class);
        $reflectionProperties = $reflectionModel->getProperties();
        foreach ($reflectionProperties as $property) {
            $attrsColumna = $property->getAttributes(ColumnaAtribut::class);
            if (\count($attrsColumna) === 1) {
                /** @var string $columnName */
                $columnName = $attrsColumna[0]->newInstance()->nom ?? $property->getName();
                $modelName = $property->getName();
                $mappedColumns[$columnName] = $modelName;
            }
        }

        return $mappedColumns;
    }
}
