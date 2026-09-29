<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/OrderRepository.php';
require_once __DIR__ . '/../Models/MotorcycleRepository.php';
require_once __DIR__ . '/../Services/CartService.php';

class OrderController extends BaseController
{
    private MotorcycleRepository $repository;
    private OrderRepository $orderRepository;
    private CartService $cartService;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->repository = new MotorcycleRepository($config);
        $this->orderRepository = new OrderRepository($config);
        $this->cartService = new CartService();
    }

    public function checkout(): void
    {
        $items = $this->cartService->getItems($this->repository);

        if ($items === []) {
            $this->redirect('/cart');
        }

        $this->view('orders/checkout', [
            'title' => 'Оформлення замовлення',
            'appName' => $this->config['app_name'] ?? 'MotoCycle Store',
            'baseUrl' => $this->config['base_url'] ?? '',
            'items' => $items,
            'totalAmount' => $this->cartService->getTotalAmount($this->repository),
            'errors' => $_SESSION['order_errors'] ?? [],
            'old' => $_SESSION['order_old'] ?? [],
        ]);

        unset($_SESSION['order_errors'], $_SESSION['order_old']);
    }

    public function place(): void
    {
        $items = $this->cartService->getItems($this->repository);

        if ($items === []) {
            $this->redirect('/cart');
        }

        $data = [
            'customer_name' => trim($_POST['customer_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'notes' => trim($_POST['notes'] ?? ''),
        ];

        $errors = $this->validate($data);

        if ($errors !== []) {
            $_SESSION['order_errors'] = $errors;
            $_SESSION['order_old'] = $data;
            $this->redirect('/checkout');
        }

        $orderId = $this->orderRepository->create($data, $items, $this->cartService->getTotalAmount($this->repository));
        $this->cartService->clear();

        $this->view('orders/success', [
            'title' => 'Замовлення оформлено',
            'appName' => $this->config['app_name'] ?? 'MotoCycle Store',
            'baseUrl' => $this->config['base_url'] ?? '',
            'orderId' => $orderId,
        ]);
    }

    private function validate(array $data): array
    {
        $errors = [];

        if ($data['customer_name'] === '') {
            $errors['customer_name'] = 'Вкажіть ім’я та прізвище.';
        }

        if ($data['phone'] === '') {
            $errors['phone'] = 'Вкажіть номер телефону.';
        }

        if ($data['email'] === '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Вкажіть коректний email.';
        }

        if ($data['address'] === '') {
            $errors['address'] = 'Вкажіть адресу або спосіб отримання.';
        }

        return $errors;
    }
}
