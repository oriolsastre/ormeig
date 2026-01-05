<?php

declare(strict_types=1);

namespace Sastreo\Ormeig;

use Sastreo\Ormeig\Atributs\Columna as ColumnaAtribut;
use Sastreo\Ormeig\Atributs\Pk;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Enums\Comparacio;
use Sastreo\Ormeig\Enums\Join as JoinEnum;
use Sastreo\Ormeig\Enums\Ordenacio as OrdenacioEnum;
use Sastreo\Ormeig\Excepcions\ClauPrimariaNoDefinida;
use Sastreo\Ormeig\Interfaces\Model;
use Sastreo\Ormeig\Sql\Condicio;
use Sastreo\Ormeig\Sql\Join;
use Sastreo\Ormeig\Sql\Ordenacio;

class Gestor
{
    private readonly string $taula;
    /** @var string[] */
    private array $pk;

    /**
     * @param class-string<T> $model
     *
     * @template T of Model
     */
    public function __construct(
        private readonly Ormeig $ormeig,
        public readonly string $model,
    ) {
        $this->pk = [];
        $reflectionModel = new \ReflectionClass($model);

        $attrTaula = $reflectionModel->getAttributes(Taula::class);
        if (\count($attrTaula) === 1 && $attrTaula[0]->newInstance()->nom !== null) {
            $this->taula = $attrTaula[0]->newInstance()->nom;
        } else {
            $this->taula = $reflectionModel->getShortName();
        }

        $reflectionProperties = $reflectionModel->getProperties();
        foreach ($reflectionProperties as $property) {
            $attrsPk = $property->getAttributes(Pk::class);
            if (\count($attrsPk) === 1) {
                $attrsColumna = $property->getAttributes(ColumnaAtribut::class);
                if (\count($attrsColumna) === 1 && $attrsColumna[0]->newInstance()->nom !== null) {
                    array_push($this->pk, $attrsColumna[0]->newInstance()->nom);
                } else {
                    array_push($this->pk, $property->getName());
                }
            }
        }
        if (\count($this->pk) === 0) {
            $idProp = array_filter($reflectionProperties, fn ($property): bool => $property->getName() === 'id');
            if (\count($idProp) === 1) {
                array_push($this->pk, $idProp[0]->getName());
            } else {
                throw new ClauPrimariaNoDefinida($model);
            }
        }
    }

    /**
     * @return class-string<Model>
     */
    public function getModel(): string
    {
        return $this->model;
    }

    public function getTaula(): string
    {
        return $this->taula;
    }

    /**
     * @return string[]
     */
    public function getPk(): array
    {
        return $this->pk;
    }

    public function consulta(): Consulta
    {
        return new Consulta($this->getModel());
    }

    /**
     * @param Columna  $columnaOrigen
     * @param Columna  $columnaDesti
     * @param JoinEnum $join
     *
     * @return Join
     */
    public function join(Columna $columnaOrigen, Columna $columnaDesti, JoinEnum $join = JoinEnum::INNER): Join
    {
        return new Join($columnaOrigen, $columnaDesti, $join);
    }

    /**
     * @param Columna    $columna
     * @param Comparacio $comparacio
     *
     * @return Condicio
     *
     * @throws Excepcions\ColumnaNoExisteix
     */
    public function condicio(Columna $columna, Comparacio $comparacio, Columna|string|int|float|bool|\DateTime|null $valor): Condicio
    {
        // TODO: S'hauria de controlar que la taula de la columna està inclosa al FORM o JOIN
        return new Condicio($columna, $comparacio, $valor);
    }

    /**
     * Summary of ordenacio.
     *
     * @param Columna       $columna
     * @param OrdenacioEnum $ordre
     *
     * @return Ordenacio
     *
     * @throws Excepcions\ColumnaNoExisteix
     */
    public function ordenacio(Columna $columna, OrdenacioEnum $ordre): Ordenacio
    {
        return new Ordenacio($columna, $ordre);
    }

    /**
     * @param Consulta $consulta
     *
     * @return ?Model[]
     */
    public function executaConsulta(Consulta $consulta): ?array
    {
        $stmt = $this->ormeig->executaConsulta($consulta);
        if ($stmt !== false) {
            $rawData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $data = [];
            /** @var array<string, mixed> $raw */
            foreach ($rawData as $raw) {
                array_push($data, $this->mapToModel($raw, $this->getModel()));
            }

            return $data;
        }

        return null;
    }

    #region CRUD?
    /**
     * @param int $limit
     */
    public function trobaTots(int $limit = 100): Consulta
    {
        return $this->consulta()->limit($limit);
    }

    /**
     * Troba una entrada per la seva clau primària. En cas de ser mixta, passa un array associatiu amb propietat => valor.
     *
     * @param mixed $id
     *
     * @return Consulta
     */
    public function trobaPerId(mixed $id): Consulta
    {
        // TODO: Comprovar que l'$id és del tipus que toca
        $clausPrimaries = $this->getModel()::getClausPrimaries();
        if (!\is_array($id)) {
            $id = [$id];
        }
        if (\count($id) !== \count($clausPrimaries)) {
            throw new \TypeError();
        }
        $consulta = $this->consulta()->limit(1);
        if (\count($clausPrimaries) === 1) {
            $pk = $clausPrimaries[0];
            /** @var Columna $pkColumna */
            $pkColumna = $this->getModel()::$pk();
            $consulta->condicio($this->condicio($pkColumna, Comparacio::EQ, $id[0]));
        } else {
            foreach ($id as $key => $value) {
                /** @var string $key */
                $pk = $clausPrimaries[$key];
                /** @var Columna $pkColumna */
                $pkColumna = $this->getModel()::$pk();
                $consulta->condicio($this->condicio($pkColumna, Comparacio::EQ, $value));
            }
        }

        return $consulta;
    }

    // public function desar(Model $model): Model {}
    // public function eliminar(Model $model): void {}
    #endregion
    /**
     * @param array<string, mixed> $data
     * @param class-string<T>      $model
     *
     * @template T of Model
     *
     * @return Model
     */
    private function mapToModel(array $data, string $model): Model
    {
        $mappedData = [];
        $mapping = $model::getMappedColumns();
        foreach ($data as $key => $value) {
            if (isset($mapping[$key])) {
                $mappedData[$mapping[$key]] = $value;
            }
        }
        $reflection = new \ReflectionClass($model);
        /** @var Model $instance */
        $instance = $reflection->newInstanceWithoutConstructor();
        foreach ($mappedData as $key => $value) {
            $prop = $reflection->getProperty($key);
            $prop->setValue($instance, $value);
        }

        return $instance;
    }
}
