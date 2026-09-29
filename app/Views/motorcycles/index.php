<?php
$filters = $filters ?? [];
$filterOptions = $filterOptions ?? ['brands' => [], 'types' => []];
$databaseAvailable = $databaseAvailable ?? false;
$motorcycles = $motorcycles ?? [];
require __DIR__ . '/../partials/header.php';
?>
<section class="rounded-[2rem] bg-white p-8 shadow-soft">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-600">Каталог</p>
            <h1 class="mt-2 text-4xl font-black tracking-tight">Знайди свій мотоцикл</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-500">Переглядай моделі, користуйся пошуком і обирай клас мотоцикла, щоб швидко знайти найкращий варіант.</p>
        </div>
        <div class="rounded-2xl bg-slate-100 px-5 py-4 text-sm text-slate-600">
            Знайдено моделей: <span class="font-bold text-slate-900"><?= count($motorcycles ?? []) ?></span>
        </div>
    </div>
</section>

<section class="mt-8 rounded-[2rem] border border-slate-200 bg-white p-6 shadow-soft">
    <div class="mb-5">
        <h2 class="text-2xl font-bold tracking-tight">Пошук і фільтрація</h2>
        <p class="mt-2 text-sm text-slate-500">Шукай за назвою, брендом, класом мотоцикла та обирай ціновий діапазон.</p>
    </div>
    <form method="get" action="<?= htmlspecialchars($catalogUrl) ?>" class="flex flex-col gap-4 lg:flex-row lg:items-center">
        <input class="w-full min-w-0 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-0 transition focus:border-brand-500 lg:w-[150px] lg:flex-none" type="text" name="search" placeholder="Назва або бренд" value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
        <select class="w-full min-w-0 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-brand-500 lg:flex-1" name="brand">
            <option value="">Усі бренди</option>
            <?php foreach (($filterOptions['brands'] ?? []) as $brand): ?>
                <option value="<?= htmlspecialchars($brand) ?>" <?= ($filters['brand'] ?? '') === $brand ? 'selected' : '' ?>><?= htmlspecialchars($brand) ?></option>
            <?php endforeach; ?>
        </select>
        <select class="w-full min-w-0 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-brand-500 lg:flex-1" name="type">
            <option value="">Усі класи</option>
            <?php foreach (($filterOptions['types'] ?? []) as $type): ?>
                <option value="<?= htmlspecialchars($type) ?>" <?= ($filters['type'] ?? '') === $type ? 'selected' : '' ?>><?= htmlspecialchars($type) ?></option>
            <?php endforeach; ?>
        </select>
        <input class="w-full min-w-0 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-0 transition focus:border-brand-500 lg:flex-1" type="number" name="min_price" placeholder="Ціна від" value="<?= htmlspecialchars($filters['min_price'] ?? '') ?>">
        <input class="w-full min-w-0 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-0 transition focus:border-brand-500 lg:flex-1" type="number" name="max_price" placeholder="Ціна до" value="<?= htmlspecialchars($filters['max_price'] ?? '') ?>">
        <div class="flex gap-3 lg:flex-none">
            <button type="submit" class="flex-1 whitespace-nowrap rounded-2xl bg-brand-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-600">Застосувати</button>
            <a href="<?= htmlspecialchars($catalogUrl) ?>" class="flex-1 whitespace-nowrap rounded-2xl border border-slate-200 px-5 py-3 text-center text-sm font-semibold text-slate-700 transition hover:border-slate-900 hover:text-slate-900">Скинути</a>
        </div>
    </form>
</section>

<section class="mt-8">
    <?php if (!$databaseAvailable): ?>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
            <h3 class="text-lg font-semibold">Товари тимчасово недоступні</h3>
            <p class="mt-2 text-sm">Каталог тимчасово недоступний. Зателефонуйте нам — підкажемо наявність моделей.</p>
        </div>
    <?php elseif (($motorcycles ?? []) === []): ?>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
            <h3 class="text-lg font-semibold">Товарів не знайдено</h3>
            <p class="mt-2 text-sm text-slate-500">Спробуйте змінити параметри пошуку або зв’яжіться з нами — підберемо модель індивідуально.</p>
        </div>
    <?php else: ?>
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            <?php foreach ($motorcycles as $motorcycle): ?>
                <article class="group flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-soft transition hover:-translate-y-1 hover:shadow-2xl">
                    <div class="flex h-[220px] items-center justify-center overflow-hidden rounded-2xl bg-slate-100 p-4">
                        <img src="<?= htmlspecialchars($motorcycle['image']) ?>" alt="<?= htmlspecialchars($motorcycle['name']) ?>" class="h-full w-full object-contain transition duration-300 group-hover:scale-[1.03]">
                    </div>
                    <div class="mt-5 flex flex-1 flex-col">
                        <h3 class="text-2xl font-bold tracking-tight text-slate-900"><?= htmlspecialchars($motorcycle['name']) ?></h3>
                        <p class="mt-2 text-sm text-slate-500"><?= htmlspecialchars($motorcycle['brand']) ?> • Клас: <?= htmlspecialchars($motorcycle['type']) ?></p>
                        <ul class="mt-4 space-y-2 text-sm text-slate-600">
                            <li>Об’єм: <?= htmlspecialchars((string) $motorcycle['engine_volume']) ?> см³</li>
                            <li>Потужність: <?= htmlspecialchars((string) $motorcycle['power']) ?> к.с.</li>
                            <li>Рік: <?= htmlspecialchars((string) $motorcycle['model_year']) ?></li>
                        </ul>
                        <p class="mt-5 text-3xl font-black text-brand-600"><?= number_format((float) $motorcycle['price'], 0, '', ' ') ?> грн</p>
                        <div class="mt-auto grid grid-cols-1 gap-3 pt-6 sm:grid-cols-3">
                            <a href="<?= htmlspecialchars($baseUrl . '/motorcycle?id=' . (int) $motorcycle['id']) ?>" class="rounded-full bg-brand-500 px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-brand-600">Детальніше</a>
                            <form method="post" action="<?= htmlspecialchars($baseUrl . '/cart/add') ?>" class="w-full">
                                <input type="hidden" name="motorcycle_id" value="<?= (int) $motorcycle['id'] ?>">
                                <button type="submit" class="w-full rounded-full bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">У кошик</button>
                            </form>
                            <form method="post" action="<?= htmlspecialchars($baseUrl . '/comparison/add') ?>" class="w-full">
                                <input type="hidden" name="motorcycle_id" value="<?= (int) $motorcycle['id'] ?>">
                                <button type="submit" class="w-full rounded-full border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-900 hover:text-slate-900">Порівняти</button>
                            </form>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../partials/footer.php'; ?>