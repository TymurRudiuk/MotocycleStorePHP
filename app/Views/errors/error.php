<?php
$code = $code ?? 404;
$heading = $heading ?? 'Сторінку не знайдено';
$message = $message ?? 'Можливо, ця сторінка була переміщена або більше не існує.';
require __DIR__ . '/../partials/header.php';
?>
<section class="rounded-[2rem] bg-white p-12 text-center shadow-soft">
    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-600">Помилка <?= (int) $code ?></p>
    <h1 class="mt-4 text-4xl font-black tracking-tight text-slate-900"><?= htmlspecialchars($heading) ?></h1>
    <p class="mx-auto mt-4 max-w-xl text-sm leading-7 text-slate-500"><?= htmlspecialchars($message) ?></p>
    <div class="mt-8 flex flex-wrap justify-center gap-3">
        <a href="<?= htmlspecialchars($homeUrl) ?>" class="rounded-full bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-brand-600">На головну</a>
        <a href="<?= htmlspecialchars($catalogUrl) ?>" class="rounded-full border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-900 hover:text-slate-900">До каталогу</a>
    </div>
</section>
<?php require __DIR__ . '/../partials/footer.php'; ?>
