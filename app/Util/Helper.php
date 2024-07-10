<?php

namespace App\Util;

use App\Auth\Auth;

class Helper
{
    public static function view(string $view, array $data = []): string
    {
        $viewPath = str_replace('.', '/', $view);
        extract($data);
        ob_start();
        $viewFile = VIEW_PATH . "{$viewPath}.php";
        $baseTemplate = BASE_TEMPLATE;
        $authLayout = AUTH_LAYOUT;

        $templateFile = Auth::get('user') ? $authLayout : $baseTemplate;

        if (file_exists($viewFile) && file_exists($templateFile)) {
            $content = self::renderView($viewFile, $data);
            $template = file_get_contents($templateFile);
            $renderedTemplate = str_replace(['{{ content }}', '{{ title }}'], [$content, $title], $template);
            echo $renderedTemplate;
        } else {
            self::renderErrorView("View or Template Not Found", "The view or base template file was not found.");
        }

        return ob_get_clean();
    }

    private static function renderView(string $viewFile, array $data): string
    {
        extract($data);
        ob_start();
        include $viewFile;
        return ob_get_clean();
    }

    public static function renderErrorView(string $title, string $message): void
    {
        require BASE_PATH . '/errors/view-not-found.php';
        exit();
    }


    public static function renderError(string $view, array $data = []): string
    {
        $viewPath = str_replace('.', '/', $view);
        extract($data);
        ob_start();
        $errorPath =  BASE_PATH . '/errors/' . "{$viewPath}.php";
        echo $errorPath;
        return ob_get_clean();
    }
}
