<?php

namespace App\Util;

use App\Framework\Http\Response;

class Redirect
{
    public static function to(string $url, int $statusCode = 302): Response
    {
        return new Response('', $statusCode, ['Location' => $url]);
    }

    public static function back($defaultPath = '/')
    {
        session_start();
        $previousUrl = $_SESSION['previous_url'] ?? $defaultPath;
        unset($_SESSION['previous_url']);
        return static::to($previousUrl);
    }
}
