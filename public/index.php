<?php
// public/index.php
session_start();

// Load configuration files
require_once '../config/load_env.php';
require_once '../config/app.php';
require_once '../config/dbconn.php';
require_once '../app/middleware/auth.php';

// Get action from query parameter
$action = $_GET['action'] ?? '';

// Special handling for empty action
if (empty($action)) {
  if (isset($_SESSION['user_id'])) {
    // Redirect authenticated users to appropriate dashboard
    if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'staff') {
      header('Location: index.php?action=admin/dashboard');
    } else {
      header('Location: index.php?action=dashboard');
    }
  } else {
    // Redirect guests to login
    header('Location: index.php?action=login');
  }
  exit;
}
// Route handling
switch ($action) {
  // ========== AUTHENTICATION ROUTES ==========
  case 'login':
    guest_only();
    require_once '../app/controllers/Auth/AuthController.php';
    $controller = new AuthController($pdo);
    $controller->login();
    break;

  case 'register':
    guest_only();
    require_once '../app/controllers/Auth/AuthController.php';
    $controller = new AuthController($pdo);
    $controller->register();
    break;

  case 'forgot-password':
    guest_only();
    require_once '../app/controllers/Auth/AuthController.php';
    $controller = new AuthController($pdo);
    $controller->forgotPassword();
    break;

  case 'reset-password':
    guest_only();
    require_once '../app/controllers/Auth/AuthController.php';
    $controller = new AuthController($pdo);
    $controller->resetPassword();
    break;

  case 'logout':
    require_once '../app/controllers/Auth/AuthController.php';
    $controller = new AuthController($pdo);
    $controller->logout();
    break;

  // ========== DASHBOARD ROUTES ==========
  case 'dashboard':
    authorize(['customer']);
    require_once '../app/controllers/DashboardController.php';
    $controller = new DashboardController($pdo);
    $controller->index();
    break;

  case 'admin/dashboard':
    authorize(['admin', 'staff']);
    require_once '../app/controllers/Admin/DashboardController.php';
    $controller = new AdminDashboardController($pdo);
    $controller->index();
    break;

  // ========== ADMIN ROUTES ==========
  case 'admin/users':
    authorize(['admin']);
    require_once '../app/controllers/Admin/UserController.php';
    $controller = new UserController($pdo);

    // Handle sub-actions
    $sub_action = $_GET['sub_action'] ?? 'index';
    $id = $_GET['id'] ?? 0;

    switch ($sub_action) {
      case 'create':
        $controller->create();
        break;
      case 'edit':
        if ($id) $controller->edit($id);
        else $controller->index();
        break;
      case 'delete':
        if ($id) $controller->delete($id);
        else $controller->index();
        break;
      case 'toggle-status':
        if ($id) $controller->toggleStatus($id);
        else $controller->index();
        break;
      default:
        $controller->index();
        break;
    }
    break;

  case 'admin/reservations':
    authorize(['admin', 'staff']);
    require_once '../app/controllers/Admin/ReservationController.php';
    $controller = new ReservationController($pdo);

    $sub_action = $_GET['sub_action'] ?? 'index';
    $id = $_GET['id'] ?? 0;

    switch ($sub_action) {
      case 'view':
        if ($id) $controller->view($id);
        else $controller->index();
        break;
      case 'create':
        $controller->create();
        break;
      case 'edit':
        if ($id) $controller->edit($id);
        else $controller->index();
        break;
      case 'update-status':
        if ($id) $controller->updateStatus($id);
        else $controller->index();
        break;
      case 'delete':
        if ($id) $controller->delete($id);
        else $controller->index();
        break;
      default:
        $controller->index();
        break;
    }
    break;

  case 'admin/rooms':
    authorize(['admin', 'staff']);
    require_once '../app/controllers/Admin/RoomController.php';
    $controller = new RoomController($pdo);

    $sub_action = $_GET['sub_action'] ?? 'index';
    $id = $_GET['id'] ?? 0;

    switch ($sub_action) {
      case 'create':
        $controller->create();
        break;
      case 'edit':
        if ($id) $controller->edit($id);
        else $controller->index();
        break;
      case 'view':
        if ($id) $controller->view($id);
        else $controller->index();
        break;
      case 'delete':
        if ($id) $controller->delete($id);
        else $controller->index();
        break;
      default:
        $controller->index();
        break;
    }
    break;

  case 'admin/services':
    authorize(['admin', 'staff']);
    require_once '../app/controllers/Admin/ServiceController.php';
    $controller = new ServiceController($pdo);

    $sub_action = $_GET['sub_action'] ?? 'index';
    $id = $_GET['id'] ?? 0;

    switch ($sub_action) {
      case 'create':
        $controller->create();
        break;
      case 'edit':
        if ($id) $controller->edit($id);
        else $controller->index();
        break;
      case 'view':  // ADD THIS CASE
        if ($id) $controller->view($id);
        else $controller->index();
        break;
      case 'delete':
        if ($id) $controller->delete($id);
        else $controller->index();
        break;
      case 'toggle-status':
        if ($id) $controller->toggleStatus($id);
        else $controller->index();
        break;
      default:
        $controller->index();
        break;
    }
    break;

  case 'admin/reports':
    authorize(['admin']);
    require_once '../app/controllers/Admin/ReportController.php';
    $controller = new ReportController($pdo);

    $sub_action = $_GET['sub_action'] ?? 'index';

    switch ($sub_action) {
      case 'export':
        $controller->export();
        break;
      default:
        $controller->index();
        break;
    }
    break;

  case 'admin/contact':
    authorize(['admin', 'staff']);
    require_once '../app/controllers/ContactController.php';
    $controller = new ContactController($pdo);

    $sub_action = $_GET['sub_action'] ?? 'index';
    $id = $_GET['id'] ?? 0;

    switch ($sub_action) {
      case 'view':
        if ($id) $controller->adminView($id);
        else $controller->adminIndex();
        break;
      case 'reply':
        if ($id) $controller->adminReply($id);
        else $controller->adminIndex();
        break;
      case 'delete':
        if ($id) $controller->adminDelete($id);
        else $controller->adminIndex();
        break;
      default:
        $controller->adminIndex();
        break;
    }
    break;

  // ========== CUSTOMER ROUTES ==========
  case 'profile':
    authorize(['admin', 'staff', 'customer']);
    require_once '../app/controllers/ProfileController.php';
    $controller = new ProfileController($pdo);

    $sub_action = $_GET['sub_action'] ?? 'index';

    switch ($sub_action) {
      case 'edit':
        $controller->edit();
        break;
      case 'change-password':
        $controller->changePassword();
        break;
      default:
        $controller->index();
        break;
    }
    break;

  case 'my-reservations':
    authorize(['customer']);
    require_once '../app/controllers/ReservationController.php';
    $controller = new ReservationController($pdo);

    $sub_action = $_GET['sub_action'] ?? 'index';
    $id = $_GET['id'] ?? 0;

    switch ($sub_action) {
      case 'view':
        if ($id) $controller->view($id);
        else $controller->index();
        break;
      case 'cancel':
        if ($id) $controller->cancel($id);
        else $controller->index();
        break;
      case 'request-cancellation':
        if ($id) $controller->requestCancellation($id);
        else $controller->index();
        break;
      case 'print-invoice':
        if ($id) $controller->printInvoice($id);
        else $controller->index();
        break;
      default:
        $controller->index();
        break;
    }
    break;

  case 'book-room':
    authorize(['customer']);
    require_once '../app/controllers/BookingController.php';
    $controller = new BookingController($pdo);

    $sub_action = $_GET['sub_action'] ?? 'index';

    switch ($sub_action) {
      case 'check-availability':
        $controller->checkAvailability();
        break;
      case 'calculate-price':
        $controller->calculatePrice();
        break;
      default:
        $controller->index();
        break;
    }
    break;

  case 'room-search':
    require_once '../app/controllers/RoomSearchController.php';
    $controller = new RoomSearchController($pdo);

    $sub_action = $_GET['sub_action'] ?? 'index';
    $room_id = $_GET['room_id'] ?? 0;

    switch ($sub_action) {
      case 'quick-search':
        $controller->quickSearch();
        break;
      case 'get-room-details':
        if ($room_id) $controller->getRoomDetails($room_id);
        else $controller->index();
        break;
      default:
        $controller->index();
        break;
    }
    break;

  // ========== PUBLIC ROUTES ==========
  case 'contact':
    require_once '../app/controllers/ContactController.php';
    $controller = new ContactController($pdo);
    $controller->index();
    break;

  case 'about':
    require_once '../app/controllers/AboutController.php';
    $controller = new AboutController($pdo);

    $sub_action = $_GET['sub_action'] ?? 'index';

    switch ($sub_action) {
      case 'amenities':
        $controller->amenities();
        break;
      case 'gallery':
        $controller->gallery();
        break;
      default:
        $controller->index();
        break;
    }
    break;

  case 'rooms':
    require_once '../app/controllers/RoomController.php';
    $controller = new RoomController($pdo);

    $sub_action = $_GET['sub_action'] ?? 'index';
    $id = $_GET['id'] ?? 0;

    switch ($sub_action) {
      case 'view':
        if ($id) $controller->view($id);
        else $controller->index();
        break;
      case 'submit-review':
        if ($id) $controller->submitReview($id);
        else $controller->index();
        break;
      case 'compare':
        $controller->compare();
        break;
      default:
        $controller->index();
        break;
    }
    break;

  // ========== ERROR PAGES ==========
  case '403':
    http_response_code(403);
    require_once '../app/views/errors/403.php';
    break;

  case '404':
    http_response_code(404);
    require_once '../app/views/errors/404.php';
    break;

  default:
    http_response_code(404);
    require_once '../app/views/errors/404.php';
    break;
}
