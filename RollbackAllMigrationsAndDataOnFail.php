<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RollbackAllMigrationsAndDataOnFail extends Command
{
    protected $signature = 'migrate:transaction';
    protected $description = 'Run all migrations within a single transaction with custom handling';

    public function handle()
    {
        $databaseName = 'mysql'; // Replace this with Database Connections from config/database.php

        $connection = DB::connection($this->getConnectionName($databaseName));

        try {
            $connection->beginTransaction();
            $migrationsPath = 'database/migrations';

            $restorationClosure = function () use ($connection) {
                $connection->rollBack();
                $this->info('Rollback failed. Data has been restored.');
                exit(1);
            };

            $this->call('migrate:rollback', [
                '--path' => $migrationsPath,
                '--force' => true,
                '--pretend' => true,
            ]);

            $this->call('migrate', [
                '--path' => $migrationsPath,
                '--force' => true,
            ]);

            // When you reach this point
            // so all migrations have been successfully executed
            $connection->commit();
            $this->info('Migrations executed successfully.');
        } catch (\Exception $e) {
            // Restore data
            $restorationClosure();
            throw $e;
        }
    }

    protected function getConnectionName($databaseName)
    {
        // If $databaseName is null, use the default connection name
        if ($databaseName === null) {
            return 'default'; // Replace 'default' with your actual default connection name
        }

        // If $databaseName is not null, create a custom connection name based on it
        return $databaseName; // You can modify this to create a unique custom connection name
    }
}
