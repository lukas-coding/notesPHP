<?php

declare(strict_types=1);

namespace App;

use App\Exception\AppException;
use App\Exception\ConfigurationException;
use App\Exception\NotFoundException;

require_once("src/View.php");
require_once("src/Database.php");

class Controller
{
    private const DEFAULT_ACTION = 'list';
    private static $connection = [];
    private $database;
    private $request;
    private $view;

    public static function initConfig(array $connection): void
    {

        self::$connection = $connection;
    }

    public function __construct(array $request)
    {

        if (empty(self::$connection['db'])) {
            throw new ConfigurationException('Błąd przy próbie połączenia');
        } else {
            $this->database = new Database(self::$connection['db']);
            $this->request = $request;
            $this->view = new View();
        }
    }

    public function run(): void
    {

        switch ($this->getAction()) {
            case 'create';
                $page = 'create';
                $dataPost = $this->getRequestPost();

                if (!empty($dataPost)) {
                    $noteData = [
                        'title' => $dataPost['title'],
                        'description' => $dataPost['description']
                    ];
                    $this->database->createNote($noteData);
                    header('location: /?before=created');
                }
                break;

            case 'show';
                $page = 'show';
                $data = $this->getRequestGet();
                $noteId = (int) $data['id'];

                try {
                    $this->database->getNote($noteId);
                } catch (NotFoundException $e) {
                    exit('Tu controller');
                }

                $viewParams = [
                    'title' => 'Moja notatka',
                    'description' => 'Opis'
                ];
                break;

            default:
                $page = 'list';
                $data = $this->getRequestGet();
                $viewParams = [
                    'notes' => $this->database->getNotes(),
                    'before' => $data['before'] ?? null
                ];


                break;
        }
        $this->view->render($page, $viewParams ?? []);
    }

    private function getAction(): string
    {
        $dataGet = $this->getRequestGet();
        return $dataGet['action'] ?? self::DEFAULT_ACTION;
    }

    private function getRequestGet(): array
    {
        return $this->request['get'] ?? [];
    }

    private function getRequestPost(): array
    {
        return $this->request['post'] ?? [];
    }
}
