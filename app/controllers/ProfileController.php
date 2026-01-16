<?php
// app/controllers/ProfileController.php
require_once __DIR__ . '/Path/BaseController.php';

class ProfileController extends BaseController
{
    public function __construct($pdo)
    {
        parent::__construct($pdo);
    }

    public function index()
    {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];
        $this->showProfile($userId);
    }

    public function edit()
    {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleProfileUpdate($userId);
        } else {
            $this->showEditForm($userId);
        }
    }

    private function showEditForm($userId)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, username, email, first_name, last_name,
                       phone, address, date_of_birth, role, created_at
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
                'page_title' => 'Edit Profile'
            ];

            $this->render('customer/profile/edit', $data);
        } catch (PDOException $e) {
            error_log("Show edit form error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load edit form.";
            $this->redirect('profile');
        }
    }

    private function showProfile($userId)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, username, email, first_name, last_name,
                       phone, address, role, created_at
                FROM users
                WHERE id = ?
            ");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                // User not found, logout
                session_destroy();
                $this->redirect('login');
            }

            // Get user statistics
            $stats = $this->getUserStatistics($userId);

            $data = [
                'user' => $user,
                'stats' => $stats,
                'page_title' => 'My Profile'
            ];

            $this->render('customer/profile/index', $data);
        } catch (PDOException $e) {
            error_log("Show profile error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load profile.";
            $this->redirect('dashboard');
        }
    }

    private function handleProfileUpdate($userId)
    {
        $errors = [];

        // Collect form data
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

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
                    first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, updated_at = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([$first_name, $last_name, $email, $phone, $address, $userId]);

                // Update session
                $_SESSION['email'] = $email;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;

                // Log the action
                $this->logAction($userId, "Updated profile");

                $_SESSION['success'] = "Profile updated successfully.";
                $this->redirect('profile');

            } catch (PDOException $e) {
                error_log("Profile update error: " . $e->getMessage());
                $errors[] = "Failed to update profile. Please try again.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            $_SESSION['old'] = $_POST;
            $this->redirect('profile');
        }
    }

    public function changePassword()
    {
        $this->requireLogin();

        $userId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePasswordChange($userId);
        } else {
            $this->redirect('profile');
        }
    }

    private function handlePasswordChange($userId)
    {
        $errors = [];

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
        } elseif (strlen($new_password) < 6) {
            $errors[] = "New password must be at least 6 characters.";
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
                $this->logAction($userId, "Changed password");

                $_SESSION['success'] = "Password changed successfully.";
                $this->redirect('profile');

            } catch (PDOException $e) {
                error_log("Password change error: " . $e->getMessage());
                $errors[] = "Failed to change password. Please try again.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            $this->redirect('profile');
        }
    }

    private function getUserStatistics($userId)
    {
        $stats = [];

        try {
            // Total reservations
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE user_id = ?");
            $stmt->execute([$userId]);
            $stats['total_reservations'] = $stmt->fetchColumn();

            // Completed reservations
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE user_id = ? AND status = 'completed'");
            $stmt->execute([$userId]);
            $stats['completed_reservations'] = $stmt->fetchColumn();

            // Total spent
            $stmt = $this->pdo->prepare("SELECT SUM(total_amount) FROM reservations WHERE user_id = ? AND status = 'completed'");
            $stmt->execute([$userId]);
            $stats['total_spent'] = $stmt->fetchColumn() ?: 0;

            // Upcoming reservations
            $today = date('Y-m-d');
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM reservations
                WHERE user_id = ?
                AND status IN ('confirmed', 'pending')
                AND check_in >= ?
            ");
            $stmt->execute([$userId, $today]);
            $stats['upcoming_reservations'] = $stmt->fetchColumn();

            // Favorite room type
            $stmt = $this->pdo->prepare("
                SELECT rt.name as type, COUNT(*) as count
                FROM reservations r
                JOIN rooms rm ON r.room_id = rm.id
                JOIN room_types rt ON rm.room_type_id = rt.id
                WHERE r.user_id = ?
                GROUP BY rt.name
                ORDER BY count DESC
                LIMIT 1
            ");
            $stmt->execute([$userId]);
            $favorite = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['favorite_room_type'] = $favorite ? $favorite['type'] : 'None';

        } catch (PDOException $e) {
            error_log("User statistics error: " . $e->getMessage());
            // Return empty stats if there's an error
            $stats = [
                'total_reservations' => 0,
                'completed_reservations' => 0,
                'total_spent' => 0,
                'upcoming_reservations' => 0,
                'favorite_room_type' => 'None'
            ];
        }

        return $stats;
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
}
