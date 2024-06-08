<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    function removeTenantDatabaseIfExists($databaseName)
    {
        // Usar parâmetros para evitar injeção de SQL
        $databaseExists = DB::select("SHOW DATABASES LIKE ?", [$databaseName]);

        if ($databaseExists) {
            // Uso seguro do nome do banco de dados com interpolação segura
            DB::statement("DROP DATABASE `$databaseName`");
            echo "Removed existing database: $databaseName\n";
        }
    }

    public function run(): void
    {
        // Chamar a função corretamente e passar o nome do banco de dados
        $this->removeTenantDatabaseIfExists("purosaas");
    }
}
