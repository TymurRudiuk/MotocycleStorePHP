<?php
$title = $title ?? 'Модерація відгуків';
$reviews = $reviews ?? [];
require __DIR__ . '/../partials/header.php';
?>
<section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft sm:p-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <span class="text-xs font-semibold uppercase tracking-[0.25em] text-brand-600">Адмін-панель</span>
            <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900">Модерація відгуків</h1>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="<?= htmlspecialchars($baseUrl . '/admin') ?>"
               class="rounded-full border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                ← До адмін-панелі
            </a>
            <form method="post" action="<?= htmlspecialchars($baseUrl . '/admin/logout') ?>">
                <?= Csrf::field() ?>
                <button type="submit"
                        class="rounded-full bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700">
                    Вийти
                </button>
            </form>
        </div>
    </div>

    <?php if (!empty($successMessage)): ?>
        <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            <?= htmlspecialchars($successMessage) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($reviews)): ?>
        <p class="mt-8 text-slate-500">Немає відгуків, що очікують на схвалення.</p>
    <?php else: ?>
        <div class="mt-8 overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="px-3 py-3 font-semibold">Автор</th>
                        <th class="px-3 py-3 font-semibold">Рейтинг</th>
                        <th class="px-3 py-3 font-semibold">Коментар</th>
                        <th class="px-3 py-3 font-semibold">Мотоцикл</th>
                        <th class="px-3 py-3 font-semibold">Дата</th>
                        <th class="px-3 py-3 font-semibold">Дії</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($reviews as $review): ?>
                        <tr class="align-top">
                            <td class="px-3 py-4 font-medium text-slate-900"><?= htmlspecialchars($review['author_name']) ?></td>
                            <td class="px-3 py-4 text-slate-700"><?= (int) $review['rating'] ?>/5</td>
                            <td class="max-w-xs whitespace-pre-line px-3 py-4 text-slate-700"><?= htmlspecialchars($review['comment']) ?></td>
                            <td class="px-3 py-4 text-slate-700"><?= htmlspecialchars($review['motorcycle_name']) ?></td>
                            <td class="whitespace-nowrap px-3 py-4 text-slate-500"><?= htmlspecialchars($review['created_at']) ?></td>
                            <td class="px-3 py-4">
                                <div class="flex gap-2">
                                    <form method="post" action="<?= htmlspecialchars($baseUrl . '/admin/reviews/approve') ?>">
                                        <?= Csrf::field() ?>
                                        <input type="hidden" name="id" value="<?= (int) $review['id'] ?>">
                                        <button type="submit"
                                                class="rounded-full bg-brand-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-brand-700">
                                            Схвалити
                                        </button>
                                    </form>
                                    <form method="post" action="<?= htmlspecialchars($baseUrl . '/admin/reviews/delete') ?>"
                                          onsubmit="return confirm('Видалити цей відгук?');">
                                        <?= Csrf::field() ?>
                                        <input type="hidden" name="id" value="<?= (int) $review['id'] ?>">
                                        <button type="submit"
                                                class="rounded-full border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                                            Видалити
                                        </button>
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
<?php require __DIR__ . '/../partials/footer.php'; ?>
