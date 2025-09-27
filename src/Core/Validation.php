<?php

namespace App\Core;

abstract class Validation
{
    protected function textValidate(string $value, int $min = 1, int $max = 100): bool
    {
        $value = trim($value);
        $value = stripslashes($value);
        return strlen($value) >= $min && strlen($value) <= $max;
    }

    protected function checkNum(string $number): bool
    {
        $number = (float) $number;
        return ($number <= 0) ? false : true;
    }

    protected function arrayValidate(?array $array)
    {
        return (!is_array($array) || empty($array)) ? false : true;
    }

    protected function imageValidate($image)
    {
        return ($image['image']['name'] === "") ? false : true;

    }

    protected function imageHandle(array $image, string $productName)
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
