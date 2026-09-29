<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Адмін-панель') ?> | <?= htmlspecialchars($appName ?? 'MotoCycle Store') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars(rtrim($baseUrl ?? '', '/')) ?>/assets/css/style.css">
</head>
<body>
<?php
$baseUrl = rtrim($baseUrl ?? '', '/');
$homeUrl = $baseUrl === '' ? '/' : $baseUrl . '/';
$catalogUrl = $baseUrl . '/motorcycles';
$cartUrl = $baseUrl . '/cart';
$errors = $errors ?? [];
$passwordErrors = $passwordErrors ?? [];
$old = $old ?? [];
$orders = $orders ?? [];
$stats = $stats ?? [
    'motorcycles_count' => 0,
    'orders_count' => 0,
    'new_orders_count' => 0,
    'total_revenue' => 0,
];
$motorcycleClasses = $motorcycleClasses ?? [];
$editMotorcycle = $editMotorcycle ?? null;
$adminLogin = $adminLogin ?? 'admin';
$isEditing = is_array($editMotorcycle);
$formAction = $isEditing ? $baseUrl . '/admin/motorcycles/update' : $baseUrl . '/admin/motorcycles';
$formTitle = $isEditing ? 'Редагувати мотоцикл' : 'Додати мотоцикл';
$formButton = $isEditing ? 'Оновити мотоцикл' : 'Додати мотоцикл';
$formData = $isEditing ? $editMotorcycle : $old;
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
    <section class="stats-grid">
        <article class="info-card">
            <span class="section-label">Статистика</span>
            <h3><?= (int) $stats['motorcycles_count'] ?></h3>
            <p>Мотоциклів у каталозі</p>
        </article>
        <article class="info-card">
            <span class="section-label">Замовлення</span>
            <h3><?= (int) $stats['orders_count'] ?></h3>
            <p>Усього замовлень</p>
        </article>
        <article class="info-card">
            <span class="section-label">Нові</span>
            <h3><?= (int) $stats['new_orders_count'] ?></h3>
            <p>Нових замовлень</p>
        </article>
        <article class="info-card">
            <span class="section-label">Виручка</span>
            <h3><?= number_format((float) $stats['total_revenue'], 0, '', ' ') ?> грн</h3>
            <p>Загальна сума замовлень</p>
        </article>
        <article class="info-card">
            <span class="section-label">Відгуки</span>
            <a class="button button-secondary button-small" href="<?= htmlspecialchars($baseUrl . '/admin/reviews') ?>" style="display:block; margin-top:10px;">Модерація</a>
            <p>Керування відгуками</p>
        </article>
    </section>

    <section class="contact-grid">
        <div class="contact-card">
            <span class="section-label">Адмін-панель</span>
            <h2><?= htmlspecialchars($formTitle) ?></h2>
            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
            <?php endif; ?>
            <form method="post" action="<?= htmlspecialchars($formAction) ?>" class="contact-form" enctype="multipart/form-data">
                <?php if ($isEditing): ?>
                    <input type="hidden" name="id" value="<?= (int) ($editMotorcycle['id'] ?? 0) ?>">
                <?php endif; ?>
                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($formData['image'] ?? '') ?>">
                <label>Назва<input type="text" name="name" value="<?= htmlspecialchars($formData['name'] ?? '') ?>"><?php if (isset($errors['name'])): ?><span class="error-text"><?= htmlspecialchars($errors['name']) ?></span><?php endif; ?></label>
                <label>Бренд<input type="text" name="brand" value="<?= htmlspecialchars($formData['brand'] ?? '') ?>"><?php if (isset($errors['brand'])): ?><span class="error-text"><?= htmlspecialchars($errors['brand']) ?></span><?php endif; ?></label>
                <label>Клас мотоцикла
                    <select name="type">
                        <option value="">Оберіть клас</option>
                        <?php foreach ($motorcycleClasses as $motorcycleClass): ?>
                            <option value="<?= htmlspecialchars($motorcycleClass['name']) ?>" <?= ($formData['type'] ?? '') === $motorcycleClass['name'] ? 'selected' : '' ?>><?= htmlspecialchars($motorcycleClass['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['type'])): ?><span class="error-text"><?= htmlspecialchars($errors['type']) ?></span><?php endif; ?>
                </label>
                <label>Об’єм двигуна<input type="number" name="engine_volume" value="<?= htmlspecialchars((string) ($formData['engine_volume'] ?? '')) ?>"><?php if (isset($errors['engine_volume'])): ?><span class="error-text"><?= htmlspecialchars($errors['engine_volume']) ?></span><?php endif; ?></label>
                <label>Потужність<input type="number" name="power" value="<?= htmlspecialchars((string) ($formData['power'] ?? '')) ?>"><?php if (isset($errors['power'])): ?><span class="error-text"><?= htmlspecialchars($errors['power']) ?></span><?php endif; ?></label>
                <label>Ціна<input type="number" step="0.01" name="price" value="<?= htmlspecialchars((string) ($formData['price'] ?? '')) ?>"><?php if (isset($errors['price'])): ?><span class="error-text"><?= htmlspecialchars($errors['price']) ?></span><?php endif; ?></label>
                <label>Рік випуску<input type="number" name="model_year" value="<?= htmlspecialchars((string) ($formData['model_year'] ?? '')) ?>"><?php if (isset($errors['model_year'])): ?><span class="error-text"><?= htmlspecialchars($errors['model_year']) ?></span><?php endif; ?></label>
                <label>Завантажити фото<input type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp,.gif"></label>
                <?php if (!empty($formData['image'])): ?>
                    <div class="image-preview">
                        <img src="<?= htmlspecialchars($formData['image']) ?>" alt="Поточне фото мотоцикла">
                    </div>
                <?php endif; ?>
                <label>Або URL зображення<input type="text" name="image" value="<?= htmlspecialchars($formData['image'] ?? '') ?>"><?php if (isset($errors['image'])): ?><span class="error-text"><?= htmlspecialchars($errors['image']) ?></span><?php endif; ?></label>
                <label>Опис<textarea name="description" rows="5"><?= htmlspecialchars($formData['description'] ?? '') ?></textarea><?php if (isset($errors['description'])): ?><span class="error-text"><?= htmlspecialchars($errors['description']) ?></span><?php endif; ?></label>
                <div class="actions-row">
                    <button type="submit" class="button"><?= htmlspecialchars($formButton) ?></button>
                    <?php if ($isEditing): ?>
                        <a class="button button-secondary" href="<?= htmlspecialchars($baseUrl . '/admin') ?>">Скасувати редагування</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="contact-card">
            <span class="section-label">Безпека</span>
            <h2>Зміна пароля адміністратора</h2>
            <p class="meta">Поточний логін: <strong><?= htmlspecialchars($adminLogin) ?></strong></p>
            <form method="post" action="<?= htmlspecialchars($baseUrl . '/admin/password') ?>" class="contact-form">
                <label>Поточний пароль<input type="password" name="current_password"><?php if (isset($passwordErrors['current_password'])): ?><span class="error-text"><?= htmlspecialchars($passwordErrors['current_password']) ?></span><?php endif; ?></label>
                <label>Новий пароль<input type="password" name="new_password"><?php if (isset($passwordErrors['new_password'])): ?><span class="error-text"><?= htmlspecialchars($passwordErrors['new_password']) ?></span><?php endif; ?></label>
                <label>Підтвердіть новий пароль<input type="password" name="confirm_password"><?php if (isset($passwordErrors['confirm_password'])): ?><span class="error-text"><?= htmlspecialchars($passwordErrors['confirm_password']) ?></span><?php endif; ?></label>
                <button type="submit" class="button">Змінити пароль</button>
            </form>
        </div>
    </section>

    <section class="contact-card">
        <span class="section-label">Товари</span>
        <h2>Список мотоциклів</h2>
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>ID</th><th>Фото</th><th>Назва</th><th>Бренд</th><th>Ціна</th><th>Дії</th></tr></thead>
                <tbody>
                <?php foreach (($motorcycles ?? []) as $motorcycle): ?>
                    <tr>
                        <td><?= (int) $motorcycle['id'] ?></td>
                        <td><?php if (!empty($motorcycle['image'])): ?><img class="table-thumb" src="<?= htmlspecialchars($motorcycle['image']) ?>" alt="<?= htmlspecialchars($motorcycle['name']) ?>"><?php endif; ?></td>
                        <td><?= htmlspecialchars($motorcycle['name']) ?></td>
                        <td><?= htmlspecialchars($motorcycle['brand']) ?></td>
                        <td><?= number_format((float) $motorcycle['price'], 0, '', ' ') ?> грн</td>
                        <td>
                            <div class="inline-actions">
                                <a class="button button-secondary button-small" href="<?= htmlspecialchars($baseUrl . '/admin/motorcycles/edit?id=' . (int) $motorcycle['id']) ?>">Редагувати</a>
                                <form method="post" action="<?= htmlspecialchars($baseUrl . '/admin/motorcycles/delete') ?>">
                                    <input type="hidden" name="id" value="<?= (int) $motorcycle['id'] ?>">
                                    <button type="submit" class="button button-secondary button-small">Видалити</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="contact-card">
        <span class="section-label">Замовлення</span>
        <h2>Список оформлених замовлень</h2>
        <?php if ($orders === []): ?>
            <p>Замовлень поки немає.</p>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <h3>Замовлення #<?= (int) $order['id'] ?></h3>
                            <p><strong>Клієнт:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                        </div>
                        <form method="post" action="<?= htmlspecialchars($baseUrl . '/admin/orders/status') ?>" class="status-form">
                            <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
                            <select name="status">
                                <?php foreach (['new' => 'Нове', 'processing' => 'В обробці', 'completed' => 'Завершене', 'cancelled' => 'Скасоване'] as $value => $label): ?>
                                    <option value="<?= htmlspecialchars($value) ?>" <?= $order['status'] === $value ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="button button-small">Змінити статус</button>
                        </form>
                    </div>
                    <p><strong>Телефон:</strong> <?= htmlspecialchars($order['phone']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
                    <p><strong>Адреса:</strong> <?= htmlspecialchars($order['address']) ?></p>
                    <p><strong>Статус:</strong> <?= htmlspecialchars($order['status']) ?></p>
                    <p><strong>Сума:</strong> <?= number_format((float) $order['total_amount'], 0, '', ' ') ?> грн</p>
                    <?php if (!empty($order['notes'])): ?><p><strong>Коментар:</strong> <?= htmlspecialchars($order['notes']) ?></p><?php endif; ?>
                    <?php if (!empty($order['items'])): ?>
                        <ul class="summary-list">
                            <?php foreach ($order['items'] as $item): ?>
                                <li><span><?= htmlspecialchars($item['motorcycle_name']) ?> × <?= (int) $item['quantity'] ?></span><strong><?= number_format((float) $item['subtotal'], 0, '', ' ') ?> грн</strong></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>
<footer>
    <p>© <?= date('Y') ?> <?= htmlspecialchars($appName ?? 'MotoCycle Store') ?>. Всі права захищено.</p>
</footer>
</body>
</html>
