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
                SELECT id, room_number, type, description,
                       price_per_night, capacity, amenities, status
                FROM rooms
                WHERE status = 'available'
                ORDER BY type, room_number
            ");
            $stmt->execute();
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get available services
            $stmt = $this->pdo->prepare("
                SELECT id, name, description, price
                FROM services
                WHERE status = 'active'
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
        $special_requests = trim($_POST['special_requests'] ?? '');
        $services = $_POST['services'] ?? [];

        // Validation
        if ($room_id <= 0) {
            $errors[] = "Please select a room.";
        }

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
                    AND status IN ('confirmed', 'checked_in')
                    AND (
                        (check_in <= ? AND check_out >= ?) OR
                        (check_in <= ? AND check_out >= ?) OR
                        (check_in >= ? AND check_out <= ?)
                    )
                ");
                $stmt->execute([
                    $room_id,
                    $check_out, $check_in,
                    $check_in, $check_out,
                    $check_in, $check_out
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
                $stmt = $this->pdo->prepare("SELECT capacity FROM rooms WHERE id = ?");
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
                    WHERE id IN ($placeholders) AND status = 'active'
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
                $stmt = $this->pdo->prepare("SELECT price_per_night FROM rooms WHERE id = ?");
                $stmt->execute([$room_id]);
                $room = $stmt->fetch(PDO::FETCH_ASSOC);

                $nights = $check_in_date->diff($check_out_date)->days;
                $room_total = $room['price_per_night'] * $nights;
                $total_amount = $room_total + $services_total;

                // Start transaction
                $this->pdo->beginTransaction();

                // Create reservation
                $stmt = $this->pdo->prepare("
                    INSERT INTO reservations
                    (user_id, room_id, check_in, check_out, guests,
                     special_requests, total_amount, status, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
                ");
                $stmt->execute([
                    $userId, $room_id, $check_in, $check_out, $guests,
                    $special_requests, $total_amount
                ]);

                $reservation_id = $this->pdo->lastInsertId();

                // Add services
                foreach ($selectedServices as $service) {
                    $stmt = $this->pdo->prepare("
                        INSERT INTO reservation_services
                        (reservation_id, service_id, service_price)
                        VALUES (?, ?, ?)
                    ");
                    $stmt->execute([$reservation_id, $service['id'], $service['price']]);
                }

                // Commit transaction
                $this->pdo->commit();

                // Log the action
                $this->logAction($userId, "Created booking #$reservation_id for room #$room_id");

                // Send confirmation email (in production)
                // $this->sendConfirmationEmail($userId, $reservation_id);

                $_SESSION['success'] = "Booking submitted successfully! Your reservation is pending approval.";
                $this->redirect('my-reservations');

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

    public function checkAvailability()
    {
        // This can be called via AJAX
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
                    AND status IN ('confirmed', 'checked_in')
                    AND (
                        (check_in <= ? AND check_out >= ?) OR
                        (check_in <= ? AND check_out >= ?) OR
                        (check_in >= ? AND check_out <= ?)
                    )
                ");
                $stmt->execute([
                    $room_id,
                    $check_out, $check_in,
                    $check_in, $check_out,
                    $check_in, $check_out
                ]);
                $conflicts = $stmt->fetchColumn();

                if ($conflicts > 0) {
                    echo json_encode(['available' => false, 'message' => 'Room not available for selected dates']);
                } else {
                    // Get room price
                    $stmt = $this->pdo->prepare("SELECT price_per_night, capacity FROM rooms WHERE id = ?");
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
                $stmt = $this->pdo->prepare("SELECT price_per_night FROM rooms WHERE id = ?");
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
                        WHERE id IN ($placeholders) AND status = 'active'
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
            $stmt = $this->pdo->prepare("INSERT INTO logs (user_id, action, created_at) VALUES (?, ?, NOW())");
            $stmt->execute([$userId, $action]);
        } catch (PDOException $e) {
            error_log("Log action error: " . $e->getMessage());
        }
    }

    private function sendConfirmationEmail($userId, $reservationId)
    {
        // In production, implement email sending
        // This is a placeholder function
        return true;
    }
}
