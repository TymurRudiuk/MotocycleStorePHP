<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/MotorcycleRepository.php';

class HomeController extends BaseController
{
    public function index(): void
    {
        $repository = new MotorcycleRepository($this->config);

        $featuredMotorcycles = $repository->getFeatured(6);
        $databaseAvailable = $repository->isDatabaseAvailable();

        $this->view('home', [
            'title' => 'Головна',
            'appName' => $this->config['app_name'] ?? 'MotoCycle Store',
            'baseUrl' => $this->config['base_url'] ?? '',
            'featuredMotorcycles' => $featuredMotorcycles,
            'databaseAvailable' => $databaseAvailable,
        ]);
    }
}
