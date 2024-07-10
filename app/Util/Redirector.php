<?php

namespace App\Util;

use App\Framework\Http\Response;

class Redirector
{
    public static function to($path, $status = 302, $headers = [])
    {
        $response = new Response('', $status, $headers);
        $response->withHeader('Location', $path);
        return $response;
    }

    public static function back($defaultPath = '/', $status = 302, $headers = [])
    {
        $previousUrl = $_SESSION['previous_url'] ?? $defaultPath;
        unset($_SESSION['previous_url']);
        return static::to($previousUrl, $status, $headers);
    }
}
