<?php

declare(strict_types=1);

require_once __DIR__ . '/Database.php';

class ReviewRepository
{
    public function __construct(
        private readonly array $config
    ) {
    }

    public function getApprovedByMotorcycleId(int $motorcycleId): array
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return [];
        }

        $statement = $connection->prepare(
            'SELECT id, author_name, rating, comment, created_at
             FROM reviews
             WHERE motorcycle_id = :motorcycle_id
               AND is_approved = 1
             ORDER BY id DESC'
        );
        $statement->execute(['motorcycle_id' => $motorcycleId]);

        return $statement->fetchAll() ?: [];
    }

    public function getPending(): array
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return [];
        }

        $statement = $connection->query(
            'SELECT r.id, r.author_name, r.rating, r.comment, r.created_at, m.name AS motorcycle_name
             FROM reviews r
             JOIN motorcycles m ON m.id = r.motorcycle_id
             WHERE r.is_approved = 0
             ORDER BY r.id DESC'
        );

        return $statement->fetchAll() ?: [];
    }

    public function create(array $data): bool
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return false;
        }

        $statement = $connection->prepare(
            'INSERT INTO reviews (motorcycle_id, author_name, rating, comment, is_approved, created_at)
             VALUES (:motorcycle_id, :author_name, :rating, :comment, 0, NOW())'
        );

        return $statement->execute([
            'motorcycle_id' => $data['motorcycle_id'],
            'author_name' => $data['author_name'],
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ]);
    }

    public function approve(int $id): bool
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return false;
        }

        $statement = $connection->prepare('UPDATE reviews SET is_approved = 1 WHERE id = :id');

        return $statement->execute(['id' => $id]);
    }

    public function delete(int $id): bool
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return false;
        }

        $statement = $connection->prepare('DELETE FROM reviews WHERE id = :id');

        return $statement->execute(['id' => $id]);
    }

    public function getAverageRating(int $motorcycleId): float
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return 0;
        }

        $statement = $connection->prepare(
            'SELECT AVG(rating) AS average_rating
             FROM reviews
             WHERE motorcycle_id = :motorcycle_id
               AND is_approved = 1'
        );
        $statement->execute(['motorcycle_id' => $motorcycleId]);
        $result = $statement->fetch();

        return round((float) ($result['average_rating'] ?? 0), 1);
    }
}
