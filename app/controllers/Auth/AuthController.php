<?php
// app/controllers/Auth/AuthController.php


class AuthController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // ==============================
    // LOGIN FUNCTIONALITY
    // ==============================
    public function login()
    {
        // If user is already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard();
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
        } else {
            $this->showLoginForm();
        }
    }

    private function handleLogin()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validate input
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Please enter both email and password.";
            header('Location: index.php?action=login');
            exit();
        }

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Security: Regenerate session ID
                session_regenerate_id(true);

                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['first_name'] = $user['first_name'] ?? '';
                $_SESSION['last_name'] = $user['last_name'] ?? '';

                // Log the login
                $this->logAction($user['id'], "User logged in");

                // Clear any previous error messages
                unset($_SESSION['error']);

                // Set success message
                $_SESSION['success'] = 'Welcome back, ' . htmlspecialchars($user['username']) . '!';

                // Redirect based on role
                $this->redirectToDashboard();
                exit();
            } else {
                $_SESSION['error'] = "Invalid email or password.";
                header('Location: index.php?action=login');
                exit();
            }
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            $_SESSION['error'] = "System error. Please try again.";
            header('Location: index.php?action=login');
            exit();
        }
    }

    // ==============================
    // REGISTRATION FUNCTIONALITY
    // ==============================
    public function register()
    {
        // If user is already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard();
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleRegistration();
        } else {
            $this->showRegistrationForm();
        }
    }

    private function handleRegistration()
    {
        $errors = [];

        // Collect form data
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name  = trim($_POST['last_name'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $phone      = trim($_POST['phone'] ?? '');
        $password   = $_POST['password'] ?? '';
        $confirm    = $_POST['confirm_password'] ?? '';

        // Generate username from email
        $username = $email;

        // Validation
        if (empty($first_name)) {
            $errors[] = "First name is required.";
        } elseif (strlen($first_name) < 2) {
            $errors[] = "First name must be at least 2 characters.";
        }

        if (empty($last_name)) {
            $errors[] = "Last name is required.";
        } elseif (strlen($last_name) < 2) {
            $errors[] = "Last name must be at least 2 characters.";
        }

        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (empty($password)) {
            $errors[] = "Password is required.";
        } elseif (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter and one number.";
        }

        if ($password !== $confirm) {
            $errors[] = "Passwords do not match.";
        }

        // Check if email already exists
        if (empty($errors)) {
            try {
                $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->rowCount() > 0) {
                    $errors[] = "Email is already registered.";
                }
            } catch (PDOException $e) {
                error_log("Registration email check error: " . $e->getMessage());
                $errors[] = "System error. Please try again.";
            }
        }

        // Check if username already exists
        if (empty($errors)) {
            try {
                $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = ?");
                $stmt->execute([$username]);
                if ($stmt->rowCount() > 0) {
                    $errors[] = "Username is already taken. Please use a different email.";
                }
            } catch (PDOException $e) {
                error_log("Registration username check error: " . $e->getMessage());
                $errors[] = "System error. Please try again.";
            }
        }

        // Register user if no errors
        if (empty($errors)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'customer'; // Default role for new registrations

            try {
                // Start transaction
                $this->pdo->beginTransaction();

                // Insert into users table with all required fields
                $stmt = $this->pdo->prepare("
                    INSERT INTO users
                    (username, email, password, first_name, last_name, phone, role, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
                ");

                $stmt->execute([
                    $username,
                    $email,
                    $hashed_password,
                    $first_name,
                    $last_name,
                    $phone,
                    $role
                ]);

                $userId = $this->pdo->lastInsertId();

                // Log the registration
                $this->logAction($userId, "New user registration: " . $email);

                // Commit transaction
                $this->pdo->commit();

                // Clear any old form data
                unset($_SESSION['old']);

                // Set success message
                $_SESSION['success'] = "Registration successful! You can now log in.";
                header("Location: index.php?action=login");
                exit();

            } catch (PDOException $e) {
                $this->pdo->rollBack();
                error_log("Registration error: " . $e->getMessage());
                $errors[] = "Registration failed. Please try again.";
            }
        }

        // If errors, store them for display
        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);

            // Preserve form data
            $_SESSION['old'] = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone' => $phone
            ];

            // Redirect back to register to show errors
            header("Location: index.php?action=register");
            exit();
        }
    }

    // ==============================
    // FORGOT PASSWORD FUNCTIONALITY
    // ==============================
    public function forgotPassword()
    {
        // If user is already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard();
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleForgotPassword();
        } else {
            $this->showForgotPasswordForm();
        }
    }

    private function handleForgotPassword()
    {
        $email = trim($_POST['email'] ?? '');

        if (empty($email)) {
            $_SESSION['error'] = "Please enter your email address.";
            header('Location: index.php?action=forgot-password');
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Please enter a valid email address.";
            header('Location: index.php?action=forgot-password');
            exit();
        }

        try {
            // Check if email exists
            $stmt = $this->pdo->prepare("SELECT id, email, username FROM users WHERE email = ? AND is_active = 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Generate reset token (in a real app, you would send an email)
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Store token in database (you would need a password_resets table)
                // For now, we'll just simulate it

                // In a real implementation:
                // $stmt = $this->pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
                // $stmt->execute([$email, $token, $expires]);

                // Log the request
                $this->logAction($user['id'], "Password reset requested");

                // Simulate sending email (in production, use PHPMailer or similar)
                $resetLink = "index.php?action=reset-password&token=$token&email=" . urlencode($email);

                // For demo purposes, we'll store the token in session
                $_SESSION['reset_token'] = $token;
                $_SESSION['reset_email'] = $email;
                $_SESSION['token_expires'] = $expires;

                $_SESSION['success'] = "Password reset instructions have been sent to your email.";
                header('Location: index.php?action=login');
                exit();
            } else {
                // For security, don't reveal if email exists or not
                $_SESSION['success'] = "If your email is registered, you will receive reset instructions shortly.";
                header('Location: index.php?action=login');
                exit();
            }
        } catch (PDOException $e) {
            error_log("Forgot password error: " . $e->getMessage());
            $_SESSION['error'] = "System error. Please try again.";
            header('Location: index.php?action=forgot-password');
            exit();
        }
    }

    // ==============================
    // RESET PASSWORD FUNCTIONALITY
    // ==============================
    public function resetPassword()
    {
        // If user is already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard();
            exit();
        }

        // Check if token is provided
        $token = $_GET['token'] ?? '';
        $email = $_GET['email'] ?? '';

        if (empty($token) || empty($email)) {
            $_SESSION['error'] = "Invalid reset link.";
            header('Location: index.php?action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleResetPassword($token, $email);
        } else {
            $this->showResetPasswordForm($token, $email);
        }
    }

    private function handleResetPassword($token, $email)
    {
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        // Validate input
        if (empty($password) || empty($confirm)) {
            $_SESSION['error'] = "Please enter both password fields.";
            header("Location: index.php?action=reset-password&token=$token&email=" . urlencode($email));
            exit();
        }

        if ($password !== $confirm) {
            $_SESSION['error'] = "Passwords do not match.";
            header("Location: index.php?action=reset-password&token=$token&email=" . urlencode($email));
            exit();
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = "Password must be at least 6 characters.";
            header("Location: index.php?action=reset-password&token=$token&email=" . urlencode($email));
            exit();
        }

        try {
            // In a real implementation, verify token from database
            // For demo, we're using session
            if (!isset($_SESSION['reset_token']) ||
                $_SESSION['reset_token'] !== $token ||
                $_SESSION['reset_email'] !== $email ||
                strtotime($_SESSION['token_expires']) < time()) {

                $_SESSION['error'] = "Invalid or expired reset token.";
                header('Location: index.php?action=forgot-password');
                exit();
            }

            // Update password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->execute([$hashed_password, $email]);

            // Get user ID for logging
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $this->logAction($user['id'], "Password reset successfully");
            }

            // Clear reset token
            unset($_SESSION['reset_token']);
            unset($_SESSION['reset_email']);
            unset($_SESSION['token_expires']);

            $_SESSION['success'] = "Password has been reset successfully. You can now login with your new password.";
            header('Location: index.php?action=login');
            exit();

        } catch (PDOException $e) {
            error_log("Reset password error: " . $e->getMessage());
            $_SESSION['error'] = "System error. Please try again.";
            header("Location: index.php?action=reset-password&token=$token&email=" . urlencode($email));
            exit();
        }
    }

    // ==============================
    // LOGOUT FUNCTIONALITY
    // ==============================
    public function logout()
    {
        // Log the logout
        if (isset($_SESSION['user_id'])) {
            $this->logAction($_SESSION['user_id'], "User logged out");
        }

        // Clear all session variables
        $_SESSION = [];

        // Destroy the session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        // Redirect to login page
        $_SESSION['success'] = "You have been logged out successfully.";
        header('Location: index.php?action=login');
        exit();
    }

    // ==============================
    // HELPER METHODS
    // ==============================
    private function showLoginForm()
    {
        require_once '../app/views/auth/login.php';
    }

    private function showRegistrationForm()
    {
        require_once '../app/views/auth/register.php';
    }

    private function showForgotPasswordForm()
    {
        require_once '../app/views/auth/forgot-password.php';
    }

    private function showResetPasswordForm($token, $email)
    {
        require_once '../app/views/auth/reset-password.php';
    }

    private function logAction($userId, $action)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)");
            $stmt->execute([$userId, $action]);
        } catch (PDOException $e) {
            error_log("Failed to log action: " . $e->getMessage());
        }
    }

    private function redirectToDashboard()
    {
        if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'staff') {
            header('Location: index.php?action=admin/dashboard');
        } else {
            header('Location: index.php?action=dashboard');
        }
        exit();
    }
}
