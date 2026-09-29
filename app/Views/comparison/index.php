<?php
$motorcycles = $motorcycles ?? [];
require __DIR__ . '/../partials/header.php';
?>
<section class="rounded-[2rem] bg-white p-8 shadow-soft">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-600">Порівняння</p>
            <h1 class="mt-2 text-4xl font-black tracking-tight">Порівняйте обрані моделі</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-500">Додайте до трьох мотоциклів із каталогу або картки товару, щоб побачити різницю в характеристиках і ціні.</p>
        </div>
        <div class="rounded-2xl bg-slate-100 px-5 py-4 text-sm text-slate-600">
            У порівнянні: <span class="font-bold text-slate-900"><?= count($motorcycles) ?></span> / 3
        </div>
    </div>
</section>

<?php if ($motorcycles === []): ?>
    <section class="mt-8 rounded-[1.75rem] border border-slate-200 bg-white p-10 text-center shadow-soft">
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Список порівняння порожній</h2>
        <p class="mx-auto mt-3 max-w-lg text-sm leading-7 text-slate-500">Оберіть моделі в каталозі та натисніть «Порівняти» — вони з’являться тут у вигляді зручної таблиці.</p>
        <a class="mt-6 inline-flex rounded-full bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-brand-600" href="<?= htmlspecialchars($catalogUrl) ?>">Перейти до каталогу</a>
    </section>
<?php else: ?>
    <section class="mt-8 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        <?php foreach ($motorcycles as $motorcycle): ?>
            <article class="flex h-full flex-col rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-soft">
                <div class="flex h-[180px] items-center justify-center overflow-hidden rounded-2xl bg-slate-100 p-4">
                    <img src="<?= htmlspecialchars($motorcycle['image']) ?>" alt="<?= htmlspecialchars($motorcycle['name']) ?>" class="h-full w-full object-contain">
                </div>
                <h2 class="mt-4 text-xl font-bold tracking-tight text-slate-900"><?= htmlspecialchars($motorcycle['name']) ?></h2>
                <p class="mt-1 text-sm text-slate-500"><?= htmlspecialchars($motorcycle['brand']) ?> • <?= htmlspecialchars($motorcycle['type']) ?></p>
                <p class="mt-3 text-2xl font-black text-brand-600"><?= number_format((float) $motorcycle['price'], 0, '', ' ') ?> грн</p>
                <form method="post" action="<?= htmlspecialchars($baseUrl . '/comparison/remove') ?>" class="mt-auto pt-5">
                    <input type="hidden" name="motorcycle_id" value="<?= (int) $motorcycle['id'] ?>">
                    <button type="submit" class="w-full rounded-full border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-900 hover:text-slate-900">Прибрати з порівняння</button>
                </form>
            </article>
        <?php endforeach; ?>
    </section>

    <section class="mt-8 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-soft">
        <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Таблиця характеристик</h2>
                <p class="mt-1 text-sm text-slate-500">Порівняйте ключові параметри обраних мотоциклів.</p>
            </div>
            <form method="post" action="<?= htmlspecialchars($baseUrl . '/comparison/clear') ?>">
                <button type="submit" class="whitespace-nowrap rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-900 hover:text-slate-900">Очистити порівняння</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Характеристика</th>
                        <?php foreach ($motorcycles as $motorcycle): ?>
                            <th class="min-w-[180px] px-6 py-4 font-semibold text-slate-900"><?= htmlspecialchars($motorcycle['name']) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr>
                        <td class="px-6 py-4 font-semibold text-slate-500">Бренд</td>
                        <?php foreach ($motorcycles as $motorcycle): ?><td class="px-6 py-4"><?= htmlspecialchars($motorcycle['brand']) ?></td><?php endforeach; ?>
                    </tr>
                    <tr class="bg-slate-50/60">
                        <td class="px-6 py-4 font-semibold text-slate-500">Клас</td>
                        <?php foreach ($motorcycles as $motorcycle): ?><td class="px-6 py-4"><?= htmlspecialchars($motorcycle['type']) ?></td><?php endforeach; ?>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 font-semibold text-slate-500">Об’єм двигуна</td>
                        <?php foreach ($motorcycles as $motorcycle): ?><td class="px-6 py-4"><?= htmlspecialchars((string) $motorcycle['engine_volume']) ?> см³</td><?php endforeach; ?>
                    </tr>
                    <tr class="bg-slate-50/60">
                        <td class="px-6 py-4 font-semibold text-slate-500">Потужність</td>
                        <?php foreach ($motorcycles as $motorcycle): ?><td class="px-6 py-4"><?= htmlspecialchars((string) $motorcycle['power']) ?> к.с.</td><?php endforeach; ?>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 font-semibold text-slate-500">Рік випуску</td>
                        <?php foreach ($motorcycles as $motorcycle): ?><td class="px-6 py-4"><?= htmlspecialchars((string) $motorcycle['model_year']) ?></td><?php endforeach; ?>
                    </tr>
                    <tr class="bg-slate-50/60">
                        <td class="px-6 py-4 font-semibold text-slate-500">Ціна</td>
                        <?php foreach ($motorcycles as $motorcycle): ?><td class="px-6 py-4 font-bold text-brand-600"><?= number_format((float) $motorcycle['price'], 0, '', ' ') ?> грн</td><?php endforeach; ?>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 font-semibold text-slate-500">Дії</td>
                        <?php foreach ($motorcycles as $motorcycle): ?>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-2">
                                    <a href="<?= htmlspecialchars($baseUrl . '/motorcycle?id=' . (int) $motorcycle['id']) ?>" class="whitespace-nowrap rounded-full bg-brand-500 px-4 py-2 text-center text-xs font-semibold text-white transition hover:bg-brand-600">Детальніше</a>
                                    <form method="post" action="<?= htmlspecialchars($baseUrl . '/cart/add') ?>">
                                        <input type="hidden" name="motorcycle_id" value="<?= (int) $motorcycle['id'] ?>">
                                        <button type="submit" class="w-full whitespace-nowrap rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white transition hover:bg-slate-700">У кошик</button>
                                    </form>
                                </div>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
<?php endif; ?>
<?php require __DIR__ . '/../partials/footer.php'; ?>
