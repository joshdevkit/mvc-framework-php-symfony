<?php

use App\Framework\Http\Response;
use App\Util\Helper;
use App\Util\Redirector;

if (!function_exists('view')) {

    function view(string $view, array $data = []): string
    {
        return Helper::view($view, $data);
    }
}

if (!function_exists('errorView')) {
    function errorView(string $view, array $data = []): string
    {
        return Helper::renderError($view, $data);
    }
}

if (!function_exists('redirect')) {
    function redirect($path = '/')
    {
        // Store the current URL in the session
        $_SESSION['previous_url'] = $_SERVER['REQUEST_URI'];

        // Redirect response
        $response = new Response('', 302);
        $response->withHeader('Location', $path);
        return $response;
    }
}

if (!function_exists('response')) {
    function response()
    {
        return new class
        {
            public function json(array $data = [], int $status = 200, array $headers = [])
            {
                header('Content-Type: application/json', true, $status);
                foreach ($headers as $header => $value) {
                    header("{$header}: {$value}");
                }
                echo json_encode($data);
                exit;
            }
        };
    }
}

if (!function_exists('storeAs')) {
    function storeAs($file, $destinationDirectory, $fileNamePrefix = '')
    {
        // Check if the file is valid
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return false; // or handle error as needed
        }

        // Generate a unique filename
        $fileName = $fileNamePrefix . time() . '_' . $file['name'];

        // Set the destination path
        $destinationPath = BASE_PATH . '/public/' . $destinationDirectory;

        // Create the destination directory if it doesn't exist
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        // Move the uploaded file to the destination directory
        if (move_uploaded_file($file['tmp_name'], $destinationPath . $fileName)) {
            return $destinationDirectory . $fileName;
        } else {
            return false; // or handle error as needed
        }
    }
}


if (!function_exists('extends')) {
    function extend($path)
    {
        return Helper::extends($path);
    }
}
