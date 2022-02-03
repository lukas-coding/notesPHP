<?php

declare(strict_types=1);

namespace App;

use Exception;
use PDO;

class Database
{
    public function __construct(array $config)
    {
        $dsn = "mysql:dbname={$config['database']}; host={$config['host']}";

        try {
            $connection = new PDO($dsn, $config['user'], $config['password']);
        } catch (Exception $error) {
            echo '<h1 style="color: gray">Błąd połączenia z bazą danych</h1>';
            exit;
        }
    }
}
