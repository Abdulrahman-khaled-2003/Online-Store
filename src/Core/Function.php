<?php

use App\Core\Database;
use App\Core\Exceptions\FileNotFoundException;
use App\Http\Validation\ImageValidation;

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


function checkColor($category)
{
    return ($category === "Clothies-Category" || $category === "Technology-Category") ? true : false;
}

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

function autoloaderFileNotFound($file)
{
    return (! file_exists($file)) ? throw new FileNotFoundException("Autoloader error: Class file not found: {$file}")
        :  true;
}

function stringReplace($class)
{
    $class = str_replace("\\", "/", $class);
    $class = str_replace("App/", "", $class);
    return $class;
}

function splAutoLoaderHandle($class)
{
    if (strpos($class, "App\\") !== 0) {
        return;
    }
    $class = stringReplace($class);
    $file = base_path("{$class}.php");
    try {
        autoloaderFileNotFound($file);
    } catch (RuntimeException $e) {
        serverError($e->getMessage(), $e->getFile(), $e->getLine());
    }
    require $file;
}

function imageValidation()
{
    static $image = null;
    if ($image === null) {
        $image = new ImageValidation();
    }
    return $image;
}
