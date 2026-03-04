<?php

namespace App\Database;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use App\Utils\MConfigLoader;

class MEloquentBootstrap
{
    private static ?Capsule $capsule = null;

    /** MIOLO system name → Eloquent driver name */
    private const DRIVER_MAP = [
        'postgres'  => 'pgsql',
        'mysql'     => 'mysql',
        'sqlite'    => 'sqlite',
        'mssql'     => 'sqlsrv',
        'oracle8'   => 'oracle',   // requires yajra/laravel-oci8
        'firebird'  => 'firebird', // requires harrygulliford/laravel-firebird
    ];

    /**
     * Initialize Eloquent from MIOLO's configuration.
     *
     * Safe to call multiple times — returns the existing Capsule after first boot.
     */
    public static function boot(MConfigLoader $configLoader): Capsule
    {
        if (self::$capsule !== null) {
            return self::$capsule;
        }

        $capsule = new Capsule;
        $capsule->setEventDispatcher(new Dispatcher);

        self::registerConnections($capsule, $configLoader);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        self::$capsule = $capsule;
        return $capsule;
    }

    public static function getCapsule(): ?Capsule
    {
        return self::$capsule;
    }

    /**
     * Reset the bootstrap state (useful for testing).
     */
    public static function reset(): void
    {
        self::$capsule = null;
    }

    /**
     * Discover all db.<connName>.* entries and register each as an Eloquent connection.
     *
     * MIOLO config keys: db.<name>.{system, host, name, user, password, port}
     * Eloquent expects:  driver, host, database, username, password, port, charset, prefix
     */
    private static function registerConnections(Capsule $capsule, MConfigLoader $configLoader): void
    {
        $dbEntries = $configLoader->getConfByPrefix('db.');

        // Collect unique connection names from keys like "db.miolo.system"
        $connectionNames = [];
        foreach (array_keys($dbEntries) as $key) {
            $parts = explode('.', $key, 3); // ['db', 'connName', 'field']
            if (count($parts) === 3) {
                $connectionNames[$parts[1]] = true;
            }
        }

        $first = true;
        foreach (array_keys($connectionNames) as $connName) {
            $system = $configLoader->getConf("db.{$connName}.system") ?? 'postgres';
            $driver = self::DRIVER_MAP[$system] ?? $system;

            $config = [
                'driver'   => $driver,
                'host'     => $configLoader->getConf("db.{$connName}.host") ?? 'localhost',
                'database' => $configLoader->getConf("db.{$connName}.name") ?? '',
                'username' => $configLoader->getConf("db.{$connName}.user") ?? '',
                'password' => $configLoader->getConf("db.{$connName}.password") ?? '',
                'charset'  => $configLoader->getConf("db.{$connName}.charset") ?? 'utf8',
                'prefix'   => $configLoader->getConf("db.{$connName}.prefix") ?? '',
            ];

            $port = $configLoader->getConf("db.{$connName}.port");
            if ($port !== null) {
                $config['port'] = (int) $port;
            }

            $capsule->addConnection($config, $connName);

            // First connection also registered as 'default'
            if ($first) {
                $capsule->addConnection($config, 'default');
                $first = false;
            }
        }

        // If no connections were found, register a fallback SQLite connection
        // so Eloquent doesn't fail on boot
        if (empty($connectionNames)) {
            $capsule->addConnection([
                'driver'   => 'sqlite',
                'database' => ':memory:',
            ], 'default');
        }
    }
}

class_alias(MEloquentBootstrap::class, 'MEloquentBootstrap');
