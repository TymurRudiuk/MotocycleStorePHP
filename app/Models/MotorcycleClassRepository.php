<?php

declare(strict_types=1);

require_once __DIR__ . '/Database.php';

class MotorcycleClassRepository
{
    public function __construct(
        private readonly array $config
    ) {
    }

    public function getAll(): array
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return [];
        }

        $statement = $connection->query(
            'SELECT mc.id, mc.name, COUNT(m.id) AS motorcycles_count
             FROM motorcycle_classes mc
             LEFT JOIN motorcycles m ON m.class_id = mc.id
             GROUP BY mc.id, mc.name
             ORDER BY mc.name ASC'
        );

        return $statement->fetchAll() ?: [];
    }

    public function findIdByName(string $name): ?int
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return null;
        }

        $statement = $connection->prepare(
            'SELECT id FROM motorcycle_classes WHERE name = :name LIMIT 1'
        );
        $statement->execute(['name' => $name]);
        $result = $statement->fetch();

        return $result !== false ? (int) $result['id'] : null;
    }

    public function create(string $name): bool
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return false;
        }

        try {
            $statement = $connection->prepare(
                'INSERT INTO motorcycle_classes (name, created_at) VALUES (:name, NOW())'
            );

            return $statement->execute(['name' => $name]);
        } catch (PDOException) {
            return false;
        }
    }

    public function update(int $id, string $name): bool
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return false;
        }

        try {
            $statement = $connection->prepare(
                'UPDATE motorcycle_classes SET name = :name WHERE id = :id'
            );

            return $statement->execute(['name' => $name, 'id' => $id]);
        } catch (PDOException) {
            return false;
        }
    }

    public function delete(int $id): bool
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return false;
        }

        $statement = $connection->prepare('DELETE FROM motorcycle_classes WHERE id = :id');

        return $statement->execute(['id' => $id]);
    }

    public function countMotorcycles(int $id): int
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return 0;
        }

        $statement = $connection->prepare(
            'SELECT COUNT(*) AS total FROM motorcycles WHERE class_id = :id'
        );
        $statement->execute(['id' => $id]);
        $result = $statement->fetch();

        return (int) ($result['total'] ?? 0);
    }
}
