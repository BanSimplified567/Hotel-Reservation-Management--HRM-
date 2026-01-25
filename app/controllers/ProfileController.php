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
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['role'] ?? 'customer';

        // Check if user has permission to view profile
        if (!in_array($userRole, ['customer', 'guest'])) {
            $_SESSION['error'] = "You don't have permission to access this page.";
            $this->redirect('dashboard');
        }

        $this->showProfile($userId, $userRole);
    }

    public function edit()
    {
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['role'] ?? 'customer';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleProfileUpdate($userId, $userRole);
        } else {
            $this->showEditForm($userId, $userRole);
        }
    }

    private function showEditForm($userId, $userRole)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, username, email, first_name, last_name,
                       phone, address, date_of_birth, role, created_at,
                       city, state, country, postal_code, profile_image,
                       loyalty_points, membership_tier, preferred_payment_method
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

            // Check if we should show guest-specific view or customer view
            if ($userRole === 'guest' && file_exists('../app/views/guest/profile/edit.php')) {
                $this->render('guest/profile/edit', $data);
            } else {
                $this->render('customer/profile/edit', $data);
            }
        } catch (PDOException $e) {
            error_log("Show edit form error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load edit form.";
            $this->redirect('profile');
        }
    }

    private function showProfile($userId, $userRole)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, username, email, first_name, last_name,
                       phone, address, city, state, country, postal_code,
                       role, created_at, updated_at, profile_image,
                       loyalty_points, membership_tier
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

            // Get user statistics (only for customers)
            $stats = [];
            if ($userRole === 'customer') {
                $stats = $this->getUserStatistics($userId);
            }

            $data = [
                'user' => $user,
                'stats' => $stats,
                'page_title' => 'My Profile'
            ];

            // Check if we should show guest-specific view or customer view
            if ($userRole === 'guest' && file_exists('../app/views/guest/profile/index.php')) {
                $this->render('guest/profile/index', $data);
            } else {
                $this->render('customer/profile/index', $data);
            }
        } catch (PDOException $e) {
            error_log("Show profile error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load profile.";
            $this->redirect('dashboard');
        }
    }

    private function handleProfileUpdate($userId, $userRole)
    {
        $errors = [];

        // Collect form data
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $date_of_birth = $_POST['date_of_birth'] ?? null;
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
                    address = ?, date_of_birth = ?, city = ?, state = ?,
                    country = ?, postal_code = ?, updated_at = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([
                    $first_name, $last_name, $email, $phone,
                    $address, $date_of_birth, $city, $state,
                    $country, $postal_code, $userId
                ]);

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
            $this->redirect('profile&sub_action=edit');
        }
    }

    public function changePassword()
    {
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['role'] ?? 'customer';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePasswordChange($userId);
        } else {
            $this->showChangePasswordForm($userId, $userRole);
        }
    }

    private function showChangePasswordForm($userId, $userRole)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, username, email, first_name, last_name
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
                'page_title' => 'Change Password'
            ];

            // Check if we should show guest-specific view or customer view
            if ($userRole === 'guest' && file_exists('../app/views/guest/profile/change-password.php')) {
                $this->render('guest/profile/change-password', $data);
            } else {
                $this->render('customer/profile/change-password', $data);
            }
        } catch (PDOException $e) {
            error_log("Show change password form error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load form.";
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
            $this->redirect('profile&sub_action=change-password');
        }
    }

    private function getUserStatistics($userId)
    {
        $stats = [
            'total_reservations' => 0,
            'completed_reservations' => 0,
            'total_spent' => 0,
            'upcoming_reservations' => 0,
            'favorite_room_type' => 'None'
        ];

        try {
            // Total reservations
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE user_id = ?");
            $stmt->execute([$userId]);
            $stats['total_reservations'] = (int)$stmt->fetchColumn();

            // Completed reservations (checked_out status)
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE user_id = ? AND status = 'checked_out'");
            $stmt->execute([$userId]);
            $stats['completed_reservations'] = (int)$stmt->fetchColumn();

            // Total spent
            $stmt = $this->pdo->prepare("SELECT SUM(total_amount) FROM reservations WHERE user_id = ? AND status = 'checked_out'");
            $stmt->execute([$userId]);
            $total = $stmt->fetchColumn();
            $stats['total_spent'] = $total ? (float)$total : 0;

            // Upcoming reservations
            $today = date('Y-m-d');
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM reservations
                WHERE user_id = ?
                AND status IN ('confirmed', 'pending')
                AND check_in >= ?
            ");
            $stmt->execute([$userId, $today]);
            $stats['upcoming_reservations'] = (int)$stmt->fetchColumn();

            // Favorite room type (only if user has reservations)
            if ($stats['total_reservations'] > 0) {
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
            }

        } catch (PDOException $e) {
            error_log("User statistics error: " . $e->getMessage());
            // Return empty stats if there's an error
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
