<?php
// app/controllers/BookingController.php
require_once __DIR__ . '/Path/BaseController.php';

class BookingController extends BaseController
{
  public function __construct($pdo)
  {
    parent::__construct($pdo);
  }

  public function index()
  {
    $this->requireLogin('customer');

    $userId = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->handleBooking($userId);
    } else {
      $this->showBookingForm();
    }
  }

  private function showBookingForm()
  {
    try {
      // Get available rooms
      $stmt = $this->pdo->prepare("
                SELECT r.id, r.room_number, r.status,
                       rt.name as type, rt.description,
                       rt.base_price as price_per_night, rt.capacity, rt.amenities,
                       rt.size
                FROM rooms r
                JOIN room_types rt ON r.room_type_id = rt.id
                WHERE r.status = 'available'
                AND rt.is_active = 1
                AND rt.name != 'Common / Background'
                ORDER BY rt.name, r.room_number
            ");
      $stmt->execute();
      $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Get available services
      $stmt = $this->pdo->prepare("
                SELECT id, name, description, price
                FROM services
                WHERE is_available = 1
                ORDER BY name
            ");
      $stmt->execute();
      $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Pre-fill room if specified in URL
      $room_id = $_GET['room_id'] ?? 0;
      $selectedRoom = null;

      if ($room_id) {
        foreach ($rooms as $room) {
          if ($room['id'] == $room_id) {
            $selectedRoom = $room;
            break;
          }
        }
      }

      // Decode amenities JSON for each room
      foreach ($rooms as &$room) {
        if (!empty($room['amenities'])) {
          $room['amenities'] = json_decode($room['amenities'], true);
        } else {
          $room['amenities'] = [];
        }
      }

      $data = [
        'rooms' => $rooms,
        'services' => $services,
        'selectedRoom' => $selectedRoom,
        'room_id' => $room_id,
        'page_title' => 'Book Room'
      ];

      $this->render('customer/booking/index', $data);
    } catch (PDOException $e) {
      error_log("Show booking form error: " . $e->getMessage());
      $_SESSION['error'] = "Failed to load booking form.";
      $this->redirect('dashboard');
    }
  }

  private function handleBooking($userId)
  {
    $errors = [];

    // Collect form data
    $room_id = intval($_POST['room_id'] ?? 0);
    $check_in = $_POST['check_in'] ?? '';
    $check_out = $_POST['check_out'] ?? '';
    $guests = intval($_POST['guests'] ?? 1);

    // If no room selected, treat as date update
    if ($room_id <= 0) {
      $params = [];
      if (!empty($check_in)) $params['check_in'] = $check_in;
      if (!empty($check_out)) $params['check_out'] = $check_out;
      if ($guests > 1) $params['guests'] = $guests;
      $query = http_build_query($params);
      $this->redirect('book-room' . (!empty($query) ? '?' . $query : ''));
    }

    $special_requests = trim($_POST['special_requests'] ?? '');
    $services = $_POST['services'] ?? [];

    if (empty($check_in) || empty($check_out)) {
      $errors[] = "Please select check-in and check-out dates.";
    } else {
      $check_in_date = new DateTime($check_in);
      $check_out_date = new DateTime($check_out);
      $today = new DateTime();

      if ($check_in_date < $today) {
        $errors[] = "Check-in date cannot be in the past.";
      }

      if ($check_out_date <= $check_in_date) {
        $errors[] = "Check-out date must be after check-in date.";
      }

      // Calculate maximum stay (e.g., 30 days)
      $max_stay = 30;
      $nights = $check_in_date->diff($check_out_date)->days;
      if ($nights > $max_stay) {
        $errors[] = "Maximum stay is $max_stay days.";
      }
    }

    if ($guests < 1) {
      $errors[] = "Number of guests must be at least 1.";
    }

    // Check room availability
    if (empty($errors) && $room_id > 0) {
      try {
        $stmt = $this->pdo->prepare("
                    SELECT COUNT(*) FROM reservations
                    WHERE room_id = ?
                    AND status IN ('confirmed', 'checked_in', 'pending')
                    AND (
                        (check_in <= ? AND check_out >= ?) OR
                        (check_in <= ? AND check_out >= ?) OR
                        (check_in >= ? AND check_out <= ?)
                    )
                ");
        $stmt->execute([
          $room_id,
          $check_out,
          $check_in,
          $check_in,
          $check_out,
          $check_in,
          $check_out
        ]);
        $conflicting_reservations = $stmt->fetchColumn();

        if ($conflicting_reservations > 0) {
          $errors[] = "Selected room is not available for the chosen dates.";
        }
      } catch (PDOException $e) {
        error_log("Room availability check error: " . $e->getMessage());
        $errors[] = "Failed to check room availability. Please try again.";
      }
    }

    // Check room capacity
    if (empty($errors) && $room_id > 0) {
      try {
        $stmt = $this->pdo->prepare("
                    SELECT rt.capacity
                    FROM rooms r
                    JOIN room_types rt ON r.room_type_id = rt.id
                    WHERE r.id = ?
                ");
        $stmt->execute([$room_id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($room && $guests > $room['capacity']) {
          $errors[] = "Number of guests exceeds room capacity (max: " . $room['capacity'] . ").";
        }
      } catch (PDOException $e) {
        error_log("Room capacity check error: " . $e->getMessage());
        $errors[] = "Failed to check room capacity. Please try again.";
      }
    }

    // Validate services
    $selectedServices = [];
    $services_total = 0;

    if (empty($errors) && !empty($services)) {
      try {
        $placeholders = str_repeat('?,', count($services) - 1) . '?';
        $stmt = $this->pdo->prepare("
                    SELECT id, name, price FROM services
                    WHERE id IN ($placeholders) AND is_available = 1
                ");
        $stmt->execute($services);
        $selectedServices = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($selectedServices) != count($services)) {
          $errors[] = "One or more selected services are no longer available.";
        } else {
          foreach ($selectedServices as $service) {
            $services_total += $service['price'];
          }
        }
      } catch (PDOException $e) {
        error_log("Services validation error: " . $e->getMessage());
        $errors[] = "Failed to validate services. Please try again.";
      }
    }

    if (empty($errors)) {
      try {
        // Calculate room total
        $stmt = $this->pdo->prepare("
                    SELECT rt.base_price as price_per_night, r.room_number, rt.name as room_type
                    FROM rooms r
                    JOIN room_types rt ON r.room_type_id = rt.id
                    WHERE r.id = ?
                ");
        $stmt->execute([$room_id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        $nights = $check_in_date->diff($check_out_date)->days;
        if ($nights == 0) $nights = 1; // Minimum 1 night
        $room_total = $room['price_per_night'] * $nights;
        $tax_rate = 0.10; // 10% tax
        $tax_amount = ($room_total + $services_total) * $tax_rate;
        $total_amount = $room_total + $services_total + $tax_amount;
        $deposit_rate = 0.20; // 20% deposit
        $deposit_amount = $total_amount * $deposit_rate;

        // Start transaction
        $this->pdo->beginTransaction();

        // Generate reservation code
        $reservation_code = 'RES' . strtoupper(substr(uniqid(), -8));

        // Create reservation
        $stmt = $this->pdo->prepare("
                    INSERT INTO reservations
                    (reservation_code, user_id, room_id, check_in, check_out, adults, children,
                     total_nights, base_price, taxes, discounts, total_amount, special_requests,
                     status, payment_status, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, 0, ?, ?, ?, 0.00, ?, ?, 'pending', 'pending', NOW())
                ");

        $base_price = $room_total;
        $taxes = $tax_amount;
        $adults = $guests;

        $stmt->execute([
          $reservation_code,
          $userId,
          $room_id,
          $check_in,
          $check_out,
          $adults,
          $nights,
          $base_price,
          $taxes,
          $special_requests,
          $total_amount
        ]);

        $reservation_id = $this->pdo->lastInsertId();

        // Store reservation details in session for confirmation page
        $_SESSION['last_booking'] = [
          'reservation_id' => $reservation_id,
          'reservation_code' => $reservation_code,
          'room_id' => $room_id,
          'room_number' => $room['room_number'],
          'room_type' => $room['room_type'],
          'check_in' => $check_in,
          'check_out' => $check_out,
          'guests' => $guests,
          'nights' => $nights,
          'room_total' => $room_total,
          'services_total' => $services_total,
          'tax_amount' => $tax_amount,
          'total_amount' => $total_amount,
          'deposit_amount' => $deposit_amount,
          'selected_services' => $selectedServices,
          'special_requests' => $special_requests
        ];

        // Add services
        foreach ($selectedServices as $service) {
          $stmt = $this->pdo->prepare("
                        INSERT INTO reservation_services
                        (reservation_id, service_id, quantity, total_price, created_at)
                        VALUES (?, ?, 1, ?, NOW())
                    ");
          $stmt->execute([$reservation_id, $service['id'], $service['price']]);
        }

        // Commit transaction
        $this->pdo->commit();

        // Log the action
        $this->logAction($userId, "Created booking #$reservation_id for room #$room_id");

        // Redirect to confirmation page
        $this->redirect('booking-confirmation');
      } catch (PDOException $e) {
        $this->pdo->rollBack();
        error_log("Booking creation error: " . $e->getMessage());
        $errors[] = "Failed to create booking. Please try again.";
      }
    }

    if (!empty($errors)) {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      $this->redirect('book-room');
    }
  }

  public function confirmation()
  {
    $this->requireLogin('customer');

    if (!isset($_SESSION['last_booking'])) {
      $_SESSION['error'] = "No booking found. Please make a reservation first.";
      $this->redirect('book-room');
    }

    $booking = $_SESSION['last_booking'];

    // Get service details if any
    $service_name = '';
    $service_price = 0;
    if (!empty($booking['selected_services'])) {
      $first_service = $booking['selected_services'][0];
      $service_name = $first_service['name'];
      $service_price = $first_service['price'];
    }

    $data = [
      'reservation' => [
        'id' => $booking['reservation_id'],
        'reservation_code' => $booking['reservation_code'],
        'room_number' => $booking['room_number'],
        'room_type' => $booking['room_type'],
        'check_in' => $booking['check_in'],
        'check_out' => $booking['check_out'],
        'guests' => $booking['guests'],
        'deposit_amount' => $booking['deposit_amount'],
        'created_at' => date('Y-m-d H:i:s'),
        'service_name' => $service_name,
        'service_price' => $service_price
      ],
      'nights' => $booking['nights'],
      'roomTotal' => $booking['room_total'],
      'serviceTotal' => $booking['services_total'],
      'page_title' => 'Booking Confirmation'
    ];

    $this->render('customer/booking/confirmation', $data);
  }

  public function checkAvailability()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $room_id = intval($_POST['room_id'] ?? 0);
      $check_in = $_POST['check_in'] ?? '';
      $check_out = $_POST['check_out'] ?? '';

      if ($room_id <= 0 || empty($check_in) || empty($check_out)) {
        echo json_encode(['available' => false, 'message' => 'Invalid input']);
        exit();
      }

      try {
        $stmt = $this->pdo->prepare("
                    SELECT COUNT(*) as conflicts FROM reservations
                    WHERE room_id = ?
                    AND status IN ('confirmed', 'checked_in', 'pending')
                    AND (
                        (check_in <= ? AND check_out >= ?) OR
                        (check_in <= ? AND check_out >= ?) OR
                        (check_in >= ? AND check_out <= ?)
                    )
                ");
        $stmt->execute([
          $room_id,
          $check_out,
          $check_in,
          $check_in,
          $check_out,
          $check_in,
          $check_out
        ]);
        $conflicts = $stmt->fetchColumn();

        if ($conflicts > 0) {
          echo json_encode(['available' => false, 'message' => 'Room not available for selected dates']);
        } else {
          // Get room price
          $stmt = $this->pdo->prepare("
                        SELECT rt.base_price as price_per_night, rt.capacity
                        FROM rooms r
                        JOIN room_types rt ON r.room_type_id = rt.id
                        WHERE r.id = ?
                    ");
          $stmt->execute([$room_id]);
          $room = $stmt->fetch(PDO::FETCH_ASSOC);

          // Calculate nights and total
          $check_in_date = new DateTime($check_in);
          $check_out_date = new DateTime($check_out);
          $nights = $check_in_date->diff($check_out_date)->days;
          $total = $room['price_per_night'] * $nights;

          echo json_encode([
            'available' => true,
            'price_per_night' => $room['price_per_night'],
            'nights' => $nights,
            'total' => $total,
            'capacity' => $room['capacity']
          ]);
        }
      } catch (PDOException $e) {
        error_log("Check availability error: " . $e->getMessage());
        echo json_encode(['available' => false, 'message' => 'System error']);
      }
      exit();
    }

    // If not POST, redirect to booking page
    $this->redirect('book-room');
  }

  public function calculatePrice()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $room_id = intval($_POST['room_id'] ?? 0);
      $check_in = $_POST['check_in'] ?? '';
      $check_out = $_POST['check_out'] ?? '';
      $services = $_POST['services'] ?? [];

      if ($room_id <= 0 || empty($check_in) || empty($check_out)) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit();
      }

      try {
        // Get room price
        $stmt = $this->pdo->prepare("
                    SELECT rt.base_price as price_per_night
                    FROM rooms r
                    JOIN room_types rt ON r.room_type_id = rt.id
                    WHERE r.id = ?
                ");
        $stmt->execute([$room_id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$room) {
          echo json_encode(['success' => false, 'message' => 'Room not found']);
          exit();
        }

        // Calculate nights and room total
        $check_in_date = new DateTime($check_in);
        $check_out_date = new DateTime($check_out);
        $nights = $check_in_date->diff($check_out_date)->days;
        $room_total = $room['price_per_night'] * $nights;

        // Calculate services total
        $services_total = 0;
        $service_details = [];

        if (!empty($services)) {
          $placeholders = str_repeat('?,', count($services) - 1) . '?';
          $stmt = $this->pdo->prepare("
                        SELECT id, name, price FROM services
                        WHERE id IN ($placeholders) AND is_available = 1
                    ");
          $stmt->execute($services);
          $service_details = $stmt->fetchAll(PDO::FETCH_ASSOC);

          foreach ($service_details as $service) {
            $services_total += $service['price'];
          }
        }

        $grand_total = $room_total + $services_total;

        echo json_encode([
          'success' => true,
          'room_total' => $room_total,
          'services_total' => $services_total,
          'grand_total' => $grand_total,
          'nights' => $nights,
          'services' => $service_details
        ]);
      } catch (PDOException $e) {
        error_log("Calculate price error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'System error']);
      }
      exit();
    }

    $this->redirect('book-room');
  }

  private function logAction($userId, $action)
  {
    try {
      $stmt = $this->pdo->prepare("INSERT INTO logs (user_id, action, timestamp) VALUES (?, ?, NOW())");
      $stmt->execute([$userId, $action]);
    } catch (PDOException $e) {
      error_log("Log action error: " . $e->getMessage());
    }
  }

  public function payment()
  {
    $this->requireLogin('customer');

    $userId = $_SESSION['user_id'];
    $reservationId = intval($_GET['id'] ?? 0);

    if (!$reservationId) {
      $_SESSION['error'] = "Invalid reservation ID.";
      $this->redirect('my-reservations');
    }

    // Verify the reservation belongs to the user and is pending
    $stmt = $this->pdo->prepare("
        SELECT r.*, rt.base_price as price_per_night
        FROM reservations r
        JOIN rooms rm ON r.room_id = rm.id
        JOIN room_types rt ON rm.room_type_id = rt.id
        WHERE r.id = ? AND r.user_id = ? AND r.status = 'pending'
    ");
    $stmt->execute([$reservationId, $userId]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
      $_SESSION['error'] = "Reservation not found or already processed.";
      $this->redirect('my-reservations');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->processPayment($reservation, $userId);
    } else {
      // Calculate deposit amount
      $nights = (new DateTime($reservation['check_in']))->diff(new DateTime($reservation['check_out']))->days;
      $room_total = $reservation['price_per_night'] * $nights;
      $tax_amount = $room_total * 0.10; // 10% tax
      $total_amount = $room_total + $tax_amount;
      $deposit_amount = $total_amount * 0.20; // 20% deposit

      $data = [
        'reservation' => $reservation,
        'nights' => $nights,
        'room_total' => $room_total,
        'tax_amount' => $tax_amount,
        'total_amount' => $total_amount,
        'deposit_amount' => $deposit_amount,
        'page_title' => 'Payment'
      ];

      $this->render('customer/booking/payment', $data);
    }
  }

  private function processPayment($reservation, $userId)
  {
    $payment_method = $_POST['payment_method'] ?? '';
    $card_number = $_POST['card_number'] ?? '';
    $card_expiry = $_POST['card_expiry'] ?? '';
    $card_cvc = $_POST['card_cvc'] ?? '';
    $card_name = $_POST['card_name'] ?? '';

    $errors = [];

    if (empty($payment_method)) {
      $errors[] = "Please select a payment method.";
    }

    if (empty($card_number) || !preg_match('/^\d{4}\s\d{4}\s\d{4}\s\d{4}$/', $card_number)) {
      $errors[] = "Please enter a valid card number.";
    }

    if (empty($card_expiry) || !preg_match('/^\d{2}\/\d{2}$/', $card_expiry)) {
      $errors[] = "Please enter a valid expiry date (MM/YY).";
    }

    if (empty($card_cvc) || !preg_match('/^\d{3,4}$/', $card_cvc)) {
      $errors[] = "Please enter a valid CVC.";
    }

    if (empty($card_name)) {
      $errors[] = "Please enter the name on the card.";
    }

    if (!isset($_POST['paymentTerms'])) {
      $errors[] = "Please accept the payment terms.";
    }

    if (!empty($errors)) {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      $this->redirect('customer/booking/payment?id=' . $reservation['id']);
    }

    try {
      // Calculate amounts
      $nights = (new DateTime($reservation['check_in']))->diff(new DateTime($reservation['check_out']))->days;
      $room_total = $reservation['price_per_night'] * $nights;
      $tax_amount = $room_total * 0.10;
      $total_amount = $room_total + $tax_amount;
      $deposit_amount = $total_amount * 0.20;

      // Start transaction
      $this->pdo->beginTransaction();

      // Create payment record
      $stmt = $this->pdo->prepare("
          INSERT INTO payments
          (reservation_id, payment_method, amount, transaction_id, payment_date, status, card_last_four)
          VALUES (?, ?, ?, ?, NOW(), 'completed', ?)
      ");
      $transaction_id = 'TXN' . strtoupper(substr(uniqid(), -8));
      $card_last_four = substr(str_replace(' ', '', $card_number), -4);
      $stmt->execute([
        $reservation['id'],
        $payment_method,
        $deposit_amount,
        $transaction_id,
        $card_last_four
      ]);

      // Update reservation status to confirmed
      $stmt = $this->pdo->prepare("
          UPDATE reservations SET
          status = 'confirmed',
          payment_status = 'paid'
          WHERE id = ?
      ");
      $stmt->execute([$reservation['id']]);

      // Log the payment
      $this->logAction($userId, "Paid deposit for reservation #$reservation[id]");

      // Commit transaction
      $this->pdo->commit();

      $_SESSION['success'] = "Payment processed successfully! Your reservation is now confirmed.";
      unset($_SESSION['last_booking']);
      $this->redirect('my-reservations');
    } catch (PDOException $e) {
      $this->pdo->rollBack();
      error_log("Payment processing error: " . $e->getMessage());
      $_SESSION['error'] = "Payment processing failed. Please try again.";
      $this->redirect('customer/booking/payment?id=' . $reservation['id']);
    }
  }
}
