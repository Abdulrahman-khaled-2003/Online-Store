<?php

use App\Core\Csrf;
use App\Core\Database;
use App\Core\Exceptions\DirectoryNotFoundException;
use App\Core\MigrationCreator;
use App\Core\MigrationRunner;
use App\Core\Session;
use App\Http\Validation\ImageValidation;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;

function dd($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}

function stringToArray(string $seprator, string $string)
{
    return explode($seprator, $string);
}

function base_path(string $path)
{
    return __DIR__ . '/../' . $path;
}

function view(string $path, array $attributes = [])
{
    extract($attributes);
    require base_path("Views/Template/{$path}.phtml");
}

function abort(int $code, string $message)
{
    http_response_code($code);

    view(
        "statusCode",
        [
            "message" => $message,
            "code" => $code
        ]
    );
    die();
}

function redirect($path, $code = 200)
{
    http_response_code($code);
    header("Location: {$path}");
    die();
}

function errorLog($error, $file, $line)
{
    error_log(
        "[Time] " . date('Y-m-d H:i:s') .  " | " .
            "[Error] " . $error .  " | " .
            "[File] " . $file .  " | " .
            "[Line] " . $line .   "\n",
        3,
        __DIR__ . "/../../logs/error.log"
    );
}

function serverError($error, $file, $line)
{
    errorLog($error, $file, $line);
    abort(500, "Something went wrong, please try again later");
}

function db()
{
    static $database = null;
    if ($database === null) {
        $config = require base_path("./config/database.php");
        $database = new Database($config['connections'][$config['default']]);
    }
    return $database;
}

// future modification from static to dynamic
function checkColor($category)
{
    return ($category === "Clothies-Category" || $category === "Technology-Category") ? true : false;
}

// future modification from static to dynamic
function checkSize($category)
{
    return ($category === "Clothies-Category") ? true : false;
}

function checkImage($newImage, $oldImage)
{
    if ($newImage['image']['name'] === "") {
        $extension = pathinfo($oldImage, PATHINFO_EXTENSION);
    } else {

        $extension = pathinfo($newImage['image']['name'], PATHINFO_EXTENSION);
    }
    return $extension;
}

function moveUploadedFile($fileName, $destination)
{
    return move_uploaded_file($fileName, $destination);
}

function checkTypeOfImage($imgTmp)
{
    $imgType = mime_content_type($imgTmp);
    [, $extension] = explode("/", $imgType);
    return (strtolower($extension) === "png" ||
        strtolower($extension) === "jpg" ||
        strtolower($extension) === "jpeg")  ? true : false;
}

function imageValidation()
{
    static $image = null;
    if ($image === null) {
        $image = new ImageValidation();
    }
    return $image;
}

function resolveClassName(string $migrationName): string
{
    $withoutTimestamp = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $migrationName);
    $words = explode('_', $withoutTimestamp);
    $words = array_map('ucfirst', $words);
    return implode('', $words);
}

function sterilizeMigrationFileName(string $file): string
{
    $file = basename($file, ".php");
    $file = explode("_", $file);
    $file = array_slice($file, 4, count($file) - 1);
    return implode("_", $file);
}

function category()
{
    static $category = null;
    if ($category === null) {
        $category = new Category;
    }
    return $category;
}

function product()
{
    static $product = null;
    if ($product === null) {
        $product = new Product;
    }
    return $product;
}

function size()
{
    static $size = null;
    if ($size === null) {
        $size = new Size;
    }
    return $size;
}

function color()
{
    static $color = null;
    if ($color === null) {
        $color = new Color;
    }
    return $color;
}

function checkFetchMode($statement, $class)
{
    if ($class) {
        $statement->setFetchMode(PDO::FETCH_CLASS, $class);
    } else {
        $statement->setFetchMode(PDO::FETCH_ASSOC);
    }
    return $statement;
}

function destorySessionForLoginUser()
{
    Session::unset("user_id");
    Session::unset("user_name");
    session_destroy();
    redirect('/login');
}

function destoryUserTokenForLoginUser()
{
    Session::unset("user_token");
}

function migrationsNotEmpty(?array $migrations)
{
    if (empty($migrations)) {
        return false;
    }
    return true;
}

function csrf_token()
{
    return (new Csrf)->token();
}

function csrf()
{
    $token = htmlspecialchars(csrf_token());
    return '<input type="hidden" name="user_token" value="' . $token . '">';
}

function migrationFileExists(string $migrationName, string $migrationPath = "src/Database/Migrations"): bool
{
    if (! is_dir($migrationPath)) {
        throw new DirectoryNotFoundException("Directory Of Migration Path Not Found..!");
    }
    $files = glob($migrationPath . '/*.php');

    foreach ($files as $file) {
        $currentFile = sterilizeMigrationFileName($file);
        if ($currentFile === $migrationName) {
            return false;
        }
    }
    return true;
}

function verifyUserToken(?string $userToken)
{
    if (! Csrf::verify($userToken)) {
        abort(419, "Page Expired");
    }
}
