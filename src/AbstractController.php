<?php

declare(strict_types=1);

namespace App;

use App\Request;
use App\Exception\ConfigurationException;

require_once('src/Request.php');
require_once("src/View.php");
require_once("src/Exceptions/ConfigurationException.php");
require_once("src/Database.php");

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

    private function action(): string
    {
        return $this->request->getParam('action', self::DEFAULT_ACTION);
    }
}
