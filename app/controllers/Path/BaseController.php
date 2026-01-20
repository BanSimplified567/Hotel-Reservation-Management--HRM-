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
    // Extract data to variables
    extract($data);

    // Get user role
    $role = $_SESSION['role'] ?? 'guest';
    $isCustomer = $role === 'customer';
    $isStaff = in_array($role, ['admin', 'staff']);

    // Start output buffering
    ob_start();

    // Include the view
    $viewPath = BASE_PATH . '/app/views/' . $view . '.php';
    if (file_exists($viewPath)) {
      include $viewPath;
    } else {
      echo "<div class='alert alert-danger'>View not found: $view</div>";
    }

    $content = ob_get_clean();

    // Include header only for customers/guests
    if ($isCustomer || $role === 'guest') {
      include BASE_PATH . '/app/views/layout/header.php';
      include BASE_PATH . '/app/views/layout/navbar.php';
    }

    // Include admin header and sidebar only for admin/staff
    if ($isStaff) {
      include BASE_PATH . '/app/views/layout/admin-header.php';
      include BASE_PATH . '/app/views/layout/sidebar.php';
    }

    // Output content
    echo $content;

    // Include footer
    if ($isCustomer || $role === 'guest') {
      include BASE_PATH . '/app/views/layout/footer.php';
    }
  }

  protected function redirect($action, $params = [])
  {
    $queryString = http_build_query(array_merge(['action' => $action], $params));
    header("Location: index.php?$queryString");
    exit();
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

    if ($role && isset($_SESSION['role']) && $_SESSION['role'] != $role) {
      $this->redirect('403');
    }
  }
}
