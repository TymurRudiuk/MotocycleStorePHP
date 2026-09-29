<?php
$featuredMotorcycles = $featuredMotorcycles ?? [];
$databaseAvailable = $databaseAvailable ?? false;
require __DIR__ . '/partials/header.php';
?>
<section class="overflow-hidden rounded-[2rem] bg-gradient-to-br from-slate-950 via-slate-900 to-brand-900 px-8 py-14 text-white shadow-soft">
    <div class="grid gap-10 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
        <div>
            <span class="inline-flex rounded-full bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-brand-100">Мотосалон у Києві</span>
            <h1 class="mt-6 max-w-3xl text-4xl font-black tracking-tight sm:text-5xl">Мотоцикли для міста, траси та подорожей</h1>
            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-200">Офіційна гарантія, доставка по всій Україні та безкоштовна консультація з підбору моделі під ваш стиль їзди та бюджет.</p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="<?= htmlspecialchars($catalogUrl) ?>" class="rounded-full bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-brand-600">Перейти до каталогу</a>
                <a href="<?= htmlspecialchars($comparisonUrl) ?>" class="rounded-full border border-white/20 bg-white/10 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/20">Порівняти моделі</a>
            </div>
        </div>
        <div class="rounded-[1.5rem] border border-white/10 bg-white/10 p-6 backdrop-blur">
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-2xl bg-white/10 p-5">
                    <p class="text-sm text-slate-300">Гарантія</p>
                    <p class="mt-2 text-3xl font-bold">2 роки</p>
                    <p class="mt-1 text-sm text-slate-300">на нові мотоцикли</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-5">
                    <p class="text-sm text-slate-300">Доставка</p>
                    <p class="mt-2 text-3xl font-bold">2-4 дні</p>
                    <p class="mt-1 text-sm text-slate-300">по всій Україні</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mt-10">
    <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-600">Популярні моделі</p>
            <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Хіти продажів</h2>
        </div>
        <p class="max-w-xl text-sm leading-7 text-slate-500">Моделі, які наші клієнти обирають найчастіше. Усі мотоцикли в наявності та готові до відправлення.</p>
    </div>

    <?php if (!$databaseAvailable): ?>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 text-slate-700 shadow-soft">
            <h3 class="text-lg font-semibold">Товари тимчасово недоступні</h3>
            <p class="mt-2 text-sm text-slate-500">Завітайте трохи позніше або зв’яжіться з нами — підберемо модель індивідуально.</p>
        </div>
    <?php elseif ($featuredMotorcycles === []): ?>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 text-slate-700 shadow-soft">
            <h3 class="text-lg font-semibold">Незабаром тут з’явяться нові моделі</h3>
            <p class="mt-2 text-sm text-slate-500">Ми оновлюємо асортимент щотижня. Зателефонуйте, щоб дізнатися про наявність.</p>
        </div>
    <?php else: ?>
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            <?php foreach ($featuredMotorcycles as $motorcycle): ?>
                <article class="group flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-soft transition hover:-translate-y-1 hover:shadow-2xl">
                    <div class="flex h-[220px] items-center justify-center overflow-hidden rounded-2xl bg-slate-100 p-4">
                        <img src="<?= htmlspecialchars($motorcycle['image']) ?>" alt="<?= htmlspecialchars($motorcycle['name']) ?>" class="h-full w-full object-contain transition duration-300 group-hover:scale-[1.03]">
                    </div>
                    <div class="mt-5 flex flex-1 flex-col">
                        <h3 class="text-2xl font-bold tracking-tight text-slate-900"><?= htmlspecialchars($motorcycle['name']) ?></h3>
                        <p class="mt-2 text-sm text-slate-500"><?= htmlspecialchars($motorcycle['brand']) ?> • Клас: <?= htmlspecialchars($motorcycle['type']) ?></p>
                        <p class="mt-5 text-3xl font-black text-brand-600"><?= number_format((float) $motorcycle['price'], 0, '', ' ') ?> грн</p>
                        <div class="mt-auto grid grid-cols-1 gap-3 pt-6 sm:grid-cols-3">
                            <a href="<?= htmlspecialchars($baseUrl . '/motorcycle?id=' . (int) $motorcycle['id']) ?>" class="rounded-full bg-brand-500 px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-brand-600">Детальніше</a>
                            <form method="post" action="<?= htmlspecialchars($baseUrl . '/cart/add') ?>" class="w-full">
                                <?= Csrf::field() ?>
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

<section class="mt-10 grid gap-6 md:grid-cols-3">
    <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-soft">
        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-600">Гарантія</p>
        <h3 class="mt-3 text-xl font-bold">Офіційна гарантія 2 роки</h3>
        <p class="mt-3 text-sm leading-7 text-slate-500">Усі мотоцикли проходять предпродажну підготовку та обслуговуються в нашому сервісному центрі.</p>
    </article>
    <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-soft">
        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-600">Доставка</p>
        <h3 class="mt-3 text-xl font-bold">По всій Україні</h3>
        <p class="mt-3 text-sm leading-7 text-slate-500">Відправляємо мототехніку в будь-яке місто впродовж 2-4 днів або готуємо до самовивозу в салоні.</p>
    </article>
    <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-soft">
        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-600">Консультація</p>
        <h3 class="mt-3 text-xl font-bold">Допоможемо з вибором</h3>
        <p class="mt-3 text-sm leading-7 text-slate-500">Підберемо модель за досвідом, зростом та бюджетом, розкажемо про обслугування та екіпірування.</p>
    </article>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
