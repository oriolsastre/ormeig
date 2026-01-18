<?php

declare(strict_types=1);

namespace Sastreo\Ormeig;

use Sastreo\Ormeig\Atributs\Columna as ColumnaAtribut;
use Sastreo\Ormeig\Atributs\Pk;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Enums\Comparacio;
use Sastreo\Ormeig\Enums\Consulta as ConsultaEnum;
use Sastreo\Ormeig\Enums\Join as JoinEnum;
use Sastreo\Ormeig\Enums\Ordenacio as OrdenacioEnum;
use Sastreo\Ormeig\Excepcions\ClauPrimariaNoDefinida;
use Sastreo\Ormeig\Excepcions\ColumnaNoExisteix;
use Sastreo\Ormeig\Sql\Condicio;
use Sastreo\Ormeig\Sql\Join;
use Sastreo\Ormeig\Sql\Ordenacio;

class Gestor
{
    private readonly string $taula;
    /** @var string[] */
    private array $pk;

    /**
     * @param class-string $model
     */
    public function __construct(
        private readonly Ormeig $ormeig,
        public readonly string $model,
    ) {
        classEsModel($this->model);

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
     * @return class-string
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

    public function consulta(ConsultaEnum $tipus = ConsultaEnum::SELECT): Consulta
    {
        return new Consulta($this->getModel(), $tipus);
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
     * @param Columna|string $columna
     * @param Comparacio     $comparacio
     * @param mixed          $valor
     *
     * @return Condicio
     *
     * @throws ColumnaNoExisteix
     */
    public function condicio(Columna|string $columna, Comparacio $comparacio, mixed $valor): Condicio
    {
        if (\is_string($columna)) {
            $columna = new Columna($this->getModel(), $columna);
        }

        // TODO: S'hauria de controlar que la taula de la columna està inclosa al FORM o JOIN
        return new Condicio($columna, $comparacio, $valor);
    }

    /**
     * Summary of ordenacio.
     *
     * @param Columna|string $columna
     * @param OrdenacioEnum  $ordre
     *
     * @return Ordenacio
     *
     * @throws ColumnaNoExisteix
     */
    public function ordenacio(Columna|string $columna, OrdenacioEnum $ordre): Ordenacio
    {
        if (\is_string($columna)) {
            $columna = new Columna($this->getModel(), $columna);
        }

        return new Ordenacio($columna, $ordre);
    }

    /**
     * @param Consulta $consulta
     *
     * @return ?object[]
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
     *
     * @return object[]
     */
    public function trobaTots(int $limit = 100): array
    {
        $consulta = $this->consulta()->limit($limit);
        $resultat = $this->executaConsulta($consulta);
        if ($resultat === null) {
            return [];
        }

        return $resultat;
    }

    /**
     * Troba una entrada per la seva clau primària. En cas de ser mixta, passa un array associatiu amb propietat => valor.
     *
     * @param mixed $id
     *
     * @return object|null
     */
    public function trobaPerId(mixed $id): ?object
    {
        $id = clausPrimariesValides($this->getModel(), $id);
        $clausPrimaries = getClausPrimaries($this->getModel());

        $consulta = $this->consulta()->limit(1);

        foreach ($id as $key => $value) {
            $pk = array_filter($clausPrimaries, fn (Columna $columna) => $columna->columna === $key)[0];
            $consulta->condicio($this->condicio($pk, Comparacio::EQ, $value));
        }

        $resultat = $this->executaConsulta($consulta);
        if ($resultat === null || \count($resultat) === 0) {
            return null;
        } elseif (\count($resultat) === 1) {
            return $resultat[0];
        } else {
            // TODO : PENSAR L'ERROR
            throw new \TypeError();
        }
    }

    // public function desar(Model $model): Model {}
    public function eliminar(object $entitat): void
    {
        classEsModel($entitat::class);
        if ($this->getModel() !== $entitat::class) {
            // TODO : PENSAR L'ERROR
            throw new \TypeError('El model de l\'entitat no coincideix amb el model del gestor.');
        }

        $consulta = $this->consulta(ConsultaEnum::DELETE);

        $pks = getClausPrimaries($this->getModel());
        foreach ($pks as $pk) {
            // TODO : Comprovar que l'entitat té valors en les claus primàries?
            $consulta->condicio($this->condicio($pk, Comparacio::EQ, getValorColumnaModel($entitat, $pk)));
        }

        $this->executaConsulta($consulta);
    }

    #endregion
    /**
     * @param array<string, mixed> $data
     * @param class-string         $modelClass
     *
     * @return object
     */
    private function mapToModel(array $data, string $modelClass): object
    {
        $mappedData = [];
        $mapping = getMappedColumns($modelClass);
        foreach ($data as $key => $value) {
            if (isset($mapping[$key])) {
                $mappedData[$mapping[$key]] = $value;
            }
        }
        $reflection = new \ReflectionClass($modelClass);
        $instance = $reflection->newInstanceWithoutConstructor();
        foreach ($mappedData as $key => $value) {
            $prop = $reflection->getProperty($key);
            $prop->setValue($instance, $value);
        }

        return $instance;
    }
}
