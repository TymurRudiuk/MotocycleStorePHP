<?php

declare(strict_types=1);

require_once __DIR__ . '/Database.php';

class OrderRepository
{
    public function __construct(
        private readonly array $config
    ) {
    }

    public function create(array $customer, array $items, float $totalAmount): ?int
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return null;
        }

        try {
            $connection->beginTransaction();

            $statement = $connection->prepare(
                'INSERT INTO orders (customer_name, phone, email, address, notes, total_amount, status, created_at)
                 VALUES (:customer_name, :phone, :email, :address, :notes, :total_amount, :status, NOW())'
            );

            $statement->execute([
                'customer_name' => $customer['customer_name'],
                'phone' => $customer['phone'],
                'email' => $customer['email'],
                'address' => $customer['address'],
                'notes' => $customer['notes'],
                'total_amount' => $totalAmount,
                'status' => 'new',
            ]);

            $orderId = (int) $connection->lastInsertId();

            $itemStatement = $connection->prepare(
                'INSERT INTO order_items (order_id, motorcycle_id, motorcycle_name, price, quantity, subtotal)
                 VALUES (:order_id, :motorcycle_id, :motorcycle_name, :price, :quantity, :subtotal)'
            );

            foreach ($items as $item) {
                $itemStatement->execute([
                    'order_id' => $orderId,
                    'motorcycle_id' => $item['id'],
                    'motorcycle_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            $connection->commit();

            return $orderId;
        } catch (Throwable) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }

            return null;
        }
    }

    public function getAll(): array
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return [];
        }

        $statement = $connection->query(
            'SELECT id, customer_name, phone, email, address, notes, total_amount, status, created_at
             FROM orders
             ORDER BY id DESC'
        );

        return $statement->fetchAll() ?: [];
    }

    public function getItemsByOrderId(int $orderId): array
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return [];
        }

        $statement = $connection->prepare(
            'SELECT motorcycle_name, price, quantity, subtotal
             FROM order_items
             WHERE order_id = :order_id
             ORDER BY id ASC'
        );
        $statement->execute(['order_id' => $orderId]);

        return $statement->fetchAll() ?: [];
    }

    public function updateStatus(int $orderId, string $status): bool
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return false;
        }

        $statement = $connection->prepare(
            'UPDATE orders
             SET status = :status
             WHERE id = :id'
        );

        return $statement->execute([
            'status' => $status,
            'id' => $orderId,
        ]);
    }

    public function getStats(): array
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return [
                'orders_count' => 0,
                'new_orders_count' => 0,
                'total_revenue' => 0,
            ];
        }

        $statement = $connection->query(
            "SELECT
                COUNT(*) AS orders_count,
                SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) AS new_orders_count,
                COALESCE(SUM(total_amount), 0) AS total_revenue
             FROM orders"
        );

        $stats = $statement->fetch();

        return [
            'orders_count' => (int) ($stats['orders_count'] ?? 0),
            'new_orders_count' => (int) ($stats['new_orders_count'] ?? 0),
            'total_revenue' => (float) ($stats['total_revenue'] ?? 0),
        ];
    }
}
