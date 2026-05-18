<?php

declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {
        View::render($view, $data, $layout);
    }

    protected function redirect(string $path): never
    {
        redirect($path);
    }

    protected function input(string $key, int $filter = FILTER_DEFAULT, array|int $options = 0): mixed
    {
        return filter_input(INPUT_POST, $key, $filter, $options);
    }

    protected function query(string $key, int $filter = FILTER_DEFAULT, array|int $options = 0): mixed
    {
        return filter_input(INPUT_GET, $key, $filter, $options);
    }
}
