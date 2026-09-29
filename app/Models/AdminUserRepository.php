<?php

declare(strict_types=1);

require_once __DIR__ . '/Database.php';

class AdminUserRepository
{
    public function __construct(
        private readonly array $config
    ) {
    }

    public function findById(int $id): ?array
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return null;
        }

        $statement = $connection->prepare(
            'SELECT id, login, password_hash
             FROM admins
             WHERE id = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $admin = $statement->fetch();

        return $admin !== false ? $admin : null;
    }

    public function findByLogin(string $login): ?array
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return null;
        }

        $statement = $connection->prepare(
            'SELECT id, login, password_hash
             FROM admins
             WHERE login = :login
             LIMIT 1'
        );
        $statement->execute(['login' => $login]);
        $admin = $statement->fetch();

        return $admin !== false ? $admin : null;
    }

    public function updatePassword(int $id, string $passwordHash): bool
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return false;
        }

        $statement = $connection->prepare(
            'UPDATE admins
             SET password_hash = :password_hash
             WHERE id = :id'
        );

        return $statement->execute([
            'password_hash' => $passwordHash,
            'id' => $id,
        ]);
    }
}
