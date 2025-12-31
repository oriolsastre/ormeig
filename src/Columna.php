<?php

declare(strict_types=1);

namespace Sastreo\Ormeig;

use ReflectionAttribute;
use ReflectionClass;
use Sastreo\Ormeig\Atributs\Columna as ColumnaAtribut;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Excepcions\ColumnaNoExisteix;
use Sastreo\Ormeig\Excepcions\TaulaNoDefinida;
use Sastreo\Ormeig\Interfaces\Model;
use Throwable;

class Columna
{
    public readonly string $taulaSql;
    public readonly string $columnaSql;
    public readonly string $tipus;
    /**
     * @param class-string<Model> $model
     * @param string $columna
     * @throws \Sastreo\Ormeig\Excepcions\ColumnaNoExisteix
     */
    public function __construct(
        public readonly string $model,
        public readonly string $columna
    ) {
        $reflectModel = new ReflectionClass($this->model);
        $this->taulaSql = $this->getTaulaSql($reflectModel);
        try {
            $reflectProperty = $reflectModel->getProperty($this->columna);
        } catch (Throwable) {
            throw new ColumnaNoExisteix($this->columna, $this->model);
        }

        $attrColumna = $reflectProperty->getAttributes(ColumnaAtribut::class);
        if (count($attrColumna) !== 1) {
            throw new ColumnaNoExisteix($this->columna, $this->model);
        }
        $reflectPropertyType = $reflectProperty->getType()->getName();

        $this->columnaSql = $this->getColumnaSqlFromColumna($attrColumna[0], $this->columna);
        $this->tipus = $attrColumna[0]->newInstance()->tipus ?? (string) $reflectPropertyType;
    }

    public function __toString(): string
    {
        return "$this->taulaSql.$this->columnaSql";
    }
    /**
     * @param ReflectionAttribute<ColumnaAtribut> $attrColumna
     * @param string $columna
     * @throws \Sastreo\Ormeig\Excepcions\ColumnaNoExisteix
     * @return string
     */
    private function getColumnaSqlFromColumna(ReflectionAttribute $attrColumna, string $columna): string
    {
        return $attrColumna->newInstance()->nom ?? $columna;
    }

    /**
     * @param ReflectionClass<Model> $reflectModel
     * @throws \Sastreo\Ormeig\Excepcions\ColumnaNoExisteix
     * @return string
     */
    private function getTaulaSql(ReflectionClass $reflectModel): string
    {
        $attrTaula = $reflectModel->getAttributes(Taula::class);
        if (count($attrTaula) !== 1) {
            throw new TaulaNoDefinida($this->model);
        }
        return $attrTaula[0]->newInstance()->nom ?? $reflectModel->getShortName();
    }
}
