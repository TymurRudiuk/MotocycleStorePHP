<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/MotorcycleRepository.php';
require_once __DIR__ . '/../Services/CartService.php';

class CartController extends BaseController
{
    private MotorcycleRepository $repository;
    private CartService $cartService;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->repository = new MotorcycleRepository($config);
        $this->cartService = new CartService();
    }

    public function index(): void
    {
        $items = $this->cartService->getItems($this->repository);

        $this->view('cart/index', [
            'title' => 'Кошик',
            'appName' => $this->config['app_name'] ?? 'MotoCycle Store',
            'baseUrl' => $this->config['base_url'] ?? '',
            'items' => $items,
            'totalAmount' => $this->cartService->getTotalAmount($this->repository),
        ]);
    }

    public function add(): void
    {
        $motorcycleId = (int) ($_POST['motorcycle_id'] ?? 0);

        if ($this->repository->findById($motorcycleId) !== null) {
            if (!$this->cartService->add($motorcycleId)) {
                $_SESSION['cart_error'] = 'Максимальна кількість товару в кошику — 10 шт.';
            }
        }

        $this->redirect('/cart');
    }

    public function update(): void
    {
        $error = false;
        foreach (($_POST['quantities'] ?? []) as $motorcycleId => $quantity) {
            if (!$this->cartService->update((int) $motorcycleId, (int) $quantity)) {
                $error = true;
            }
        }

        if ($error) {
            $_SESSION['cart_error'] = 'Максимальна кількість товару в кошику — 10 шт.';
        }

        $this->redirect('/cart');
    }

    public function remove(): void
    {
        $motorcycleId = (int) ($_POST['motorcycle_id'] ?? 0);
        $this->cartService->remove($motorcycleId);
        $this->redirect('/cart');
    }
}
