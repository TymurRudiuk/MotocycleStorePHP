<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/MotorcycleRepository.php';

class ComparisonController extends BaseController
{
    private MotorcycleRepository $repository;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->repository = new MotorcycleRepository($config);
    }

    public function index(): void
    {
        $ids = $_SESSION['comparison'] ?? [];
        $motorcycles = [];

        foreach ($ids as $id) {
            $motorcycle = $this->repository->findById((int) $id);

            if ($motorcycle !== null) {
                $motorcycles[] = $motorcycle;
            }
        }

        $this->view('comparison/index', [
            'title' => 'Порівняння мотоциклів',
            'appName' => $this->config['app_name'] ?? 'MotoCycle Store',
            'baseUrl' => $this->config['base_url'] ?? '',
            'motorcycles' => $motorcycles,
        ]);
    }

    public function add(): void
    {
        $id = (int) ($_POST['motorcycle_id'] ?? 0);
        $comparison = $_SESSION['comparison'] ?? [];

        if ($id > 0 && !in_array($id, $comparison, true) && count($comparison) < 3) {
            $comparison[] = $id;
        }

        $_SESSION['comparison'] = $comparison;
        $this->redirect('/comparison');
    }

    public function remove(): void
    {
        $id = (int) ($_POST['motorcycle_id'] ?? 0);
        $_SESSION['comparison'] = array_values(array_filter(
            $_SESSION['comparison'] ?? [],
            static fn ($comparisonId): bool => (int) $comparisonId !== $id
        ));

        $this->redirect('/comparison');
    }

    public function clear(): void
    {
        $_SESSION['comparison'] = [];
        $this->redirect('/comparison');
    }
}
