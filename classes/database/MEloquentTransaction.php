<?php

namespace App\Database;

use Illuminate\Database\Capsule\Manager as DB;

/**
 * Transaction helper wrapping Eloquent's transaction support.
 *
 * Provides both closure-based (recommended) and manual begin/commit/rollback APIs,
 * familiar to both MIOLO (MTransaction) and Adianti (TTransaction) users.
 *
 * Usage (closure-based — auto commits/rolls back):
 *   MEloquentTransaction::run(function () {
 *       MExampleProduto::create(['nome' => 'Widget', 'preco' => 9.99]);
 *   });
 *
 * Usage (manual):
 *   MEloquentTransaction::begin();
 *   try {
 *       // ... operations ...
 *       MEloquentTransaction::commit();
 *   } catch (\Throwable $e) {
 *       MEloquentTransaction::rollback();
 *       throw $e;
 *   }
 */
class MEloquentTransaction
{
    /**
     * Execute a callback within a database transaction.
     * Automatically commits on success, rolls back on exception.
     */
    public static function run(callable $callback, string $connection = 'default'): mixed
    {
        return DB::connection($connection)->transaction($callback);
    }

    public static function begin(string $connection = 'default'): void
    {
        DB::connection($connection)->beginTransaction();
    }

    public static function commit(string $connection = 'default'): void
    {
        DB::connection($connection)->commit();
    }

    public static function rollback(string $connection = 'default'): void
    {
        DB::connection($connection)->rollBack();
    }
}

class_alias(MEloquentTransaction::class, 'MEloquentTransaction');
