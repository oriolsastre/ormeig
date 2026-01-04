<?php

declare(strict_types=1);

namespace Sastreo\Ormeig;

use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Enums\Consulta as EnumsConsulta;
use Sastreo\Ormeig\Excepcions\TaulaNoDefinida;
use Sastreo\Ormeig\Interfaces\Model;
use Sastreo\Ormeig\Interfaces\OperadorLogic;
use Sastreo\Ormeig\Logic\LogicI;
use Sastreo\Ormeig\Sql\Condicio;
use Sastreo\Ormeig\Sql\Join;
use Sastreo\Ormeig\Sql\Ordenacio;

class Consulta
{
    private string $taula;
    /** @var array<int, Join> */
    private array $joins = [];
    private LogicI $condicions;
    /** @var array<int, Ordenacio> */
    private array $ordre = [];
    private int $limit = 100;

    /**
     * @param class-string<Model> $model
     * @param EnumsConsulta       $tipus
     */
    public function __construct(
        private readonly string $model,
        public EnumsConsulta $tipus = EnumsConsulta::SELECT,
    ) {
        $this->taula = $this->getTaulaFromModel($this->model);
        $this->condicions = new LogicI();
    }

    public function join(Join $join): self
    {
        array_push($this->joins, $join);

        return $this;
    }

    public function condicio(OperadorLogic|Condicio $condicio): self
    {
        $this->condicions->append($condicio);

        return $this;
    }

    public function ordena(Ordenacio $ordre): self
    {
        array_push($this->ordre, $ordre);

        return $this;
    }

    /**
     * Límit per defecte és 100. Si es vol sense límit, passa 0.
     *
     * @param int $limit
     *
     * @return Consulta
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    #region SQL
    public function getSql(): string
    {
        $select = 'SELECT *';
        $from = $this->getFromSql();
        $where = $this->getWhereSql();
        $ordre = $this->getOrdenacioSql();
        $limit = $this->getLimitSql();

        $sql = "$select $from";
        if ($where !== false) {
            $sql .= " $where";
        }
        if ($ordre !== false) {
            $sql .= " $ordre";
        }
        if ($limit !== false) {
            $sql .= " $limit";
        }
        $sql .= ';';

        return $sql;
    }

    private function getFromSql(): string
    {
        return "FROM $this->taula";
    }

    private function getWhereSql(): string|false
    {
        if (\count($this->condicions) === 0) {
            return false;
        }
        $sql = 'WHERE '.$this->condicions->toSql();

        return $sql;
    }

    private function getOrdenacioSql(): string|false
    {
        if (\count($this->ordre) === 0) {
            return false;
        }
        $sql = 'ORDER BY ';
        $sql .= implode(', ', array_map(function (Ordenacio $ordenacio): string {
            return $ordenacio->toSql();
        }, array_values($this->ordre)));

        return $sql;
    }

    private function getLimitSql(): string|false
    {
        if ($this->limit > 0) {
            return "LIMIT $this->limit";
        }

        return false;
    }

    #endregion
    /**
     * @param class-string<Model> $model
     *
     * @return string
     *
     * @throws TaulaNoDefinida
     */
    private function getTaulaFromModel(string $model): string
    {
        $reflectModel = new \ReflectionClass($model);
        $attrTaula = $reflectModel->getAttributes(Taula::class);
        if (\count($attrTaula) !== 1) {
            throw new TaulaNoDefinida($model);
        }

        return $attrTaula[0]->newInstance()->nom ?? $reflectModel->getShortName();
    }
}
