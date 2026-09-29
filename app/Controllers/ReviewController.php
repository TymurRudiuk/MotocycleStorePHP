<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/ReviewRepository.php';
require_once __DIR__ . '/../Models/MotorcycleRepository.php';

class ReviewController extends BaseController
{
    private $reviewRepository;
    private MotorcycleRepository $motorcycleRepository;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->reviewRepository = new ReviewRepository($config);
        $this->motorcycleRepository = new MotorcycleRepository($config);
    }

    public function store(): void
    {
        $motorcycleId = (int) ($_POST['motorcycle_id'] ?? 0);
        $motorcycle = $this->motorcycleRepository->findById($motorcycleId);

        if ($motorcycle === null) {
            http_response_code(404);
            echo '404 - Мотоцикл не знайдено';
            return;
        }

        $data = [
            'motorcycle_id' => $motorcycleId,
            'author_name' => trim($_POST['author_name'] ?? ''),
            'rating' => (int) ($_POST['rating'] ?? 0),
            'comment' => trim($_POST['comment'] ?? ''),
        ];

        $errors = $this->validate($data);

        if ($errors !== []) {
            $_SESSION['review_errors_' . $motorcycleId] = $errors;
            $_SESSION['review_old_' . $motorcycleId] = $data;
            $this->redirect('/motorcycle?id=' . $motorcycleId);
        }

        $saved = $this->reviewRepository->create($data);
        $_SESSION['review_success_' . $motorcycleId] = $saved
            ? 'Дякуємо! Ваш відгук додано.'
            : 'Не вдалося зберегти відгук. Перевірте таблицю reviews.';

        $this->redirect('/motorcycle?id=' . $motorcycleId);
    }

    private function validate(array $data): array
    {
        $errors = [];

        if ($data['author_name'] === '') {
            $errors['author_name'] = 'Вкажіть ваше ім’я.';
        }

        if ($data['rating'] < 1 || $data['rating'] > 5) {
            $errors['rating'] = 'Оберіть оцінку від 1 до 5.';
        }

        if ($data['comment'] === '') {
            $errors['comment'] = 'Напишіть текст відгуку.';
        }

        return $errors;
    }
}
