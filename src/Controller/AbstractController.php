<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request;
use App\Database;
use App\View;
use App\Exception\ConfigurationException;

abstract class AbstractController
{
    protected const DEFAULT_ACTION = 'list';
    protected static $connection = [];
    protected $database;
    protected $request;
    protected $view;

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
        $action = $this->action() . 'Action';
        if (!method_exists($this, $action)) {
            $action = self::DEFAULT_ACTION . 'Action';
            $this->$action();
        } else {
            $this->$action();
        }
    }

    protected function redirect(string $to, array $params): void
    {
        $location = $to;
        if (count($params)) {
            $queryParams = [];
            foreach ($params as $key => $value) {
                $queryParams[] = urlencode($key) . '=' . urldecode($value);
            }
            $queryParams = implode('&', $queryParams);
            $location .= '?' . $queryParams;
        }
        header("Location: $location");
        exit;
    }

    private function action(): string
    {
        return $this->request->getParam('action', self::DEFAULT_ACTION);
    }
}
