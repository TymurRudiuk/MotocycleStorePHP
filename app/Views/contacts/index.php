<?php
$errors = $errors ?? [];
$old = $old ?? [];
$successMessage = $successMessage ?? null;
require __DIR__ . '/../partials/header.php';
?>
<section class="rounded-[2rem] bg-white p-8 shadow-soft">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-600">Контакти</p>
            <h1 class="mt-2 text-4xl font-black tracking-tight">Зв’яжіться з нами</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-500">Ми допоможемо підібрати мотоцикл, пояснимо характеристики моделей та підкажемо оптимальний варіант для покупки.</p>
        </div>
        <div class="rounded-2xl bg-brand-50 px-5 py-4 text-sm text-brand-700">
            Відповідаємо щодня з <span class="font-bold">09:00</span> до <span class="font-bold">19:00</span>
        </div>
    </div>
</section>

<section class="mt-8 grid gap-6 lg:grid-cols-2">
    <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-soft">
        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-600">Контактна інформація</p>
        <h2 class="mt-3 text-2xl font-bold tracking-tight">Як нас знайти</h2>
        <div class="mt-5 space-y-4 text-sm leading-7 text-slate-600">
            <p><span class="font-semibold text-slate-900">Телефон:</span> +38 (099) 123-45-67</p>
            <p><span class="font-semibold text-slate-900">Email:</span> info@motocyclestore.local</p>
            <p><span class="font-semibold text-slate-900">Адреса:</span> м. Київ, вул. Мотоциклетна, 15</p>
            <p><span class="font-semibold text-slate-900">Графік:</span> Пн-Сб 09:00–19:00</p>
        </div>
    </article>

    <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-soft">
        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-600">Форма звернення</p>
        <h2 class="mt-3 text-2xl font-bold tracking-tight">Напишіть нам</h2>
        <?php if ($successMessage !== null): ?>
            <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= htmlspecialchars($baseUrl . '/contacts') ?>" class="mt-5 space-y-4">
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Ім’я</label>
                <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-brand-500" type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>">
                <?php if (isset($errors['name'])): ?><p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($errors['name']) ?></p><?php endif; ?>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Телефон</label>
                <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-brand-500" type="text" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                <?php if (isset($errors['phone'])): ?><p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($errors['phone']) ?></p><?php endif; ?>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-brand-500" type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                <?php if (isset($errors['email'])): ?><p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($errors['email']) ?></p><?php endif; ?>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Повідомлення</label>
                <textarea class="min-h-[140px] w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-brand-500" name="message"><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
                <?php if (isset($errors['message'])): ?><p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($errors['message']) ?></p><?php endif; ?>
            </div>
            <button type="submit" class="rounded-full bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-brand-600">Надіслати</button>
        </form>
    </article>
</section>
<?php require __DIR__ . '/../partials/footer.php'; ?>