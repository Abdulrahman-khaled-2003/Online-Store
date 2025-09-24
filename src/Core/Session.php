<?php

namespace App\Core;

class Session
{

    public static function has(string $key): bool{
        return $_SESSION['_flash'][$key] ?? $_SESSION[$key] ?? false ;
    }

    public static function put(string $key, string | float $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key) : ?string
    {
        return $_SESSION['_flash'][$key] ?? $_SESSION[$key] ?? null;
    }

    public static function flash(string $key, string | int $value)
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function unflash()
    {
        unset($_SESSION['_flash']);
    }
}
