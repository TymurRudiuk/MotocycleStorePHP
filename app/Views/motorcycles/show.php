<?php
$motorcycle = $motorcycle ?? [];
$reviews = $reviews ?? [];
$reviewErrors = $reviewErrors ?? [];
$reviewOld = $reviewOld ?? [];
$reviewSuccess = $reviewSuccess ?? null;
$averageRating = $averageRating ?? 0;
require __DIR__ . '/../partials/header.php';
?>
<section class="grid gap-8 rounded-[2rem] border border-slate-200 bg-white p-6 shadow-soft lg:grid-cols-2 lg:p-10">
    <div class="flex items-center justify-center rounded-2xl bg-slate-100 p-6">
        <img src="<?= htmlspecialchars($motorcycle['image']) ?>" alt="<?= htmlspecialchars($motorcycle['name']) ?>" class="max-h-[420px] w-full object-contain">
    </div>

    <div class="flex flex-col justify-center">
        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-600">Картка товару</p>
        <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900"><?= htmlspecialchars($motorcycle['name']) ?></h1>
        <p class="mt-2 text-sm text-slate-500"><?= htmlspecialchars($motorcycle['brand']) ?> • Клас: <?= htmlspecialchars($motorcycle['type']) ?></p>
        <p class="mt-3 text-sm text-slate-600">Середня оцінка: <strong><?= htmlspecialchars((string) $averageRating) ?>/5</strong></p>
        <p class="mt-4 text-4xl font-black text-brand-600"><?= number_format((float) $motorcycle['price'], 0, '', ' ') ?> грн</p>
        <p class="mt-4 text-sm leading-7 text-slate-600"><?= htmlspecialchars($motorcycle['description']) ?></p>

        <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
            <div class="rounded-2xl bg-slate-100 p-4 text-sm"><strong class="block text-slate-900">Об’єм двигуна</strong><span class="text-slate-600"><?= htmlspecialchars((string) $motorcycle['engine_volume']) ?> см³</span></div>
            <div class="rounded-2xl bg-slate-100 p-4 text-sm"><strong class="block text-slate-900">Потужність</strong><span class="text-slate-600"><?= htmlspecialchars((string) $motorcycle['power']) ?> к.с.</span></div>
            <div class="rounded-2xl bg-slate-100 p-4 text-sm"><strong class="block text-slate-900">Рік випуску</strong><span class="text-slate-600"><?= htmlspecialchars((string) $motorcycle['model_year']) ?></span></div>
            <div class="rounded-2xl bg-slate-100 p-4 text-sm"><strong class="block text-slate-900">Клас</strong><span class="text-slate-600"><?= htmlspecialchars($motorcycle['type']) ?></span></div>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <form method="post" action="<?= htmlspecialchars($baseUrl . '/cart/add') ?>">
                <?= Csrf::field() ?>
                <input type="hidden" name="motorcycle_id" value="<?= (int) $motorcycle['id'] ?>">
                <button type="submit" class="rounded-full bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-brand-600">Додати в кошик</button>
            </form>
            <form method="post" action="<?= htmlspecialchars($baseUrl . '/comparison/add') ?>">
                <?= Csrf::field() ?>
                <input type="hidden" name="motorcycle_id" value="<?= (int) $motorcycle['id'] ?>">
                <button type="submit" class="rounded-full border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-900 hover:text-slate-900">Порівняти</button>
            </form>
            <a href="<?= htmlspecialchars($contactsUrl) ?>" class="rounded-full border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-900 hover:text-slate-900">Замовити консультацію</a>
            <a href="<?= htmlspecialchars($catalogUrl) ?>" class="rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">Назад до каталогу</a>
        </div>
    </div>
</section>

<section class="mt-10 grid gap-6 lg:grid-cols-2">
    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-soft">
        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-600">Відгуки</p>
        <h2 class="mt-2 text-2xl font-bold text-slate-900">Відгуки покупців</h2>
        <?php if ($reviews === []): ?>
            <p class="mt-4 text-sm text-slate-500">Відгуків поки немає. Будьте першим, хто поділиться враженнями.</p>
        <?php else: ?>
            <div class="mt-4 space-y-4">
                <?php foreach ($reviews as $review): ?>
                    <article class="rounded-2xl bg-slate-100 p-4">
                        <div class="flex items-center justify-between text-sm">
                            <strong class="text-slate-900"><?= htmlspecialchars($review['author_name']) ?></strong>
                            <span class="text-brand-600"><?= (int) $review['rating'] ?>/5</span>
                        </div>
                        <p class="mt-2 text-sm text-slate-600"><?= htmlspecialchars($review['comment']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-soft">
        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-600">Ваш відгук</p>
        <h2 class="mt-2 text-2xl font-bold text-slate-900">Залишити відгук</h2>
        <?php if ($reviewSuccess !== null): ?>
            <div class="mt-4 rounded-2xl bg-green-50 p-4 text-sm text-green-700"><?= htmlspecialchars($reviewSuccess) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= htmlspecialchars($baseUrl . '/reviews') ?>" class="mt-4 space-y-4">
            <?= Csrf::field() ?>
            <input type="hidden" name="motorcycle_id" value="<?= (int) $motorcycle['id'] ?>">

            <label class="block text-sm font-medium text-slate-700">Ім’я
                <input type="text" name="author_name" value="<?= htmlspecialchars($reviewOld['author_name'] ?? '') ?>" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-2 text-sm">
                <?php if (isset($reviewErrors['author_name'])): ?><span class="mt-1 block text-xs text-red-600"><?= htmlspecialchars($reviewErrors['author_name']) ?></span><?php endif; ?>
            </label>

            <label class="block text-sm font-medium text-slate-700">Оцінка
                <select name="rating" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-2 text-sm">
                    <option value="">Оберіть оцінку</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>" <?= (int) ($reviewOld['rating'] ?? 0) === $i ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
                <?php if (isset($reviewErrors['rating'])): ?><span class="mt-1 block text-xs text-red-600"><?= htmlspecialchars($reviewErrors['rating']) ?></span><?php endif; ?>
            </label>

            <label class="block text-sm font-medium text-slate-700">Текст відгуку
                <textarea name="comment" rows="5" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-2 text-sm"><?= htmlspecialchars($reviewOld['comment'] ?? '') ?></textarea>
                <?php if (isset($reviewErrors['comment'])): ?><span class="mt-1 block text-xs text-red-600"><?= htmlspecialchars($reviewErrors['comment']) ?></span><?php endif; ?>
            </label>

            <button type="submit" class="rounded-full bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-brand-600">Надіслати відгук</button>
        </form>
    </div>
</section>
<?php require __DIR__ . '/../partials/footer.php'; ?>
