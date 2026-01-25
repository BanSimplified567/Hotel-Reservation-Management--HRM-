<?php
// app/controllers/BaseController.php
class BaseController
{
  protected $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  protected function render($view, $data = [])
  {
    extract($data);

    $role = $_SESSION['role'] ?? 'guest';
    $isCustomer = $role === 'customer' || $role === 'guest';
    $isStaff = in_array($role, ['admin', 'staff']);

    // Global Header (Bootstrap always loaded)
    include BASE_PATH . '/app/views/layout/base-header.php';

    // Role-specific UI
    if ($isCustomer) {
      include BASE_PATH . '/app/views/layout/navbar.php';
    }

    if ($isStaff) {
      include BASE_PATH . '/app/views/layout/admin-header.php';
      include BASE_PATH . '/app/views/layout/sidebar.php';
    }

    // View
    $viewPath = BASE_PATH . '/app/views/' . $view . '.php';
    if (file_exists($viewPath)) {
      include $viewPath;
    } else {
      echo "<div class='alert alert-danger'>View not found: $view</div>";
    }

    // Role-specific UI
// Role-specific UI
if ($role === 'customer' || $role === 'guest' || !isset($_SESSION['user_id'])) {
  include BASE_PATH . '/app/views/layout/footer.php';
}

    // Footer
    include BASE_PATH . '/app/views/layout/base-footer.php';
  }

  protected function redirect($action, $params = [])
  {
    $queryString = http_build_query(array_merge(['action' => $action], $params));
    header("Location: index.php?$queryString");
    exit;
  }

protected function requireRole($allowedRoles = ['customer'])
{
    $this->requireLogin();
    $userRole = $_SESSION['role'] ?? 'guest';

    if (!in_array($userRole, $allowedRoles)) {
        $_SESSION['error'] = "You don't have permission to access this page.";
        $this->redirect('dashboard');
    }
}
  protected function isLoggedIn()
  {
    return isset($_SESSION['user_id']);
  }

  protected function requireLogin($role = null)
  {
    if (!$this->isLoggedIn()) {
      $this->redirect('login');
    }

    if ($role && ($_SESSION['role'] ?? null) !== $role) {
      $this->redirect('403');
    }
  }
}
