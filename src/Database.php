<?php

declare(strict_types=1);

namespace App;

use App\Exception\ConfigurationException;
use App\Exception\StorageException;
use App\Exception\NotFoundException;
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

    public function getNote(int $id): array
    {
        try {
            $query = "SELECT * FROM notes WHERE id = $id";
            $result = $this->conn->query($query);
            $note = $result->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się pobrać notatki', 400, $e);
        }

        if (!$note) {
            throw new NotFoundException("Notatka o id: $id nie istnieje");
        }
        return $note;
    }

    public function getNotes(): array
    {
        try {
            $query = "SELECT ROW_NUMBER() OVER (ORDER BY id) AS lp, id, title, description, created FROM notes";
            $result = $this->conn->query($query);
            $notes = $result->fetchAll(PDO::FETCH_ASSOC);
            return $notes;
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się pobrać notatek', 400, $e);
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

    public function editNote(int $id, array $data): void
    {
        try {
            $title = $this->conn->quote($data['title']);
            $desc = $this->conn->quote($data['description']);
            $query = "UPDATE notes SET title = $title, description = $desc WHERE id = $id";
            $result = $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się edytować notatki', 400, $e);
        }
    }

    public function deleteNote(int $id): void
    {
        try {
            $query = "DELETE FROM notes WHERE id = $id LIMIT 1";
            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się usunać notatki', '400', $e);
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
