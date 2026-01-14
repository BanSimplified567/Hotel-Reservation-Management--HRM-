<?php
// app/controllers/Admin/ReportController.php

class ReportController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
            header('Location: index.php?action=403');
            exit();
        }

        // Get report parameters
        $report_type = $_GET['type'] ?? 'revenue';
        $period = $_GET['period'] ?? 'month';
        $start_date = $_GET['start_date'] ?? date('Y-m-01');
        $end_date = $_GET['end_date'] ?? date('Y-m-d');
        $format = $_GET['format'] ?? 'view'; // view, pdf, excel

        // Generate report based on type
        $reportData = [];
        $reportTitle = '';

        switch ($report_type) {
            case 'revenue':
                $reportData = $this->generateRevenueReport($start_date, $end_date);
                $reportTitle = 'Revenue Report';
                break;

            case 'occupancy':
                $reportData = $this->generateOccupancyReport($start_date, $end_date);
                $reportTitle = 'Occupancy Report';
                break;

            case 'reservations':
                $reportData = $this->generateReservationsReport($start_date, $end_date);
                $reportTitle = 'Reservations Report';
                break;

            case 'customers':
                $reportData = $this->generateCustomersReport($start_date, $end_date);
                $reportTitle = 'Customers Report';
                break;

            case 'services':
                $reportData = $this->generateServicesReport($start_date, $end_date);
                $reportTitle = 'Services Report';
                break;
        }

        // Export if requested
        if ($format == 'pdf') {
            $this->exportPDF($reportData, $reportTitle);
        } elseif ($format == 'excel') {
            $this->exportExcel($reportData, $reportTitle);
        }

        // Calculate summary statistics
        $summary = $this->calculateSummary($reportData, $report_type);

        require_once '../app/views/admin/reports/index.php';
    }

    private function generateRevenueReport($start_date, $end_date)
    {
        try {
            // Daily revenue
            $stmt = $this->pdo->prepare("
                SELECT
                    DATE(created_at) as date,
                    COUNT(*) as reservation_count,
                    SUM(total_amount) as total_revenue,
                    AVG(total_amount) as avg_revenue
                FROM reservations
                WHERE status = 'completed'
                AND created_at BETWEEN ? AND ?
                GROUP BY DATE(created_at)
                ORDER BY date
            ");
            $stmt->execute([$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
            $dailyData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Revenue by room type
            $stmt = $this->pdo->prepare("
                SELECT
                    rm.type,
                    COUNT(*) as reservation_count,
                    SUM(r.total_amount) as total_revenue,
                    AVG(r.total_amount) as avg_revenue
                FROM reservations r
                JOIN rooms rm ON r.room_id = rm.id
                WHERE r.status = 'completed'
                AND r.created_at BETWEEN ? AND ?
                GROUP BY rm.type
                ORDER BY total_revenue DESC
            ");
            $stmt->execute([$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
            $byRoomType = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Revenue by service
            $stmt = $this->pdo->prepare("
                SELECT
                    s.name,
                    COUNT(rs.id) as service_count,
                    SUM(rs.service_price) as total_revenue
                FROM reservation_services rs
                JOIN services s ON rs.service_id = s.id
                JOIN reservations r ON rs.reservation_id = r.id
                WHERE r.status = 'completed'
                AND r.created_at BETWEEN ? AND ?
                GROUP BY s.id
                ORDER BY total_revenue DESC
            ");
            $stmt->execute([$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
            $byService = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'daily' => $dailyData,
                'by_room_type' => $byRoomType,
                'by_service' => $byService
            ];
        } catch (PDOException $e) {
            error_log("Revenue report error: " . $e->getMessage());
            return [];
        }
    }

    private function generateOccupancyReport($start_date, $end_date)
    {
        try {
            // Room occupancy by date
            $stmt = $this->pdo->prepare("
                SELECT
                    date_range.date,
                    COUNT(DISTINCT r.id) as total_rooms,
                    COUNT(DISTINCT res.room_id) as occupied_rooms,
                    ROUND((COUNT(DISTINCT res.room_id) * 100.0 / COUNT(DISTINCT r.id)), 2) as occupancy_rate
                FROM (
                    SELECT DATE(?) + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as date
                    FROM (SELECT 0 as a UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) as a
                    CROSS JOIN (SELECT 0 as a UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) as b
                    CROSS JOIN (SELECT 0 as a UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) as c
                ) date_range
                CROSS JOIN rooms r
                LEFT JOIN reservations res ON r.id = res.room_id
                    AND res.status IN ('confirmed', 'checked_in')
                    AND date_range.date BETWEEN res.check_in AND DATE_SUB(res.check_out, INTERVAL 1 DAY)
                WHERE date_range.date BETWEEN ? AND ?
                GROUP BY date_range.date
                ORDER BY date_range.date
            ");
            $stmt->execute([$start_date, $start_date, $end_date]);
            $dailyOccupancy = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Occupancy by room type
            $stmt = $this->pdo->prepare("
                SELECT
                    rm.type,
                    COUNT(DISTINCT rm.id) as total_rooms,
                    COUNT(DISTINCT CASE WHEN res.id IS NOT NULL THEN rm.id END) as occupied_rooms,
                    ROUND((COUNT(DISTINCT CASE WHEN res.id IS NOT NULL THEN rm.id END) * 100.0 / COUNT(DISTINCT rm.id)), 2) as occupancy_rate
                FROM rooms rm
                LEFT JOIN reservations res ON rm.id = res.room_id
                    AND res.status IN ('confirmed', 'checked_in')
                    AND ? BETWEEN res.check_in AND DATE_SUB(res.check_out, INTERVAL 1 DAY)
                GROUP BY rm.type
                ORDER BY rm.type
            ");
            $stmt->execute([date('Y-m-d')]);
            $byRoomType = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'daily' => $dailyOccupancy,
                'by_room_type' => $byRoomType
            ];
        } catch (PDOException $e) {
            error_log("Occupancy report error: " . $e->getMessage());
            return [];
        }
    }

    private function generateReservationsReport($start_date, $end_date)
    {
        try {
            // Reservations by status
            $stmt = $this->pdo->prepare("
                SELECT
                    status,
                    COUNT(*) as count,
                    AVG(total_amount) as avg_amount,
                    SUM(total_amount) as total_amount,
                    MIN(created_at) as first_reservation,
                    MAX(created_at) as last_reservation
                FROM reservations
                WHERE created_at BETWEEN ? AND ?
                GROUP BY status
                ORDER BY count DESC
            ");
            $stmt->execute([$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
            $byStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Reservations by source (if you have this field)
            $stmt = $this->pdo->prepare("
                SELECT
                    DATE(created_at) as date,
                    COUNT(*) as count,
                    SUM(total_amount) as total_amount
                FROM reservations
                WHERE created_at BETWEEN ? AND ?
                GROUP BY DATE(created_at)
                ORDER BY date
            ");
            $stmt->execute([$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
            $byDate = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Top customers by reservations
            $stmt = $this->pdo->prepare("
                SELECT
                    u.id,
                    u.username,
                    u.email,
                    u.first_name,
                    u.last_name,
                    COUNT(r.id) as reservation_count,
                    SUM(r.total_amount) as total_spent,
                    AVG(r.total_amount) as avg_spent
                FROM users u
                JOIN reservations r ON u.id = r.user_id
                WHERE r.created_at BETWEEN ? AND ?
                GROUP BY u.id
                ORDER BY reservation_count DESC
                LIMIT 10
            ");
            $stmt->execute([$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
            $topCustomers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'by_status' => $byStatus,
                'by_date' => $byDate,
                'top_customers' => $topCustomers
            ];
        } catch (PDOException $e) {
            error_log("Reservations report error: " . $e->getMessage());
            return [];
        }
    }

    private function generateCustomersReport($start_date, $end_date)
    {
        try {
            // Customer registration trend
            $stmt = $this->pdo->prepare("
                SELECT
                    DATE(created_at) as date,
                    COUNT(*) as registrations,
                    SUM(CASE WHEN role = 'customer' THEN 1 ELSE 0 END) as customers,
                    SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as admins,
                    SUM(CASE WHEN role = 'staff' THEN 1 ELSE 0 END) as staff
                FROM users
                WHERE created_at BETWEEN ? AND ?
                GROUP BY DATE(created_at)
                ORDER BY date
            ");
            $stmt->execute([$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
            $registrationTrend = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Customer demographics (if you have these fields)
            $stmt = $this->pdo->prepare("
                SELECT
                    role,
                    COUNT(*) as count,
                    MIN(created_at) as first_registration,
                    MAX(created_at) as last_registration
                FROM users
                WHERE created_at BETWEEN ? AND ?
                GROUP BY role
                ORDER BY count DESC
            ");
            $stmt->execute([$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
            $byRole = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Customer activity
            $stmt = $this->pdo->prepare("
                SELECT
                    u.id,
                    u.username,
                    u.email,
                    u.first_name,
                    u.last_name,
                    u.created_at as registered_date,
                    COUNT(r.id) as total_reservations,
                    SUM(r.total_amount) as total_spent,
                    MAX(r.created_at) as last_booking
                FROM users u
                LEFT JOIN reservations r ON u.id = r.user_id
                WHERE u.created_at BETWEEN ? AND ?
                AND u.role = 'customer'
                GROUP BY u.id
                ORDER BY total_reservations DESC
                LIMIT 20
            ");
            $stmt->execute([$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
            $customerActivity = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'registration_trend' => $registrationTrend,
                'by_role' => $byRole,
                'customer_activity' => $customerActivity
            ];
        } catch (PDOException $e) {
            error_log("Customers report error: " . $e->getMessage());
            return [];
        }
    }

    private function generateServicesReport($start_date, $end_date)
    {
        try {
            // Services usage
            $stmt = $this->pdo->prepare("
                SELECT
                    s.id,
                    s.name,
                    s.description,
                    s.price,
                    s.status,
                    COUNT(rs.id) as times_used,
                    SUM(rs.service_price) as total_revenue,
                    COUNT(DISTINCT r.user_id) as unique_customers
                FROM services s
                LEFT JOIN reservation_services rs ON s.id = rs.service_id
                LEFT JOIN reservations r ON rs.reservation_id = r.id
                    AND r.created_at BETWEEN ? AND ?
                GROUP BY s.id
                ORDER BY times_used DESC
            ");
            $stmt->execute([$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
            $servicesUsage = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Services by reservation
            $stmt = $this->pdo->prepare("
                SELECT
                    DATE(r.created_at) as date,
                    COUNT(DISTINCT rs.service_id) as unique_services,
                    COUNT(rs.id) as total_services,
                    SUM(rs.service_price) as services_revenue
                FROM reservations r
                LEFT JOIN reservation_services rs ON r.id = rs.reservation_id
                WHERE r.created_at BETWEEN ? AND ?
                GROUP BY DATE(r.created_at)
                ORDER BY date
            ");
            $stmt->execute([$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
            $servicesByDate = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'services_usage' => $servicesUsage,
                'by_date' => $servicesByDate
            ];
        } catch (PDOException $e) {
            error_log("Services report error: " . $e->getMessage());
            return [];
        }
    }

    private function calculateSummary($reportData, $report_type)
    {
        $summary = [];

        switch ($report_type) {
            case 'revenue':
                if (!empty($reportData['daily'])) {
                    $totalRevenue = array_sum(array_column($reportData['daily'], 'total_revenue'));
                    $totalReservations = array_sum(array_column($reportData['daily'], 'reservation_count'));
                    $avgRevenue = $totalReservations > 0 ? $totalRevenue / $totalReservations : 0;

                    $summary = [
                        'total_revenue' => $totalRevenue,
                        'total_reservations' => $totalReservations,
                        'avg_revenue_per_reservation' => $avgRevenue,
                        'date_range_count' => count($reportData['daily'])
                    ];
                }
                break;

            case 'occupancy':
                if (!empty($reportData['daily'])) {
                    $avgOccupancy = array_sum(array_column($reportData['daily'], 'occupancy_rate')) / count($reportData['daily']);
                    $maxOccupancy = max(array_column($reportData['daily'], 'occupancy_rate'));
                    $minOccupancy = min(array_column($reportData['daily'], 'occupancy_rate'));

                    $summary = [
                        'avg_occupancy_rate' => round($avgOccupancy, 2),
                        'max_occupancy_rate' => round($maxOccupancy, 2),
                        'min_occupancy_rate' => round($minOccupancy, 2),
                        'date_range_count' => count($reportData['daily'])
                    ];
                }
                break;

            case 'reservations':
                if (!empty($reportData['by_status'])) {
                    $totalReservations = array_sum(array_column($reportData['by_status'], 'count'));
                    $totalRevenue = array_sum(array_column($reportData['by_status'], 'total_amount'));

                    $summary = [
                        'total_reservations' => $totalReservations,
                        'total_revenue' => $totalRevenue,
                        'status_count' => count($reportData['by_status'])
                    ];
                }
                break;
        }

        return $summary;
    }

    private function exportPDF($reportData, $title)
    {
        // This is a basic implementation. In production, use a library like TCPDF or Dompdf
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $title . '_' . date('Y-m-d') . '.pdf"');

        // For now, just redirect back
        header('Location: index.php?action=admin/reports&error=PDF export not implemented');
        exit();
    }

    private function exportExcel($reportData, $title)
    {
        // This is a basic implementation. In production, use a library like PhpSpreadsheet
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $title . '_' . date('Y-m-d') . '.xls"');

        // For now, just redirect back
        header('Location: index.php?action=admin/reports&error=Excel export not implemented');
        exit();
    }

    public function export()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
            header('Location: index.php?action=403');
            exit();
        }

        $type = $_GET['type'] ?? '';
        $format = $_GET['format'] ?? 'csv';

        if (empty($type)) {
            $_SESSION['error'] = "Export type not specified.";
            header('Location: index.php?action=admin/reports');
            exit();
        }

        switch ($type) {
            case 'reservations':
                $this->exportReservations($format);
                break;
            case 'customers':
                $this->exportCustomers($format);
                break;
            case 'rooms':
                $this->exportRooms($format);
                break;
            default:
                $_SESSION['error'] = "Invalid export type.";
                header('Location: index.php?action=admin/reports');
                exit();
        }
    }

    private function exportReservations($format)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT
                    r.id,
                    r.check_in,
                    r.check_out,
                    r.guests,
                    r.status,
                    r.total_amount,
                    r.created_at,
                    u.username,
                    u.email,
                    u.first_name,
                    u.last_name,
                    rm.room_number,
                    rm.type as room_type
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                JOIN rooms rm ON r.room_id = rm.id
                ORDER BY r.created_at DESC
            ");
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->sendCSV($data, 'reservations_' . date('Y-m-d') . '.csv');
        } catch (PDOException $e) {
            error_log("Export reservations error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to export data.";
            header('Location: index.php?action=admin/reports');
            exit();
        }
    }

    private function exportCustomers($format)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT
                    id,
                    username,
                    email,
                    first_name,
                    last_name,
                    phone,
                    role,
                    is_active,
                    created_at
                FROM users
                WHERE role = 'customer'
                ORDER BY created_at DESC
            ");
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->sendCSV($data, 'customers_' . date('Y-m-d') . '.csv');
        } catch (PDOException $e) {
            error_log("Export customers error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to export data.";
            header('Location: index.php?action=admin/reports');
            exit();
        }
    }

    private function exportRooms($format)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT
                    id,
                    room_number,
                    type,
                    description,
                    price_per_night,
                    capacity,
                    amenities,
                    status,
                    created_at
                FROM rooms
                ORDER BY room_number
            ");
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->sendCSV($data, 'rooms_' . date('Y-m-d') . '.csv');
        } catch (PDOException $e) {
            error_log("Export rooms error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to export data.";
            header('Location: index.php?action=admin/reports');
            exit();
        }
    }

    private function sendCSV($data, $filename)
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Add headers
        if (!empty($data)) {
            fputcsv($output, array_keys($data[0]));
        }

        // Add data
        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
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
