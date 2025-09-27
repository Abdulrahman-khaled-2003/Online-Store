<?php

use App\Core\Database;

require "Database.php";

function dd($value)
{
    echo "<pre>";
    echo var_dump($value);
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

function imageHandle($image, $productName)
{
    $imgData = $image['image'];
    $imgName = $imgData['name'];
    $imgTmp = $imgData['tmp_name'];
    $extension = ["png", "jpg", "jpeg"];
    $imgExtension = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));

    if (! in_array($imgExtension, $extension)) {
        return false;
    }

    $imgPath = base_path("../public/assets/images/");
    if (move_uploaded_file($imgTmp, $imgPath . $productName . "." . $imgExtension)) {
        return true;
    }
}

function checkImage($newImage, $oldImage)
{
    if ($newImage['image']['name'] === "") {
        $extension = pathinfo($oldImage['productImage'], PATHINFO_EXTENSION);
    } else {

        $extension = pathinfo($newImage['image']['name'], PATHINFO_EXTENSION);
    }
    return $extension;
}
