<?php
// app/controllers/Admin/UserController.php
require_once __DIR__ . '/../Path/BaseController.php';

class UserController extends BaseController
{
    public function __construct($pdo)
    {
        parent::__construct($pdo);
    }

    public function index()
    {
        $this->requireLogin('admin');

        // Get search/filter parameters
        $search = $_GET['search'] ?? '';
        $role = $_GET['role'] ?? '';
        $status = $_GET['status'] ?? '';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 10;

        // Build query
        $query = "SELECT * FROM users WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (username LIKE ? OR email LIKE ? OR first_name LIKE ? OR last_name LIKE ?)";
            $searchTerm = "%$search%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }

        if (!empty($role)) {
            $query .= " AND role = ?";
            $params[] = $role;
        }

        if (!empty($status)) {
            if ($status == 'active') {
                $query .= " AND is_active = 1";
            } elseif ($status == 'inactive') {
                $query .= " AND is_active = 0";
            }
        }

        // Get total count
        $countQuery = "SELECT COUNT(*) FROM (" . $query . ") as total";
        $countStmt = $this->pdo->prepare($countQuery);
        $countStmt->execute($params);
        $totalUsers = $countStmt->fetchColumn();
        $totalPages = ceil($totalUsers / $perPage);

        // Add pagination
        $offset = ($page - 1) * $perPage;
        $query .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;

        // Execute query
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'status' => $status,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalUsers' => $totalUsers,
            'page_title' => 'Manage Users'
        ];

        $this->render('admin/users/index', $data);
    }

    public function create()
    {
        $this->requireLogin('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreateUser();
        } else {
            $this->showCreateForm();
        }
    }

    private function handleCreateUser()
    {
        $errors = [];

        // Collect form data
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = $_POST['role'] ?? 'customer';
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        // Validation
        if (empty($username)) {
            $errors[] = "Username is required.";
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
        } elseif ($password !== $confirm_password) {
            $errors[] = "Passwords do not match.";
        }

        // Check if username/email already exists
        if (empty($errors)) {
            try {
                $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                $stmt->execute([$username, $email]);
                if ($stmt->rowCount() > 0) {
                    $errors[] = "Username or email already exists.";
                }
            } catch (PDOException $e) {
                error_log("User check error: " . $e->getMessage());
                $errors[] = "System error. Please try again.";
            }
        }

        if (empty($errors)) {
            try {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $this->pdo->prepare("
                    INSERT INTO users
                    (username, email, password, first_name, last_name, phone, role, is_active, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
                ");

                $stmt->execute([
                    $username, $email, $hashed_password, $first_name,
                    $last_name, $phone, $role, $is_active
                ]);

                // Log the action
                $this->logAction($_SESSION['user_id'], "Created user: $email");

                $_SESSION['success'] = "User created successfully.";
                $this->redirect('admin/users');

            } catch (PDOException $e) {
                error_log("Create user error: " . $e->getMessage());
                $errors[] = "Failed to create user. Please try again.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            $_SESSION['old'] = $_POST;
            $this->redirect('admin/users', ['sub_action' => 'create']);
        }
    }

    private function showCreateForm()
    {
        $data = [
            'page_title' => 'Create User'
        ];

        $this->render('admin/users/create', $data);
    }

    public function edit($id)
    {
        $this->requireLogin('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEditUser($id);
        } else {
            $this->showEditForm($id);
        }
    }

    private function handleEditUser($id)
    {
        $errors = [];

        // Collect form data
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = $_POST['role'] ?? 'customer';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $change_password = isset($_POST['change_password']);

        // Validation
        if (empty($username)) {
            $errors[] = "Username is required.";
        }

        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        // Check if username/email already exists (excluding current user)
        if (empty($errors)) {
            try {
                $stmt = $this->pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
                $stmt->execute([$username, $email, $id]);
                if ($stmt->rowCount() > 0) {
                    $errors[] = "Username or email already exists.";
                }
            } catch (PDOException $e) {
                error_log("User check error: " . $e->getMessage());
                $errors[] = "System error. Please try again.";
            }
        }

        // Handle password change
        $passwordUpdate = '';
        $passwordParams = [];
        if ($change_password) {
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($password)) {
                $errors[] = "Password is required when changing password.";
            } elseif (strlen($password) < 6) {
                $errors[] = "Password must be at least 6 characters.";
            } elseif ($password !== $confirm_password) {
                $errors[] = "Passwords do not match.";
            } else {
                $passwordUpdate = ", password = ?";
                $passwordParams[] = password_hash($password, PASSWORD_DEFAULT);
            }
        }

        if (empty($errors)) {
            try {
                $query = "UPDATE users SET
                          username = ?, email = ?, first_name = ?, last_name = ?,
                          phone = ?, role = ?, is_active = ?
                          $passwordUpdate
                          WHERE id = ?";

                $params = [$username, $email, $first_name, $last_name, $phone, $role, $is_active];
                if ($change_password) {
                    $params = array_merge($params, $passwordParams);
                }
                $params[] = $id;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute($params);

                // Log the action
                $this->logAction($_SESSION['user_id'], "Updated user #$id");

                $_SESSION['success'] = "User updated successfully.";
                $this->redirect('admin/users');

            } catch (PDOException $e) {
                error_log("Edit user error: " . $e->getMessage());
                $errors[] = "Failed to update user. Please try again.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            $_SESSION['old'] = $_POST;
            $this->redirect('admin/users', ['sub_action' => 'edit', 'id' => $id]);
        }
    }

    private function showEditForm($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $_SESSION['error'] = "User not found.";
                $this->redirect('admin/users');
            }

            $data = [
                'user' => $user,
                'page_title' => 'Edit User'
            ];

            $this->render('admin/users/edit', $data);
        } catch (PDOException $e) {
            error_log("Get user error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load user.";
            $this->redirect('admin/users');
        }
    }

    public function delete($id)
    {
        $this->requireLogin('admin');

        // Prevent deleting own account
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = "You cannot delete your own account.";
            $this->redirect('admin/users');
        }

        try {
            // Check if user has any reservations
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE user_id = ?");
            $stmt->execute([$id]);
            $reservationCount = $stmt->fetchColumn();

            if ($reservationCount > 0) {
                $_SESSION['error'] = "Cannot delete user with existing reservations. Deactivate instead.";
                $this->redirect('admin/users');
            }

            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);

            // Log the action
            $this->logAction($_SESSION['user_id'], "Deleted user #$id");

            $_SESSION['success'] = "User deleted successfully.";
        } catch (PDOException $e) {
            error_log("Delete user error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to delete user.";
        }

        $this->redirect('admin/users');
    }

    public function toggleStatus($id)
    {
        $this->requireLogin('admin');

        try {
            // Get current status
            $stmt = $this->pdo->prepare("SELECT is_active FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $newStatus = $user['is_active'] ? 0 : 1;
                $stmt = $this->pdo->prepare("UPDATE users SET is_active = ? WHERE id = ?");
                $stmt->execute([$newStatus, $id]);

                $statusText = $newStatus ? 'activated' : 'deactivated';

                // Log the action
                $this->logAction($_SESSION['user_id'], "$statusText user #$id");

                $_SESSION['success'] = "User $statusText successfully.";
            } else {
                $_SESSION['error'] = "User not found.";
            }
        } catch (PDOException $e) {
            error_log("Toggle user status error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to update user status.";
        }

        $this->redirect('admin/users');
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
