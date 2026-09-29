<?php

declare(strict_types=1);

class Csrf
{
    private const SESSION_KEY = 'csrf_token';

    public static function token(): string
    {
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(self::token(), ENT_QUOTES) . '">';
    }

    public static function isValid(?string $token): bool
    {
        $expected = $_SESSION[self::SESSION_KEY] ?? '';

        if ($expected === '' || $token === null || $token === '') {
            return false;
        }

        return hash_equals($expected, $token);
    }

    public static function verify(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            return;
        }

        if (self::isValid($_POST['csrf_token'] ?? null)) {
            return;
        }

        http_response_code(419);
        echo 'Сесія втратила актуальність. Оновіть сторінку та спробуйте ще раз.';
        exit;
    }
}
