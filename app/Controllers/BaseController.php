<?php

declare(strict_types=1);

class BaseController
{
    protected array $config;

    public function __construct(array $config = [])
    {
        require_once __DIR__ . '/../helpers.php';
        $this->config = $config;
    }

    protected function view(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../Views/' . $view . '.php';
    }

    protected function redirect(string $path): void
    {
        $baseUrl = rtrim($this->config['base_url'] ?? '', '/');
        header('Location: ' . $baseUrl . $path);
        exit;
    }
}
