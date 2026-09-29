<?php

declare(strict_types=1);

require_once __DIR__ . '/Database.php';

class ContactRequestRepository
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
            'INSERT INTO contact_requests (name, phone, email, message, created_at)
             VALUES (:name, :phone, :email, :message, NOW())'
        );

        return $statement->execute([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'message' => $data['message'],
        ]);
    }
}
