<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Вхід адміністратора') ?> | <?= htmlspecialchars($appName ?? 'MotoCycle Store') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars(rtrim($baseUrl ?? '', '/')) ?>/assets/css/style.css">
</head>
<body>
<?php
$baseUrl = rtrim($baseUrl ?? '', '/');
$homeUrl = $baseUrl === '' ? '/' : $baseUrl . '/';
$error = $error ?? null;
?>
<header>
    <h1><?= htmlspecialchars($appName ?? 'MotoCycle Store') ?></h1>
    <nav>
        <a href="<?= htmlspecialchars($homeUrl) ?>">Головна</a>
    </nav>
</header>
<main>
    <section class="auth-box contact-card">
        <span class="section-label">Авторизація</span>
        <h2>Вхід для персоналу</h2>
        <?php if ($error !== null): ?>
            <div class="error-banner"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= htmlspecialchars($baseUrl . '/admin/login') ?>" class="contact-form">
            <?= Csrf::field() ?>
            <label>
                Логін
                <input type="text" name="login" required>
            </label>
            <label>
                Пароль
                <input type="password" name="password" required>
            </label>
            <button type="submit" class="button">Увійти</button>
        </form>
        <p class="meta">Доступ лише для авторизованих працівників магазину.</p>
    </section>
</main>
<footer>
    <p>© <?= date('Y') ?> <?= htmlspecialchars($appName ?? 'MotoCycle Store') ?>. Всі права захищено.</p>
</footer>
</body>
</html>
