<?php
/**
 * Допоміжні функції для проєкту
 */

if (!function_exists('e')) {
    /**
     * Скорочення для htmlspecialchars.
     * Використовуємо, щоб захистити сайт від XSS-атак при виводі даних.
     */
    function e($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('current_year')) {
    /**
     * Повертає поточний рік для футера, щоб не міняти його вручну щороку
     */
    function current_year() {
        return date('Y');
    }
}
