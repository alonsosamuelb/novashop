<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    public static function render(string $view, array $data = [], string $layout = 'main'): void
    {
        $viewFile = VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';
        $layoutFile = VIEW_PATH . '/layouts/' . $layout . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            exit('La vista no existe: ' . e($view));
        }

        if (!file_exists($layoutFile)) {
            http_response_code(500);
            exit('El layout no existe: ' . e($layout));
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require $layoutFile;
    }
}
