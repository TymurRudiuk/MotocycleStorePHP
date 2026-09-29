<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Замовлення оформлено') ?> | <?= htmlspecialchars($appName ?? 'MotoCycle Store') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars(rtrim($baseUrl ?? '', '/')) ?>/assets/css/style.css">
</head>
<body>
<?php
$baseUrl = rtrim($baseUrl ?? '', '/');
$homeUrl = $baseUrl === '' ? '/' : $baseUrl . '/';
$catalogUrl = $baseUrl . '/motorcycles';
$orderId = $orderId ?? null;
$message = $orderId !== null
    ? 'Ваше замовлення успішно збережено під номером #' . (int) $orderId . '.'
    : 'Замовлення прийнято. Наш менеджер зв’яжеться з вами найближчим часом для підтвердження.';
?>
<header>
    <h1><?= htmlspecialchars($appName ?? 'MotoCycle Store') ?></h1>
    <nav>
        <a href="<?= htmlspecialchars($homeUrl) ?>">Головна</a>
        <a href="<?= htmlspecialchars($catalogUrl) ?>">Каталог</a>
    </nav>
</header>
<main>
    <section class="empty-state">
        <span class="section-label">Успіх</span>
        <h2>Замовлення оформлено</h2>
        <p><?= htmlspecialchars($message) ?></p>
        <a class="button" href="<?= htmlspecialchars($catalogUrl) ?>">Повернутися до каталогу</a>
    </section>
</main>
<footer>
    <p>© <?= date('Y') ?> <?= htmlspecialchars($appName ?? 'MotoCycle Store') ?>. Всі права захищено.</p>
</footer>
</body>
</html>
