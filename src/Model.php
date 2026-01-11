<?php

declare(strict_types=1);

namespace Sastreo\Ormeig;

use Sastreo\Ormeig\Atributs\Columna as ColumnaAtribut;
use Sastreo\Ormeig\Atributs\Pk;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Excepcions\ClauPrimariaNoDefinida;
use Sastreo\Ormeig\Excepcions\TaulaNoDefinida;

/**
 * Comprova si una classe té l'atribut de Taula definit.
 *
 * @param class-string $modelClass
 *
 * @return void
 *
 * @throws TaulaNoDefinida
 */
function classEsModel(string $modelClass): void
{
    $reflectModel = new \ReflectionClass($modelClass);
    $attrTaula = $reflectModel->getAttributes(Taula::class);
    if (\count($attrTaula) !== 1) {
        throw new TaulaNoDefinida($modelClass);
    }
}

/**
 * @param class-string $modelClass
 *
 * @return Columna[]
 */
function getClausPrimaries(string $modelClass): array
{
    classEsModel($modelClass);

    $reflectionModel = new \ReflectionClass($modelClass);
    $reflectionProperties = $reflectionModel->getProperties();
    $clausPrimaries = [];
    foreach ($reflectionProperties as $property) {
        $attrsPk = $property->getAttributes(Pk::class);
        if (\count($attrsPk) === 1) {
            $cPColumna = new Columna($modelClass, $property->getName());
            array_push($clausPrimaries, $cPColumna);
        }
    }

    if (\count($clausPrimaries) === 0) {
        throw new ClauPrimariaNoDefinida($modelClass);
    }

    return $clausPrimaries;
}

/**
 * Retorna un array amb un mapeig entre el nom de la columnes a la taula i les propieta de la classe [columna => propietat].
 *
 * @param class-string $modelClass
 *
 * @return array<string, string>
 */
function getMappedColumns(string $modelClass): array
{
    classEsModel($modelClass);

    $mappedColumns = [];
    $reflectionModel = new \ReflectionClass($modelClass);
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
