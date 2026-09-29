<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/MotorcycleRepository.php';
require_once __DIR__ . '/../Models/AdminMotorcycleRepository.php';
require_once __DIR__ . '/../Models/OrderRepository.php';
require_once __DIR__ . '/../Models/AdminUserRepository.php';
require_once __DIR__ . '/../Models/MotorcycleClassRepository.php';
require_once __DIR__ . '/../Models/ReviewRepository.php';

class AdminController extends BaseController
{
    private MotorcycleRepository $motorcycleRepository;
    private AdminMotorcycleRepository $adminMotorcycleRepository;
    private OrderRepository $orderRepository;
    private AdminUserRepository $adminUserRepository;
    private MotorcycleClassRepository $motorcycleClassRepository;
    private ReviewRepository $reviewRepository;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->motorcycleRepository = new MotorcycleRepository($config);
        $this->adminMotorcycleRepository = new AdminMotorcycleRepository($config);
        $this->orderRepository = new OrderRepository($config);
        $this->adminUserRepository = new AdminUserRepository($config);
        $this->motorcycleClassRepository = new MotorcycleClassRepository($config);
        $this->reviewRepository = new ReviewRepository($config);
    }

    public function index(): void
    {
        $this->ensureAuthenticated();
        $orders = $this->orderRepository->getAll();

        foreach ($orders as &$order) {
            $order['items'] = $this->orderRepository->getItemsByOrderId((int) $order['id']);
        }
        unset($order);

        $orderStats = $this->orderRepository->getStats();

        $this->view('admin/index', [
            'title' => 'Адмін-панель',
            'appName' => $this->config['app_name'] ?? 'MotoCycle Store',
            'baseUrl' => $this->config['base_url'] ?? '',
            'motorcycles' => $this->motorcycleRepository->getAll(),
            'orders' => $orders,
            'stats' => [
                'motorcycles_count' => $this->adminMotorcycleRepository->countAll(),
                'orders_count' => $orderStats['orders_count'],
                'new_orders_count' => $orderStats['new_orders_count'],
                'total_revenue' => $orderStats['total_revenue'],
            ],
            'errors' => $_SESSION['admin_errors'] ?? [],
            'old' => $_SESSION['admin_old'] ?? [],
            'passwordErrors' => $_SESSION['admin_password_errors'] ?? [],
            'editMotorcycle' => $_SESSION['admin_edit_motorcycle'] ?? null,
            'successMessage' => $_SESSION['admin_success'] ?? null,
            'adminLogin' => $_SESSION['admin_login'] ?? 'admin',
            'motorcycleClasses' => $this->motorcycleClassRepository->getAll(),
        ]);

        unset($_SESSION['admin_errors'], $_SESSION['admin_old'], $_SESSION['admin_password_errors'], $_SESSION['admin_edit_motorcycle'], $_SESSION['admin_success']);
    }

    public function storeMotorcycle(): void
    {
        $this->ensureAuthenticated();
        $imagePath = $this->handleImageUpload(
            $_FILES['image_file'] ?? null,
            trim($_POST['image'] ?? '') !== '' ? trim($_POST['image']) : trim($_POST['existing_image'] ?? '')
        );
        $type = trim($_POST['type'] ?? '');
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'brand' => trim($_POST['brand'] ?? ''),
            'type' => $type,
            'class_id' => $this->motorcycleClassRepository->findIdByName($type),
            'engine_volume' => (int) ($_POST['engine_volume'] ?? 0),
            'power' => (int) ($_POST['power'] ?? 0),
            'price' => (float) ($_POST['price'] ?? 0),
            'model_year' => (int) ($_POST['model_year'] ?? 0),
            'image' => $imagePath,
            'description' => trim($_POST['description'] ?? ''),
        ];

        $errors = $this->validateMotorcycle($data);

        if ($errors !== []) {
            $_SESSION['admin_errors'] = $errors;
            $_SESSION['admin_old'] = $data;
            $this->redirect('/admin');
        }

        $saved = $this->adminMotorcycleRepository->create($data);
        $_SESSION['admin_success'] = $saved
            ? 'Новий мотоцикл успішно додано.'
            : 'Не вдалося зберегти мотоцикл. Перевірте підключення до бази даних.';

        $this->redirect('/admin');
    }

    public function editMotorcycle(): void
    {
        $this->ensureAuthenticated();

        $id = (int) ($_GET['id'] ?? 0);
        $motorcycle = $this->motorcycleRepository->findById($id);

        if ($motorcycle === null) {
            $_SESSION['admin_success'] = 'Мотоцикл не знайдено.';
            $this->redirect('/admin');
        }

        $_SESSION['admin_edit_motorcycle'] = $motorcycle;
        $this->redirect('/admin');
    }

    public function updateMotorcycle(): void
    {
        $this->ensureAuthenticated();

        $id = (int) ($_POST['id'] ?? 0);
        $imagePath = $this->handleImageUpload(
            $_FILES['image_file'] ?? null,
            trim($_POST['image'] ?? '') !== '' ? trim($_POST['image']) : trim($_POST['existing_image'] ?? '')
        );
        $type = trim($_POST['type'] ?? '');
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'brand' => trim($_POST['brand'] ?? ''),
            'type' => $type,
            'class_id' => $this->motorcycleClassRepository->findIdByName($type),
            'engine_volume' => (int) ($_POST['engine_volume'] ?? 0),
            'power' => (int) ($_POST['power'] ?? 0),
            'price' => (float) ($_POST['price'] ?? 0),
            'model_year' => (int) ($_POST['model_year'] ?? 0),
            'image' => $imagePath,
            'description' => trim($_POST['description'] ?? ''),
        ];

        $errors = $this->validateMotorcycle($data);

        if ($errors !== []) {
            $_SESSION['admin_errors'] = $errors;
            $_SESSION['admin_old'] = $data;
            $_SESSION['admin_edit_motorcycle'] = array_merge($data, ['id' => $id]);
            $this->redirect('/admin');
        }

        $updated = $this->adminMotorcycleRepository->update($id, $data);
        $_SESSION['admin_success'] = $updated
            ? 'Мотоцикл успішно оновлено.'
            : 'Не вдалося оновити мотоцикл.';
        $this->redirect('/admin');
    }

    public function deleteMotorcycle(): void
    {
        $this->ensureAuthenticated();

        $id = (int) ($_POST['id'] ?? 0);
        $this->adminMotorcycleRepository->delete($id);
        $_SESSION['admin_success'] = 'Мотоцикл видалено.';
        $this->redirect('/admin');
    }

    public function updateOrderStatus(): void
    {
        $this->ensureAuthenticated();

        $orderId = (int) ($_POST['order_id'] ?? 0);
        $status = trim($_POST['status'] ?? 'new');
        $allowedStatuses = ['new', 'processing', 'completed', 'cancelled'];

        if (!in_array($status, $allowedStatuses, true)) {
            $_SESSION['admin_success'] = 'Некоректний статус замовлення.';
            $this->redirect('/admin');
        }

        $updated = $this->orderRepository->updateStatus($orderId, $status);
        $_SESSION['admin_success'] = $updated
            ? 'Статус замовлення оновлено.'
            : 'Не вдалося оновити статус замовлення.';
        $this->redirect('/admin');
    }

    public function changePassword(): void
    {
        $this->ensureAuthenticated();

        $adminId = (int) ($_SESSION['admin_id'] ?? 0);
        $currentPassword = trim($_POST['current_password'] ?? '');
        $newPassword = trim($_POST['new_password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');
        $errors = [];

        $admin = $this->adminUserRepository->findById($adminId);

        if ($admin === null || !password_verify($currentPassword, $admin['password_hash'])) {
            $errors['current_password'] = 'Поточний пароль вказано неправильно.';
        }

        if (mb_strlen($newPassword) < 8) {
            $errors['new_password'] = 'Новий пароль має містити щонайменше 8 символів.';
        }

        if ($newPassword !== $confirmPassword) {
            $errors['confirm_password'] = 'Підтвердження пароля не збігається.';
        }

        if ($errors !== []) {
            $_SESSION['admin_password_errors'] = $errors;
            $this->redirect('/admin');
        }

        $updated = $this->adminUserRepository->updatePassword($adminId, password_hash($newPassword, PASSWORD_DEFAULT));
        $_SESSION['admin_success'] = $updated
            ? 'Пароль адміністратора успішно змінено.'
            : 'Не вдалося змінити пароль.';

        $this->redirect('/admin');
    }

    public function reviews(): void
    {
        $this->ensureAuthenticated();

        $pendingReviews = $this->reviewRepository->getPending();

        $this->view('admin/reviews', [
            'title' => 'Модерація відгуків',
            'appName' => $this->config['app_name'] ?? 'MotoCycle Store',
            'baseUrl' => $this->config['base_url'] ?? '',
            'reviews' => $pendingReviews,
            'successMessage' => $_SESSION['admin_success'] ?? null,
        ]);

        unset($_SESSION['admin_success']);
    }

    public function approveReview(): void
    {
        $this->ensureAuthenticated();

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0 && $this->reviewRepository->approve($id)) {
            $_SESSION['admin_success'] = 'Відгук схвалено.';
        } else {
            $_SESSION['admin_success'] = 'Не вдалося схвалити відгук.';
        }

        $this->redirect('/admin/reviews');
    }

    public function deleteReview(): void
    {
        $this->ensureAuthenticated();

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0 && $this->reviewRepository->delete($id)) {
            $_SESSION['admin_success'] = 'Відгук видалено.';
        } else {
            $_SESSION['admin_success'] = 'Не вдалося видалити відгук.';
        }

        $this->redirect('/admin/reviews');
    }

    private function ensureAuthenticated(): void
    {
        if (!($_SESSION['is_admin'] ?? false)) {
            $this->redirect('/admin/login');
        }
    }

    private function handleImageUpload(?array $file, string $existingImage = ''): string
    {
        if ($file === null || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return $existingImage;
        }

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            return $existingImage;
        }

        // Validate MIME type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            return $existingImage;
        }

        $extension = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (!in_array($extension, $allowedExtensions, true)) {
            return $existingImage;
        }

        $uploadDirectory = __DIR__ . '/../../public/uploads/';
        $fileName = uniqid('motorcycle_', true) . '.' . $extension;
        $targetPath = $uploadDirectory . $fileName;

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        if (!move_uploaded_file((string) $file['tmp_name'], $targetPath)) {
            return $existingImage;
        }

        return ($this->config['base_url'] ?? '') . '/uploads/' . $fileName;
    }

    private function validateMotorcycle(array $data): array
    {
        $errors = [];

        if ($data['name'] === '') {
            $errors['name'] = 'Вкажіть назву мотоцикла.';
        }
        if ($data['brand'] === '') {
            $errors['brand'] = 'Вкажіть бренд.';
        }
        if ($data['type'] === '') {
            $errors['type'] = 'Вкажіть тип мотоцикла.';
        }
        if ($data['engine_volume'] <= 0) {
            $errors['engine_volume'] = 'Вкажіть коректний об’єм двигуна.';
        }
        if ($data['power'] <= 0) {
            $errors['power'] = 'Вкажіть коректну потужність.';
        }
        if ($data['price'] <= 0) {
            $errors['price'] = 'Вкажіть коректну ціну.';
        }
        if ($data['model_year'] <= 0) {
            $errors['model_year'] = 'Вкажіть рік випуску.';
        }
        if ($data['image'] === '') {
            $errors['image'] = 'Вкажіть посилання на зображення.';
        }
        if ($data['description'] === '') {
            $errors['description'] = 'Додайте опис мотоцикла.';
        }

        return $errors;
    }
}
