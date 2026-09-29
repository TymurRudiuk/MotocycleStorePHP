<?php
$items = $items ?? [];
$totalAmount = $totalAmount ?? 0;
$checkoutUrl = rtrim($baseUrl ?? '', '/') . '/checkout';
require __DIR__ . '/../partials/header.php';
?>
<section class="rounded-[2rem] bg-white p-8 shadow-soft">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-600">Кошик</p>
            <h1 class="mt-2 text-4xl font-black tracking-tight">Ваші обрані мотоцикли</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-500">Перевірте список товарів, змініть кількість і перейдіть до оформлення замовлення.</p>
        </div>
        <div class="rounded-2xl bg-slate-100 px-5 py-4 text-sm text-slate-600">
            Загальна сума: <span class="font-bold text-slate-900"><?= number_format((float) $totalAmount, 0, '', ' ') ?> грн</span>
        </div>
    </div>
</section>

<?php if ($items === []): ?>
    <section class="mt-8 rounded-[1.75rem] border border-slate-200 bg-white p-8 text-center shadow-soft">
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Кошик порожній</h2>
        <p class="mt-3 text-sm leading-7 text-slate-500">Додайте мотоцикли з каталогу, щоб оформити замовлення.</p>
        <a class="mt-6 inline-flex rounded-full bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-brand-600" href="<?= htmlspecialchars($catalogUrl) ?>">Перейти до каталогу</a>
    </section>
<?php else: ?>
    <form method="post" action="<?= htmlspecialchars($baseUrl . '/cart/update') ?>" class="mt-8 space-y-6">
        <?= Csrf::field() ?>
        <section class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-soft">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Мотоцикл</th>
                            <th class="px-6 py-4 font-semibold">Ціна</th>
                            <th class="px-6 py-4 font-semibold">Кількість</th>
                            <th class="px-6 py-4 font-semibold">Сума</th>
                            <th class="px-6 py-4 font-semibold">Дія</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white text-slate-700">
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td class="px-6 py-5 font-semibold text-slate-900"><?= htmlspecialchars($item['name']) ?></td>
                                <td class="px-6 py-5"><?= number_format((float) $item['price'], 0, '', ' ') ?> грн</td>
                                <td class="px-6 py-5">
                                    <input class="w-24 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 outline-none transition focus:border-brand-500" type="number" min="1" name="quantities[<?= (int) $item['id'] ?>]" value="<?= (int) $item['quantity'] ?>">
                                </td>
                                <td class="px-6 py-5 font-semibold text-brand-600"><?= number_format((float) $item['subtotal'], 0, '', ' ') ?> грн</td>
                                <td class="px-6 py-5">
                                    <button type="submit" formaction="<?= htmlspecialchars($baseUrl . '/cart/remove') ?>" name="motorcycle_id" value="<?= (int) $item['id'] ?>" class="rounded-full border border-slate-300 px-4 py-2 font-semibold text-slate-700 transition hover:border-slate-900 hover:text-slate-900">Видалити</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <section class="flex flex-col gap-4 rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-soft md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm text-slate-500">Після оновлення кількості натисни відповідну кнопку, щоб зберегти зміни.</p>
                <p class="mt-2 text-3xl font-black text-brand-600">Разом: <?= number_format((float) $totalAmount, 0, '', ' ') ?> грн</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-900 hover:text-slate-900">Оновити кошик</button>
                <a href="<?= htmlspecialchars($checkoutUrl) ?>" class="rounded-full bg-brand-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-600">Оформити замовлення</a>
            </div>
        </section>
    </form>
<?php endif; ?>
<?php require __DIR__ . '/../partials/footer.php'; ?>
