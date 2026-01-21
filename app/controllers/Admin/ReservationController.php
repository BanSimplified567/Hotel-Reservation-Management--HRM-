<?php
// app/controllers/Admin/ReservationController.php

require_once __DIR__ . '/../Path/BaseController.php';

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

    $query = "
            SELECT r.*,
                   u.username, u.email, u.first_name, u.last_name,
                   rm.room_number, rt.name as room_type, rt.base_price as price_per_night
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

    // Calculate total guests for each reservation
    foreach ($reservations as &$reservation) {
      $reservation['guests'] = ($reservation['adults'] ?? 1) + ($reservation['children'] ?? 0);
    }

    $data = [
      'reservations' => $reservations,
      'totalPages' => $totalPages,
      'currentPage' => $page,
      'search' => $search,
      'status' => $status,
      'date_from' => $date_from,
      'date_to' => $date_to,
      'page_title' => 'Manage Reservations'
    ];

    $this->render('admin/reservations/index', $data);
  }


  public function create()
  {
      $this->requireLogin();
      if (!in_array($_SESSION['role'] ?? '', ['admin', 'staff'])) {
          $this->redirect('403');
      }

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          // Handle form submission
          $this->handleCreateReservation();
      } else {
          // Show empty form
          $this->showCreateForm();
      }
  }

  private function showCreateForm()
  {
      try {
          // Get available rooms - FIXED: Based on your database status values
          $stmt = $this->pdo->prepare("
              SELECT r.id, r.room_number, rt.name as room_type, rt.base_price, rt.capacity
              FROM rooms r
              JOIN room_types rt ON r.room_type_id = rt.id
              WHERE r.status IN ('available', 'cleaning')
              AND rt.name != 'Common / Background'
              ORDER BY r.room_number
          ");
          $stmt->execute();
          $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

          // Get customers - FIXED: Check your users table
          $stmt = $this->pdo->prepare("
              SELECT id, username, email, first_name, last_name, phone
              FROM users
              WHERE role = 'customer'
              AND (is_active = 1 OR is_active IS NULL)
              ORDER BY first_name, last_name
          ");
          $stmt->execute();
          $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

          $data = [
              'rooms' => $rooms,
              'customers' => $customers,
              'page_title' => 'Create New Reservation'
          ];

          $this->render('admin/reservations/create', $data);

      } catch (PDOException $e) {
          error_log("Show create form error: " . $e->getMessage());
          $_SESSION['error'] = "Failed to load form data. Database error: " . $e->getMessage();
          $this->redirect('admin/reservations');
      }
  }

  private function handleCreateReservation()
    {
        $errors = [];

        // Collect form data
        $user_id = intval($_POST['user_id'] ?? 0);
        $room_id = intval($_POST['room_id'] ?? 0);
        $check_in = $_POST['check_in'] ?? '';
        $check_out = $_POST['check_out'] ?? '';
        $adults = intval($_POST['adults'] ?? 1);
        $children = intval($_POST['children'] ?? 0);
        $guests = $adults + $children;
        $status = $_POST['status'] ?? 'pending';
        $special_requests = trim($_POST['special_requests'] ?? '');

        // Validation
        if ($user_id < 1) {
            $errors[] = "Please select a customer.";
        }

        if ($room_id < 1) {
            $errors[] = "Please select a room.";
        }

        if (empty($check_in) || empty($check_out)) {
            $errors[] = "Check-in and check-out dates are required.";
        } elseif (!strtotime($check_in) || !strtotime($check_out)) {
            $errors[] = "Invalid date format.";
        } elseif (strtotime($check_out) <= strtotime($check_in)) {
            $errors[] = "Check-out date must be after check-in date.";
        }

        if ($guests < 1) {
            $errors[] = "Number of guests must be at least 1.";
        }

        // Check room capacity
        if ($room_id > 0 && empty($errors)) {
            try {
                $stmt = $this->pdo->prepare("
                    SELECT rt.capacity, rt.base_price, r.room_number, rt.name as room_type
                    FROM rooms r
                    JOIN room_types rt ON r.room_type_id = rt.id
                    WHERE r.id = ?
                ");
                $stmt->execute([$room_id]);
                $room = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$room) {
                    $errors[] = "Selected room not found.";
                } elseif ($guests > $room['capacity']) {
                    $errors[] = "Number of guests ({$guests}) exceeds room capacity ({$room['capacity']}).";
                }
            } catch (PDOException $e) {
                error_log("Room capacity check error: " . $e->getMessage());
                $errors[] = "Failed to check room capacity.";
            }
        }

        // Check room availability based on your database statuses
        if (empty($errors) && $room_id > 0) {
            try {
                // First check if room is actually available (not occupied/reserved)
                $stmt = $this->pdo->prepare("
                    SELECT status FROM rooms WHERE id = ?
                ");
                $stmt->execute([$room_id]);
                $room_status = $stmt->fetchColumn();

                // In your database, 'occupied', 'reserved' are not available
                if (!in_array($room_status, ['available', 'cleaning'])) {
                    $errors[] = "Room is currently {$room_status} and not available for booking.";
                }

                // Check for date conflicts with existing reservations
                $stmt = $this->pdo->prepare("
                    SELECT COUNT(*) FROM reservations
                    WHERE room_id = ?
                    AND status IN ('confirmed', 'checked_in', 'pending')
                    AND NOT (
                        check_out <= ? OR
                        check_in >= ?
                    )
                ");
                $stmt->execute([
                    $room_id,
                    $check_in,
                    $check_out
                ]);
                $conflicts = $stmt->fetchColumn();

                if ($conflicts > 0) {
                    $errors[] = "Room is not available for the selected dates due to existing reservations.";
                }
            } catch (PDOException $e) {
                error_log("Room availability check error: " . $e->getMessage());
                $errors[] = "Failed to check room availability.";
            }
        }

        if (empty($errors)) {
            try {
                // Get room price for calculation
                $stmt = $this->pdo->prepare("
                    SELECT rt.base_price as price_per_night
                    FROM rooms r
                    JOIN room_types rt ON r.room_type_id = rt.id
                    WHERE r.id = ?
                ");
                $stmt->execute([$room_id]);
                $room = $stmt->fetch(PDO::FETCH_ASSOC);
                $price_per_night = $room['price_per_night'] ?? 0;

                // Calculate total nights and amount
                $check_in_date = new DateTime($check_in);
                $check_out_date = new DateTime($check_out);
                $interval = $check_in_date->diff($check_out_date);
                $nights = $interval->days;

                if ($nights < 1) {
                    $errors[] = "Minimum stay is 1 night.";
                    throw new Exception("Invalid night calculation");
                }

                $base_price = $price_per_night * $nights;
                $total_amount = $base_price;

                // Generate reservation code (matching your database format)
                $reservation_code = 'RES' . strtoupper(substr(uniqid(), -8));

                // Start transaction
                $this->pdo->beginTransaction();

                // Insert reservation - MATCHING YOUR DATABASE COLUMNS
                $stmt = $this->pdo->prepare("
                    INSERT INTO reservations (
                        reservation_code, user_id, room_id, check_in, check_out,
                        adults, children, total_nights, base_price, total_amount,
                        special_requests, status, payment_status, created_at, updated_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW(), NOW())
                ");
                $stmt->execute([
                    $reservation_code,
                    $user_id,
                    $room_id,
                    $check_in,
                    $check_out,
                    $adults,
                    $children,
                    $nights,
                    $base_price,
                    $total_amount,
                    $special_requests,
                    $status
                ]);

                $reservation_id = $this->pdo->lastInsertId();

                // Update room status based on reservation status
                if ($status == 'checked_in') {
                    $new_status = 'occupied';
                } elseif ($status == 'confirmed') {
                    $new_status = 'reserved';  // Your database uses 'reserved' not 'booked'
                } else {
                    $new_status = 'available'; // Keep available for pending
                }

                $stmt = $this->pdo->prepare("UPDATE rooms SET status = ? WHERE id = ?");
                $stmt->execute([$new_status, $room_id]);

                // Commit transaction
                $this->pdo->commit();

                // Log the action
                $this->logAction($_SESSION['user_id'], "Created reservation #$reservation_id");

                $_SESSION['success'] = "Reservation created successfully (Code: $reservation_code).";
                $this->redirect("admin/reservations/view/$reservation_id");

            } catch (Exception $e) {
                // Rollback on error
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }

                error_log("Create reservation error: " . $e->getMessage());
                $errors[] = "Failed to create reservation: " . ($e->getMessage());
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            $_SESSION['old'] = $_POST;
            $this->redirect('admin/reservations/create');
        }
    }
  public function view($id)
  {
    $this->requireLogin();
    if (!in_array($_SESSION['role'] ?? '', ['admin', 'staff'])) {
      $this->redirect('403');
    }

    try {
      // FIXED QUERY - matches your actual database structure
      $stmt = $this->pdo->prepare("
                SELECT r.*,
                       u.username, u.email, u.first_name, u.last_name, u.phone,
                       rm.room_number, rt.name as room_type, rt.base_price as price_per_night
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

      // Calculate guests from adults + children
      $reservation['guests'] = ($reservation['adults'] ?? 1) + ($reservation['children'] ?? 0);

      // For now, use empty services array
      $services = [];

      $data = [
        'reservation' => $reservation,
        'services' => $services,
        'page_title' => 'Reservation #' . $reservation['id']
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
    $room_id = intval($_POST['room_id'] ?? 0);
    $check_in = $_POST['check_in'] ?? '';
    $check_out = $_POST['check_out'] ?? '';
    $adults = intval($_POST['adults'] ?? 1);
    $children = intval($_POST['children'] ?? 0);
    $guests = $adults + $children;
    $status = $_POST['status'] ?? 'pending';
    $special_requests = trim($_POST['special_requests'] ?? '');
    $admin_notes = trim($_POST['admin_notes'] ?? '');

    // Get current reservation details
    $stmt = $this->pdo->prepare("SELECT room_id, status FROM reservations WHERE id = ?");
    $stmt->execute([$id]);
    $current = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$current) {
      $errors[] = "Reservation not found.";
    }
    $current_room_id = $current['room_id'];
    $current_status = $current['status'];

    // Validation
    if ($room_id < 1) {
      $errors[] = "Please select a room.";
    }
    if (empty($check_in) || empty($check_out)) {
      $errors[] = "Check-in and check-out dates are required.";
    }
    if (strtotime($check_out) <= strtotime($check_in)) {
      $errors[] = "Check-out date must be after check-in date.";
    }
    if ($guests < 1) {
      $errors[] = "Number of guests must be at least 1.";
    }

    // Capacity check (for the selected room)
    if (empty($errors) && $room_id > 0) {
      try {
        $stmt = $this->pdo->prepare("
                    SELECT rt.capacity FROM rooms r
                    JOIN room_types rt ON r.room_type_id = rt.id
                    WHERE r.id = ?
                ");
        $stmt->execute([$room_id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($room && $guests > $room['capacity']) {
          $errors[] = "Number of guests exceeds room capacity ({$room['capacity']}).";
        }
      } catch (PDOException $e) {
        error_log("Room capacity check error: " . $e->getMessage());
        $errors[] = "Failed to check room capacity.";
      }
    }

    // Availability check (exclude current reservation if same room)
    if (empty($errors) && $room_id > 0) {
      try {
        $query = "
                    SELECT COUNT(*) FROM reservations
                    WHERE room_id = ?
                    AND status IN ('confirmed', 'checked_in')
                ";
        $params = [$room_id, $check_out, $check_in, $check_in, $check_out, $check_in, $check_out];

        if ($room_id == $current_room_id) {
          $query .= " AND id != ?";
          $params[] = $id;  // Exclude self
        }

        $query .= " AND (
                    (check_in <= ? AND check_out >= ?) OR
                    (check_in <= ? AND check_out >= ?) OR
                    (check_in >= ? AND check_out <= ?)
                )";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
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
        // Get room price for new/selected room
        $stmt = $this->pdo->prepare("
                    SELECT rt.base_price as price_per_night
                    FROM rooms r
                    JOIN room_types rt ON r.room_type_id = rt.id
                    WHERE r.id = ?
                ");
        $stmt->execute([$room_id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        $price_per_night = $room['price_per_night'] ?? 0;

        // Calculate nights and base total
        $check_in_date = new DateTime($check_in);
        $check_out_date = new DateTime($check_out);
        $interval = $check_in_date->diff($check_out_date);
        $nights = $interval->days;
        $base_price = $price_per_night * $nights;

        // Update reservation - FIXED to match your database columns
        $stmt = $this->pdo->prepare("
                    UPDATE reservations SET
                    room_id = ?, check_in = ?, check_out = ?, adults = ?, children = ?,
                    total_nights = ?, base_price = ?, total_amount = ?,
                    special_requests = ?, status = ?, updated_at = NOW()
                    WHERE id = ?
                ");
        $stmt->execute([
          $room_id,
          $check_in,
          $check_out,
          $adults,
          $children,
          $nights,
          $base_price,
          $base_price,
          $special_requests,
          $status,
          $id
        ]);

        // Handle room status changes
        if ($room_id != $current_room_id) {
          // Free old room if it was occupied
          $stmt = $this->pdo->prepare("UPDATE rooms SET status = 'available' WHERE id = ? AND status = 'occupied'");
          $stmt->execute([$current_room_id]);
        }

        if ($status == 'checked_in') {
          $stmt = $this->pdo->prepare("UPDATE rooms SET status = 'occupied' WHERE id = ?");
          $stmt->execute([$room_id]);
        } elseif ($status == 'completed' || $status == 'cancelled') {
          $stmt = $this->pdo->prepare("UPDATE rooms SET status = 'available' WHERE id = ?");
          $stmt->execute([$room_id]);
        }

        // Log the action
        $this->logAction($_SESSION['user_id'], "Updated reservation #$id");

        $_SESSION['success'] = "Reservation updated successfully.";
        $this->redirect("admin/reservations/view/$id");
      } catch (PDOException $e) {
        error_log("Update reservation error: " . $e->getMessage());
        $errors[] = "Failed to update reservation.";
      }
    }

    if (!empty($errors)) {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      $this->redirect("admin/reservations/edit/$id");
    }
  }

  private function showEditForm($id)
  {
    try {
      // Get current reservation
      $stmt = $this->pdo->prepare("
                SELECT r.*,
                       u.username, u.email,
                       rm.room_number, rm.id as room_id, rt.name as room_type, rt.capacity,
                       rt.base_price as price_per_night
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

      // Calculate guests from adults + children
      $reservation['guests'] = ($reservation['adults'] ?? 1) + ($reservation['children'] ?? 0);

      // Get all rooms for editing
      $stmt = $this->pdo->prepare("
                SELECT r.id, r.room_number, rt.name as room_type, rt.base_price, rt.capacity
                FROM rooms r
                JOIN room_types rt ON r.room_type_id = rt.id
                ORDER BY r.room_number
            ");
      $stmt->execute();
      $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $data = [
        'reservation' => $reservation,
        'rooms' => $rooms,
        'page_title' => 'Edit Reservation #' . $reservation['id']
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

      $valid_statuses = ['pending', 'confirmed', 'checked_in', 'completed', 'cancelled', 'no_show'];

      if (!in_array($status, $valid_statuses)) {
        $_SESSION['error'] = "Invalid status.";
        $this->redirect("admin/reservations/view/$id");
      }

      try {
        // Get current admin_notes and append new note
        $stmt = $this->pdo->prepare("SELECT admin_notes FROM reservations WHERE id = ?");
        $stmt->execute([$id]);
        $currentNotes = $stmt->fetchColumn();

        $noteEntry = "\n[" . date('Y-m-d H:i:s') . "] Status changed to $status: " . ($notes ?: 'No notes provided');
        $newNotes = ($currentNotes ? $currentNotes : '') . $noteEntry;

        $stmt = $this->pdo->prepare("
                    UPDATE reservations SET
                    status = ?, admin_notes = ?,
                    updated_at = NOW()
                    WHERE id = ?
                ");
        $stmt->execute([$status, $newNotes, $id]);

        // Get room_id for status updates
        $stmt = $this->pdo->prepare("SELECT room_id FROM reservations WHERE id = ?");
        $stmt->execute([$id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        $room_id = $room['room_id'] ?? null;

        // Update room status based on reservation status
        if ($room_id) {
          if ($status == 'checked_in') {
            $stmt = $this->pdo->prepare("UPDATE rooms SET status = 'occupied' WHERE id = ?");
            $stmt->execute([$room_id]);
          } elseif ($status == 'completed' || $status == 'cancelled' || $status == 'no_show') {
            $stmt = $this->pdo->prepare("UPDATE rooms SET status = 'available' WHERE id = ?");
            $stmt->execute([$room_id]);
          } elseif ($status == 'confirmed') {
            $stmt = $this->pdo->prepare("UPDATE rooms SET status = 'booked' WHERE id = ?");
            $stmt->execute([$room_id]);
          }
        }

        // Log the action
        $this->logAction($_SESSION['user_id'], "Changed reservation #$id status to $status");

        $_SESSION['success'] = "Reservation status updated successfully.";
        $this->redirect("admin/reservations/view/$id");
      } catch (PDOException $e) {
        error_log("Update status error: " . $e->getMessage());
        $_SESSION['error'] = "Failed to update status.";
        $this->redirect("admin/reservations/view/$id");
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
