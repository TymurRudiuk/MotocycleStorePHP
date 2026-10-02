<?php
$title = $title ?? 'Адмін-панель';
$errors = $errors ?? [];
$passwordErrors = $passwordErrors ?? [];
$old = $old ?? [];
$orders = $orders ?? [];
$motorcycles = $motorcycles ?? [];
$stats = $stats ?? [
    'motorcycles_count' => 0,
    'orders_count' => 0,
    'new_orders_count' => 0,
    'total_revenue' => 0,
];
$motorcycleClasses = $motorcycleClasses ?? [];
$editMotorcycle = $editMotorcycle ?? null;
$adminLogin = $adminLogin ?? 'admin';

require __DIR__ . '/../partials/header.php';

$isEditing = is_array($editMotorcycle);
$formAction = $baseUrl . ($isEditing ? '/admin/motorcycles/update' : '/admin/motorcycles');
$formTitle = $isEditing ? 'Редагувати мотоцикл' : 'Додати мотоцикл';
$formButton = $isEditing ? 'Оновити мотоцикл' : 'Додати мотоцикл';
$formData = $isEditing ? $editMotorcycle : $old;

$statuses = [
    'new' => 'Нове',
    'processing' => 'В обробці',
    'completed' => 'Завершене',
    'cancelled' => 'Скасоване',
];
$statusBadge = [
    'new' => 'bg-blue-100 text-blue-700',
    'processing' => 'bg-amber-100 text-amber-700',
    'completed' => 'bg-emerald-100 text-emerald-700',
    'cancelled' => 'bg-slate-200 text-slate-600',
];

$card = 'rounded-3xl border border-slate-200 bg-white p-6 shadow-soft sm:p-8';
$label = 'block text-sm font-medium text-slate-700';
$input = 'mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100';
$eyebrow = 'text-xs font-semibold uppercase tracking-[0.25em] text-brand-600';
$btnPrimary = 'rounded-full bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700';
$btnGhost = 'rounded-full border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100';
$fieldError = static function (array $errs, string $key): string {
    return isset($errs[$key])
        ? '<span class="mt-1 block text-xs font-normal text-red-600">' . htmlspecialchars($errs[$key]) . '</span>'
        : '';
};

$formFields = [
    ['name', 'Назва', 'text', ''],
    ['brand', 'Бренд', 'text', ''],
    ['engine_volume', 'Об’єм двигуна, см³', 'number', ''],
    ['power', 'Потужність', 'number', ''],
    ['price', 'Ціна, грн', 'number', 'step="0.01"'],
    ['model_year', 'Рік випуску', 'number', ''],
];
?>
<!-- Шапка панелі -->
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <span class="<?= $eyebrow ?>">Адмін-панель</span>
        <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Керування магазином</h1>
        <p class="mt-1 text-sm text-slate-500">Ви увійшли як <strong class="text-slate-700"><?= htmlspecialchars($adminLogin) ?></strong></p>
    </div>
    <form method="post" action="<?= htmlspecialchars($baseUrl . '/admin/logout') ?>">
        <?= Csrf::field() ?>
        <button type="submit" class="rounded-full bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">Вийти</button>
    </form>
</div>

<?php if (!empty($successMessage)): ?>
    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        <?= htmlspecialchars($successMessage) ?>
    </div>
<?php endif; ?>

<!-- Статистика -->
<section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
    <?php
    $cards = [
        ['Каталог', (string) (int) $stats['motorcycles_count'], 'Мотоциклів у каталозі'],
        ['Замовлення', (string) (int) $stats['orders_count'], 'Усього замовлень'],
        ['Нові', (string) (int) $stats['new_orders_count'], 'Нових замовлень'],
        ['Виручка', number_format((float) $stats['total_revenue'], 0, '', ' ') . ' грн', 'Загальна сума замовлень'],
    ];
    foreach ($cards as [$cardLabel, $value, $caption]): ?>
        <article class="<?= $card ?> !p-5">
            <span class="<?= $eyebrow ?>"><?= htmlspecialchars($cardLabel) ?></span>
            <p class="mt-2 text-2xl font-black text-slate-900"><?= htmlspecialchars($value) ?></p>
            <p class="mt-1 text-sm text-slate-500"><?= htmlspecialchars($caption) ?></p>
        </article>
    <?php endforeach; ?>
    <article class="<?= $card ?> !p-5">
        <span class="<?= $eyebrow ?>">Відгуки</span>
        <a href="<?= htmlspecialchars($baseUrl . '/admin/reviews') ?>" class="mt-3 inline-block <?= $btnPrimary ?>">Модерація</a>
        <p class="mt-2 text-sm text-slate-500">Керування відгуками</p>
    </article>
</section>

<!-- Форма товару + пароль -->
<section class="mt-8 grid gap-6 lg:grid-cols-3">
    <div class="<?= $card ?> lg:col-span-2">
        <span class="<?= $eyebrow ?>">Товари</span>
        <h2 class="mt-2 text-xl font-bold text-slate-900"><?= htmlspecialchars($formTitle) ?></h2>

        <form method="post" action="<?= htmlspecialchars($formAction) ?>" enctype="multipart/form-data" class="mt-6 grid gap-5 sm:grid-cols-2" novalidate>
            <?= Csrf::field() ?>
            <?php if ($isEditing): ?>
                <input type="hidden" name="id" value="<?= (int) ($editMotorcycle['id'] ?? 0) ?>">
            <?php endif; ?>
            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($formData['image'] ?? '') ?>">

            <?php foreach (array_slice($formFields, 0, 2) as [$name, $text, $type, $attrs]): ?>
                <label class="<?= $label ?>"><?= $text ?>
                    <input type="<?= $type ?>" name="<?= $name ?>" <?= $attrs ?> value="<?= htmlspecialchars((string) ($formData[$name] ?? '')) ?>" class="<?= $input ?>">
                    <?= $fieldError($errors, $name) ?>
                </label>
            <?php endforeach; ?>

            <label class="<?= $label ?> sm:col-span-2">Клас мотоцикла
                <select name="type" class="<?= $input ?>">
                    <option value="">Оберіть клас</option>
                    <?php foreach ($motorcycleClasses as $motorcycleClass): ?>
                        <option value="<?= htmlspecialchars($motorcycleClass['name']) ?>" <?= ($formData['type'] ?? '') === $motorcycleClass['name'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($motorcycleClass['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?= $fieldError($errors, 'type') ?>
            </label>

            <?php foreach (array_slice($formFields, 2) as [$name, $text, $type, $attrs]): ?>
                <label class="<?= $label ?>"><?= $text ?>
                    <input type="<?= $type ?>" name="<?= $name ?>" <?= $attrs ?> value="<?= htmlspecialchars((string) ($formData[$name] ?? '')) ?>" class="<?= $input ?>">
                    <?= $fieldError($errors, $name) ?>
                </label>
            <?php endforeach; ?>

            <label class="<?= $label ?> sm:col-span-2">Завантажити фото
                <input type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp,.gif"
                       class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-700">
            </label>

            <?php if (!empty($formData['image'])): ?>
                <div class="sm:col-span-2">
                    <img src="<?= htmlspecialchars($formData['image']) ?>" alt="Поточне фото мотоцикла"
                         class="h-40 rounded-2xl border border-slate-200 bg-slate-50 object-contain p-2">
                </div>
            <?php endif; ?>

            <label class="<?= $label ?> sm:col-span-2">Або URL зображення
                <input type="text" name="image" value="<?= htmlspecialchars($formData['image'] ?? '') ?>" class="<?= $input ?>">
                <?= $fieldError($errors, 'image') ?>
            </label>

            <label class="<?= $label ?> sm:col-span-2">Опис
                <textarea name="description" rows="5" class="<?= $input ?>"><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                <?= $fieldError($errors, 'description') ?>
            </label>

            <div class="flex flex-wrap gap-3 sm:col-span-2">
                <button type="submit" class="<?= $btnPrimary ?>"><?= htmlspecialchars($formButton) ?></button>
                <?php if ($isEditing): ?>
                    <a href="<?= htmlspecialchars($baseUrl . '/admin') ?>" class="rounded-full border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Скасувати редагування</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="<?= $card ?> h-fit">
        <span class="<?= $eyebrow ?>">Безпека</span>
        <h2 class="mt-2 text-xl font-bold text-slate-900">Зміна пароля</h2>
        <form method="post" action="<?= htmlspecialchars($baseUrl . '/admin/password') ?>" class="mt-6 space-y-5" novalidate>
            <?= Csrf::field() ?>
            <?php foreach (['current_password' => 'Поточний пароль', 'new_password' => 'Новий пароль', 'confirm_password' => 'Підтвердіть новий пароль'] as $name => $text): ?>
                <label class="<?= $label ?>"><?= $text ?>
                    <input type="password" name="<?= $name ?>" autocomplete="<?= $name === 'current_password' ? 'current-password' : 'new-password' ?>" class="<?= $input ?>">
                    <?= $fieldError($passwordErrors, $name) ?>
                </label>
            <?php endforeach; ?>
            <button type="submit" class="w-full <?= $btnPrimary ?>">Змінити пароль</button>
        </form>
    </div>
</section>

<!-- Список мотоциклів -->
<section class="<?= $card ?> mt-8">
    <span class="<?= $eyebrow ?>">Товари</span>
    <h2 class="mt-2 text-xl font-bold text-slate-900">Список мотоциклів</h2>
    <div class="mt-6 overflow-x-auto">
        <table class="w-full min-w-[640px] text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-slate-500">
                    <th class="px-3 py-3 font-semibold">ID</th>
                    <th class="px-3 py-3 font-semibold">Фото</th>
                    <th class="px-3 py-3 font-semibold">Назва</th>
                    <th class="px-3 py-3 font-semibold">Бренд</th>
                    <th class="px-3 py-3 font-semibold">Ціна</th>
                    <th class="px-3 py-3 font-semibold">Дії</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($motorcycles as $motorcycle): ?>
                    <tr class="align-middle">
                        <td class="px-3 py-3 text-slate-500"><?= (int) $motorcycle['id'] ?></td>
                        <td class="px-3 py-3">
                            <?php if (!empty($motorcycle['image'])): ?>
                                <img src="<?= htmlspecialchars($motorcycle['image']) ?>" alt="<?= htmlspecialchars($motorcycle['name']) ?>"
                                     class="h-12 w-16 rounded-lg border border-slate-200 bg-slate-50 object-contain">
                            <?php endif; ?>
                        </td>
                        <td class="px-3 py-3 font-medium text-slate-900"><?= htmlspecialchars($motorcycle['name']) ?></td>
                        <td class="px-3 py-3 text-slate-700"><?= htmlspecialchars($motorcycle['brand']) ?></td>
                        <td class="whitespace-nowrap px-3 py-3 text-slate-700"><?= number_format((float) $motorcycle['price'], 0, '', ' ') ?> грн</td>
                        <td class="px-3 py-3">
                            <div class="flex gap-2">
                                <a href="<?= htmlspecialchars($baseUrl . '/admin/motorcycles/edit?id=' . (int) $motorcycle['id']) ?>" class="<?= $btnGhost ?>">Редагувати</a>
                                <form method="post" action="<?= htmlspecialchars($baseUrl . '/admin/motorcycles/delete') ?>"
                                      onsubmit="return confirm('Видалити «<?= htmlspecialchars(addslashes($motorcycle['name']), ENT_QUOTES) ?>»?');">
                                    <?= Csrf::field() ?>
                                    <input type="hidden" name="id" value="<?= (int) $motorcycle['id'] ?>">
                                    <button type="submit" class="rounded-full border border-red-200 px-4 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">Видалити</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Замовлення -->
<section class="<?= $card ?> mt-8">
    <span class="<?= $eyebrow ?>">Замовлення</span>
    <h2 class="mt-2 text-xl font-bold text-slate-900">Список оформлених замовлень</h2>

    <?php if ($orders === []): ?>
        <p class="mt-6 text-slate-500">Замовлень поки немає.</p>
    <?php else: ?>
        <div class="mt-6 space-y-4">
            <?php foreach ($orders as $order): ?>
                <article class="rounded-2xl border border-slate-200 p-5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <div class="flex flex-wrap items-center gap-3">
                                <h3 class="text-lg font-bold text-slate-900">Замовлення #<?= (int) $order['id'] ?></h3>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold <?= $statusBadge[$order['status']] ?? 'bg-slate-100 text-slate-600' ?>">
                                    <?= htmlspecialchars($statuses[$order['status']] ?? $order['status']) ?>
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-slate-500"><?= htmlspecialchars($order['customer_name']) ?></p>
                        </div>
                        <form method="post" action="<?= htmlspecialchars($baseUrl . '/admin/orders/status') ?>" class="flex flex-wrap items-center gap-2">
                            <?= Csrf::field() ?>
                            <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
                            <select name="status" class="rounded-full border border-slate-300 bg-white px-4 py-2 text-sm text-slate-700 outline-none focus:border-brand-500">
                                <?php foreach ($statuses as $value => $text): ?>
                                    <option value="<?= htmlspecialchars($value) ?>" <?= $order['status'] === $value ? 'selected' : '' ?>><?= htmlspecialchars($text) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="<?= $btnPrimary ?> !py-2">Змінити статус</button>
                        </form>
                    </div>

                    <dl class="mt-4 grid gap-x-6 gap-y-2 text-sm sm:grid-cols-2">
                        <div><dt class="inline text-slate-500">Телефон: </dt><dd class="inline text-slate-900"><?= htmlspecialchars($order['phone']) ?></dd></div>
                        <div><dt class="inline text-slate-500">Email: </dt><dd class="inline text-slate-900"><?= htmlspecialchars($order['email']) ?></dd></div>
                        <div class="sm:col-span-2"><dt class="inline text-slate-500">Адреса: </dt><dd class="inline text-slate-900"><?= htmlspecialchars($order['address']) ?></dd></div>
                        <?php if (!empty($order['notes'])): ?>
                            <div class="sm:col-span-2"><dt class="inline text-slate-500">Коментар: </dt><dd class="inline text-slate-900"><?= htmlspecialchars($order['notes']) ?></dd></div>
                        <?php endif; ?>
                    </dl>

                    <?php if (!empty($order['items'])): ?>
                        <ul class="mt-4 divide-y divide-slate-100 border-t border-slate-100 text-sm">
                            <?php foreach ($order['items'] as $item): ?>
                                <li class="flex justify-between gap-4 py-2">
                                    <span class="text-slate-700"><?= htmlspecialchars($item['motorcycle_name']) ?> <span class="text-slate-400">× <?= (int) $item['quantity'] ?></span></span>
                                    <strong class="whitespace-nowrap text-slate-900"><?= number_format((float) $item['subtotal'], 0, '', ' ') ?> грн</strong>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <p class="mt-3 text-right text-base font-black text-brand-600">
                        Разом: <?= number_format((float) $order['total_amount'], 0, '', ' ') ?> грн
                    </p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../partials/footer.php'; ?>
