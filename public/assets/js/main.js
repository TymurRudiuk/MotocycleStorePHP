/**
 * Основний файл JavaScript для MotoCycle Store
 */

document.addEventListener('DOMContentLoaded', () => {
    initLiveCatalogFilter();
    initContactFormValidation();
});

/**
 * Жива фільтрація каталогу мотоциклів
 * Дозволяє фільтрувати картки товарів без перезавантаження сторінки
 */
function initLiveCatalogFilter() {
    const searchInput = document.querySelector('input[name="search"]');
    const brandSelect = document.querySelector('select[name="brand"]');
    const typeSelect = document.querySelector('select[name="type"]');
    const minPriceInput = document.querySelector('input[name="min_price"]');
    const maxPriceInput = document.querySelector('input[name="max_price"]');
    const grid = document.getElementById('motorcycle-grid');
    const noResultsMessage = document.getElementById('no-results-message');

    if (!grid) return;

    const filterCards = () => {
        const searchVal = searchInput?.value.toLowerCase() || '';
        const brandVal = brandSelect?.value.toLowerCase() || '';
        const typeVal = typeSelect?.value.toLowerCase() || '';
        const minPrice = parseFloat(minPriceInput?.value) || 0;
        const maxPrice = parseFloat(maxPriceInput?.value) || Infinity;

        const cards = grid.querySelectorAll('.motorcycle-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.dataset.name || '';
            const brand = card.dataset.brand || '';
            const type = card.dataset.type || '';
            const price = parseFloat(card.dataset.price) || 0;

            const matchesSearch = !searchVal || name.includes(searchVal) || brand.includes(searchVal);
            const matchesBrand = !brandVal || brand === brandVal;
            const matchesType = !typeVal || type === typeVal;
            const matchesPrice = price >= minPrice && price <= maxPrice;

            if (matchesSearch && matchesBrand && matchesType && matchesPrice) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Керування повідомленням про відсутність результатів
        if (noResultsMessage) {
            noResultsMessage.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        // Оновлення лічильника знайдених моделей
        const countSpan = document.querySelector('section:first-of-type span.font-bold');
        if (countSpan) {
            countSpan.textContent = visibleCount;
        }
    };

    [searchInput, brandSelect, typeSelect, minPriceInput, maxPriceInput].forEach(el => {
        el?.addEventListener('input', filterCards);
    });
}

/**
 * Валідація форми контактів
 * Перевіряє коректність даних перед відправкою на сервер
 */
function initContactFormValidation() {
    const form = document.querySelector('form[action*="/contacts"]');
    if (!form) return;

    form.addEventListener('submit', (e) => {
        let isValid = true;
        const inputs = form.querySelectorAll('input, textarea');

        inputs.forEach(input => {
            // Очищення попередніх помилок
            const errorElement = input.nextElementSibling;
            if (errorElement && errorElement.classList.contains('text-red-600')) {
                errorElement.remove();
            }

            // Базова перевірка на порожнечу (крім повідомлення, якщо воно необов'язкове)
            if (input.name !== 'message' && !input.value.trim()) {
                showError(input, 'Це поле обов’язкове для заповнення');
                isValid = false;
            }

            // Валідація Email
            if (input.type === 'email' && input.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(input.value)) {
                    showError(input, 'Будь ласка, введіть коректну адресу email');
                    isValid = false;
                }
            }

            // Валідація телефону (мінімальна перевірка)
            if (input.name === 'phone' && input.value && input.value.length < 7) {
                showError(input, 'Номер телефону занадто короткий');
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            // Скрол до першої помилки
            const firstError = form.querySelector('.text-red-600');
            firstError?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    function showError(input, message) {
        const p = document.createElement('p');
        p.className = 'mt-2 text-sm text-red-600';
        p.textContent = message;
        input.after(p);
        input.classList.add('border-red-500');
        input.addEventListener('input', () => {
            input.classList.remove('border-red-500');
        }, { once: true });
    }
}
