<?php
// app/controllers/Admin/ReportController.php
require_once __DIR__ . '/../Path/BaseController.php';

class ReportController extends BaseController
{
  public function __construct($pdo)
  {
    parent::__construct($pdo);
  }

  public function index()
  {
    $this->requireLogin();

    if (!in_array($_SESSION['role'], ['admin'])) {
      $this->redirect('403');
    }

    $report_type = $_GET['type'] ?? 'revenue';
    $start_date = $_GET['start_date'] ?? date('Y-m-d');
    $end_date = $_GET['end_date'] ?? date('Y-m-d');
    $period = $_GET['period'] ?? 'today';

    // Validate dates
    if (strtotime($start_date) > strtotime($end_date)) {
      $start_date = $end_date;
    }

    // Get report data based on type
    $summary = $this->getReportSummary($report_type, $start_date, $end_date);
    $reportData = $this->getReportData($report_type, $start_date, $end_date);

    // Set report title
    $titles = [
      'revenue' => 'Revenue Report',
      'occupancy' => 'Occupancy Report',
      'reservations' => 'Reservations Report',
      'customers' => 'Customers Report',
      'services' => 'Services Report'
    ];
    $reportTitle = $titles[$report_type] ?? 'Report';

    $data = [
      'reportTitle' => $reportTitle,
      'report_type' => $report_type,
      'start_date' => $start_date,
      'end_date' => $end_date,
      'period' => $period,
      'summary' => $summary,
      'reportData' => $reportData
    ];

    $this->render('admin/reports/index', $data);
  }

  public function export()
  {
    $this->requireLogin();

    if (!in_array($_SESSION['role'], ['admin'])) {
      $this->redirect('403');
    }

    $report_type = $_GET['type'] ?? 'revenue';
    $start_date = $_GET['start_date'] ?? date('Y-m-d');
    $end_date = $_GET['end_date'] ?? date('Y-m-d');
    $format = $_GET['format'] ?? 'csv';

    // Get report data
    $summary = $this->getReportSummary($report_type, $start_date, $end_date);
    $reportData = $this->getReportData($report_type, $start_date, $end_date);

    // Set filename
    $filename = $report_type . '_report_' . date('Y-m-d');

    switch ($format) {
      case 'excel':
        $this->exportExcel($reportData, $summary, $filename);
        break;
      case 'pdf':
        $this->exportPDF($reportData, $summary, $filename);
        break;
      default:
        $this->exportCSV($reportData, $summary, $filename);
        break;
    }
  }

  private function getReportSummary($type, $start_date, $end_date)
  {
    switch ($type) {
      case 'revenue':
        return $this->getRevenueSummary($start_date, $end_date);
      case 'occupancy':
        return $this->getOccupancySummary($start_date, $end_date);
      case 'reservations':
        return $this->getReservationsSummary($start_date, $end_date);
      case 'customers':
        return $this->getCustomersSummary($start_date, $end_date);
      case 'services':
        return $this->getServicesSummary($start_date, $end_date);
      default:
        return [];
    }
  }

  private function getReportData($type, $start_date, $end_date)
  {
    switch ($type) {
      case 'revenue':
        return $this->getRevenueData($start_date, $end_date);
      case 'occupancy':
        return $this->getOccupancyData($start_date, $end_date);
      case 'reservations':
        return $this->getReservationsData($start_date, $end_date);
      case 'customers':
        return $this->getCustomersData($start_date, $end_date);
      case 'services':
        return $this->getServicesData($start_date, $end_date);
      default:
        return [];
    }
  }

  private function getRevenueSummary($start_date, $end_date)
  {
    $stmt = $this->pdo->prepare("
      SELECT
        SUM(total_amount) as total_revenue,
        COUNT(*) as total_reservations,
        AVG(total_amount) as avg_reservation_value
      FROM reservations
      WHERE check_in >= ? AND check_out <= ?
      AND status IN ('confirmed', 'checked_out')
    ");
    $stmt->execute([$start_date, $end_date]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return [
      'total_revenue' => $result['total_revenue'] ?? 0,
      'total_reservations' => $result['total_reservations'] ?? 0,
      'avg_reservation_value' => $result['avg_reservation_value'] ?? 0
    ];
  }

  private function getRevenueData($start_date, $end_date)
  {
    $stmt = $this->pdo->prepare("
      SELECT
        DATE(check_in) as date,
        SUM(total_amount) as daily_revenue,
        COUNT(*) as reservations_count
      FROM reservations
      WHERE check_in >= ? AND check_out <= ?
      AND status IN ('confirmed', 'checked_out')
      GROUP BY DATE(check_in)
      ORDER BY date
    ");
    $stmt->execute([$start_date, $end_date]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  private function getOccupancySummary($start_date, $end_date)
  {
    // Calculate total rooms
    $stmt = $this->pdo->prepare("SELECT COUNT(*) as total_rooms FROM rooms WHERE status IN ('available', 'occupied', 'maintenance', 'cleaning', 'reserved')");
    $stmt->execute();
    $total_rooms = $stmt->fetch(PDO::FETCH_ASSOC)['total_rooms'] ?? 0;

    // Calculate occupied rooms
    $stmt = $this->pdo->prepare("
      SELECT COUNT(DISTINCT room_id) as occupied_rooms
      FROM reservations
      WHERE ? BETWEEN check_in AND check_out
      AND status IN ('confirmed', 'checked_in')
    ");
    $stmt->execute([$start_date]);
    $occupied_rooms = $stmt->fetch(PDO::FETCH_ASSOC)['occupied_rooms'] ?? 0;

    $occupancy_rate = $total_rooms > 0 ? ($occupied_rooms / $total_rooms) * 100 : 0;

    return [
      'total_rooms' => $total_rooms,
      'occupied_rooms' => $occupied_rooms,
      'occupancy_rate' => $occupancy_rate
    ];
  }

  private function getOccupancyData($start_date, $end_date)
  {
    $data = [];
    $current = strtotime($start_date);
    $end = strtotime($end_date);

    while ($current <= $end) {
      $date = date('Y-m-d', $current);

      $stmt = $this->pdo->prepare("
        SELECT COUNT(DISTINCT room_id) as occupied_rooms
        FROM reservations
        WHERE ? BETWEEN check_in AND check_out
        AND status IN ('confirmed', 'checked_in')
      ");
      $stmt->execute([$date]);
      $occupied = $stmt->fetch(PDO::FETCH_ASSOC)['occupied_rooms'] ?? 0;

      $stmt = $this->pdo->prepare("SELECT COUNT(*) as total_rooms FROM rooms WHERE status IN ('available', 'occupied', 'maintenance', 'cleaning', 'reserved')");
      $stmt->execute();
      $total = $stmt->fetch(PDO::FETCH_ASSOC)['total_rooms'] ?? 0;

      $data[] = [
        'date' => $date,
        'occupied_rooms' => $occupied,
        'total_rooms' => $total,
        'occupancy_rate' => $total > 0 ? ($occupied / $total) * 100 : 0
      ];

      $current = strtotime('+1 day', $current);
    }

    return $data;
  }

  private function getReservationsSummary($start_date, $end_date)
  {
    $stmt = $this->pdo->prepare("
      SELECT
        COUNT(*) as total_reservations,
        SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_reservations,
        SUM(CASE WHEN status = 'checked_out' THEN 1 ELSE 0 END) as completed_reservations,
        SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_reservations
      FROM reservations
      WHERE check_in >= ? AND check_out <= ?
    ");
    $stmt->execute([$start_date, $end_date]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return [
      'total_reservations' => $result['total_reservations'] ?? 0,
      'confirmed_reservations' => $result['confirmed_reservations'] ?? 0,
      'completed_reservations' => $result['completed_reservations'] ?? 0,
      'cancelled_reservations' => $result['cancelled_reservations'] ?? 0
    ];
  }

  private function getReservationsData($start_date, $end_date)
  {
    $stmt = $this->pdo->prepare("
      SELECT
        r.*,
        rm.room_number,
        u.first_name,
        u.last_name,
        u.email
      FROM reservations r
      LEFT JOIN rooms rm ON r.room_id = rm.id
      LEFT JOIN users u ON r.user_id = u.id
      WHERE r.check_in >= ? AND r.check_out <= ?
      ORDER BY r.check_in DESC
    ");
    $stmt->execute([$start_date, $end_date]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  private function getCustomersSummary($start_date, $end_date)
  {
    $stmt = $this->pdo->prepare("
      SELECT
        COUNT(DISTINCT user_id) as total_customers,
        COUNT(*) as total_bookings
      FROM reservations
      WHERE check_in >= ? AND check_out <= ?
      AND status IN ('confirmed', 'checked_out')
    ");
    $stmt->execute([$start_date, $end_date]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return [
      'total_customers' => $result['total_customers'] ?? 0,
      'total_bookings' => $result['total_bookings'] ?? 0
    ];
  }

  private function getCustomersData($start_date, $end_date)
  {
    $stmt = $this->pdo->prepare("
      SELECT
        u.id,
        u.first_name,
        u.last_name,
        u.email,
        COUNT(r.id) as total_reservations,
        SUM(r.total_amount) as total_spent,
        MAX(r.check_out) as last_visit
      FROM users u
      LEFT JOIN reservations r ON u.id = r.user_id
      WHERE r.check_in >= ? AND r.check_out <= ?
      AND r.status IN ('confirmed', 'checked_out')
      GROUP BY u.id
      ORDER BY total_spent DESC
    ");
    $stmt->execute([$start_date, $end_date]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  private function getServicesSummary($start_date, $end_date)
  {
    $stmt = $this->pdo->prepare("
      SELECT
        COUNT(*) as total_services,
        SUM(price) as total_service_revenue
      FROM services
      WHERE created_at >= ? AND created_at <= ?
    ");
    $stmt->execute([$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return [
      'total_services' => $result['total_services'] ?? 0,
      'total_service_revenue' => $result['total_service_revenue'] ?? 0
    ];
  }

  private function getServicesData($start_date, $end_date)
  {
    $stmt = $this->pdo->prepare("
      SELECT
        s.*,
        COUNT(rs.id) as usage_count
      FROM services s
      LEFT JOIN reservation_services rs ON s.id = rs.service_id
      LEFT JOIN reservations r ON rs.reservation_id = r.id
      WHERE r.check_in >= ? AND r.check_out <= ?
      GROUP BY s.id
      ORDER BY usage_count DESC
    ");
    $stmt->execute([$start_date, $end_date]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  private function exportCSV($data, $summary, $filename)
  {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '.csv"');

    $output = fopen('php://output', 'w');

    // Write summary
    if (!empty($summary)) {
      fputcsv($output, ['Summary']);
      foreach ($summary as $key => $value) {
        fputcsv($output, [ucfirst(str_replace('_', ' ', $key)), $value]);
      }
      fputcsv($output, []);
    }

    // Write data
    if (!empty($data)) {
      fputcsv($output, array_keys($data[0]));
      foreach ($data as $row) {
        fputcsv($output, $row);
      }
    }

    fclose($output);
    exit;
  }

  private function exportExcel($data, $summary, $filename)
  {
    // For simplicity, export as CSV with Excel headers
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $filename . '.xls"');

    $output = fopen('php://output', 'w');

    // Write summary
    if (!empty($summary)) {
      fwrite($output, "Summary\n");
      foreach ($summary as $key => $value) {
        fwrite($output, ucfirst(str_replace('_', ' ', $key)) . "\t" . $value . "\n");
      }
      fwrite($output, "\n");
    }

    // Write data
    if (!empty($data)) {
      fwrite($output, implode("\t", array_keys($data[0])) . "\n");
      foreach ($data as $row) {
        fwrite($output, implode("\t", $row) . "\n");
      }
    }

    fclose($output);
    exit;
  }

  private function exportPDF($data, $summary, $filename)
  {
    // Basic PDF export using simple HTML to PDF conversion
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '.pdf"');

    echo "<html><body>";
    echo "<h1>Report</h1>";

    if (!empty($summary)) {
      echo "<h2>Summary</h2><ul>";
      foreach ($summary as $key => $value) {
        echo "<li>" . ucfirst(str_replace('_', ' ', $key)) . ": " . $value . "</li>";
      }
      echo "</ul>";
    }

    if (!empty($data)) {
      echo "<h2>Data</h2><table border='1'><tr>";
      foreach (array_keys($data[0]) as $header) {
        echo "<th>" . htmlspecialchars($header) . "</th>";
      }
      echo "</tr>";
      foreach ($data as $row) {
        echo "<tr>";
        foreach ($row as $cell) {
          echo "<td>" . htmlspecialchars($cell) . "</td>";
        }
        echo "</tr>";
      }
      echo "</table>";
    }

    echo "</body></html>";
    exit;
  }
}
