<?php

namespace App\Core;

class Validation
{
    public static function textValidate(string $value, int $min = 1, int $max = 100): bool
    {
        $value = trim($value);
        $value = stripslashes($value);
        return strlen($value) >= $min && strlen($value) <= $max;
    }

    public static function checkNum(string $number): bool
    {
        $number = (float) $number;
        if ($number <= 0) {
            return false;
        }
        return true;
    }

    public static function arrayValidate(?array $array)
    {
        if (!is_array($array) || empty($array)) {
            return false;
        }
        return true;
    }

    public static function imageHandle(array $image, string $productName)
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
}