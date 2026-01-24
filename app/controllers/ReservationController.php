<?php
// app/controllers/ReservationController.php
require_once __DIR__ . '/Path/BaseController.php';

class ReservationController extends BaseController
{
    public function __construct($pdo)
    {
        parent::__construct($pdo);
    }

    public function index()
    {
        $this->requireLogin('customer');

        $userId = $_SESSION['user_id'];

        // Get filter parameters
        $status = $_GET['status'] ?? '';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 10;

        // Build query - FIXED: Removed duplicate price_per_night column
        $query = "
            SELECT r.*,
                   rm.room_number,
                   rt.name as room_type,
                   rt.base_price,
                   rt.description as room_description
            FROM reservations r
            JOIN rooms rm ON r.room_id = rm.id
            JOIN room_types rt ON rm.room_type_id = rt.id
            WHERE r.user_id = ?
        ";
        $params = [$userId];

        if (!empty($status)) {
            $query .= " AND r.status = ?";
            $params[] = $status;
        }

        // Get total count
        $countQuery = "SELECT COUNT(*) FROM (" . str_replace("SELECT r.*,", "SELECT r.id,", $query) . ") as total";
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

        // Calculate upcoming vs past
        $today = date('Y-m-d');
        $upcomingCount = 0;
        $pastCount = 0;

        foreach ($reservations as $res) {
            if ($res['check_in'] >= $today && in_array($res['status'], ['pending', 'confirmed'])) {
                $upcomingCount++;
            } elseif (in_array($res['status'], ['checked_out', 'cancelled'])) {
                $pastCount++;
            }
        }

        $data = [
            'reservations' => $reservations,
            'totalReservations' => $totalReservations, // Added this missing variable
            'status' => $status,
            'page' => $page,
            'totalPages' => $totalPages,
            'upcomingCount' => $upcomingCount,
            'pastCount' => $pastCount,
            'page_title' => 'My Reservations'
        ];

        $this->render('customer/reservations/index', $data);
    }

    public function view($id)
    {
        $this->requireLogin('customer');

        $userId = $_SESSION['user_id'];

        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*,
                       rm.room_number,
                       rt.name as room_type,
                       rt.description as room_description,
                       rt.base_price as price_per_night,
                       GROUP_CONCAT(s.name SEPARATOR ', ') as services,
                       SUM(rs.total_price) as total_services_price
                FROM reservations r
                JOIN rooms rm ON r.room_id = rm.id
                JOIN room_types rt ON rm.room_type_id = rt.id
                LEFT JOIN reservation_services rs ON r.id = rs.reservation_id
                LEFT JOIN services s ON rs.service_id = s.id
                WHERE r.id = ? AND r.user_id = ?
                GROUP BY r.id
            ");
            $stmt->execute([$id, $userId]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$reservation) {
                $_SESSION['error'] = "Reservation not found or access denied.";
                $this->redirect('my-reservations');
            }

            // Get individual services
            $stmt = $this->pdo->prepare("
                SELECT s.name, s.description, rs.total_price as service_price, rs.quantity
                FROM reservation_services rs
                JOIN services s ON rs.service_id = s.id
                WHERE rs.reservation_id = ?
            ");
            $stmt->execute([$id]);
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Calculate nights
            $check_in = new DateTime($reservation['check_in']);
            $check_out = new DateTime($reservation['check_out']);
            $nights = $check_in->diff($check_out)->days;
            if ($nights == 0) $nights = 1; // Minimum 1 night

            $reservation['nights'] = $nights;
            $reservation['room_total'] = $reservation['price_per_night'] * $nights;

            $data = [
                'reservation' => $reservation,
                'services' => $services,
                'page_title' => 'View Reservation'
            ];

            $this->render('customer/reservations/view', $data);
        } catch (PDOException $e) {
            error_log("View reservation error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load reservation.";
            $this->redirect('my-reservations');
        }
    }

    public function cancel($id)
    {
        $this->requireLogin('customer');

        $userId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reason = trim($_POST['reason'] ?? '');

            try {
                // Check if reservation exists and belongs to user
                $stmt = $this->pdo->prepare("
                    SELECT status, check_in FROM reservations
                    WHERE id = ? AND user_id = ?
                ");
                $stmt->execute([$id, $userId]);
                $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$reservation) {
                    $_SESSION['error'] = "Reservation not found or access denied.";
                    $this->redirect('my-reservations');
                }

                // Check if reservation can be cancelled
                if (!in_array($reservation['status'], ['pending', 'confirmed'])) {
                    $_SESSION['error'] = "Only pending or confirmed reservations can be cancelled.";
                    $this->redirect('my-reservations');
                }

                // Check cancellation policy (e.g., at least 24 hours before check-in)
                $check_in = new DateTime($reservation['check_in']);
                $now = new DateTime();
                $hours_diff = ($check_in->getTimestamp() - $now->getTimestamp()) / 3600;

                if ($hours_diff < 24) {
                    $_SESSION['error'] = "Cannot cancel within 24 hours of check-in.";
                    $this->redirect('my-reservations');
                }

                // Update reservation status with reason
                $stmt = $this->pdo->prepare("
                    UPDATE reservations SET
                    status = 'cancelled',
                    cancellation_reason = ?,
                    updated_at = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([$reason, $id]);

                // Log the action
                $this->logAction($userId, "Cancelled reservation #$id. Reason: $reason");

                $_SESSION['success'] = "Reservation cancelled successfully.";
                $this->redirect('my-reservations');

            } catch (PDOException $e) {
                error_log("Cancel reservation error: " . $e->getMessage());
                $_SESSION['error'] = "Failed to cancel reservation.";
                $this->redirect('my-reservations');
            }
        } else {
            // Show cancellation form
            try {
                $stmt = $this->pdo->prepare("
                    SELECT id, reservation_code, check_in, total_amount
                    FROM reservations
                    WHERE id = ? AND user_id = ?
                ");
                $stmt->execute([$id, $userId]);
                $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$reservation) {
                    $_SESSION['error'] = "Reservation not found or access denied.";
                    $this->redirect('my-reservations');
                }

                $data = [
                    'reservation' => $reservation,
                    'page_title' => 'Cancel Reservation'
                ];

                $this->render('customer/reservations/cancel', $data);
            } catch (PDOException $e) {
                error_log("Show cancellation form error: " . $e->getMessage());
                $_SESSION['error'] = "Failed to load reservation.";
                $this->redirect('my-reservations');
            }
        }
    }

    public function requestCancellation($id)
    {
        $this->requireLogin('customer');

        $userId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reason = trim($_POST['reason'] ?? '');

            if (empty($reason)) {
                $_SESSION['error'] = "Please provide a cancellation reason.";
                $this->redirect('my-reservations', ['sub_action' => 'view', 'id' => $id]);
            }

            try {
                // Check if reservation exists and belongs to user
                $stmt = $this->pdo->prepare("
                    SELECT status FROM reservations
                    WHERE id = ? AND user_id = ?
                ");
                $stmt->execute([$id, $userId]);
                $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$reservation) {
                    $_SESSION['error'] = "Reservation not found or access denied.";
                    $this->redirect('my-reservations');
                }

                if (!in_array($reservation['status'], ['pending', 'confirmed'])) {
                    $_SESSION['error'] = "Only pending or confirmed reservations can be cancelled.";
                    $this->redirect('my-reservations');
                }

                // Update reservation with cancellation request
                $stmt = $this->pdo->prepare("
                    UPDATE reservations SET
                    status = 'cancelled',
                    cancellation_reason = ?,
                    admin_notes = CONCAT(COALESCE(admin_notes, ''), ?),
                    updated_at = NOW()
                    WHERE id = ?
                ");

                $note = "\n[" . date('Y-m-d H:i:s') . "] Cancellation requested by customer. Reason: $reason";
                $stmt->execute([$reason, $note, $id]);

                // Log the action
                $this->logAction($userId, "Requested cancellation for reservation #$id. Reason: $reason");

                $_SESSION['success'] = "Cancellation request submitted successfully.";
                $this->redirect('my-reservations');

            } catch (PDOException $e) {
                error_log("Request cancellation error: " . $e->getMessage());
                $_SESSION['error'] = "Failed to submit cancellation request.";
                $this->redirect('my-reservations', ['sub_action' => 'view', 'id' => $id]);
            }
        } else {
            // Show cancellation request form - same as cancel form
            try {
                $stmt = $this->pdo->prepare("
                    SELECT id, reservation_code, check_in, total_amount
                    FROM reservations
                    WHERE id = ? AND user_id = ?
                ");
                $stmt->execute([$id, $userId]);
                $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$reservation) {
                    $_SESSION['error'] = "Reservation not found or access denied.";
                    $this->redirect('my-reservations');
                }

                $data = [
                    'reservation' => $reservation,
                    'page_title' => 'Request Cancellation'
                ];

                $this->render('customer/reservations/cancel', $data);
            } catch (PDOException $e) {
                error_log("Show cancellation form error: " . $e->getMessage());
                $_SESSION['error'] = "Failed to load reservation.";
                $this->redirect('my-reservations');
            }
        }
    }

    public function printInvoice($id)
    {
        $this->requireLogin('customer');

        $userId = $_SESSION['user_id'];

        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*,
                       rm.room_number,
                       rt.name as room_type,
                       rt.base_price as price_per_night,
                       u.username, u.email, u.first_name, u.last_name, u.phone, u.address,
                       GROUP_CONCAT(s.name SEPARATOR ', ') as services,
                       SUM(rs.total_price) as total_services_price
                FROM reservations r
                JOIN rooms rm ON r.room_id = rm.id
                JOIN room_types rt ON rm.room_type_id = rt.id
                JOIN users u ON r.user_id = u.id
                LEFT JOIN reservation_services rs ON r.id = rs.reservation_id
                LEFT JOIN services s ON rs.service_id = s.id
                WHERE r.id = ? AND r.user_id = ?
                GROUP BY r.id
            ");
            $stmt->execute([$id, $userId]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$reservation) {
                $_SESSION['error'] = "Reservation not found or access denied.";
                $this->redirect('my-reservations');
            }

            // Get individual services
            $stmt = $this->pdo->prepare("
                SELECT s.name, s.description, rs.total_price as service_price, rs.quantity
                FROM reservation_services rs
                JOIN services s ON rs.service_id = s.id
                WHERE rs.reservation_id = ?
            ");
            $stmt->execute([$id]);
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Calculate nights and totals
            $check_in = new DateTime($reservation['check_in']);
            $check_out = new DateTime($reservation['check_out']);
            $nights = $check_in->diff($check_out)->days;
            if ($nights == 0) $nights = 1; // Minimum 1 night

            $roomTotal = $reservation['price_per_night'] * $nights;
            $servicesTotal = $reservation['total_services_price'] ?: 0;
            $tax_rate = 0.10; // 10% tax
            $taxAmount = ($roomTotal + $servicesTotal) * $tax_rate;
            $grandTotal = $roomTotal + $servicesTotal + $taxAmount;

            $data = [
                'reservation' => $reservation,
                'services' => $services,
                'nights' => $nights,
                'roomTotal' => $roomTotal, // Fixed variable name
                'servicesTotal' => $servicesTotal, // Fixed variable name
                'taxAmount' => $taxAmount, // Fixed variable name
                'grandTotal' => $grandTotal, // Fixed variable name
                'page_title' => 'Invoice'
            ];

            $this->render('customer/reservations/invoice', $data);
        } catch (PDOException $e) {
            error_log("Print invoice error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to generate invoice.";
            $this->redirect('my-reservations');
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
