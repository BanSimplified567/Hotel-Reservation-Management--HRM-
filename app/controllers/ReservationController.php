<?php
// app/controllers/ReservationController.php

class ReservationController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        // Check if user is logged in as customer
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
            header('Location: index.php?action=403');
            exit();
        }

        $userId = $_SESSION['user_id'];

        // Get filter parameters
        $status = $_GET['status'] ?? '';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 10;

        // Build query
        $query = "
            SELECT r.*,
                   rm.room_number, rm.type as room_type,
                   rm.price_per_night
            FROM reservations r
            JOIN rooms rm ON r.room_id = rm.id
            WHERE r.user_id = ?
        ";
        $params = [$userId];

        if (!empty($status)) {
            $query .= " AND r.status = ?";
            $params[] = $status;
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

        // Calculate upcoming vs past
        $today = date('Y-m-d');
        $upcomingCount = 0;
        $pastCount = 0;

        foreach ($reservations as $res) {
            if ($res['check_in'] >= $today && in_array($res['status'], ['pending', 'confirmed'])) {
                $upcomingCount++;
            } else {
                $pastCount++;
            }
        }

        require_once '../app/views/customer/reservations/index.php';
    }

    public function view($id)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
            header('Location: index.php?action=403');
            exit();
        }

        $userId = $_SESSION['user_id'];

        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*,
                       rm.room_number, rm.type as room_type,
                       rm.description as room_description,
                       rm.price_per_night,
                       GROUP_CONCAT(s.name SEPARATOR ', ') as services,
                       SUM(rs.service_price) as total_services_price
                FROM reservations r
                JOIN rooms rm ON r.room_id = rm.id
                LEFT JOIN reservation_services rs ON r.id = rs.reservation_id
                LEFT JOIN services s ON rs.service_id = s.id
                WHERE r.id = ? AND r.user_id = ?
                GROUP BY r.id
            ");
            $stmt->execute([$id, $userId]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$reservation) {
                $_SESSION['error'] = "Reservation not found or access denied.";
                header('Location: index.php?action=my-reservations');
                exit();
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

            // Calculate nights
            $check_in = new DateTime($reservation['check_in']);
            $check_out = new DateTime($reservation['check_out']);
            $nights = $check_in->diff($check_out)->days;

            $reservation['nights'] = $nights;
            $reservation['room_total'] = $reservation['price_per_night'] * $nights;

            require_once '../app/views/customer/reservations/view.php';
        } catch (PDOException $e) {
            error_log("View reservation error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load reservation.";
            header('Location: index.php?action=my-reservations');
            exit();
        }
    }

    public function cancel($id)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
            header('Location: index.php?action=403');
            exit();
        }

        $userId = $_SESSION['user_id'];

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
                header('Location: index.php?action=my-reservations');
                exit();
            }

            // Check if reservation can be cancelled
            if (!in_array($reservation['status'], ['pending', 'confirmed'])) {
                $_SESSION['error'] = "Only pending or confirmed reservations can be cancelled.";
                header('Location: index.php?action=my-reservations');
                exit();
            }

            // Check cancellation policy (e.g., at least 24 hours before check-in)
            $check_in = new DateTime($reservation['check_in']);
            $now = new DateTime();
            $hours_diff = ($check_in->getTimestamp() - $now->getTimestamp()) / 3600;

            if ($hours_diff < 24) {
                $_SESSION['error'] = "Cannot cancel within 24 hours of check-in.";
                header('Location: index.php?action=my-reservations');
                exit();
            }

            // Update reservation status
            $stmt = $this->pdo->prepare("
                UPDATE reservations SET
                status = 'cancelled',
                updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$id]);

            // Log the action
            $this->logAction($userId, "Cancelled reservation #$id");

            $_SESSION['success'] = "Reservation cancelled successfully.";
            header('Location: index.php?action=my-reservations');
            exit();

        } catch (PDOException $e) {
            error_log("Cancel reservation error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to cancel reservation.";
            header('Location: index.php?action=my-reservations');
            exit();
        }
    }

    public function requestCancellation($id)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
            header('Location: index.php?action=403');
            exit();
        }

        $userId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reason = trim($_POST['reason'] ?? '');

            if (empty($reason)) {
                $_SESSION['error'] = "Please provide a cancellation reason.";
                header("Location: index.php?action=my-reservations&action=view&id=$id");
                exit();
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
                    header('Location: index.php?action=my-reservations');
                    exit();
                }

                if (!in_array($reservation['status'], ['pending', 'confirmed'])) {
                    $_SESSION['error'] = "Only pending or confirmed reservations can be cancelled.";
                    header('Location: index.php?action=my-reservations');
                    exit();
                }

                // Update reservation with cancellation request
                $stmt = $this->pdo->prepare("
                    UPDATE reservations SET
                    status = 'cancellation_requested',
                    admin_notes = CONCAT(COALESCE(admin_notes, ''), ?),
                    updated_at = NOW()
                    WHERE id = ?
                ");

                $note = "\n[" . date('Y-m-d H:i:s') . "] Cancellation requested by customer. Reason: $reason";
                $stmt->execute([$note, $id]);

                // Log the action
                $this->logAction($userId, "Requested cancellation for reservation #$id. Reason: $reason");

                $_SESSION['success'] = "Cancellation request submitted. Please wait for admin approval.";
                header('Location: index.php?action=my-reservations');
                exit();

            } catch (PDOException $e) {
                error_log("Request cancellation error: " . $e->getMessage());
                $_SESSION['error'] = "Failed to submit cancellation request.";
                header("Location: index.php?action=my-reservations&action=view&id=$id");
                exit();
            }
        } else {
            // Show cancellation request form
            try {
                $stmt = $this->pdo->prepare("
                    SELECT id, check_in, total_amount
                    FROM reservations
                    WHERE id = ? AND user_id = ?
                ");
                $stmt->execute([$id, $userId]);
                $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$reservation) {
                    $_SESSION['error'] = "Reservation not found or access denied.";
                    header('Location: index.php?action=my-reservations');
                    exit();
                }

                require_once '../app/views/customer/reservations/cancel.php';
            } catch (PDOException $e) {
                error_log("Show cancellation form error: " . $e->getMessage());
                $_SESSION['error'] = "Failed to load reservation.";
                header('Location: index.php?action=my-reservations');
                exit();
            }
        }
    }

    public function printInvoice($id)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
            header('Location: index.php?action=403');
            exit();
        }

        $userId = $_SESSION['user_id'];

        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*,
                       rm.room_number, rm.type as room_type,
                       rm.price_per_night,
                       u.username, u.email, u.first_name, u.last_name, u.phone, u.address,
                       GROUP_CONCAT(s.name SEPARATOR ', ') as services,
                       SUM(rs.service_price) as total_services_price
                FROM reservations r
                JOIN rooms rm ON r.room_id = rm.id
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
                header('Location: index.php?action=my-reservations');
                exit();
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

            // Calculate nights and totals
            $check_in = new DateTime($reservation['check_in']);
            $check_out = new DateTime($reservation['check_out']);
            $nights = $check_in->diff($check_out)->days;

            $room_total = $reservation['price_per_night'] * $nights;
            $services_total = $reservation['total_services_price'] ?: 0;
            $grand_total = $room_total + $services_total;

            require_once '../app/views/customer/reservations/invoice.php';
        } catch (PDOException $e) {
            error_log("Print invoice error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to generate invoice.";
            header('Location: index.php?action=my-reservations');
            exit();
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
