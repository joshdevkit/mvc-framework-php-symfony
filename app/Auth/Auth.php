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
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        self::start();
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
            return $value !== false ? $value : $default;
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
}