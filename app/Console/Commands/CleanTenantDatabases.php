<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class CleanTenantDatabases extends Command
{
    protected $signature = "tenant:clean-databases";
    protected $description = 'Remove all tenant databases that start with "tenant"';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Configurações do banco de dados
        $connection = Config::get("database.default");
        $config = Config::get("database.connections.$connection");

        // Verifica se a conexão é MySQL
        if ($config["driver"] !== "mysql") {
            $this->error("Este comando é suportado apenas para MySQL.");
            return 1;
        }

        $host = $config["host"];
        $username = $config["username"];
        $password = $config["password"];

        try {
            $pdo = new \PDO("mysql:host=$host", $username, $password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Obtém todos os bancos de dados
            $query = $pdo->query("SHOW DATABASES");
            $databases = $query->fetchAll(\PDO::FETCH_COLUMN);

            // Remove bancos de dados que começam com letras de A a Z, exceto information_schema
            foreach ($databases as $database) {
                if (
                    !in_array($database, [
                        "information_schema",
                        "mysql",
                        "performance_schema",
                        "purosaas",
                    ]) &&
                    preg_match("/^[A-Z]/i", $database)
                ) {
                    $this->info("Dropping database: $database");
                    $pdo->exec("DROP DATABASE `$database`");
                }
            }

            $this->info(
                "All databases starting with letters A to Z, excluding information_schema, have been removed."
            );
            return 0;
        } catch (\PDOException $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
