<?php

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Hash;
use App\Core\Session;
use App\Http\Validation\LoginValidation;
use App\Http\Validation\RegisterValidation;
use App\Models\User;

class AuthController extends Controller
{

    // Register
    public function showRegister()
    {
        Auth::guest();
        $this->render("Auth/register", [
            "categories" => $this->getCategories()
        ]);
    }

    public function register(array $attributes)
    {
        Auth::guest();
        $validate = new RegisterValidation($attributes);
        if (! empty($validate->errors)) {
            $this->render("Auth/register", [
                "categories" => $this->getCategories(),
                "error" => $validate->errors
            ]);
        }
        $user = new User();
        $user->name = $attributes['name'];
        $user->email = $attributes['email'];
        $user->password = Hash::make($attributes['password']);
        $user->save();

        Session::put("user_id", $user->id);
        Session::put("user_name", $user->name);
        redirect('/');
    }

    // Login
    public function showLogin()
    {
        Auth::guest();
        $this->render("Auth/login", [
            "categories" => $this->getCategories()
        ]);
    }

    public function login(array $attributes)
    {
        Auth::guest();
        $validate = new LoginValidation($attributes);
        if (! empty($validate->errors)) {
            $this->render("Auth/login", [
                "categories" => $this->getCategories(),
                "error" => $validate->errors
            ]);
        }
        redirect('/');
    }

    // Logout

    public function logout(array $attributes)
    {
        destoryUserTokenForLoginUser();
        destorySessionForLoginUser();
    }

    private function getCategories()
    {
        return category()->all();
    }
}
