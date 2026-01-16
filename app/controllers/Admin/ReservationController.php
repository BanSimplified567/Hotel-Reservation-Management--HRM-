<?php
// app/controllers/Admin/ReservationController.php

require_once __DIR__ . '/Path/BaseController.php';

class ReservationController extends BaseController
{
  public function index()
  {
    // Check authorization
    $this->requireLogin();
    if (!in_array($_SESSION['role'] ?? '', ['admin', 'staff'])) {
      $this->redirect('403');
    }

    // Get filter parameters
    $search = $_GET['search'] ?? '';
    $status = $_GET['status'] ?? '';
    $date_from = $_GET['date_from'] ?? '';
    $date_to = $_GET['date_to'] ?? '';
    $page = max(1, intval($_GET['page'] ?? 1));
    $perPage = 15;

    // Build query
    $query = "
            SELECT r.*,
                   u.username, u.email, u.first_name, u.last_name,
                   rm.room_number, rt.name as room_type
            FROM reservations r
            JOIN users u ON r.user_id = u.id
            JOIN rooms rm ON r.room_id = rm.id
            JOIN room_types rt ON rm.room_type_id = rt.id
            WHERE 1=1
        ";
    $params = [];

    if (!empty($search)) {
      $query .= " AND (
                u.username LIKE ? OR
                u.email LIKE ? OR
                u.first_name LIKE ? OR
                u.last_name LIKE ? OR
                rm.room_number LIKE ?
            )";
      $searchTerm = "%$search%";
      $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    }

    if (!empty($status)) {
      $query .= " AND r.status = ?";
      $params[] = $status;
    }

    if (!empty($date_from)) {
      $query .= " AND r.check_in >= ?";
      $params[] = $date_from;
    }

    if (!empty($date_to)) {
      $query .= " AND r.check_out <= ?";
      $params[] = $date_to;
    }

    // Get total count
    $countQuery = "SELECT COUNT(*) FROM (" . $query . ") as total";
    $countStmt = $this->pdo->prepare($countQuery);
    $countStmt->execute($params);
    $totalReservations = $countStmt->fetchColumn();
    $totalPages = ceil($totalReservations / $perPage);

    // Add pagination
    $offset = ($page - 1) * $perPage;
    $query .= " ORDER BY r.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $perPage;
    $params[] = $offset;

    // Execute query
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [
      'reservations' => $reservations,
      'totalPages' => $totalPages,
      'currentPage' => $page,
      'search' => $search,
      'status' => $status,
      'date_from' => $date_from,
      'date_to' => $date_to
    ];

    $this->render('admin/reservations/index', $data);
  }

  public function view($id)
  {
    $this->requireLogin();
    if (!in_array($_SESSION['role'] ?? '', ['admin', 'staff'])) {
      $this->redirect('403');
    }

    try {
      $stmt = $this->pdo->prepare("
                SELECT r.*,
                       u.username, u.email, u.first_name, u.last_name, u.phone,
                       rm.room_number, rt.name as room_type, rt.base_price as price_per_night,
                       GROUP_CONCAT(s.name SEPARATOR ', ') as services,
                       SUM(rs.service_price) as total_services_price
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                JOIN rooms rm ON r.room_id = rm.id
                JOIN room_types rt ON rm.room_type_id = rt.id
                LEFT JOIN reservation_services rs ON r.id = rs.reservation_id
                LEFT JOIN services s ON rs.service_id = s.id
                WHERE r.id = ?
                GROUP BY r.id
            ");
      $stmt->execute([$id]);
      $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$reservation) {
        $_SESSION['error'] = "Reservation not found.";
        $this->redirect('admin/reservations');
      }

      // Get individual services
      $stmt = $this->pdo->prepare("
                SELECT s.name, s.description, rs.service_price
                FROM reservation_services rs
                JOIN services s ON rs.service_id = s.id
                WHERE rs.reservation_id = ?
            ");
      $stmt->execute([$id]);
      $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $data = [
        'reservation' => $reservation,
        'services' => $services
      ];

      $this->render('admin/reservations/view', $data);
    } catch (PDOException $e) {
      error_log("View reservation error: " . $e->getMessage());
      $_SESSION['error'] = "Failed to load reservation.";
      $this->redirect('admin/reservations');
    }
  }

  public function edit($id)
  {
    $this->requireLogin();
    if (!in_array($_SESSION['role'] ?? '', ['admin', 'staff'])) {
      $this->redirect('403');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->handleEditReservation($id);
    } else {
      $this->showEditForm($id);
    }
  }

  private function handleEditReservation($id)
  {
    $errors = [];

    // Collect form data
    $check_in = $_POST['check_in'] ?? '';
    $check_out = $_POST['check_out'] ?? '';
    $guests = intval($_POST['guests'] ?? 1);
    $status = $_POST['status'] ?? 'pending';
    $special_requests = trim($_POST['special_requests'] ?? '');
    $admin_notes = trim($_POST['admin_notes'] ?? '');

    // Validation
    if (empty($check_in) || empty($check_out)) {
      $errors[] = "Check-in and check-out dates are required.";
    }

    if (strtotime($check_out) <= strtotime($check_in)) {
      $errors[] = "Check-out date must be after check-in date.";
    }

    if ($guests < 1) {
      $errors[] = "Number of guests must be at least 1.";
    }

    // Check room availability (excluding current reservation)
    if (empty($errors)) {
      try {
        // Get current room_id
        $stmt = $this->pdo->prepare("SELECT room_id FROM reservations WHERE id = ?");
        $stmt->execute([$id]);
        $currentRoom = $stmt->fetch(PDO::FETCH_ASSOC);
        $room_id = $currentRoom['room_id'];

        // Check for conflicts
        $stmt = $this->pdo->prepare("
                    SELECT COUNT(*) FROM reservations
                    WHERE room_id = ?
                    AND id != ?
                    AND status IN ('confirmed', 'checked_in')
                    AND (
                        (check_in <= ? AND check_out >= ?) OR
                        (check_in <= ? AND check_out >= ?) OR
                        (check_in >= ? AND check_out <= ?)
                    )
                ");
        $stmt->execute([
          $room_id,
          $id,
          $check_out,
          $check_in,
          $check_in,
          $check_out,
          $check_in,
          $check_out
        ]);
        $conflicts = $stmt->fetchColumn();

        if ($conflicts > 0) {
          $errors[] = "Room is not available for the selected dates.";
        }
      } catch (PDOException $e) {
        error_log("Room availability check error: " . $e->getMessage());
        $errors[] = "Failed to check room availability.";
      }
    }

    if (empty($errors)) {
      try {
        // Calculate new total
        $stmt = $this->pdo->prepare("
                    SELECT price_per_night FROM rooms WHERE id = ?
                ");
        $stmt->execute([$room_id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        $nights = (strtotime($check_out) - strtotime($check_in)) / (60 * 60 * 24);
        $base_price = $room['price_per_night'] * $nights;

        // Get services total
        $stmt = $this->pdo->prepare("
                    SELECT SUM(service_price) as services_total
                    FROM reservation_services
                    WHERE reservation_id = ?
                ");
        $stmt->execute([$id]);
        $services = $stmt->fetch(PDO::FETCH_ASSOC);
        $services_total = $services['services_total'] ?? 0;

        $total_amount = $base_price + $services_total;

        // Update reservation
        $stmt = $this->pdo->prepare("
                    UPDATE reservations SET
                    check_in = ?, check_out = ?, guests = ?, status = ?,
                    special_requests = ?, admin_notes = ?, total_amount = ?,
                    updated_at = NOW()
                    WHERE id = ?
                ");
        $stmt->execute([
          $check_in,
          $check_out,
          $guests,
          $status,
          $special_requests,
          $admin_notes,
          $total_amount,
          $id
        ]);

        // Log the action
        $this->logAction($_SESSION['user_id'], "Updated reservation #$id");

        $_SESSION['success'] = "Reservation updated successfully.";
        $this->redirect('admin/reservations/view', ['id' => $id]);
      } catch (PDOException $e) {
        error_log("Update reservation error: " . $e->getMessage());
        $errors[] = "Failed to update reservation.";
      }
    }

    if (!empty($errors)) {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      $this->redirect('admin/reservations/edit', ['id' => $id]);
    }
  }

  private function showEditForm($id)
  {
    try {
      $stmt = $this->pdo->prepare("
                SELECT r.*,
                       u.username, u.email,
                       rm.room_number, rt.name as room_type, rt.capacity
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                JOIN rooms rm ON r.room_id = rm.id
                JOIN room_types rt ON rm.room_type_id = rt.id
                WHERE r.id = ?
            ");
      $stmt->execute([$id]);
      $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$reservation) {
        $_SESSION['error'] = "Reservation not found.";
        $this->redirect('admin/reservations');
      }

      $data = [
        'reservation' => $reservation
      ];

      $this->render('admin/reservations/edit', $data);
    } catch (PDOException $e) {
      error_log("Get reservation error: " . $e->getMessage());
      $_SESSION['error'] = "Failed to load reservation.";
      $this->redirect('admin/reservations');
    }
  }

  public function updateStatus($id)
  {
    $this->requireLogin();
    if (!in_array($_SESSION['role'] ?? '', ['admin', 'staff'])) {
      $this->redirect('403');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $status = $_POST['status'] ?? '';
      $notes = trim($_POST['notes'] ?? '');

      $valid_statuses = ['pending', 'confirmed', 'checked_in', 'completed', 'cancelled'];

      if (!in_array($status, $valid_statuses)) {
        $_SESSION['error'] = "Invalid status.";
        $this->redirect('admin/reservations/view', ['id' => $id]);
      }

      try {
        $stmt = $this->pdo->prepare("
                    UPDATE reservations SET
                    status = ?, admin_notes = CONCAT(COALESCE(admin_notes, ''), ?),
                    updated_at = NOW()
                    WHERE id = ?
                ");

        $noteEntry = "\n[" . date('Y-m-d H:i:s') . "] Status changed to $status: $notes";
        $stmt->execute([$status, $noteEntry, $id]);

        // If checking in, mark room as occupied
        if ($status == 'checked_in') {
          $stmt = $this->pdo->prepare("
                        UPDATE rooms r
                        JOIN reservations res ON r.id = res.room_id
                        SET r.status = 'occupied'
                        WHERE res.id = ?
                    ");
          $stmt->execute([$id]);
        }

        // If checking out or cancelling, mark room as available
        if ($status == 'completed' || $status == 'cancelled') {
          $stmt = $this->pdo->prepare("
                        UPDATE rooms r
                        JOIN reservations res ON r.id = res.room_id
                        SET r.status = 'available'
                        WHERE res.id = ?
                    ");
          $stmt->execute([$id]);
        }

        // Log the action
        $this->logAction($_SESSION['user_id'], "Changed reservation #$id status to $status");

        $_SESSION['success'] = "Reservation status updated successfully.";
        $this->redirect('admin/reservations/view', ['id' => $id]);
      } catch (PDOException $e) {
        error_log("Update status error: " . $e->getMessage());
        $_SESSION['error'] = "Failed to update status.";
        $this->redirect('admin/reservations/view', ['id' => $id]);
      }
    }
  }

  public function delete($id)
  {
    $this->requireLogin();
    if (($_SESSION['role'] ?? '') != 'admin') {
      $this->redirect('403');
    }

    try {
      // Get reservation details for logging
      $stmt = $this->pdo->prepare("SELECT room_id, status FROM reservations WHERE id = ?");
      $stmt->execute([$id]);
      $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

      // Delete reservation services first
      $stmt = $this->pdo->prepare("DELETE FROM reservation_services WHERE reservation_id = ?");
      $stmt->execute([$id]);

      // Delete reservation
      $stmt = $this->pdo->prepare("DELETE FROM reservations WHERE id = ?");
      $stmt->execute([$id]);

      // If reservation was active, free up the room
      if ($reservation && in_array($reservation['status'], ['confirmed', 'checked_in'])) {
        $stmt = $this->pdo->prepare("UPDATE rooms SET status = 'available' WHERE id = ?");
        $stmt->execute([$reservation['room_id']]);
      }

      // Log the action
      $this->logAction($_SESSION['user_id'], "Deleted reservation #$id");

      $_SESSION['success'] = "Reservation deleted successfully.";
    } catch (PDOException $e) {
      error_log("Delete reservation error: " . $e->getMessage());
      $_SESSION['error'] = "Failed to delete reservation.";
    }

    $this->redirect('admin/reservations');
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
