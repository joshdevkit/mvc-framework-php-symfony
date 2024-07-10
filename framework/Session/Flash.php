<?php

namespace App\Framework\Session;

class Flash
{
    const FLASH_KEY = 'flash_messages';

    public static function add(string $key, string $message)
    {
        if (!isset($_SESSION[self::FLASH_KEY])) {
            $_SESSION[self::FLASH_KEY] = [];
        }

        $_SESSION[self::FLASH_KEY][$key] = $message;
    }

    public static function get(string $key)
    {
        if (isset($_SESSION[self::FLASH_KEY][$key])) {
            $message = $_SESSION[self::FLASH_KEY][$key];
            unset($_SESSION[self::FLASH_KEY][$key]);
            return $message;
        }
        return null;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[self::FLASH_KEY][$key]);
    }

    public static function all(): array
    {
        $messages = $_SESSION[self::FLASH_KEY] ?? [];
        unset($_SESSION[self::FLASH_KEY]);
        return $messages;
    }
}
