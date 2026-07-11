<?php

use App\Core\Exceptions\FileNotFoundException;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require __DIR__ . ("/../src/Core/Function.php");
require __DIR__ . ("/../src/Core/Exceptions/FileNotFoundException.php");

spl_autoload_register(function ($class) {
    $class = str_replace("\\", "/", $class);
    $class = str_replace("App/", "", $class);
    $file = base_path("{$class}.php");
    try {
        autoloaderFileNotFound($file);
    } catch (RuntimeException $e) {
        serverError($e->getMessage(), $e->getFile(), $e->getLine());
    }
    require $file;
});

use App\Core\Exceptions\RecordNotFoundException;
use App\Core\Router;

$uri = parse_url($_SERVER['REQUEST_URI'])["path"];
$method = $_POST["_method"] ?? $_SERVER["REQUEST_METHOD"];

$router = new Router();

require base_path("config/routes.php");

try {
    $router->route($method, $uri);
} catch (RecordNotFoundException $e) {
    abort(404, $e->getMessage());
} catch (RuntimeException $e) {
    serverError($e->getMessage(), $e->getFile(), $e->getLine());
} catch (Exception $e) {
    serverError($e->getMessage(), $e->getFile(), $e->getLine());
} finally {
    db()->disConnect();
}
