<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Seed;

use Sastreo\Ormeig\Enums\Driver;

class DatabaseSetUp
{
    private static ?\PDO $dbcnx = null;

    public static function crearBaseDades(Driver $driver = Driver::SQLITE): void
    {
        if (!self::$dbcnx) {
            $sqliteDbPath = __DIR__.'/test.db';
            self::$dbcnx = new \PDO(
                dsn: 'sqlite:'.$sqliteDbPath,
                username: null,
                password: null,
                options: [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
            $sql_crea_taules = file_get_contents(__DIR__.'/db_test.sql');
            self::$dbcnx->exec($sql_crea_taules);
        }
    }

    public static function seedDatabase(): void
    {
        $dbcnx = self::$dbcnx;
        if (!$dbcnx) {
            self::crearBaseDades();
        }
        $sql_seed = file_get_contents(__DIR__.'/db_test_seed.sql');
        $dbcnx->exec($sql_seed);
    }
}
