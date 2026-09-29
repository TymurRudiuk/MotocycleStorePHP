<?php

declare(strict_types=1);

return [
    'GET /' => ['HomeController', 'index'],
    'GET /motorcycles' => ['MotorcycleController', 'index'],
    'GET /motorcycle' => ['MotorcycleController', 'show'],
    'POST /reviews' => ['ReviewController', 'store'],
    'GET /comparison' => ['ComparisonController', 'index'],
    'POST /comparison/add' => ['ComparisonController', 'add'],
    'POST /comparison/remove' => ['ComparisonController', 'remove'],
    'POST /comparison/clear' => ['ComparisonController', 'clear'],
    'GET /about' => ['PageController', 'about'],
    'GET /contacts' => ['ContactController', 'index'],
    'POST /contacts' => ['ContactController', 'submit'],
    'GET /cart' => ['CartController', 'index'],
    'POST /cart/add' => ['CartController', 'add'],
    'POST /cart/update' => ['CartController', 'update'],
    'POST /cart/remove' => ['CartController', 'remove'],
    'GET /checkout' => ['OrderController', 'checkout'],
    'POST /checkout' => ['OrderController', 'place'],
    'GET /admin/login' => ['AuthController', 'loginForm'],
    'POST /admin/login' => ['AuthController', 'login'],
    'POST /admin/logout' => ['AuthController', 'logout'],
    'GET /admin' => ['AdminController', 'index'],
    'GET /admin/motorcycles/edit' => ['AdminController', 'editMotorcycle'],
    'POST /admin/motorcycles' => ['AdminController', 'storeMotorcycle'],
    'POST /admin/motorcycles/update' => ['AdminController', 'updateMotorcycle'],
    'POST /admin/motorcycles/delete' => ['AdminController', 'deleteMotorcycle'],
    'POST /admin/orders/status' => ['AdminController', 'updateOrderStatus'],
    'POST /admin/password' => ['AdminController', 'changePassword'],
];
