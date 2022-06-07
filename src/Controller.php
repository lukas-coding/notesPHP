<?php

declare(strict_types=1);

namespace App;

use App\Request;
use App\Exception\AppException;
use App\Exception\ConfigurationException;
use App\Exception\NotFoundException;

require_once('src/Request.php');
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

    public function __construct(Request $request)
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

                if ($this->request->hasPost()) {
                    $noteData = [
                        'title' => $this->request->postParam('title'),
                        'description' => $this->request->postParam('description')
                    ];
                    $this->database->createNote($noteData);
                    header('location: /?before=created');
                    exit;
                }
                break;

            case 'show';
                $page = 'show';
                $noteId = (int)$this->request->getParam('id');
                if (!$noteId) {
                    header('Location: /?error=missingNoteId');
                    exit;
                }

                try {
                    $note = $this->database->getNote($noteId);
                } catch (NotFoundException $e) {
                    header('Location: /?error=noteNotFound');
                    exit;
                }

                $viewParams = [
                    'note' => $note
                ];

                break;

            default:
                $page = 'list';
                $viewParams = [
                    'notes' => $this->database->getNotes(),
                    'before' => $this->request->getParam('before') ?? null,
                    'error' => $this->request->getParam('error') ?? null
                ];
                break;
        }

        $this->view->render($page, $viewParams ?? []);
    }

    private function getAction(): string
    {
        return $this->request->getParam('action', self::DEFAULT_ACTION);
    }
}
