<?php

declare(strict_types=1);

namespace Sastreo\Ormeig;

use Sastreo\Ormeig\Enums\Driver;
use Sastreo\Ormeig\Interfaces\Model;

class Ormeig
{
    private \PDO $dbcnx;

    public function __construct(
        public readonly Driver $driver,
        public readonly string $dbname,
        public readonly ?string $host = null,
        public readonly ?string $user = null,
        public readonly ?string $password = null,
    ) {
        $dsn = "{$this->driver->value}";
        switch ($this->driver) {
            case Driver::SQLITE:
                $dsn .= ":{$this->dbname}";
                break;
            case Driver::MYSQL:
                if ($this->host) {
                    $dsn .= "host={$this->host};";
                }
                $dsn .= "dbname={$this->dbname};";
                break;
        }

        try {
            $this->dbcnx = new \PDO(
                dsn: $dsn,
                username: $this->user,
                password: $this->password,
                options: [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getDbcnx(): \PDO
    {
        return $this->dbcnx;
    }

    /**
     * @param class-string<T> $model
     *
     * @return Gestor
     *
     * @template T of Model
     */
    public function getGestor(string $model): Gestor
    {
        return new Gestor($this, $model);
    }

    public function executaConsulta(Consulta $consulta): \PDOStatement|false
    {
        $sql = $consulta->getSql();
        $stmt = $this->dbcnx->prepare($sql);
        // TODO: Bind
        $stmt->execute();

        return $stmt;
    }
}
