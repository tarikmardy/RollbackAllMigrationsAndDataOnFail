# Rollback All Migrations and Data On Fail

This Laravel custom command allows you to run all migrations within a single transaction with custom handling. It ensures that if any migration fails, the entire migration process will be rolled back, including the data changes made during the migrations.

## How to Use

1. Create the custom command using `php artisan make:command`:

```bash
php artisan make:command RollbackAllMigrationsAndDataOnFail
```
This will create a new file named **`RollbackAllMigrationsAndDataOnFail.php`** in the **`app/Console/Commands`** directory.

2. Open the **`RollbackAllMigrationsAndDataOnFail.php`** file and replace its content with the code provided in this repository.

3. Open the **`RollbackAllMigrationsAndDataOnFail.php`** file and make sure to update the **$databaseName** variable with the desired database connection name from your **`config/database.php`** file. This is the database connection that the command will use to perform the migrations.

4. Run the custom command using Artisan:

```bash
php artisan migrate:transaction
```
The command will execute all the pending migrations within a single transaction. If any migration fails during the process, the entire migration process will be rolled back, and the data will be restored to its original state.

## Notes

- Before running the custom command, make sure to have proper database backup mechanisms in place since it involves migrations and data changes.
- The custom command uses the ``migrate:rollback`` command with the ``--pretend`` option to simulate the rollback process. If any migration fails during the simulation, the rollback process will be triggered.
- The custom command will display informative messages during the process, indicating the status of the migration.
- The ``getConnectionName`` method can be modified to create a custom connection name based on the ``$databaseName`` variable. By default, it uses the provided ``$databaseName`` as the connection name.
