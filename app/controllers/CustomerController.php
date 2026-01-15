<?php
// app/controllers/CustomerController.php

class CustomerController
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  // ==============================
  // MIDDLEWARE METHODS
  // ==============================
  private function requireCustomerLogin()
  {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
      $_SESSION['error'] = "Please login as a customer to access this page.";
      header('Location: index.php?action=login');
      exit();
    }
  }

  private function logAction($action)
  {
    if (isset($_SESSION['user_id'])) {
      try {
        $stmt = $this->pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $action]);
      } catch (PDOException $e) {
        error_log("Failed to log action: " . $e->getMessage());
      }
    }
  }

  // ==============================
  // DASHBOARD
  // ==============================
  public function dashboard()
  {
    $this->requireCustomerLogin();

    try {
      // Get user info
      $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
      $stmt->execute([$_SESSION['user_id']]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      // Get recent reservations
      $stmt = $this->pdo->prepare("
                SELECT r.*, s.name as service_name, s.price as service_price,
                       rm.room_number, rm.room_type, rm.price_per_night
                FROM reservations r
                LEFT JOIN services s ON r.service_id = s.id
                LEFT JOIN rooms rm ON r.room_id = rm.id
                WHERE r.user_id = ?
                ORDER BY r.created_at DESC
                LIMIT 5
            ");
      $stmt->execute([$_SESSION['user_id']]);
      $recentReservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Get reservation stats
      $stmt = $this->pdo->prepare("
                SELECT
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
                FROM reservations
                WHERE user_id = ?
            ");
      $stmt->execute([$_SESSION['user_id']]);
      $stats = $stmt->fetch(PDO::FETCH_ASSOC);

      // Get upcoming reservations
      $stmt = $this->pdo->prepare("
                SELECT r.*, rm.room_number, rm.room_type
                FROM reservations r
                LEFT JOIN rooms rm ON r.room_id = rm.id
                WHERE r.user_id = ?
                AND r.check_in >= CURDATE()
                AND r.status = 'confirmed'
                ORDER BY r.check_in ASC
                LIMIT 3
            ");
      $stmt->execute([$_SESSION['user_id']]);
      $upcomingReservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $this->logAction("Accessed dashboard");

      require_once '../app/views/customer/dashboard.php';
    } catch (PDOException $e) {
      error_log("Dashboard error: " . $e->getMessage());
      $_SESSION['error'] = "Unable to load dashboard. Please try again.";
      header('Location: index.php');
      exit();
    }
  }

  // ==============================
  // RESERVATIONS - INDEX
  // ==============================
  public function reservationsIndex()
  {
    $this->requireCustomerLogin();

    $page = $_GET['page'] ?? 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;
    $status = $_GET['status'] ?? '';

    try {
      // Build query based on filters
      $whereClause = "WHERE r.user_id = ?";
      $params = [$_SESSION['user_id']];

      if ($status && in_array($status, ['pending', 'confirmed', 'cancelled', 'completed'])) {
        $whereClause .= " AND r.status = ?";
        $params[] = $status;
      }

      // Get total count
      $countStmt = $this->pdo->prepare("
                SELECT COUNT(*) as total
                FROM reservations r
                $whereClause
            ");
      $countStmt->execute($params);
      $totalReservations = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
      $totalPages = ceil($totalReservations / $limit);

      // Get reservations
      $stmt = $this->pdo->prepare("
                SELECT r.*,
                       rm.room_number, rm.room_type, rm.price_per_night,
                       s.name as service_name, s.price as service_price
                FROM reservations r
                LEFT JOIN rooms rm ON r.room_id = rm.id
                LEFT JOIN services s ON r.service_id = s.id
                $whereClause
                ORDER BY r.created_at DESC
                LIMIT ? OFFSET ?
            ");
      $params[] = $limit;
      $params[] = $offset;
      $stmt->execute($params);
      $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $this->logAction("Viewed reservations list");

      require_once '../app/views/customer/reservations/index.php';
    } catch (PDOException $e) {
      error_log("Reservations index error: " . $e->getMessage());
      $_SESSION['error'] = "Unable to load reservations.";
      header('Location: index.php?action=dashboard');
      exit();
    }
  }

  // ==============================
  // RESERVATIONS - VIEW
  // ==============================
  public function viewReservation($id = null)
  {
    $this->requireCustomerLogin();

    if (!$id) {
      $id = $_GET['id'] ?? 0;
    }

    try {
      $stmt = $this->pdo->prepare("
                SELECT r.*,
                       rm.room_number, rm.room_type, rm.price_per_night, rm.description as room_description,
                       s.name as service_name, s.price as service_price, s.description as service_description,
                       u.first_name, u.last_name, u.email, u.phone
                FROM reservations r
                LEFT JOIN rooms rm ON r.room_id = rm.id
                LEFT JOIN services s ON r.service_id = s.id
                LEFT JOIN users u ON r.user_id = u.id
                WHERE r.id = ? AND r.user_id = ?
            ");
      $stmt->execute([$id, $_SESSION['user_id']]);
      $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$reservation) {
        $_SESSION['error'] = "Reservation not found or access denied.";
        header('Location: index.php?action=customer/reservations');
        exit();
      }

      $this->logAction("Viewed reservation #$id");

      require_once '../app/views/customer/reservations/view.php';
    } catch (PDOException $e) {
      error_log("View reservation error: " . $e->getMessage());
      $_SESSION['error'] = "Unable to load reservation details.";
      header('Location: index.php?action=customer/reservations');
      exit();
    }
  }

  // ==============================
  // RESERVATIONS - CANCEL
  // ==============================
  public function cancelReservation($id = null)
  {
    $this->requireCustomerLogin();

    if (!$id) {
      $id = $_GET['id'] ?? 0;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $_SESSION['error'] = "Invalid request method.";
      header('Location: index.php?action=customer/reservations');
      exit();
    }

    try {
      // First, check if reservation exists and belongs to user
      $stmt = $this->pdo->prepare("
                SELECT status, check_in
                FROM reservations
                WHERE id = ? AND user_id = ?
            ");
      $stmt->execute([$id, $_SESSION['user_id']]);
      $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$reservation) {
        $_SESSION['error'] = "Reservation not found or access denied.";
        header('Location: index.php?action=customer/reservations');
        exit();
      }

      // Check if reservation can be cancelled
      if ($reservation['status'] === 'cancelled') {
        $_SESSION['error'] = "Reservation is already cancelled.";
        header('Location: index.php?action=customer/reservations&sub_action=view&id=' . $id);
        exit();
      }

      if ($reservation['status'] === 'completed') {
        $_SESSION['error'] = "Completed reservations cannot be cancelled.";
        header('Location: index.php?action=customer/reservations&sub_action=view&id=' . $id);
        exit();
      }

      // Check if check-in is within 24 hours
      $checkIn = new DateTime($reservation['check_in']);
      $now = new DateTime();
      $hoursDiff = ($checkIn->getTimestamp() - $now->getTimestamp()) / 3600;

      // Apply cancellation fee if within 24 hours
      $cancellationFee = 0;
      if ($hoursDiff < 24) {
        $cancellationFee = 0.50; // 50% cancellation fee
      }

      // Update reservation status
      $stmt = $this->pdo->prepare("
                UPDATE reservations
                SET status = 'cancelled',
                    cancellation_reason = ?,
                    cancellation_fee = ?,
                    cancelled_at = NOW()
                WHERE id = ? AND user_id = ?
            ");

      $reason = $_POST['cancellation_reason'] ?? 'Customer requested cancellation';
      $stmt->execute([$reason, $cancellationFee, $id, $_SESSION['user_id']]);

      $this->logAction("Cancelled reservation #$id");

      if ($cancellationFee > 0) {
        $_SESSION['warning'] = "Reservation cancelled successfully. A 50% cancellation fee has been applied.";
      } else {
        $_SESSION['success'] = "Reservation cancelled successfully.";
      }

      header('Location: index.php?action=customer/reservations&sub_action=view&id=' . $id);
      exit();
    } catch (PDOException $e) {
      error_log("Cancel reservation error: " . $e->getMessage());
      $_SESSION['error'] = "Unable to cancel reservation. Please try again.";
      header('Location: index.php?action=customer/reservations&sub_action=view&id=' . $id);
      exit();
    }
  }

  // ==============================
  // RESERVATIONS - INVOICE
  // ==============================
  public function generateInvoice($id = null)
  {
    $this->requireCustomerLogin();

    if (!$id) {
      $id = $_GET['id'] ?? 0;
    }

    try {
      $stmt = $this->pdo->prepare("
                SELECT r.*,
                       rm.room_number, rm.room_type, rm.price_per_night,
                       s.name as service_name, s.price as service_price,
                       u.first_name, u.last_name, u.email, u.phone, u.address
                FROM reservations r
                LEFT JOIN rooms rm ON r.room_id = rm.id
                LEFT JOIN services s ON r.service_id = s.id
                LEFT JOIN users u ON r.user_id = u.id
                WHERE r.id = ? AND r.user_id = ?
            ");
      $stmt->execute([$id, $_SESSION['user_id']]);
      $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$reservation) {
        $_SESSION['error'] = "Reservation not found or access denied.";
        header('Location: index.php?action=customer/reservations');
        exit();
      }

      // Calculate total
      $checkIn = new DateTime($reservation['check_in']);
      $checkOut = new DateTime($reservation['check_out']);
      $nights = $checkOut->diff($checkIn)->days;

      $roomTotal = $reservation['price_per_night'] * $nights;
      $serviceTotal = $reservation['service_price'] ?? 0;
      $taxRate = 0.10; // 10% tax
      $taxAmount = ($roomTotal + $serviceTotal) * $taxRate;
      $grandTotal = $roomTotal + $serviceTotal + $taxAmount;

      $this->logAction("Generated invoice for reservation #$id");

      require_once '../app/views/customer/reservations/invoice.php';
    } catch (PDOException $e) {
      error_log("Invoice generation error: " . $e->getMessage());
      $_SESSION['error'] = "Unable to generate invoice.";
      header('Location: index.php?action=customer/reservations&sub_action=view&id=' . $id);
      exit();
    }
  }

  // ==============================
  // BOOKING - INDEX (Search Rooms)
  // ==============================
  public function bookingIndex()
  {
    $this->requireCustomerLogin();

    $checkIn = $_GET['check_in'] ?? '';
    $checkOut = $_GET['check_out'] ?? '';
    $guests = $_GET['guests'] ?? 1;
    $roomType = $_GET['room_type'] ?? '';

    try {
      // If search parameters are provided, find available rooms
      $availableRooms = [];

      if ($checkIn && $checkOut) {
        // Validate dates
        $checkInDate = DateTime::createFromFormat('Y-m-d', $checkIn);
        $checkOutDate = DateTime::createFromFormat('Y-m-d', $checkOut);

        if (!$checkInDate || !$checkOutDate || $checkInDate >= $checkOutDate) {
          $_SESSION['error'] = "Invalid dates. Please select a valid check-in and check-out date.";
        } else {
          // Find available rooms
          $query = "
                        SELECT r.*,
                               COALESCE(AVG(rv.rating), 0) as average_rating
                        FROM rooms r
                        LEFT JOIN reviews rv ON r.id = rv.room_id
                        WHERE r.is_available = 1
                        AND r.max_capacity >= ?
                        AND r.id NOT IN (
                            SELECT room_id
                            FROM reservations
                            WHERE status IN ('confirmed', 'pending')
                            AND (
                                (check_in <= ? AND check_out >= ?) OR
                                (check_in >= ? AND check_in < ?)
                            )
                        )
                    ";

          $params = [$guests, $checkOut, $checkIn, $checkIn, $checkOut];

          if ($roomType) {
            $query .= " AND r.room_type = ?";
            $params[] = $roomType;
          }

          $query .= " GROUP BY r.id ORDER BY r.price_per_night ASC";

          $stmt = $this->pdo->prepare($query);
          $stmt->execute($params);
          $availableRooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
      }

      // Get room types for filter
      $stmt = $this->pdo->prepare("SELECT DISTINCT room_type FROM rooms WHERE is_available = 1 ORDER BY room_type");
      $stmt->execute();
      $roomTypes = $stmt->fetchAll(PDO::FETCH_COLUMN);

      require_once '../app/views/customer/booking/index.php';
    } catch (PDOException $e) {
      error_log("Booking search error: " . $e->getMessage());
      $_SESSION['error'] = "Unable to search for rooms. Please try again.";
      require_once '../app/views/customer/booking/index.php';
    }
  }

  // ==============================
  // BOOKING - CREATE RESERVATION
  // ==============================
  public function createReservation()
  {
    $this->requireCustomerLogin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $_SESSION['error'] = "Invalid request method.";
      header('Location: index.php?action=customer/booking');
      exit();
    }

    try {
      $roomId = $_POST['room_id'] ?? 0;
      $checkIn = $_POST['check_in'] ?? '';
      $checkOut = $_POST['check_out'] ?? '';
      $guests = $_POST['guests'] ?? 1;
      $specialRequests = $_POST['special_requests'] ?? '';
      $serviceId = $_POST['service_id'] ?? null;

      // Validate input
      if (!$roomId || !$checkIn || !$checkOut) {
        $_SESSION['error'] = "Please fill all required fields.";
        header('Location: index.php?action=customer/booking');
        exit();
      }

      // Validate dates
      $checkInDate = DateTime::createFromFormat('Y-m-d', $checkIn);
      $checkOutDate = DateTime::createFromFormat('Y-m-d', $checkOut);

      if (!$checkInDate || !$checkOutDate || $checkInDate >= $checkOutDate) {
        $_SESSION['error'] = "Invalid dates selected.";
        header('Location: index.php?action=customer/booking');
        exit();
      }

      // Check room availability
      if (!$this->isRoomAvailable($roomId, $checkIn, $checkOut)) {
        $_SESSION['error'] = "Selected room is no longer available for the chosen dates.";
        header('Location: index.php?action=customer/booking');
        exit();
      }

      // Get room details
      $stmt = $this->pdo->prepare("SELECT * FROM rooms WHERE id = ? AND is_available = 1");
      $stmt->execute([$roomId]);
      $room = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$room) {
        $_SESSION['error'] = "Selected room not available.";
        header('Location: index.php?action=customer/booking');
        exit();
      }

      // Calculate total price
      $nights = $checkOutDate->diff($checkInDate)->days;
      $roomTotal = $room['price_per_night'] * $nights;
      $serviceTotal = 0;

      if ($serviceId) {
        $stmt = $this->pdo->prepare("SELECT price FROM services WHERE id = ?");
        $stmt->execute([$serviceId]);
        $service = $stmt->fetch(PDO::FETCH_ASSOC);
        $serviceTotal = $service['price'] ?? 0;
      }

      $totalAmount = $roomTotal + $serviceTotal;
      $depositAmount = $totalAmount * 0.20; // 20% deposit

      // Create reservation
      $stmt = $this->pdo->prepare("
                INSERT INTO reservations
                (user_id, room_id, service_id, check_in, check_out, guests,
                 special_requests, total_amount, deposit_amount, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
            ");

      $stmt->execute([
        $_SESSION['user_id'],
        $roomId,
        $serviceId,
        $checkIn,
        $checkOut,
        $guests,
        $specialRequests,
        $totalAmount,
        $depositAmount
      ]);

      $reservationId = $this->pdo->lastInsertId();

      $this->logAction("Created reservation #$reservationId");

      // Redirect to confirmation page
      $_SESSION['success'] = "Reservation created successfully! Please complete payment to confirm your booking.";
      header('Location: index.php?action=customer/booking&sub_action=confirmation&id=' . $reservationId);
      exit();
    } catch (PDOException $e) {
      error_log("Create reservation error: " . $e->getMessage());
      $_SESSION['error'] = "Unable to create reservation. Please try again.";
      header('Location: index.php?action=customer/booking');
      exit();
    }
  }

  // ==============================
  // BOOKING - CONFIRMATION
  // ==============================
  public function bookingConfirmation($id = null)
  {
    $this->requireCustomerLogin();

    if (!$id) {
      $id = $_GET['id'] ?? 0;
    }

    try {
      $stmt = $this->pdo->prepare("
                SELECT r.*,
                       rm.room_number, rm.room_type, rm.price_per_night,
                       s.name as service_name, s.price as service_price
                FROM reservations r
                LEFT JOIN rooms rm ON r.room_id = rm.id
                LEFT JOIN services s ON r.service_id = s.id
                WHERE r.id = ? AND r.user_id = ?
            ");
      $stmt->execute([$id, $_SESSION['user_id']]);
      $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$reservation) {
        $_SESSION['error'] = "Reservation not found.";
        header('Location: index.php?action=customer/booking');
        exit();
      }

      // Calculate details
      $checkIn = new DateTime($reservation['check_in']);
      $checkOut = new DateTime($reservation['check_out']);
      $nights = $checkOut->diff($checkIn)->days;

      $roomTotal = $reservation['price_per_night'] * $nights;
      $serviceTotal = $reservation['service_price'] ?? 0;

      $this->logAction("Viewed confirmation for reservation #$id");

      require_once '../app/views/customer/booking/confirmation.php';
    } catch (PDOException $e) {
      error_log("Confirmation error: " . $e->getMessage());
      $_SESSION['error'] = "Unable to load confirmation.";
      header('Location: index.php?action=customer/booking');
      exit();
    }
  }

  // ==============================
  // BOOKING - PAYMENT
  // ==============================
  public function processPayment($id = null)
  {
    $this->requireCustomerLogin();

    if (!$id) {
      $id = $_GET['id'] ?? 0;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $_SESSION['error'] = "Invalid request method.";
      header('Location: index.php?action=customer/booking&sub_action=confirmation&id=' . $id);
      exit();
    }

    try {
      // Get reservation details
      $stmt = $this->pdo->prepare("
                SELECT * FROM reservations
                WHERE id = ? AND user_id = ? AND status = 'pending'
            ");
      $stmt->execute([$id, $_SESSION['user_id']]);
      $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$reservation) {
        $_SESSION['error'] = "Reservation not found or already processed.";
        header('Location: index.php?action=customer/booking');
        exit();
      }

      $paymentMethod = $_POST['payment_method'] ?? '';
      $cardNumber = $_POST['card_number'] ?? '';
      $cardExpiry = $_POST['card_expiry'] ?? '';
      $cardCVC = $_POST['card_cvc'] ?? '';

      // Validate payment details
      if (!$paymentMethod || !$cardNumber || !$cardExpiry || !$cardCVC) {
        $_SESSION['error'] = "Please fill all payment details.";
        header('Location: index.php?action=customer/booking&sub_action=confirmation&id=' . $id);
        exit();
      }

      // Process payment (simulated)
      $paymentStatus = 'completed';
      $transactionId = 'TXN' . time() . rand(1000, 9999);

      // Update reservation status
      $this->pdo->beginTransaction();

      $stmt = $this->pdo->prepare("
                UPDATE reservations
                SET status = 'confirmed',
                    payment_status = 'paid',
                    payment_method = ?,
                    transaction_id = ?,
                    payment_date = NOW(),
                    confirmed_at = NOW()
                WHERE id = ? AND user_id = ?
            ");
      $stmt->execute([$paymentMethod, $transactionId, $id, $_SESSION['user_id']]);

      // Create payment record
      $stmt = $this->pdo->prepare("
                INSERT INTO payments
                (reservation_id, amount, payment_method, transaction_id, status, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
      $stmt->execute([
        $id,
        $reservation['deposit_amount'],
        $paymentMethod,
        $transactionId,
        'completed'
      ]);

      $this->pdo->commit();

      $this->logAction("Completed payment for reservation #$id");

      $_SESSION['success'] = "Payment successful! Your reservation is now confirmed.";
      header('Location: index.php?action=customer/reservations&sub_action=view&id=' . $id);
      exit();
    } catch (PDOException $e) {
      $this->pdo->rollBack();
      error_log("Payment processing error: " . $e->getMessage());
      $_SESSION['error'] = "Payment failed. Please try again or contact support.";
      header('Location: index.php?action=customer/booking&sub_action=confirmation&id=' . $id);
      exit();
    }
  }

  // ==============================
  // PROFILE - INDEX
  // ==============================
  public function profileIndex()
  {
    $this->requireCustomerLogin();

    try {
      $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
      $stmt->execute([$_SESSION['user_id']]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$user) {
        session_destroy();
        header('Location: index.php?action=login');
        exit();
      }

      $this->logAction("Viewed profile");

      require_once '../app/views/customer/profile/index.php';
    } catch (PDOException $e) {
      error_log("Profile error: " . $e->getMessage());
      $_SESSION['error'] = "Unable to load profile.";
      header('Location: index.php?action=dashboard');
      exit();
    }
  }

  // ==============================
  // PROFILE - EDIT
  // ==============================
  public function editProfile()
  {
    $this->requireCustomerLogin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->handleEditProfile();
    } else {
      $this->showEditProfileForm();
    }
  }

  private function handleEditProfile()
  {
    $errors = [];

    // Collect form data
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $date_of_birth = $_POST['date_of_birth'] ?? null;
    $emergency_contact = trim($_POST['emergency_contact'] ?? '');

    // Validation
    if (empty($first_name)) {
      $errors[] = "First name is required.";
    }

    if (empty($last_name)) {
      $errors[] = "Last name is required.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = "Valid email is required.";
    }

    // Check if email is already taken by another user
    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $_SESSION['user_id']]);
        if ($stmt->rowCount() > 0) {
          $errors[] = "Email is already registered to another account.";
        }
      } catch (PDOException $e) {
        error_log("Email check error: " . $e->getMessage());
        $errors[] = "System error. Please try again.";
      }
    }

    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("
                    UPDATE users
                    SET first_name = ?, last_name = ?, email = ?, phone = ?,
                        address = ?, date_of_birth = ?, emergency_contact = ?, updated_at = NOW()
                    WHERE id = ?
                ");

        $stmt->execute([
          $first_name,
          $last_name,
          $email,
          $phone,
          $address,
          $date_of_birth,
          $emergency_contact,
          $_SESSION['user_id']
        ]);

        // Update session variables
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['email'] = $email;

        $this->logAction("Updated profile information");

        $_SESSION['success'] = "Profile updated successfully!";
        header('Location: index.php?action=customer/profile');
        exit();
      } catch (PDOException $e) {
        error_log("Profile update error: " . $e->getMessage());
        $_SESSION['error'] = "Unable to update profile. Please try again.";
        header('Location: index.php?action=customer/profile&sub_action=edit');
        exit();
      }
    } else {
      $_SESSION['error'] = implode("<br>", $errors);
      header('Location: index.php?action=customer/profile&sub_action=edit');
      exit();
    }
  }

  private function showEditProfileForm()
  {
    try {
      $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
      $stmt->execute([$_SESSION['user_id']]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$user) {
        session_destroy();
        header('Location: index.php?action=login');
        exit();
      }

      require_once '../app/views/customer/profile/edit.php';
    } catch (PDOException $e) {
      error_log("Edit profile form error: " . $e->getMessage());
      $_SESSION['error'] = "Unable to load edit form.";
      header('Location: index.php?action=customer/profile');
      exit();
    }
  }

  // ==============================
  // PROFILE - CHANGE PASSWORD
  // ==============================
  public function changePassword()
  {
    $this->requireCustomerLogin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->handleChangePassword();
    } else {
      $this->showChangePasswordForm();
    }
  }

  private function handleChangePassword()
  {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $errors = [];

    // Validation
    if (empty($current_password)) {
      $errors[] = "Current password is required.";
    }

    if (empty($new_password)) {
      $errors[] = "New password is required.";
    } elseif (strlen($new_password) < 6) {
      $errors[] = "New password must be at least 6 characters.";
    } elseif (!preg_match('/[A-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
      $errors[] = "New password must contain at least one uppercase letter and one number.";
    }

    if ($new_password !== $confirm_password) {
      $errors[] = "New passwords do not match.";
    }

    if (empty($errors)) {
      try {
        // Verify current password
        $stmt = $this->pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($current_password, $user['password'])) {
          $errors[] = "Current password is incorrect.";
        }

        // Check if new password is same as current
        if (password_verify($new_password, $user['password'])) {
          $errors[] = "New password cannot be the same as current password.";
        }

        if (empty($errors)) {
          // Update password
          $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

          $stmt = $this->pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
          $stmt->execute([$hashed_password, $_SESSION['user_id']]);

          $this->logAction("Changed password");

          $_SESSION['success'] = "Password changed successfully!";
          header('Location: index.php?action=customer/profile');
          exit();
        }
      } catch (PDOException $e) {
        error_log("Password change error: " . $e->getMessage());
        $errors[] = "System error. Please try again.";
      }
    }

    $_SESSION['error'] = implode("<br>", $errors);
    header('Location: index.php?action=customer/profile&sub_action=change-password');
    exit();
  }

  private function showChangePasswordForm()
  {
    require_once '../app/views/customer/profile/change-password.php';
  }

  // ==============================
  // HELPER METHODS
  // ==============================
  private function isRoomAvailable($roomId, $checkIn, $checkOut)
  {
    try {
      $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as count
                FROM reservations
                WHERE room_id = ?
                AND status IN ('confirmed', 'pending')
                AND (
                    (check_in <= ? AND check_out >= ?) OR
                    (check_in >= ? AND check_in < ?)
                )
            ");
      $stmt->execute([$roomId, $checkOut, $checkIn, $checkIn, $checkOut]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['count'] == 0;
    } catch (PDOException $e) {
      error_log("Room availability check error: " . $e->getMessage());
      return false;
    }
  }
}
