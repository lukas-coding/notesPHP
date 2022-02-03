<?php

declare(strict_types=1);

namespace App;

require_once("src/View.php");
require_once("src/Database.php");

class Controller
{
    private const DEFAULT_ACTION = 'list';
    private static $connection;
    private $request;
    private $view;

    public static function initConfig(array $connection): void
    {
        self::$connection = $connection;
    }

    public function __construct(array $request)
    {
        $db = new Database(self::$connection['db']);
        $this->request = $request;
        $this->view = new View();
    }

    public function run(): void
    {
        $viewParams = [];
        switch ($this->getAction()) {
            case 'create';
                $page = 'create';
                $created = false;
                $dataPost = $this->getRequestPost();


                if (!empty($dataPost)) {
                    $created = true;
                    $viewParams = [
                        'title' => $dataPost['title'],
                        'description' => $dataPost['description']
                    ];
                }
                $viewParams['created'] = $created;
                break;

            case 'show';
                $viewParams = [
                    'title' => 'Moja notatka',
                    'description' => 'Opis'
                ];
                break;

            default:
                $page = 'list';
                $viewParams['resultList'] = "wyświetlamy notatki";
                break;
        }
        $this->view->render($page, $viewParams);
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
