<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require __DIR__ . ("/../src/Core/Function.php");

require base_path("Core/Router.php");

use App\Core\Exception\FileNotFoundException;
use App\Core\Exception\RecordNotFoundException;
use App\Core\Exception\QueryException;
use Core\Router;

$uri = parse_url($_SERVER['REQUEST_URI'])["path"];
$method = $_POST["_method"] ?? $_SERVER["REQUEST_METHOD"];

$router = new Router();

require base_path("config/routes.php");

try {
    $router->route($method, $uri);
} catch (RuntimeException $e) {
    abort(500, $e->getMessage());
} catch (RecordNotFoundException $e) {
    abort(404, $e->getMessage());
} catch (Exception $e) {
    error_log($e->getMessage(), 3, __DIR__ . "/../logs/error.log");
    abort(500, "Unexpected error, please try again later.");
} finally {
    db()->disConnect();
}
