<?php
$baseUrl = rtrim($baseUrl ?? '', '/');
$appName = $appName ?? 'MotoCycle Store';
$title = $title ?? 'MotoCycle Store';
$homeUrl = $baseUrl === '' ? '/' : $baseUrl . '/';
$catalogUrl = $baseUrl . '/motorcycles';
$comparisonUrl = $baseUrl . '/comparison';
$aboutUrl = $baseUrl . '/about';
$cartUrl = $baseUrl . '/cart';
$contactsUrl = $baseUrl . '/contacts';
$adminUrl = $baseUrl . '/admin';

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

if ($baseUrl !== '' && str_starts_with($currentPath, $baseUrl)) {
    $currentPath = substr($currentPath, strlen($baseUrl));
}

$currentPath = rtrim($currentPath, '/');
$currentPath = $currentPath === '' ? '/' : $currentPath;

$navItems = [
    ['label' => 'Головна', 'url' => $homeUrl, 'paths' => ['/']],
    ['label' => 'Каталог', 'url' => $catalogUrl, 'paths' => ['/motorcycles', '/motorcycle']],
    ['label' => 'Порівняння', 'url' => $comparisonUrl, 'paths' => ['/comparison']],
    ['label' => 'Про нас', 'url' => $aboutUrl, 'paths' => ['/about']],
    ['label' => 'Контакти', 'url' => $contactsUrl, 'paths' => ['/contacts']],
    ['label' => 'Кошик', 'url' => $cartUrl, 'paths' => ['/cart', '/checkout']],
];
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> | <?= htmlspecialchars($appName) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            900: '#7f1d1d'
                        }
                    },
                    boxShadow: {
                        soft: '0 10px 30px rgba(15, 23, 42, 0.08)'
                    }
                }
            }
        };
    </script>
    <style>body { font-family: Inter, Arial, sans-serif; }</style>
    <script src="/assets/js/main.js" defer></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
<header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/85 backdrop-blur">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
        <div>
            <a href="<?= htmlspecialchars($homeUrl) ?>" class="text-xl font-black tracking-tight text-slate-900"><?= htmlspecialchars($appName) ?></a>
            <p class="text-xs text-slate-500">Мотоцикли та екіпірування • +38 (099) 123-45-67</p>
        </div>
        <nav class="flex flex-wrap items-center gap-2 text-sm font-medium">
            <?php foreach ($navItems as $navItem): ?>
                <?php $isActive = in_array($currentPath, $navItem['paths'], true); ?>
                <a
                    href="<?= htmlspecialchars($navItem['url']) ?>"
                    class="rounded-full px-4 py-2 transition <?= $isActive ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>"
                    <?= $isActive ? 'aria-current="page"' : '' ?>
                ><?= htmlspecialchars($navItem['label']) ?></a>
            <?php endforeach; ?>
        </nav>
    </div>
</header>
<main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
