</main>
<footer class="border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-8 md:grid-cols-3">
            <div>
                <p class="text-lg font-black tracking-tight text-slate-900"><?= htmlspecialchars($appName ?? 'MotoCycle Store') ?></p>
                <p class="mt-3 text-sm leading-7 text-slate-500">Мотосалон із широким вибором мотоциклів, офіційною гарантією та доставкою по всій Україні.</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-600">Магазин</p>
                <ul class="mt-4 space-y-2 text-sm text-slate-600">
                    <li><a class="transition hover:text-slate-900" href="<?= htmlspecialchars($catalogUrl ?? '#') ?>">Каталог мотоциклів</a></li>
                    <li><a class="transition hover:text-slate-900" href="<?= htmlspecialchars($comparisonUrl ?? '#') ?>">Порівняння моделей</a></li>
                    <li><a class="transition hover:text-slate-900" href="<?= htmlspecialchars($aboutUrl ?? '#') ?>">Про нас</a></li>
                    <li><a class="transition hover:text-slate-900" href="<?= htmlspecialchars($contactsUrl ?? '#') ?>">Контакти</a></li>
                </ul>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-600">Контакти</p>
                <ul class="mt-4 space-y-2 text-sm text-slate-600">
                    <li>+38 (099) 123-45-67</li>
                    <li>info@motocyclestore.local</li>
                    <li>м. Київ, вул. Мотоциклетна, 15</li>
                    <li>Пн-Сб 09:00–19:00</li>
                </ul>
            </div>
        </div>
        <div class="mt-10 flex flex-col gap-3 border-t border-slate-200 pt-6 text-sm text-slate-500 md:flex-row md:items-center md:justify-between">
            <p>© <?= date('Y') ?> <?= htmlspecialchars($appName ?? 'MotoCycle Store') ?>. Всі права захищено.</p>
            <a class="text-slate-400 transition hover:text-slate-700" href="<?= htmlspecialchars($adminUrl ?? '#') ?>">Вхід для персоналу</a>
        </div>
    </div>
</footer>
</body>
</html>
