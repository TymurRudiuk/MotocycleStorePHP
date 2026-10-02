# MotoCycle Store

Інтернет-магазин мотоциклів на PHP (MVC без фреймворку) + MySQL.

## Запуск (WAMP)

1. Проєкт має лежати в `C:\wamp64\www\MotocycleStorePHP`
2. Переконайся, що в Apache увімкнений модуль `rewrite_module`, а для `www` стоїть `AllowOverride All`
3. Створи базу даних `motorcycle_store` (кодування `utf8mb4`)
4. Виконай SQL у порядку, вказаному нижче
5. Відкрий `http://localhost/MotocycleStorePHP/public/`

## Порядок виконання SQL

Спочатку міграції:

1. `database/migrations/001_create_motorcycles_and_contacts.sql`
2. `database/migrations/002_create_orders.sql`
3. `database/migrations/003_create_admins.sql`
4. `database/migrations/004_create_reviews.sql`
5. `database/migrations/005_create_motorcycle_classes.sql`

Потім сидери:

1. `database/seeders/002_seed_admin.sql`
2. `database/seeders/004_seed_motorcycle_classes.sql`
3. `database/seeders/005_seed_motorcycles_by_classes.sql`
4. `database/seeders/006_seed_reviews.sql`

> Порядок важливий: класи мотоциклів має бути створено до додавання товарів,
> а товари — до додавання відгуків (через зовнішні ключі).

## Структура БД

- `motorcycle_classes` — класи мотоциклів (Sport, Naked, Enduro тощо)
- `motorcycles` — товари, пов’язані з класом через `class_id`
- `reviews` — відгуки, пов’язані з товаром через `motorcycle_id`
- `orders` / `order_items` — замовлення та їх позиції
- `contact_requests` — звернення з форми контактів
- `admins` — адміністратори (пароль зберігається як хеш)

## Доступ до адмін-панелі

Адреса: `http://localhost/MotocycleStorePHP/public/admin`

- Логін: `admin`
- Пароль: 

Пароль можна змінити в самій адмін-панелі (розділ «Безпека»).

## Конфігурація

Параметри підключення до БД і базовий URL — у `config/app.php`.
