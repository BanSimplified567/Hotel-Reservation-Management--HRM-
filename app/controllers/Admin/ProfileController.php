<?php
// app/controllers/Admin/AdminProfileController.php
require_once __DIR__ . '/../Path/BaseController.php';

class AdminProfileController extends BaseController
{
    public function __construct($pdo)
    {
        parent::__construct($pdo);
    }

    public function index()
    {
        $userId = $_SESSION['user_id'];
        $this->showAdminProfile($userId);
    }

    public function edit()
    {
        $userId = $_SESSION['user_id'];
        $this->showAdminEditForm($userId);
    }

    public function update()
    {
        $userId = $_SESSION['user_id'];
        $this->handleAdminProfileUpdate($userId);
    }

    private function showAdminEditForm($userId)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, username, email, first_name, last_name,
                       phone, address, date_of_birth, role, created_at,
                       city, state, country, postal_code, profile_image
                FROM users
                WHERE id = ?
            ");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                session_destroy();
                $this->redirect('login');
            }

            $data = [
                'user' => $user,
                'page_title' => 'Edit Admin Profile',
                'roles' => ['admin', 'manager', 'staff', 'customer']
            ];

            $this->render('admin/profile/edit', $data);
        } catch (PDOException $e) {
            error_log("Admin show edit form error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load edit form.";
            $this->redirect('admin/profile');
        }
    }

    private function showAdminProfile($userId)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, username, email, first_name, last_name,
                       phone, address, role, created_at, last_login,
                       profile_image
                FROM users
                WHERE id = ?
            ");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                session_destroy();
                $this->redirect('login');
            }

            // Get admin statistics
            $stats = $this->getAdminStatistics($userId);

            // Get recent activities
            $activities = $this->getRecentActivities($userId);

            $data = [
                'user' => $user,
                'stats' => $stats,
                'activities' => $activities,
                'page_title' => 'Admin Profile'
            ];

            $this->render('admin/profile/index', $data);
        } catch (PDOException $e) {
            error_log("Admin show profile error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load profile.";
            $this->redirect('admin/dashboard');
        }
    }

    private function handleAdminProfileUpdate($userId)
    {
        $errors = [];

        // CSRF validation
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Security token invalid. Please try again.";
            $this->redirect('admin/profile/edit');
        }

        // Collect form data
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $state = trim($_POST['state'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $postal_code = trim($_POST['postal_code'] ?? '');

        // Validation
        if (empty($first_name)) {
            $errors[] = "First name is required.";
        }

        if (empty($last_name)) {
            $errors[] = "Last name is required.";
        }

        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        // Check if email already exists (excluding current user)
        if (empty($errors)) {
            try {
                $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt->execute([$email, $userId]);
                if ($stmt->rowCount() > 0) {
                    $errors[] = "Email already exists.";
                }
            } catch (PDOException $e) {
                error_log("Email check error: " . $e->getMessage());
                $errors[] = "System error. Please try again.";
            }
        }

        if (empty($errors)) {
            try {
                $stmt = $this->pdo->prepare("
                    UPDATE users SET
                    first_name = ?, last_name = ?, email = ?, phone = ?,
                    address = ?, city = ?, state = ?, country = ?, postal_code = ?,
                    updated_at = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([
                    $first_name, $last_name, $email, $phone,
                    $address, $city, $state, $country, $postal_code,
                    $userId
                ]);

                // Update session
                $_SESSION['email'] = $email;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;

                // Log the action
                $this->logAction($userId, "Updated admin profile");

                $_SESSION['success'] = "Profile updated successfully.";
                $this->redirect('admin/profile');

            } catch (PDOException $e) {
                error_log("Admin profile update error: " . $e->getMessage());
                $errors[] = "Failed to update profile. Please try again.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            $_SESSION['old'] = $_POST;
            $this->redirect('admin/profile/edit');
        }
    }

    public function changePassword()
    {
        $userId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleAdminPasswordChange($userId);
        } else {
            // Show password change form
            $data = [
                'page_title' => 'Change Password'
            ];
            $this->render('admin/profile/change_password', $data);
        }
    }

    private function handleAdminPasswordChange($userId)
    {
        $errors = [];

        // CSRF validation
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Security token invalid. Please try again.";
            $this->redirect('admin/profile/change-password');
        }

        // Collect form data
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validation
        if (empty($current_password)) {
            $errors[] = "Current password is required.";
        }

        if (empty($new_password)) {
            $errors[] = "New password is required.";
        } elseif (strlen($new_password) < 8) {
            $errors[] = "New password must be at least 8 characters.";
        }

        if ($new_password !== $confirm_password) {
            $errors[] = "New passwords do not match.";
        }

        if (empty($errors)) {
            try {
                // Get current password hash
                $stmt = $this->pdo->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user || !password_verify($current_password, $user['password'])) {
                    $errors[] = "Current password is incorrect.";
                }
            } catch (PDOException $e) {
                error_log("Password verification error: " . $e->getMessage());
                $errors[] = "System error. Please try again.";
            }
        }

        if (empty($errors)) {
            try {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                $stmt = $this->pdo->prepare("
                    UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?
                ");
                $stmt->execute([$hashed_password, $userId]);

                // Log the action
                $this->logAction($userId, "Changed password (admin)");

                $_SESSION['success'] = "Password changed successfully.";
                $this->redirect('admin/profile');

            } catch (PDOException $e) {
                error_log("Admin password change error: " . $e->getMessage());
                $errors[] = "Failed to change password. Please try again.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            $this->redirect('admin/profile/change-password');
        }
    }

    public function updateProfileImage()
    {
        $userId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($this->isAjaxRequest()) {
                echo json_encode(['success' => false, 'error' => 'Invalid request method']);
                exit;
            }
            $this->redirect('admin/profile');
        }

        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 2 * 1024 * 1024; // 2MB

            $file_type = $_FILES['profile_image']['type'];
            $file_size = $_FILES['profile_image']['size'];

            if (!in_array($file_type, $allowed_types)) {
                if ($this->isAjaxRequest()) {
                    echo json_encode(['success' => false, 'error' => 'Only JPG, PNG, and GIF files are allowed.']);
                    exit;
                }
                $_SESSION['error'] = "Only JPG, PNG, and GIF files are allowed.";
                $this->redirect('admin/profile/edit');
            }

            if ($file_size > $max_size) {
                if ($this->isAjaxRequest()) {
                    echo json_encode(['success' => false, 'error' => 'File size must be less than 2MB.']);
                    exit;
                }
                $_SESSION['error'] = "File size must be less than 2MB.";
                $this->redirect('admin/profile/edit');
            }

            $upload_dir = 'uploads/profiles/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $filename = 'admin_' . $userId . '_' . time() . '.' . $file_ext;
            $upload_path = $upload_dir . $filename;

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                try {
                    // Get old image if exists
                    $stmt = $this->pdo->prepare("SELECT profile_image FROM users WHERE id = ?");
                    $stmt->execute([$userId]);
                    $old_image = $stmt->fetchColumn();

                    // Update database
                    $stmt = $this->pdo->prepare("UPDATE users SET profile_image = ?, updated_at = NOW() WHERE id = ?");
                    $stmt->execute([$filename, $userId]);

                    // Delete old image if exists
                    if ($old_image && file_exists($upload_dir . $old_image)) {
                        unlink($upload_dir . $old_image);
                    }

                    // Update session
                    $_SESSION['profile_image'] = $filename;

                    // Log the action
                    $this->logAction($userId, "Updated profile image");

                    if ($this->isAjaxRequest()) {
                        echo json_encode([
                            'success' => true,
                            'message' => 'Profile image updated successfully!',
                            'image_url' => $filename
                        ]);
                        exit;
                    } else {
                        $_SESSION['success'] = "Profile image updated successfully.";
                        $this->redirect('admin/profile');
                    }

                } catch (PDOException $e) {
                    error_log("Profile image update error: " . $e->getMessage());
                    if ($this->isAjaxRequest()) {
                        echo json_encode(['success' => false, 'error' => 'Failed to update profile image.']);
                        exit;
                    }
                    $_SESSION['error'] = "Failed to update profile image.";
                    $this->redirect('admin/profile/edit');
                }
            } else {
                if ($this->isAjaxRequest()) {
                    echo json_encode(['success' => false, 'error' => 'Failed to upload image.']);
                    exit;
                }
                $_SESSION['error'] = "Failed to upload image.";
                $this->redirect('admin/profile/edit');
            }
        } else {
            if ($this->isAjaxRequest()) {
                echo json_encode(['success' => false, 'error' => 'Please select an image to upload.']);
                exit;
            }
            $_SESSION['error'] = "Please select an image to upload.";
            $this->redirect('admin/profile/edit');
        }
    }

    private function getAdminStatistics($userId)
    {
        $stats = [];

        try {
            // Total users
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
            $stats['total_users'] = $stmt->fetchColumn();

            // Active reservations
            $today = date('Y-m-d');
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM reservations
                WHERE status IN ('confirmed', 'checked_in')
                AND check_in <= ? AND check_out >= ?
            ");
            $stmt->execute([$today, $today]);
            $stats['active_reservations'] = $stmt->fetchColumn();

            // Revenue today
            $stmt = $this->pdo->prepare("
                SELECT COALESCE(SUM(total_amount), 0)
                FROM payments
                WHERE DATE(payment_date) = CURDATE()
                AND status = 'completed'
            ");
            $stmt->execute();
            $stats['revenue_today'] = $stmt->fetchColumn();

            // Available rooms
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM rooms WHERE status = 'available'");
            $stats['available_rooms'] = $stmt->fetchColumn();

            // Pending reservations
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'pending'");
            $stats['pending_reservations'] = $stmt->fetchColumn();

            // Recent signups (last 7 days)
            $stmt = $this->pdo->query("
                SELECT COUNT(*) FROM users
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            ");
            $stats['recent_signups'] = $stmt->fetchColumn();

        } catch (PDOException $e) {
            error_log("Admin statistics error: " . $e->getMessage());
            // Return default stats if there's an error
            $stats = [
                'total_users' => 0,
                'active_reservations' => 0,
                'revenue_today' => 0,
                'available_rooms' => 0,
                'pending_reservations' => 0,
                'recent_signups' => 0
            ];
        }

        return $stats;
    }

    private function getRecentActivities($userId)
    {
        $activities = [];

        try {
            // Get recent logs (admin actions)
            $stmt = $this->pdo->prepare("
                SELECT l.*, u.first_name, u.last_name
                FROM logs l
                JOIN users u ON l.user_id = u.id
                WHERE u.role IN ('admin', 'manager', 'staff')
                ORDER BY l.created_at DESC
                LIMIT 10
            ");
            $stmt->execute();
            $activities['logs'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get recent reservations
            $stmt = $this->pdo->query("
                SELECT r.*, u.first_name, u.last_name
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                ORDER BY r.created_at DESC
                LIMIT 5
            ");
            $activities['reservations'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get recent user registrations
            $stmt = $this->pdo->query("
                SELECT id, first_name, last_name, email, role, created_at
                FROM users
                ORDER BY created_at DESC
                LIMIT 5
            ");
            $activities['new_users'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Recent activities error: " . $e->getMessage());
            $activities = [
                'logs' => [],
                'reservations' => [],
                'new_users' => []
            ];
        }

        return $activities;
    }

    private function logAction($userId, $action)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO logs (user_id, action, created_at) VALUES (?, ?, NOW())");
            $stmt->execute([$userId, $action]);
        } catch (PDOException $e) {
            error_log("Log action error: " . $e->getMessage());
        }
    }

    private function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}
