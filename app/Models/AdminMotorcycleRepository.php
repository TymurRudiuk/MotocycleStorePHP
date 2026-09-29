<?php

declare(strict_types=1);

require_once __DIR__ . '/Database.php';

class AdminMotorcycleRepository
{
    public function __construct(
        private readonly array $config
    ) {
    }

    public function create(array $data): bool
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return false;
        }

        $statement = $connection->prepare(
            'INSERT INTO motorcycles (name, brand, type, class_id, engine_volume, power, price, model_year, image, description, created_at)
             VALUES (:name, :brand, :type, :class_id, :engine_volume, :power, :price, :model_year, :image, :description, NOW())'
        );

        return $statement->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return false;
        }

        $data['id'] = $id;

        $statement = $connection->prepare(
            'UPDATE motorcycles
             SET name = :name,
                 brand = :brand,
                 type = :type,
                 class_id = :class_id,
                 engine_volume = :engine_volume,
                 power = :power,
                 price = :price,
                 model_year = :model_year,
                 image = :image,
                 description = :description
             WHERE id = :id'
        );

        return $statement->execute($data);
    }

    public function countAll(): int
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return 0;
        }

        $statement = $connection->query('SELECT COUNT(*) AS total FROM motorcycles');
        $result = $statement->fetch();

        return (int) ($result['total'] ?? 0);
    }

    public function delete(int $id): bool
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return false;
        }

        $statement = $connection->prepare('DELETE FROM motorcycles WHERE id = :id');

        return $statement->execute(['id' => $id]);
    }
}
