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

        ob_start();
        require __DIR__ . '/../Views/' . $view . '.php';
        $html = (string) ob_get_clean();

        $html = preg_replace('/<input type="hidden" name="csrf_token"[^>]*>/i', '', $html);

        $html = preg_replace_callback(
            '/<form\b[^>]*\bmethod\s*=\s*["\']post["\'][^>]*>/i',
            static fn (array $m): string => $m[0] . Csrf::field(),
            $html
        );

        echo $html;
    }

    protected function redirect(string $path): void
    {
        $baseUrl = rtrim($this->config['base_url'] ?? '', '/');
        header('Location: ' . $baseUrl . $path);
        exit;
    }
}
