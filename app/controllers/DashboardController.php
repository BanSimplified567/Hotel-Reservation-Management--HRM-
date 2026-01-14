<?php
// app/controllers/DashboardController.php

class DashboardController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
            header('Location: index.php?action=403');
            exit();
        }

        $userId = $_SESSION['user_id'];

        // Get user details
        $user = $this->getUserDetails($userId);

        // Get upcoming reservations
        $upcomingReservations = $this->getUpcomingReservations($userId);

        // Get past reservations
        $pastReservations = $this->getPastReservations($userId);

        // Get loyalty points or rewards if applicable
        $loyaltyInfo = $this->getLoyaltyInfo($userId);

        require_once '../app/views/customer/dashboard.php';
    }

    private function getUserDetails($userId)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, username, email, first_name, last_name, phone,
                       address, created_at
                FROM users
                WHERE id = ?
            ");
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get user details error: " . $e->getMessage());
            return [];
        }
    }

    private function getUpcomingReservations($userId, $limit = 5)
    {
        try {
            $today = date('Y-m-d');
            $stmt = $this->pdo->prepare("
                SELECT r.*,
                       rm.room_number, rm.type as room_type,
                       rm.price_per_night
                FROM reservations r
                JOIN rooms rm ON r.room_id = rm.id
                WHERE r.user_id = ?
                AND r.check_in >= ?
                AND r.status IN ('confirmed', 'pending')
                ORDER BY r.check_in ASC
                LIMIT ?
            ");
            $stmt->execute([$userId, $today, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Upcoming reservations error: " . $e->getMessage());
            return [];
        }
    }

    private function getPastReservations($userId, $limit = 5)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*,
                       rm.room_number, rm.type as room_type,
                       rm.price_per_night
                FROM reservations r
                JOIN rooms rm ON r.room_id = rm.id
                WHERE r.user_id = ?
                AND (r.check_out < CURDATE() OR r.status IN ('completed', 'cancelled'))
                ORDER BY r.check_out DESC
                LIMIT ?
            ");
            $stmt->execute([$userId, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Past reservations error: " . $e->getMessage());
            return [];
        }
    }

    private function getLoyaltyInfo($userId)
    {
        try {
            // Calculate loyalty points based on completed reservations
            $stmt = $this->pdo->prepare("
                SELECT
                    COUNT(*) as total_stays,
                    SUM(total_amount) as total_spent,
                    COUNT(*) * 100 as loyalty_points -- Example: 100 points per stay
                FROM reservations
                WHERE user_id = ?
                AND status = 'completed'
            ");
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Loyalty info error: " . $e->getMessage());
            return ['total_stays' => 0, 'total_spent' => 0, 'loyalty_points' => 0];
        }
    }
}
