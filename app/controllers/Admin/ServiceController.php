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

    if (!empty($status)) {
      $query .= " AND status = ?";
      $params[] = $status;
    }

    // Get total count
    $countQuery = "SELECT COUNT(*) FROM (" . $query . ") as total";
    $countStmt = $this->pdo->prepare($countQuery);
    $countStmt->execute($params);
    $totalServices = $countStmt->fetchColumn();
    $totalPages = ceil($totalServices / $perPage);

    // Add pagination
    $offset = ($page - 1) * $perPage;
    $query .= " ORDER BY name ASC LIMIT ? OFFSET ?";
    $params[] = $perPage;
    $params[] = $offset;

    // Execute query
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [
      'services' => $services,
      'search' => $search,
      'status' => $status,
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
    $status = $_POST['status'] ?? 'active';

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
                    (name, description, price, status, created_at)
                    VALUES (?, ?, ?, ?, NOW())
                ");

        $stmt->execute([$name, $description, $price, $status]);

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
      $this->redirect('admin/services', ['sub_action' => 'create']);
    }
  }

  private function showCreateForm()
  {
    $data = [
      'page_title' => 'Create Service'
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
    $status = $_POST['status'] ?? 'active';

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
                    name = ?, description = ?, price = ?, status = ?, updated_at = NOW()
                    WHERE id = ?
                ");

        $stmt->execute([$name, $description, $price, $status, $id]);

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
      $this->redirect('admin/services', ['sub_action' => 'edit', 'id' => $id]);
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

      $data = [
        'service' => $service,
        'page_title' => 'Edit Service'
      ];

      $this->render('admin/services/edit', $data);
    } catch (PDOException $e) {
      error_log("Get service error: " . $e->getMessage());
      $_SESSION['error'] = "Failed to load service.";
      $this->redirect('admin/services');
    }
  }

  // ADD THIS METHOD - IT WAS MISSING
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
      // Get current status
      $stmt = $this->pdo->prepare("SELECT status, name FROM services WHERE id = ?");
      $stmt->execute([$id]);
      $service = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($service) {
        $newStatus = $service['status'] == 'active' ? 'inactive' : 'active';
        $stmt = $this->pdo->prepare("UPDATE services SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$newStatus, $id]);

        $statusText = $newStatus == 'active' ? 'activated' : 'deactivated';

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
