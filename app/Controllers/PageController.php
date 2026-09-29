<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';

class PageController extends BaseController
{
    public function about(): void
    {
        $this->view('pages/about', [
            'title' => 'Про нас',
            'appName' => $this->config['app_name'] ?? 'MotoCycle Store',
            'baseUrl' => $this->config['base_url'] ?? '',
        ]);
    }
}
