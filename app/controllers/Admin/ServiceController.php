<?php
// app/controllers/Admin/ServiceController.php
require_once __DIR__ . '/../Path/BaseController.php';

class ServiceController extends BaseController
{
    public function __construct($pdo)
    {
        parent::__construct($pdo);
    }

    public function index()
    {
        $this->requireLogin();

        if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
            $this->redirect('403');
        }

        // Get filter parameters
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $category = $_GET['category'] ?? '';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 15;

        // Build query
        $query = "SELECT * FROM services WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (name LIKE ? OR description LIKE ?)";
            $searchTerm = "%$search%";
            $params = array_merge($params, [$searchTerm, $searchTerm]);
        }

        if (!empty($status) && $status !== 'all') {
            $query .= " AND is_available = ?";
            $params[] = ($status == 'active' || $status == '1') ? 1 : 0;
        }

        if (!empty($category)) {
            $query .= " AND category = ?";
            $params[] = $category;
        }

        // Get total count
        $countQuery = "SELECT COUNT(*) FROM (" . $query . ") as total";
        $countStmt = $this->pdo->prepare($countQuery);
        $countStmt->execute($params);
        $totalServices = $countStmt->fetchColumn();
        $totalPages = ceil($totalServices / $perPage);

        // Add pagination and ordering
        $offset = ($page - 1) * $perPage;
        $query .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;

        // Execute query
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Map is_available to status for views
        foreach ($services as &$service) {
            $service['status'] = $service['is_available'] ? 'active' : 'inactive';
        }
        unset($service);

        // Get all categories for filter dropdown
        $categoryQuery = "SELECT DISTINCT category FROM services WHERE category IS NOT NULL ORDER BY category";
        $categoryStmt = $this->pdo->query($categoryQuery);
        $categories = $categoryStmt->fetchAll(PDO::FETCH_COLUMN);

        $data = [
            'services' => $services,
            'search' => $search,
            'status' => $status,
            'category' => $category,
            'categories' => $categories,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalServices' => $totalServices,
            'page_title' => 'Manage Services'
        ];

        $this->render('admin/services/index', $data);
    }

    public function create()
    {
        $this->requireLogin();

        if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
            $this->redirect('403');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreateService();
        } else {
            $this->showCreateForm();
        }
    }

    private function handleCreateService()
    {
        $errors = [];

        // Collect form data
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $category = $_POST['category'] ?? 'other';
        $is_available = isset($_POST['is_available']) ? 1 : 0;

        // Validation
        if (empty($name)) {
            $errors[] = "Service name is required.";
        }

        if ($price < 0) {
            $errors[] = "Price cannot be negative.";
        }

        // Check if service name already exists
        if (empty($errors)) {
            try {
                $stmt = $this->pdo->prepare("SELECT id FROM services WHERE name = ?");
                $stmt->execute([$name]);
                if ($stmt->rowCount() > 0) {
                    $errors[] = "Service name already exists.";
                }
            } catch (PDOException $e) {
                error_log("Service check error: " . $e->getMessage());
                $errors[] = "System error. Please try again.";
            }
        }

        if (empty($errors)) {
            try {
                $stmt = $this->pdo->prepare("
                    INSERT INTO services
                    (name, description, price, category, is_available, created_at)
                    VALUES (?, ?, ?, ?, ?, NOW())
                ");

                $stmt->execute([$name, $description, $price, $category, $is_available]);

                // Log the action
                $this->logAction($_SESSION['user_id'], "Created service: $name");

                $_SESSION['success'] = "Service created successfully.";
                $this->redirect('admin/services');
            } catch (PDOException $e) {
                error_log("Create service error: " . $e->getMessage());
                $errors[] = "Failed to create service. Please try again.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            $_SESSION['old'] = $_POST;
            $this->redirect('admin/services?sub_action=create');
        }
    }

    private function showCreateForm()
    {
        $data = [
            'page_title' => 'Create Service',
            'categories' => ['food', 'spa', 'transport', 'activity', 'laundry', 'concierge', 'other']
        ];

        $this->render('admin/services/create', $data);
    }

    public function edit($id)
    {
        $this->requireLogin();

        if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
            $this->redirect('403');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEditService($id);
        } else {
            $this->showEditForm($id);
        }
    }

    private function handleEditService($id)
    {
        $errors = [];

        // Collect form data
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $category = $_POST['category'] ?? 'other';
        $is_available = isset($_POST['is_available']) ? 1 : 0;

        // Validation
        if (empty($name)) {
            $errors[] = "Service name is required.";
        }

        if ($price < 0) {
            $errors[] = "Price cannot be negative.";
        }

        // Check if service name already exists (excluding current service)
        if (empty($errors)) {
            try {
                $stmt = $this->pdo->prepare("SELECT id FROM services WHERE name = ? AND id != ?");
                $stmt->execute([$name, $id]);
                if ($stmt->rowCount() > 0) {
                    $errors[] = "Service name already exists.";
                }
            } catch (PDOException $e) {
                error_log("Service check error: " . $e->getMessage());
                $errors[] = "System error. Please try again.";
            }
        }

        if (empty($errors)) {
            try {
                $stmt = $this->pdo->prepare("
                    UPDATE services SET
                    name = ?, description = ?, price = ?, category = ?, is_available = ?, updated_at = NOW()
                    WHERE id = ?
                ");

                $stmt->execute([$name, $description, $price, $category, $is_available, $id]);

                // Log the action
                $this->logAction($_SESSION['user_id'], "Updated service #$id: $name");

                $_SESSION['success'] = "Service updated successfully.";
                $this->redirect('admin/services');
            } catch (PDOException $e) {
                error_log("Update service error: " . $e->getMessage());
                $errors[] = "Failed to update service. Please try again.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            $_SESSION['old'] = $_POST;
            $this->redirect('admin/services?sub_action=edit&id=' . $id);
        }
    }

    private function showEditForm($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM services WHERE id = ?");
            $stmt->execute([$id]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$service) {
                $_SESSION['error'] = "Service not found.";
                $this->redirect('admin/services');
            }

            // Map is_available to status for view consistency
            $service['status'] = $service['is_available'] ? 'active' : 'inactive';

            $data = [
                'service' => $service,
                'page_title' => 'Edit Service',
                'categories' => ['food', 'spa', 'transport', 'activity', 'laundry', 'concierge', 'other']
            ];

            $this->render('admin/services/edit', $data);
        } catch (PDOException $e) {
            error_log("Get service error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load service.";
            $this->redirect('admin/services');
        }
    }

    public function view($id)
    {
        $this->requireLogin();

        if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
            $this->redirect('403');
        }

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM services WHERE id = ?");
            $stmt->execute([$id]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$service) {
                $_SESSION['error'] = "Service not found.";
                $this->redirect('admin/services');
            }

            // Map is_available to status for view
            $service['status'] = $service['is_available'] ? 'active' : 'inactive';

            $data = [
                'service' => $service,
                'page_title' => 'View Service: ' . htmlspecialchars($service['name'])
            ];

            $this->render('admin/services/view', $data);
        } catch (PDOException $e) {
            error_log("Get service error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load service.";
            $this->redirect('admin/services');
        }
    }

    public function delete($id)
    {
        $this->requireLogin('admin');

        try {
            // Check if service is used in any reservations
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservation_services WHERE service_id = ?");
            $stmt->execute([$id]);
            $usageCount = $stmt->fetchColumn();

            if ($usageCount > 0) {
                $_SESSION['error'] = "Cannot delete service that is used in reservations. Deactivate instead.";
                $this->redirect('admin/services');
            }

            // Get service name for logging
            $stmt = $this->pdo->prepare("SELECT name FROM services WHERE id = ?");
            $stmt->execute([$id]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);

            // Delete service
            $stmt = $this->pdo->prepare("DELETE FROM services WHERE id = ?");
            $stmt->execute([$id]);

            // Log the action
            $this->logAction($_SESSION['user_id'], "Deleted service: " . ($service['name'] ?? "#$id"));

            $_SESSION['success'] = "Service deleted successfully.";
        } catch (PDOException $e) {
            error_log("Delete service error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to delete service.";
        }

        $this->redirect('admin/services');
    }

    public function toggleStatus($id)
    {
        $this->requireLogin();

        if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
            $this->redirect('403');
        }

        try {
            // Get current availability status
            $stmt = $this->pdo->prepare("SELECT is_available, name FROM services WHERE id = ?");
            $stmt->execute([$id]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($service) {
                $newStatus = $service['is_available'] ? 0 : 1;
                $stmt = $this->pdo->prepare("UPDATE services SET is_available = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$newStatus, $id]);

                $statusText = $newStatus ? 'activated' : 'deactivated';

                // Log the action
                $this->logAction($_SESSION['user_id'], "$statusText service: " . $service['name']);

                $_SESSION['success'] = "Service $statusText successfully.";
            } else {
                $_SESSION['error'] = "Service not found.";
            }
        } catch (PDOException $e) {
            error_log("Toggle service status error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to update service status.";
        }

        $this->redirect('admin/services');
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
