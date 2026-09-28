<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_set_cookie_params([
    'httponly' => true,
    'secure' => true,
    'samesite' => 'lax'
]);
session_start();

require  __DIR__ . "/../vendor/autoload.php";

use App\Core\Exceptions\RecordNotFoundException;
use App\Core\Router;

$uri = parse_url($_SERVER['REQUEST_URI'])["path"];
$method = $_POST["_method"] ?? $_SERVER["REQUEST_METHOD"];

if (! in_array($method, ['GET', 'OPTIONS', 'HEAD'])) {
    verifyUserToken($_POST['user_token'] ?? null);
}

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
