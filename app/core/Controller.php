<?php

namespace App\Core;

class Controller
{
    protected function render($view, $data = [])
    {
        extract($data);

        ob_start();
        include VIEW_PATH . "/{$view}.php";
        $content = ob_get_clean();

        include VIEW_PATH . "/layout/main.php";
    }

    protected function redirect($path)
    {
        $baseUrl = '/Khawla_Boukniter-Youdemy/public';
        header('Location: ' . $baseUrl . '/' . ltrim($path, '/'));
        exit();
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function getPostData(): array
    {
        return $_POST;
    }

    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }
}
