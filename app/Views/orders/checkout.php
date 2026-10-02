<?php
$title = $title ?? 'Оформлення замовлення';
$errors = $errors ?? [];
$old = $old ?? [];
$items = $items ?? [];
$totalAmount = $totalAmount ?? 0;

require __DIR__ . '/../partials/header.php';

$fields = [
    'customer_name' => ['label' => 'Ім’я та прізвище', 'type' => 'text',  'autocomplete' => 'name'],
    'phone'         => ['label' => 'Телефон',          'type' => 'tel',   'autocomplete' => 'tel'],
    'email'         => ['label' => 'Email',            'type' => 'email', 'autocomplete' => 'email'],
    'address'       => ['label' => 'Адреса / спосіб отримання', 'type' => 'text', 'autocomplete' => 'street-address'],
];
$inputClass = 'mt-2 w-full rounded-2xl border bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100';
?>
<div class="mb-8">
    <span class="text-xs font-semibold uppercase tracking-[0.25em] text-brand-600">Оформлення</span>
    <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Оформлення замовлення</h1>
</div>

<div class="grid gap-6 lg:grid-cols-5">
    <!-- Підсумок -->
    <section class="h-fit rounded-3xl border border-slate-200 bg-white p-6 shadow-soft sm:p-8 lg:col-span-2 lg:sticky lg:top-28">
        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-brand-600">Ваше замовлення</span>
        <h2 class="mt-2 text-xl font-bold text-slate-900">Підсумок покупки</h2>

        <ul class="mt-6 divide-y divide-slate-100">
            <?php foreach ($items as $item): ?>
                <li class="flex items-start justify-between gap-4 py-3 text-sm">
                    <span class="text-slate-700">
                        <?= htmlspecialchars($item['name']) ?>
                        <span class="text-slate-400">× <?= (int) $item['quantity'] ?></span>
                    </span>
                    <strong class="whitespace-nowrap text-slate-900"><?= number_format((float) $item['subtotal'], 0, '', ' ') ?> грн</strong>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-4">
            <span class="text-sm font-medium text-slate-500">Разом</span>
            <span class="text-2xl font-black text-brand-600"><?= number_format((float) $totalAmount, 0, '', ' ') ?> грн</span>
        </div>

        <a href="<?= htmlspecialchars($baseUrl . '/cart') ?>"
           class="mt-6 inline-block text-sm font-medium text-slate-500 transition hover:text-slate-900">← Повернутися до кошика</a>
    </section>

    <!-- Форма -->
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft sm:p-8 lg:col-span-3">
        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-brand-600">Контактні дані</span>
        <h2 class="mt-2 text-xl font-bold text-slate-900">Дані покупця</h2>

        <form method="post" action="<?= htmlspecialchars($baseUrl . '/checkout') ?>" class="mt-6 space-y-5" novalidate>
            <?= Csrf::field() ?>

            <?php foreach ($fields as $name => $field): ?>
                <?php $hasError = isset($errors[$name]); ?>
                <label class="block text-sm font-medium text-slate-700">
                    <?= htmlspecialchars($field['label']) ?>
                    <input
                        type="<?= $field['type'] ?>"
                        name="<?= $name ?>"
                        autocomplete="<?= $field['autocomplete'] ?>"
                        value="<?= htmlspecialchars($old[$name] ?? '') ?>"
                        class="<?= $inputClass ?> <?= $hasError ? 'border-red-400' : 'border-slate-300' ?>"
                    >
                    <?php if ($hasError): ?>
                        <span class="mt-1 block text-xs font-normal text-red-600"><?= htmlspecialchars($errors[$name]) ?></span>
                    <?php endif; ?>
                </label>
            <?php endforeach; ?>

            <label class="block text-sm font-medium text-slate-700">
                Коментар <span class="font-normal text-slate-400">(необов’язково)</span>
                <textarea name="notes" rows="4" class="<?= $inputClass ?> border-slate-300"><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
            </label>

            <button type="submit"
                    class="w-full rounded-full bg-brand-600 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                Підтвердити замовлення
            </button>
        </form>
    </section>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
