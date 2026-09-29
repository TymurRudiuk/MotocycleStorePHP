<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Модерація відгуків') ?> | <?= htmlspecialchars($appName ?? 'MotoCycle Store') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars(rtrim($baseUrl ?? '', '/')) ?>/assets/css/style.css">
</head>
<body>
<?php
$baseUrl = rtrim($baseUrl ?? '', '/');
$homeUrl = $baseUrl === '' ? '/' : $baseUrl . '/';
$catalogUrl = $baseUrl . '/motorcycles';
$cartUrl = $baseUrl . '/cart';
?>
<header>
    <h1><?= htmlspecialchars($appName ?? 'MotoCycle Store') ?></h1>
    <nav>
        <a href="<?= htmlspecialchars($homeUrl) ?>">Головна</a>
        <a href="<?= htmlspecialchars($catalogUrl) ?>">Каталог</a>
        <a href="<?= htmlspecialchars($cartUrl) ?>">Кошик</a>
        <form method="post" action="<?= htmlspecialchars($baseUrl . '/admin/logout') ?>">
            <button type="submit" class="button button-secondary button-small">Вийти</button>
        </form>
    </nav>
</header>
<main>
    <section class="contact-card">
        <span class="section-label">Адмін-панель</span>
        <h2>Модерація відгуків</h2>
        <a class="button button-secondary button-small" href="<?= htmlspecialchars($baseUrl . '/admin') ?>">Повернутися до головної адмінки</a>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <?php if (empty($reviews)): ?>
            <p>Немає відгуків, що очікують на схвалення.</p>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Автор</th>
                            <th>Рейтинг</th>
                            <th>Коментар</th>
                            <th>Мотоцикл</th>
                            <th>Дата</th>
                            <th>Дії</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reviews as $review): ?>
                            <tr>
                                <td><?= htmlspecialchars($review['author_name']) ?></td>
                                <td><?= (int) $review['rating'] ?>/5</td>
                                <td><?= htmlspecialchars($review['comment']) ?></td>
                                <td><?= htmlspecialchars($review['motorcycle_name']) ?></td>
                                <td><?= htmlspecialchars($review['created_at']) ?></td>
                                <td>
                                    <div class="inline-actions">
                                        <form method="post" action="<?= htmlspecialchars($baseUrl . '/admin/reviews/approve') ?>" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= (int) $review['id'] ?>">
                                            <button type="submit" class="button button-small">Схвалити</button>
                                        </form>
                                        <form method="post" action="<?= htmlspecialchars($baseUrl . '/admin/reviews/delete') ?>" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= (int) $review['id'] ?>">
                                            <button type="submit" class="button button-secondary button-small">Видалити</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>
<footer>
    <p>© <?= date('Y') ?> <?= htmlspecialchars($appName ?? 'MotoCycle Store') ?>. Всі права захищено.</p>
</footer>
</body>
</html>
