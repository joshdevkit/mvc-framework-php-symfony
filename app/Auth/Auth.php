<?php

namespace App\Auth;

class Auth
{
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        self::start();
        if (is_object($value) && property_exists($value, 'password')) {
            unset($value->password);
        }

        if (is_object($value)) {
            $value = $value;
        }

        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        self::start();
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];

            return $value;

            return $value;
        }
        return $default;
    }

    public static function remove($key)
    {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy()
    {
        self::start();
        session_destroy();
    }

    public static function check()
    {
        self::start();
        return isset($_SESSION['user']);
    }


    public static function user()
    {
        return self::get('user');
    }

    public static function role()
    {
        $user = self::user();
        return isset($user['role']) ? $user['role'] : null;
    }
}
