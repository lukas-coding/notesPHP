<?php

declare(strict_types=1);

spl_autoload_register(function (string $classNameSpace) {
    $path = str_replace(['\\', 'App/'], ['/', ''], $classNameSpace);
    $path = "src/$path.php";
    require_once($path);
});

require_once("src/Utils/debug.php");
$config = require_once('config/config.php');

use App\Controller\AbstractController;
use App\Controller\Controller;
use App\Request;
use App\Exception\AppException;
use App\Exception\ConfigurationException;


$request = new Request($_GET, $_POST, $_SERVER);

try {
    AbstractController::initConfig($config);
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
