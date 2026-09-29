<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/MotorcycleRepository.php';
require_once __DIR__ . '/../Models/ReviewRepository.php';

class MotorcycleController extends BaseController
{
    private MotorcycleRepository $repository;
    private ReviewRepository $reviewRepository;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->repository = new MotorcycleRepository($config);
        $this->reviewRepository = new ReviewRepository($config);
    }

    public function index(): void
    {
        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'brand' => trim($_GET['brand'] ?? ''),
            'type' => trim($_GET['type'] ?? ''),
            'min_price' => trim($_GET['min_price'] ?? ''),
            'max_price' => trim($_GET['max_price'] ?? ''),
        ];

        $motorcycles = $this->repository->getAll($filters);
        $filterOptions = $this->repository->getFilterOptions();
        $databaseAvailable = $this->repository->isDatabaseAvailable();

        $this->view('motorcycles/index', [
            'title' => 'Каталог мотоциклів',
            'appName' => $this->config['app_name'] ?? 'MotoCycle Store',
            'baseUrl' => $this->config['base_url'] ?? '',
            'motorcycles' => $motorcycles,
            'filters' => $filters,
            'filterOptions' => $filterOptions,
            'databaseAvailable' => $databaseAvailable,
        ]);
    }

    public function show(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $motorcycle = $this->repository->findById($id);

        if ($motorcycle === null) {
            http_response_code(404);
            echo '404 - Мотоцикл не знайдено';
            return;
        }

        $motorcycleId = (int) $motorcycle['id'];

        $this->view('motorcycles/show', [
            'title' => $motorcycle['name'],
            'appName' => $this->config['app_name'] ?? 'MotoCycle Store',
            'baseUrl' => $this->config['base_url'] ?? '',
            'motorcycle' => $motorcycle,
            'reviews' => $this->reviewRepository->getApprovedByMotorcycleId($motorcycleId),
            'averageRating' => $this->reviewRepository->getAverageRating($motorcycleId),
            'reviewErrors' => $_SESSION['review_errors_' . $motorcycleId] ?? [],
            'reviewOld' => $_SESSION['review_old_' . $motorcycleId] ?? [],
            'reviewSuccess' => $_SESSION['review_success_' . $motorcycleId] ?? null,
        ]);

        unset(
            $_SESSION['review_errors_' . $motorcycleId],
            $_SESSION['review_old_' . $motorcycleId],
            $_SESSION['review_success_' . $motorcycleId]
        );
    }
}
