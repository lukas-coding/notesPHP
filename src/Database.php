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

    private $conn;

    public function __construct(array $config)
    {
        try {
            $this->createConnection($config);
            $this->validateConfig($config);
        } catch (PDOException $e) {
            throw new StorageException("Błąd połączenia z bazą danych");
        }
    }

    public function createNote(array $data): void
    {
        try {

            $title = $this->conn->quote($data['title']);
            $desc = $this->conn->quote($data['description']);
            $create = $this->conn->quote(date('Y-m-d H:i:s'));

            $query = "INSERT INTO notes(title, description, created) 
            VALUES($title, $desc, $create)";

            $result = $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się dodać notatki', 400, $e);
        }
    }

    private function createConnection(array $config): void
    {
        $dsn = "mysql:dbname={$config['database']}; host={$config['host']}";
        $this->conn = new PDO(
            $dsn,
            $config['user'],
            $config['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    private function validateConfig(array $config): void
    {
        if (
            empty($config['database'])
            || empty($config['host'])
            || empty($config['user'])
            || empty($config['password'])
        ) {
            throw new ConfigurationException('Storage configuration Error');
        }
    }
}
