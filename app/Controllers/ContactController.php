<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/ContactRequestRepository.php';

class ContactController extends BaseController
{
    private ContactRequestRepository $repository;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->repository = new ContactRequestRepository($config);
    }

    public function index(): void
    {
        $this->view('contacts/index', [
            'title' => 'Контакти',
            'appName' => $this->config['app_name'] ?? 'MotoCycle Store',
            'baseUrl' => $this->config['base_url'] ?? '',
            'errors' => $_SESSION['contact_errors'] ?? [],
            'old' => $_SESSION['contact_old'] ?? [],
            'successMessage' => $_SESSION['contact_success'] ?? null,
        ]);

        unset($_SESSION['contact_errors'], $_SESSION['contact_old'], $_SESSION['contact_success']);
    }

    public function submit(): void
    {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'message' => trim($_POST['message'] ?? ''),
        ];

        $errors = $this->validate($data);

        if ($errors !== []) {
            $_SESSION['contact_errors'] = $errors;
            $_SESSION['contact_old'] = $data;
            $this->redirect('/contacts');
        }

        $saved = $this->repository->create($data);
        $_SESSION['contact_success'] = $saved
            ? 'Дякуємо! Ваше звернення успішно надіслано.'
            : 'Форму прийнято. Для повної роботи створіть таблицю contact_requests у базі даних.';

        $this->redirect('/contacts');
    }

    private function validate(array $data): array
    {
        $errors = [];

        if ($data['name'] === '') {
            $errors['name'] = 'Вкажіть ваше ім’я.';
        }

        if ($data['phone'] === '') {
            $errors['phone'] = 'Вкажіть номер телефону.';
        }

        if ($data['email'] === '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Вкажіть коректний email.';
        }

        if ($data['message'] === '') {
            $errors['message'] = 'Напишіть текст звернення.';
        }

        return $errors;
    }
}
