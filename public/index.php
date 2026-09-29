<?php

declare(strict_types=1);

session_start();

$config = require __DIR__ . '/../config/app.php';
$routes = require __DIR__ . '/../routes/web.php';

spl_autoload_register(static function (string $class): void {
    $directories = [
        __DIR__ . '/../app/Controllers/',
        __DIR__ . '/../app/Models/',
        __DIR__ . '/../app/Services/',
    ];

    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';

        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

$renderError = static function (int $code, string $heading, string $message) use ($config): void {
    http_response_code($code);

    extract([
        'code' => $code,
        'heading' => $heading,
        'message' => $message,
        'title' => $heading,
        'appName' => $config['app_name'] ?? 'MotoCycle Store',
        'baseUrl' => $config['base_url'] ?? '',
    ], EXTR_SKIP);

    require __DIR__ . '/../app/Views/errors/error.php';
    exit;
};

$baseUrl = rtrim($config['base_url'] ?? '', '/');
$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($baseUrl !== '' && str_starts_with($requestUri, $baseUrl)) {
    $requestUri = substr($requestUri, strlen($baseUrl));
}

$normalizedUri = rtrim($requestUri, '/');
$normalizedUri = $normalizedUri === '' ? '/' : $normalizedUri;
$routeKey = $method . ' ' . $normalizedUri;

if (!isset($routes[$routeKey])) {
    $renderError(404, 'Сторінку не знайдено', 'Можливо, ця сторінка була переміщена або більше не існує.');
}

[$controllerClass, $action] = $routes[$routeKey];

if (!class_exists($controllerClass)) {
    $renderError(500, 'Внутрішня помилка', 'Не вдалося обробити запит. Спробуйте пізніше.');
}

$controller = new $controllerClass($config);

if (!method_exists($controller, $action)) {
    $renderError(500, 'Внутрішня помилка', 'Не вдалося обробити запит. Спробуйте пізніше.');
}

if ($method === 'POST') {
    Csrf::verify();
}

$controller->$action();
