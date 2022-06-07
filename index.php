<?php


declare(strict_types=1);

namespace App;


use App\Request;
use App\Exception\AppException;
use App\Exception\ConfigurationException;
use Throwable;

require_once("src/Utils/debug.php");
require_once("src/Controller.php");
require_once('src/Request.php');
require_once('src/Exceptions/AppException.php');
require_once('src/Exceptions/StorageException.php');
require_once('src/Exceptions/ConfigurationException.php');

$config = require_once('config/config.php');
$request = new Request($_GET, $_POST);

try {
    Controller::initConfig($config);
    (new Controller($request))->run();
} catch (ConfigurationException $e) {
    echo "<h1>Wystąpił błąd w aplikacji</h1>";
    echo $e->getTrace();
} catch (AppException $e) {
    echo "<h1>Coś poszło nie tak</h1>";
    echo $e->getTrace();
} catch (Throwable $e) {
    echo "<h1>Wystąpił błąd w aplikacji</h1>";
    echo  $e->getTrace();
}
