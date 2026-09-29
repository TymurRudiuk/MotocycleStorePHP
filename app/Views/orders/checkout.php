<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Оформлення замовлення') ?> | <?= htmlspecialchars($appName ?? 'MotoCycle Store') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars(rtrim($baseUrl ?? '', '/')) ?>/assets/css/style.css">
</head>
<body>
<?php
$baseUrl = rtrim($baseUrl ?? '', '/');
$homeUrl = $baseUrl === '' ? '/' : $baseUrl . '/';
$catalogUrl = $baseUrl . '/motorcycles';
$contactsUrl = $baseUrl . '/contacts';
$cartUrl = $baseUrl . '/cart';
$errors = $errors ?? [];
$old = $old ?? [];
$items = $items ?? [];
$totalAmount = $totalAmount ?? 0;
?>
<header>
    <h1><?= htmlspecialchars($appName ?? 'MotoCycle Store') ?></h1>
    <nav>
        <a href="<?= htmlspecialchars($homeUrl) ?>">Головна</a>
        <a href="<?= htmlspecialchars($catalogUrl) ?>">Каталог</a>
        <a href="<?= htmlspecialchars($cartUrl) ?>">Кошик</a>
        <a href="<?= htmlspecialchars($contactsUrl) ?>">Контакти</a>
    </nav>
</header>
<main>
    <section class="contact-grid">
        <div class="contact-card">
            <span class="section-label">Ваше замовлення</span>
            <h2>Підсумок покупки</h2>
            <ul class="summary-list">
                <?php foreach ($items as $item): ?>
                    <li>
                        <span><?= htmlspecialchars($item['name']) ?> × <?= (int) $item['quantity'] ?></span>
                        <strong><?= number_format((float) $item['subtotal'], 0, '', ' ') ?> грн</strong>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p class="price">Разом: <?= number_format((float) $totalAmount, 0, '', ' ') ?> грн</p>
        </div>

        <div class="contact-card">
            <span class="section-label">Оформлення</span>
            <h2>Дані покупця</h2>
            <form method="post" action="<?= htmlspecialchars($baseUrl . '/checkout') ?>" class="contact-form">
                <label>
                    Ім’я та прізвище
                    <input type="text" name="customer_name" value="<?= htmlspecialchars($old['customer_name'] ?? '') ?>">
                    <?php if (isset($errors['customer_name'])): ?>
                        <span class="error-text"><?= htmlspecialchars($errors['customer_name']) ?></span>
                    <?php endif; ?>
                </label>
                <label>
                    Телефон
                    <input type="text" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                    <?php if (isset($errors['phone'])): ?>
                        <span class="error-text"><?= htmlspecialchars($errors['phone']) ?></span>
                    <?php endif; ?>
                </label>
                <label>
                    Email
                    <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                    <?php if (isset($errors['email'])): ?>
                        <span class="error-text"><?= htmlspecialchars($errors['email']) ?></span>
                    <?php endif; ?>
                </label>
                <label>
                    Адреса / спосіб отримання
                    <input type="text" name="address" value="<?= htmlspecialchars($old['address'] ?? '') ?>">
                    <?php if (isset($errors['address'])): ?>
                        <span class="error-text"><?= htmlspecialchars($errors['address']) ?></span>
                    <?php endif; ?>
                </label>
                <label>
                    Коментар
                    <textarea name="notes" rows="4"><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
                </label>
                <button type="submit" class="button">Підтвердити замовлення</button>
            </form>
        </div>
    </section>
</main>
<footer>
    <p>© <?= date('Y') ?> <?= htmlspecialchars($appName ?? 'MotoCycle Store') ?>. Всі права захищено.</p>
</footer>
</body>
</html>
