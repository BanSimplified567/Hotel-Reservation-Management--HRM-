<?php
// app/controllers/Admin/RoomController.php
require_once __DIR__ . '/../Path/BaseController.php';

class RoomController extends BaseController
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
    $type = $_GET['type'] ?? '';
    $status = $_GET['status'] ?? '';
    $page = max(1, intval($_GET['page'] ?? 1));
    $perPage = 15;

    // Build query with JOIN to get room type name
    $query = "SELECT r.*, rt.name as room_type, rt.base_price as room_type_price, rt.capacity
FROM rooms r
LEFT JOIN room_types rt ON r.room_type_id = rt.id
WHERE 1=1";

    $params = [];

    if (!empty($search)) {
      $query .= " AND (r.room_number LIKE ? OR r.description LIKE ?)";
      $searchTerm = "%$search%";
      $params = array_merge($params, [$searchTerm, $searchTerm]);
    }

    if (!empty($type)) {
      $query .= " AND rt.name = ?";
      $params[] = $type;
    }

    if (!empty($status)) {
      $query .= " AND r.status = ?";
      $params[] = $status;
    }

    // Get total count
    $countQuery = "SELECT COUNT(*) FROM (" . $query . ") as total";
    $countStmt = $this->pdo->prepare($countQuery);
    $countStmt->execute($params);
    $totalRooms = $countStmt->fetchColumn();
    $totalPages = ceil($totalRooms / $perPage);

    // Add pagination
    $offset = ($page - 1) * $perPage;
    $query .= " ORDER BY r.room_number ASC LIMIT ? OFFSET ?";
    $params[] = $perPage;
    $params[] = $offset;

    // Execute query
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get room types for filter - updated to use room_types table
    $typeStmt = $this->pdo->prepare("SELECT DISTINCT name FROM room_types WHERE is_active = 1 ORDER BY name");
    $typeStmt->execute();
    $roomTypes = $typeStmt->fetchAll(PDO::FETCH_COLUMN);

    // Get counts for statistics
    $statsStmt = $this->pdo->prepare("
            SELECT
                COUNT(*) as total,
                SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) as available,
                SUM(CASE WHEN status = 'occupied' THEN 1 ELSE 0 END) as occupied,
                SUM(CASE WHEN status = 'maintenance' THEN 1 ELSE 0 END) as maintenance,
                SUM(CASE WHEN status = 'cleaning' THEN 1 ELSE 0 END) as cleaning,
                SUM(CASE WHEN status = 'reserved' THEN 1 ELSE 0 END) as reserved
            FROM rooms
        ");
    $statsStmt->execute();
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);

    $data = [
      'rooms' => $rooms,
      'roomTypes' => $roomTypes,
      'search' => $search,
      'type' => $type,
      'status' => $status,
      'page' => $page,
      'totalPages' => $totalPages,
      'totalRooms' => $totalRooms,
      'availableCount' => $stats['available'] ?? 0,
      'occupiedCount' => $stats['occupied'] ?? 0,
      'maintenanceCount' => $stats['maintenance'] ?? 0,
      'cleaningCount' => $stats['cleaning'] ?? 0,
      'reservedCount' => $stats['reserved'] ?? 0,
      'page_title' => 'Manage Rooms'
    ];

    $this->render('admin/rooms/index', $data);
  }

  public function create()
  {
    $this->requireLogin();

    if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
      $this->redirect('403');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->handleCreateRoom();
    } else {
      $this->showCreateForm();
    }
  }

  private function handleCreateRoom()
  {
    $errors = [];

    // Collect form data
    $room_number = trim($_POST['room_number'] ?? '');
    $room_type_id = intval($_POST['room_type_id'] ?? 0);
    $floor = intval($_POST['floor'] ?? 1);
    $view_type = $_POST['view_type'] ?? 'city';
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'available';
    $features = [];

    // Build features array based on form data - CORRECTED FIELD NAMES
    if (isset($_POST['features_bed']) && $_POST['features_bed'] !== '') {
      $features['bed'] = $_POST['features_bed'];
    }
    if (isset($_POST['features_balcony']) && $_POST['features_balcony'] == '1') {
      $features['balcony'] = true;
    }
    if (isset($_POST['features_private_pool']) && $_POST['features_private_pool'] == '1') {
      $features['private_pool'] = true;
    }
    // Validation
    if (empty($room_number)) {
      $errors[] = "Room number is required.";
    }

    if ($room_type_id <= 0) {
      $errors[] = "Room type is required.";
    }

    if ($floor < 1) {
      $errors[] = "Floor must be at least 1.";
    }

    // Check if room number already exists
    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("SELECT id FROM rooms WHERE room_number = ?");
        $stmt->execute([$room_number]);
        if ($stmt->rowCount() > 0) {
          $errors[] = "Room number already exists.";
        }
      } catch (PDOException $e) {
        error_log("Room check error: " . $e->getMessage());
        $errors[] = "System error. Please try again.";
      }
    }

    if (empty($errors)) {
      try {
        // Convert features array to JSON string
        $features_json = !empty($features) ? json_encode($features) : null;

        $stmt = $this->pdo->prepare("
                    INSERT INTO rooms
                    (room_number, room_type_id, floor, view_type, description, status, features, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
                ");

        $stmt->execute([
          $room_number,
          $room_type_id,
          $floor,
          $view_type,
          $description,
          $status,
          $features_json
        ]);

        // Log the action
        $this->logAction($_SESSION['user_id'], "Created room: $room_number");

        $_SESSION['success'] = "Room created successfully.";
        $this->redirect('admin/rooms');
      } catch (PDOException $e) {
        error_log("Create room error: " . $e->getMessage());
        $errors[] = "Failed to create room. Please try again.";
      }
    }

    if (!empty($errors)) {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      $this->redirect('admin/rooms', ['sub_action' => 'create']);
    }
  }

  private function showCreateForm()
  {
    // Get room types from database
    $typeStmt = $this->pdo->prepare("SELECT id, name, base_price, capacity, size, description FROM room_types ORDER BY name");
    $typeStmt->execute();
    $roomTypes = $typeStmt->fetchAll(PDO::FETCH_ASSOC);

    // View types
    $viewTypes = ['sea', 'garden', 'mountain', 'city', 'pool', 'ocean', 'river'];

    // Bed types
    $bedTypes = ['queen', 'king', 'twin', 'double'];

    $data = [
      'roomTypes' => $roomTypes,
      'viewTypes' => $viewTypes,
      'bedTypes' => $bedTypes,
      'page_title' => 'Create Room'
    ];

    $this->render('admin/rooms/create', $data);
  }

  public function edit($id)
  {
    $this->requireLogin();

    if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
      $this->redirect('403');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->handleEditRoom($id);
    } else {
      $this->showEditForm($id);
    }
  }

private function handleEditRoom($id)
{
  $errors = [];

  // Collect form data
  $room_number = trim($_POST['room_number'] ?? '');
  $room_type_id = intval($_POST['room_type_id'] ?? 0);
  $floor = intval($_POST['floor'] ?? 1);
  $view_type = $_POST['view_type'] ?? 'city';
  $description = trim($_POST['description'] ?? '');
  $status = $_POST['status'] ?? 'available';
  $features = [];

  // Build features array based on form data - CORRECTED FIELD NAMES
  if (isset($_POST['features_bed']) && $_POST['features_bed'] !== '') {
    $features['bed'] = $_POST['features_bed'];
  }
  if (isset($_POST['features_balcony']) && $_POST['features_balcony'] == '1') {
    $features['balcony'] = true;
  }
  if (isset($_POST['features_private_pool']) && $_POST['features_private_pool'] == '1') {
    $features['private_pool'] = true;
  }

  // Validation
  if (empty($room_number)) {
    $errors[] = "Room number is required.";
  }

  if ($room_type_id <= 0) {
    $errors[] = "Room type is required.";
  }

  if ($floor < 1) {
    $errors[] = "Floor must be at least 1.";
  }

  // Check if room number already exists (excluding current room)
  if (empty($errors)) {
    try {
      $stmt = $this->pdo->prepare("SELECT id FROM rooms WHERE room_number = ? AND id != ?");
      $stmt->execute([$room_number, $id]);
      if ($stmt->rowCount() > 0) {
        $errors[] = "Room number already exists.";
      }
    } catch (PDOException $e) {
      error_log("Room check error: " . $e->getMessage());
      $errors[] = "System error. Please try again.";
    }
  }

  // NEW CHECK: If room is currently reserved, show warning and prevent updates
  if (empty($errors)) {
    try {
      // Get current room status from database
      $stmt = $this->pdo->prepare("SELECT status FROM rooms WHERE id = ?");
      $stmt->execute([$id]);
      $currentRoom = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($currentRoom && $currentRoom['status'] == 'reserved') {
        // Check if there are active reservations for this room
        $stmt = $this->pdo->prepare("
                    SELECT COUNT(*) FROM reservations
                    WHERE room_id = ?
                    AND status IN ('confirmed', 'checked_in', 'pending')
                    AND check_out > CURDATE()
                ");
        $stmt->execute([$id]);
        $activeReservations = $stmt->fetchColumn();

        if ($activeReservations > 0) {
          $errors[] = "Cannot update room. Room is currently reserved and has active reservations.";
        }
      }
    } catch (PDOException $e) {
      error_log("Reservation check error: " . $e->getMessage());
      // Continue with update if check fails
    }
  }

  // Check if room can be set to available (not occupied by active reservation)
  if (empty($errors) && $status == 'available') {
    try {
      $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM reservations
                WHERE room_id = ?
                AND status IN ('confirmed', 'checked_in')
                AND check_out > CURDATE()
            ");
      $stmt->execute([$id]);
      $activeReservations = $stmt->fetchColumn();

      if ($activeReservations > 0) {
        $errors[] = "Cannot set room as available. It has active reservations.";
      }
    } catch (PDOException $e) {
      error_log("Room availability check error: " . $e->getMessage());
      $errors[] = "Failed to check room availability.";
    }
  }

  if (empty($errors)) {
    try {
      // Convert features array to JSON string
      $features_json = !empty($features) ? json_encode($features) : null;

      $stmt = $this->pdo->prepare("
                UPDATE rooms SET
                room_number = ?, room_type_id = ?, floor = ?, view_type = ?,
                description = ?, status = ?, features = ?, updated_at = NOW()
                WHERE id = ?
            ");

      $stmt->execute([
        $room_number,
        $room_type_id,
        $floor,
        $view_type,
        $description,
        $status,
        $features_json,
        $id
      ]);

      // Log the action
      $this->logAction($_SESSION['user_id'], "Updated room #$id: $room_number");

      $_SESSION['success'] = "Room updated successfully.";
      $this->redirect('admin/rooms');
    } catch (PDOException $e) {
      error_log("Update room error: " . $e->getMessage());
      $errors[] = "Failed to update room. Please try again.";
    }
  }

  if (!empty($errors)) {
    $_SESSION['error'] = implode("<br>", $errors);
    $_SESSION['old'] = $_POST;
    $this->redirect('admin/rooms', ['sub_action' => 'edit', 'id' => $id]);
  }
}
  private function showEditForm($id)
  {
    try {
      $stmt = $this->pdo->prepare("
      SELECT r.*,
             rt.name as room_type_name,
             rt.base_price as room_type_price,
             rt.capacity,
             rt.size,
             rt.description as room_type_description
      FROM rooms r
      LEFT JOIN room_types rt ON r.room_type_id = rt.id
      WHERE r.id = ?
  ");
      $stmt->execute([$id]);
      $room = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$room) {
        $_SESSION['error'] = "Room not found.";
        $this->redirect('admin/rooms');
      }

      // Decode features JSON
      if (!empty($room['features'])) {
        $room['features'] = json_decode($room['features'], true);
      } else {
        $room['features'] = [];
      }

      // Get active reservations count
      $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as active_reservations
                FROM reservations
                WHERE room_id = ?
                AND status IN ('confirmed', 'checked_in')
                AND check_out > CURDATE()
            ");
      $stmt->execute([$id]);
      $activeReservations = $stmt->fetchColumn();

      // Get total reservations count
      $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as total_reservations
                FROM reservations
                WHERE room_id = ?
            ");
      $stmt->execute([$id]);
      $totalReservations = $stmt->fetchColumn();

      // Get room types from database
      $typeStmt = $this->pdo->prepare("SELECT id, name, base_price, capacity, size, description FROM room_types ORDER BY name");
      $typeStmt->execute();
      $roomTypes = $typeStmt->fetchAll(PDO::FETCH_ASSOC);

      // View types
      $viewTypes = ['sea', 'garden', 'mountain', 'city', 'pool', 'ocean', 'river'];

      // Bed types
      $bedTypes = ['queen', 'king', 'twin', 'double'];

      $data = [
        'room' => $room,
        'roomTypes' => $roomTypes,
        'viewTypes' => $viewTypes,
        'bedTypes' => $bedTypes,
        'active_reservations' => $activeReservations,
        'total_reservations' => $totalReservations,
        'page_title' => 'Edit Room'
      ];

      $this->render('admin/rooms/edit', $data);
    } catch (PDOException $e) {
      error_log("Get room error: " . $e->getMessage());
      $_SESSION['error'] = "Failed to load room.";
      $this->redirect('admin/rooms');
    }
  }

  public function delete($id)
  {
    $this->requireLogin('admin');

    try {
      // Check if room has any reservations
      $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE room_id = ?");
      $stmt->execute([$id]);
      $reservationCount = $stmt->fetchColumn();

      if ($reservationCount > 0) {
        $_SESSION['error'] = "Cannot delete room with existing reservations.";
        $this->redirect('admin/rooms');
      }

      // Get room number for logging
      $stmt = $this->pdo->prepare("SELECT room_number FROM rooms WHERE id = ?");
      $stmt->execute([$id]);
      $room = $stmt->fetch(PDO::FETCH_ASSOC);

      // Delete room
      $stmt = $this->pdo->prepare("DELETE FROM rooms WHERE id = ?");
      $stmt->execute([$id]);

      // Log the action
      $this->logAction($_SESSION['user_id'], "Deleted room: " . ($room['room_number'] ?? "#$id"));

      $_SESSION['success'] = "Room deleted successfully.";
    } catch (PDOException $e) {
      error_log("Delete room error: " . $e->getMessage());
      $_SESSION['error'] = "Failed to delete room.";
    }

    $this->redirect('admin/rooms');
  }

  public function view($id)
  {
    $this->requireLogin();

    if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
      $this->redirect('403');
    }

    try {
      $stmt = $this->pdo->prepare("
                SELECT r.*, rt.name as room_type_name, rt.base_price as room_type_price,
                       COUNT(res.id) as total_reservations,
                       SUM(CASE WHEN res.status IN ('confirmed', 'checked_in') AND res.check_out > CURDATE() THEN 1 ELSE 0 END) as active_reservations
                FROM rooms r
                LEFT JOIN room_types rt ON r.room_type_id = rt.id
                LEFT JOIN reservations res ON r.id = res.room_id
                WHERE r.id = ?
                GROUP BY r.id
            ");
      $stmt->execute([$id]);
      $room = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$room) {
        $_SESSION['error'] = "Room not found.";
        $this->redirect('admin/rooms');
      }

      // Decode features JSON
      if (!empty($room['features'])) {
        $room['features'] = json_decode($room['features'], true);
      } else {
        $room['features'] = [];
      }

      // Get upcoming reservations for this room
      $stmt = $this->pdo->prepare("
                SELECT res.*, u.first_name, u.last_name, u.email
                FROM reservations res
                JOIN users u ON res.user_id = u.id
                WHERE res.room_id = ?
                AND res.check_in >= CURDATE()
                AND res.status IN ('pending', 'confirmed')
                ORDER BY res.check_in ASC
                LIMIT 10
            ");
      $stmt->execute([$id]);
      $upcomingReservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $data = [
        'room' => $room,
        'upcomingReservations' => $upcomingReservations,
        'page_title' => 'View Room'
      ];

      $this->render('admin/rooms/view', $data);
    } catch (PDOException $e) {
      error_log("View room error: " . $e->getMessage());
      $_SESSION['error'] = "Failed to load room.";
      $this->redirect('admin/rooms');
    }
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
