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
}
