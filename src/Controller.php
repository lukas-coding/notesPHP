<?php

declare(strict_types=1);

namespace App;

use App\Request;
use App\Exception\ConfigurationException;
use App\Exception\NotFoundException;

require_once('src/Request.php');
require_once("src/View.php");
require_once("src/Database.php");
require_once("src/Exceptions/ConfigurationException.php");

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

    public function createAction()
    {
        if ($this->request->hasPost()) {
            $noteData = [
                'title' => $this->request->postParam('title'),
                'description' => $this->request->postParam('description')
            ];
            $this->database->createNote($noteData);
            header('location: /?before=created');
            exit;
        }
        $this->view->render('create');
    }

    public function showAction()
    {
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

        $this->view->render('show', ['note' => $note]);
    }

    public function listAction()
    {
        $this->view->render(
            'list',
            [
                'notes' => $this->database->getNotes(),
                'before' => $this->request->getParam('before') ?? null,
                'error' => $this->request->getParam('error') ?? null
            ]
        );
    }

    public function run(): void
    {
        $action = $this->action() . 'Action';
        if (!method_exists($this, $action)) {
            $action = self::DEFAULT_ACTION . 'Action';
            $this->$action();
        } else {
            $this->$action();
        }
    }

    private function action(): string
    {
        return $this->request->getParam('action', self::DEFAULT_ACTION);
    }
}
