<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/AdminUserRepository.php';

class AuthController extends BaseController
{
    public function loginForm(): void
    {
        $this->view('auth/login', [
            'title' => 'Вхід адміністратора',
            'appName' => $this->config['app_name'] ?? 'MotoCycle Store',
            'baseUrl' => $this->config['base_url'] ?? '',
            'error' => $_SESSION['auth_error'] ?? null,
        ]);

        unset($_SESSION['auth_error']);
    }

    public function login(): void
    {
        $login = trim($_POST['login'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $repository = new AdminUserRepository($this->config);
        $admin = $repository->findByLogin($login);

        if ($admin !== null && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['is_admin'] = true;
            $_SESSION['admin_id'] = (int) $admin['id'];
            $_SESSION['admin_login'] = $admin['login'];
            $this->redirect('/admin');
        }

        $_SESSION['auth_error'] = 'Неправильний логін або пароль.';
        $this->redirect('/admin/login');
    }

    public function logout(): void
    {
        unset($_SESSION['is_admin'], $_SESSION['admin_id'], $_SESSION['admin_login']);
        session_regenerate_id(true);
        $this->redirect('/');
    }
}
