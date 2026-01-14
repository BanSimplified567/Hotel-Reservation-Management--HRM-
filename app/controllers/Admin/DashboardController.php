<?php
// app/controllers/Admin/DashboardController.php

class AdminDashboardController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        // Check if user is logged in and has admin/staff role
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'staff'])) {
            header('Location: index.php?action=403');
            exit();
        }

        // Get dashboard statistics
        $stats = $this->getDashboardStatistics();

        // Get recent reservations
        $recentReservations = $this->getRecentReservations();

        // Get recent users (for admin only)
        $recentUsers = [];
        if ($_SESSION['role'] == 'admin') {
            $recentUsers = $this->getRecentUsers();
        }

        // Get room occupancy
        $roomOccupancy = $this->getRoomOccupancy();

        // Get revenue data
        $revenueData = $this->getRevenueData();

        require_once '../app/views/admin/dashboard.php';
    }

    private function getDashboardStatistics()
    {
        $stats = [];

        try {
            // Total reservations
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations");
            $stmt->execute();
            $stats['total_reservations'] = $stmt->fetchColumn();

            // Active reservations
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE status IN ('confirmed', 'checked_in')");
            $stmt->execute();
            $stats['active_reservations'] = $stmt->fetchColumn();

            // Pending reservations
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE status = 'pending'");
            $stmt->execute();
            $stats['pending_reservations'] = $stmt->fetchColumn();

            // Total customers
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'customer'");
            $stmt->execute();
            $stats['total_customers'] = $stmt->fetchColumn();

            // Total rooms
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM rooms");
            $stmt->execute();
            $stats['total_rooms'] = $stmt->fetchColumn();

            // Available rooms
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM rooms WHERE status = 'available'");
            $stmt->execute();
            $stats['available_rooms'] = $stmt->fetchColumn();

            // Today's check-ins
            $today = date('Y-m-d');
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE check_in = ? AND status IN ('confirmed', 'pending')");
            $stmt->execute([$today]);
            $stats['today_checkins'] = $stmt->fetchColumn();

            // Today's check-outs
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE check_out = ? AND status IN ('checked_in', 'confirmed')");
            $stmt->execute([$today]);
            $stats['today_checkouts'] = $stmt->fetchColumn();

            // Monthly revenue
            $monthStart = date('Y-m-01');
            $monthEnd = date('Y-m-t');
            $stmt = $this->pdo->prepare("SELECT SUM(total_amount) FROM reservations WHERE status = 'completed' AND created_at BETWEEN ? AND ?");
            $stmt->execute([$monthStart, $monthEnd]);
            $stats['monthly_revenue'] = $stmt->fetchColumn() ?: 0;

        } catch (PDOException $e) {
            error_log("Dashboard stats error: " . $e->getMessage());
            // Return empty stats if there's an error
            return array_fill_keys([
                'total_reservations', 'active_reservations', 'pending_reservations',
                'total_customers', 'total_rooms', 'available_rooms',
                'today_checkins', 'today_checkouts', 'monthly_revenue'
            ], 0);
        }

        return $stats;
    }

    private function getRecentReservations($limit = 10)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*,
                       u.first_name, u.last_name, u.email,
                       rm.room_number, rm.type as room_type
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                JOIN rooms rm ON r.room_id = rm.id
                ORDER BY r.created_at DESC
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Recent reservations error: " . $e->getMessage());
            return [];
        }
    }

    private function getRecentUsers($limit = 5)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, username, email, first_name, last_name, role, created_at
                FROM users
                WHERE role = 'customer'
                ORDER BY created_at DESC
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Recent users error: " . $e->getMessage());
            return [];
        }
    }

    private function getRoomOccupancy()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT
                    type,
                    COUNT(*) as total_rooms,
                    SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) as available,
                    SUM(CASE WHEN status = 'occupied' THEN 1 ELSE 0 END) as occupied,
                    SUM(CASE WHEN status = 'maintenance' THEN 1 ELSE 0 END) as maintenance
                FROM rooms
                GROUP BY type
                ORDER BY type
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Room occupancy error: " . $e->getMessage());
            return [];
        }
    }

    private function getRevenueData($days = 30)
    {
        try {
            $startDate = date('Y-m-d', strtotime("-$days days"));
            $endDate = date('Y-m-d');

            $stmt = $this->pdo->prepare("
                SELECT
                    DATE(created_at) as date,
                    SUM(total_amount) as daily_revenue,
                    COUNT(*) as reservation_count
                FROM reservations
                WHERE status = 'completed'
                AND created_at BETWEEN ? AND ?
                GROUP BY DATE(created_at)
                ORDER BY date
            ");
            $stmt->execute([$startDate, $endDate]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Revenue data error: " . $e->getMessage());
            return [];
        }
    }
}
