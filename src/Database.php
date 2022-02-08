<?php

declare(strict_types=1);

namespace App;

use App\Exception\ConfigurationException;
use App\Exception\StorageException;
use PDO;
use PDOException;
use Throwable;

class Database
{
    public function __construct(array $config)
    {
        try {
            if (
                empty($config['database'])
                || empty($config['hst'])
                || empty($config['user'])
                || empty($config['password'])
            ) {
                throw new ConfigurationException('Storage configuration Error');
            }
            $dsn = "mysql:dbname={$config['database']}; host={$config['host']}";
            $connection = new PDO($dsn, $config['user'], $config['password']);
        } catch (PDOException $e) {
            throw new StorageException("Błąd połączenia z bazą danych");
        }
    }
}
