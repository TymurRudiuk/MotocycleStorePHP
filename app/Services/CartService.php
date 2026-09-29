<?php

declare(strict_types=1);

class CartService
{
    public function getItems(MotorcycleRepository $repository): array
    {
        $cart = $_SESSION['cart'] ?? [];
        $items = [];

        foreach ($cart as $motorcycleId => $quantity) {
            $motorcycle = $repository->findById((int) $motorcycleId);

            if ($motorcycle === null) {
                continue;
            }

            $motorcycle['quantity'] = (int) $quantity;
            $motorcycle['subtotal'] = (float) $motorcycle['price'] * (int) $quantity;
            $items[] = $motorcycle;
        }

        return $items;
    }

    public function add(int $motorcycleId): bool
    {
        $_SESSION['cart'] ??= [];
        $currentQuantity = (int) ($_SESSION['cart'][$motorcycleId] ?? 0);

        if ($currentQuantity >= 10) {
            return false;
        }

        $_SESSION['cart'][$motorcycleId] = $currentQuantity + 1;
        return true;
    }

    public function update(int $motorcycleId, int $quantity): bool
    {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$motorcycleId]);
            return true;
        }

        if ($quantity > 10) {
            return false;
        }

        $_SESSION['cart'][$motorcycleId] = $quantity;
        return true;
    }

    public function remove(int $motorcycleId): void
    {
        unset($_SESSION['cart'][$motorcycleId]);
    }

    public function clear(): void
    {
        $_SESSION['cart'] = [];
    }

    public function getTotalQuantity(): int
    {
        return array_sum($_SESSION['cart'] ?? []);
    }

    public function getTotalAmount(MotorcycleRepository $repository): float
    {
        $items = $this->getItems($repository);

        return array_reduce($items, static fn (float $carry, array $item): float => $carry + (float) $item['subtotal'], 0.0);
    }
}
