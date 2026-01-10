<?php

declare(strict_types=1);

namespace Sastreo\Ormeig;

use Sastreo\Ormeig\Atributs\Columna as ColumnaAtribut;
use Sastreo\Ormeig\Atributs\Pk;
use Sastreo\Ormeig\Excepcions\ClauPrimariaNoDefinida;
use Sastreo\Ormeig\Interfaces\Model as ModelInterface;

abstract class Model implements ModelInterface
{
    public static function __callStatic(string $method, mixed $_): Columna
    {
        return new Columna(static::class, $method);
    }

    /**
     * @return Columna[]
     */
    public static function getClausPrimaries(): array
    {
        $reflectionModel = new \ReflectionClass(static::class);
        $reflectionProperties = $reflectionModel->getProperties();
        $clausPrimaries = [];
        foreach ($reflectionProperties as $property) {
            $attrsPk = $property->getAttributes(Pk::class);
            if (\count($attrsPk) === 1) {
                $cPColumna = new Columna(static::class, $property->getName());
                array_push($clausPrimaries, $cPColumna);
            }
        }

        if (\count($clausPrimaries) === 0) {
            throw new ClauPrimariaNoDefinida(static::class);
        }

        return $clausPrimaries;
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
