<?php

namespace App\Core;

use App\Core\Session;

class Csrf
{
        private const SESSION_KEY = "csrf_token";

        public static function token()
        {
                if (! Session::has(static::SESSION_KEY)) {
                        Session::put(static::SESSION_KEY, bin2hex(random_bytes(32)));
                }
                return session::get(static::SESSION_KEY);
        }

        public static function verify(?string $userToken): bool
        {
                
                $sessionToken = Session::get(static::SESSION_KEY);
                if ($userToken == '' || ! $sessionToken) {
                        return false;
                }
                return hash_equals($sessionToken, $userToken);
        }
}
