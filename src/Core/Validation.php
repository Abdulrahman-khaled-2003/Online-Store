<?php

namespace App\Core;

use App\Core\Exception\FileNotFoundException;

abstract class Validation
{
    protected $method;
    public $errors = [];

    protected function textValidate(string $value, int $min = 1, int $max = 100): bool
    {
        $value = trim($value);
        $value = stripslashes($value);
        return strlen($value) >= $min && strlen($value) <= $max;
    }

    protected function isNumeric(string $number): bool
    {
        return (is_numeric($number)) ? true : false;
    }

    protected function isNotEmptyArray(?array $array)
    {
        return (!is_array($array) || empty($array)) ? false : true;
    }

    protected function isFoundImage($image)
    {
        return ($image['image']['name'] === "") ? false : true;
    }

    protected function isCorrectImage($image)
    {
    return (getImageSize($image) === false) ? false : true;
    }

    protected function fileExists($imgPath)
    {
        if (! file_exists($imgPath)) {
            throw new FileNotFoundException("File of Image Not Found!");
        }
    }
 }