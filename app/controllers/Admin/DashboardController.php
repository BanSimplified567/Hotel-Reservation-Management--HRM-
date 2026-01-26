<?php
// app/controllers/Admin/DashboardController.php
require_once __DIR__ . '/../Path/BaseController.php';

class AdminDashboardController extends BaseController
{
  public function __construct($pdo)
  {
    parent::__construct($pdo);
  }

  public function index()
  {
    $this->requireLogin();

    if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
      $this->redirect('403');
    }

    // Get date filters with defaults
    $start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
    $end_date = $_GET['end_date'] ?? date('Y-m-d');

    // Validate dates
    if (strtotime($start_date) > strtotime($end_date)) {
      $temp = $start_date;
      $start_date = $end_date;
      $end_date = $temp;
    }

    // Get dashboard statistics with date filters
    $stats = $this->getDashboardStatistics($start_date, $end_date);

    // Get recent reservations
    $recentReservations = $this->getRecentReservations();

    // Get recent users (for admin only)
    $recentUsers = [];
    if ($_SESSION['role'] == 'admin') {
      $recentUsers = $this->getRecentUsers();
    }

    // Get room occupancy
    $roomOccupancy = $this->getRoomOccupancy();

    // Get revenue data with date filter
    $revenueData = $this->getRevenueData($start_date, $end_date);

    // Get today's check-ins and check-outs for detailed view
    $todayCheckins = $this->getTodayCheckins();
    $todayCheckouts = $this->getTodayCheckouts();

    // Get additional stats for the enhanced dashboard
    $additionalStats = $this->getAdditionalStatistics($start_date, $end_date);

    // Merge all stats
    $stats = array_merge($stats, $additionalStats);

    $data = [
      'stats' => $stats,
      'recentReservations' => $recentReservations,
      'recentUsers' => $recentUsers,
      'roomOccupancy' => $roomOccupancy,
      'revenueData' => $revenueData,
      'todayCheckins' => $todayCheckins,
      'todayCheckouts' => $todayCheckouts,
      'start_date' => $start_date,
      'end_date' => $end_date,
      'page_title' => 'Admin Dashboard'
    ];

    $this->render('admin/dashboard', $data);
  }

  private function getDashboardStatistics($start_date, $end_date)
  {
    $stats = [];

    try {
      // Total reservations (with date filter)
      $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE created_at BETWEEN ? AND ?");
      $stmt->execute([$start_date, $end_date]);
      $stats['total_reservations'] = $stmt->fetchColumn();

      // Active reservations (confirmed or checked_in)
      $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE status IN ('confirmed', 'checked_in') AND created_at BETWEEN ? AND ?");
      $stmt->execute([$start_date, $end_date]);
      $stats['active_reservations'] = $stmt->fetchColumn();

      // Pending reservations
      $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE status = 'pending' AND created_at BETWEEN ? AND ?");
      $stmt->execute([$start_date, $end_date]);
      $stats['pending_reservations'] = $stmt->fetchColumn();

      // Total customers
      $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'customer' AND created_at BETWEEN ? AND ?");
      $stmt->execute([$start_date, $end_date]);
      $stats['total_customers'] = $stmt->fetchColumn();

      // Occupied rooms
      $stmt = $this->pdo->prepare("
                SELECT COUNT(DISTINCT r.room_id)
                FROM reservations res
                JOIN rooms r ON res.room_id = r.id
                WHERE res.status IN ('checked_in', 'confirmed')
                AND CURRENT_DATE BETWEEN res.check_in AND res.check_out
                AND res.created_at BETWEEN ? AND ?
            ");
      $stmt->execute([$start_date, $end_date]);
      $stats['occupied_rooms'] = $stmt->fetchColumn();

      // Today's check-ins
      $today = date('Y-m-d');
      $stmt = $this->pdo->prepare("
                SELECT COUNT(*)
                FROM reservations
                WHERE check_in = ?
                AND status IN ('confirmed', 'pending')
                AND created_at BETWEEN ? AND ?
            ");
      $stmt->execute([$today, $start_date, $end_date]);
      $stats['today_checkins'] = $stmt->fetchColumn();

      // Today's check-outs
      $stmt = $this->pdo->prepare("
                SELECT COUNT(*)
                FROM reservations
                WHERE check_out = ?
                AND status IN ('checked_in', 'confirmed')
                AND created_at BETWEEN ? AND ?
            ");
      $stmt->execute([$today, $start_date, $end_date]);
      $stats['today_checkouts'] = $stmt->fetchColumn();

      // Revenue (with date filter)
      $stmt = $this->pdo->prepare("
                SELECT SUM(total_amount)
                FROM reservations
                WHERE status = 'completed'
                AND created_at BETWEEN ? AND ?
            ");
      $stmt->execute([$start_date, $end_date]);
      $stats['total_revenue'] = $stmt->fetchColumn() ?: 0;

      // Average daily rate
      $stmt = $this->pdo->prepare("
                SELECT AVG(total_amount/total_nights)
                FROM reservations
                WHERE status = 'completed'
                AND total_nights > 0
                AND created_at BETWEEN ? AND ?
            ");
      $stmt->execute([$start_date, $end_date]);
      $stats['average_daily_rate'] = round($stmt->fetchColumn() ?: 0, 2);

      // New customers this period
      $stmt = $this->pdo->prepare("
                SELECT COUNT(*)
                FROM users
                WHERE role = 'customer'
                AND created_at BETWEEN ? AND ?
            ");
      $stmt->execute([$start_date, $end_date]);
      $stats['new_customers'] = $stmt->fetchColumn();

      // Reservations this period
      $stmt = $this->pdo->prepare("
                SELECT COUNT(*)
                FROM reservations
                WHERE created_at BETWEEN ? AND ?
            ");
      $stmt->execute([$start_date, $end_date]);
      $stats['reservations_count'] = $stmt->fetchColumn();

      // Occupancy rate
      $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM rooms WHERE status IN ('available', 'occupied', 'reserved')");
      $stmt->execute();
      $totalRooms = $stmt->fetchColumn();
      $stats['occupancy_rate'] = $totalRooms > 0 ? round(($stats['occupied_rooms'] / $totalRooms) * 100, 1) : 0;

    } catch (PDOException $e) {
      error_log("Dashboard stats error: " . $e->getMessage());
      // Return empty stats if there's an error
      return array_fill_keys([
        'total_reservations',
        'active_reservations',
        'pending_reservations',
        'total_customers',
        'occupied_rooms',
        'today_checkins',
        'today_checkouts',
        'total_revenue',
        'average_daily_rate',
        'new_customers',
        'reservations_count',
        'occupancy_rate'
      ], 0);
    }

    return $stats;
  }

  private function getAdditionalStatistics($start_date, $end_date)
  {
    $stats = [];

    try {
      // Calculate percentage change from previous period
      $prevStartDate = date('Y-m-d', strtotime($start_date . ' -30 days'));
      $prevEndDate = date('Y-m-d', strtotime($end_date . ' -30 days'));

      // Previous period revenue
      $stmt = $this->pdo->prepare("
                SELECT SUM(total_amount)
                FROM reservations
                WHERE status = 'completed'
                AND created_at BETWEEN ? AND ?
            ");
      $stmt->execute([$prevStartDate, $prevEndDate]);
      $prevRevenue = $stmt->fetchColumn() ?: 0;

      // Current period revenue
      $stmt->execute([$start_date, $end_date]);
      $currentRevenue = $stmt->fetchColumn() ?: 0;

      // Calculate percentage change
      if ($prevRevenue > 0) {
        $stats['revenue_change'] = round((($currentRevenue - $prevRevenue) / $prevRevenue) * 100, 1);
      } else {
        $stats['revenue_change'] = $currentRevenue > 0 ? 100 : 0;
      }

      // Active reservations change
      $stmt = $this->pdo->prepare("
                SELECT COUNT(*)
                FROM reservations
                WHERE status IN ('confirmed', 'checked_in')
                AND created_at BETWEEN ? AND ?
            ");
      $stmt->execute([$prevStartDate, $prevEndDate]);
      $prevActive = $stmt->fetchColumn();

      $stmt->execute([$start_date, $end_date]);
      $currentActive = $stmt->fetchColumn();

      if ($prevActive > 0) {
        $stats['active_change'] = round((($currentActive - $prevActive) / $prevActive) * 100, 1);
      } else {
        $stats['active_change'] = $currentActive > 0 ? 100 : 0;
      }

      // Pending reservations change
      $stmt = $this->pdo->prepare("
                SELECT COUNT(*)
                FROM reservations
                WHERE status = 'pending'
                AND created_at BETWEEN ? AND ?
            ");
      $stmt->execute([$prevStartDate, $prevEndDate]);
      $prevPending = $stmt->fetchColumn();

      $stmt->execute([$start_date, $end_date]);
      $currentPending = $stmt->fetchColumn();

      if ($prevPending > 0) {
        $stats['pending_change'] = round((($currentPending - $prevPending) / $prevPending) * 100, 1);
      } else {
        $stats['pending_change'] = $currentPending > 0 ? 100 : 0;
      }

      // Customers change
      $stmt = $this->pdo->prepare("
                SELECT COUNT(*)
                FROM users
                WHERE role = 'customer'
                AND created_at BETWEEN ? AND ?
            ");
      $stmt->execute([$prevStartDate, $prevEndDate]);
      $prevCustomers = $stmt->fetchColumn();

      $stmt->execute([$start_date, $end_date]);
      $currentCustomers = $stmt->fetchColumn();

      if ($prevCustomers > 0) {
        $stats['customers_change'] = round((($currentCustomers - $prevCustomers) / $prevCustomers) * 100, 1);
      } else {
        $stats['customers_change'] = $currentCustomers > 0 ? 100 : 0;
      }

    } catch (PDOException $e) {
      error_log("Additional stats error: " . $e->getMessage());
      $stats['revenue_change'] = 0;
      $stats['active_change'] = 0;
      $stats['pending_change'] = 0;
      $stats['customers_change'] = 0;
    }

    return $stats;
  }

  private function getRecentReservations($limit = 10)
  {
    try {
      $stmt = $this->pdo->prepare("
                SELECT
                    r.*,
                    u.first_name,
                    u.last_name,
                    u.email,
                    rm.room_number,
                    rt.name as room_type,
                    p.status as payment_status
                FROM reservations r
                LEFT JOIN users u ON r.user_id = u.id
                LEFT JOIN rooms rm ON r.room_id = rm.id
                LEFT JOIN room_types rt ON rm.room_type_id = rt.id
                LEFT JOIN (
                    SELECT reservation_id, status
                    FROM payments
                    ORDER BY payment_date DESC
                    LIMIT 1
                ) p ON r.id = p.reservation_id
                ORDER BY r.created_at DESC
                LIMIT ?
            ");
      $stmt->execute([$limit]);
      $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Set default payment_status if not exists
      foreach ($reservations as &$res) {
        if (!isset($res['payment_status'])) {
          $res['payment_status'] = 'pending';
        }
      }

      return $reservations;
    } catch (PDOException $e) {
      error_log("Recent reservations error: " . $e->getMessage());
      return [];
    }
  }

  private function getRecentUsers($limit = 5)
  {
    try {
      $stmt = $this->pdo->prepare("
                SELECT
                    u.id,
                    u.username,
                    u.email,
                    u.first_name,
                    u.last_name,
                    u.role,
                    u.created_at,
                    COUNT(r.id) as total_bookings
                FROM users u
                LEFT JOIN reservations r ON u.id = r.user_id
                WHERE u.role = 'customer'
                GROUP BY u.id
                ORDER BY u.created_at DESC
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
                    rt.name as type,
                    COUNT(rm.id) as total_rooms,
                    SUM(CASE WHEN rm.status = 'available' THEN 1 ELSE 0 END) as available,
                    SUM(CASE WHEN rm.status IN ('occupied', 'reserved') THEN 1 ELSE 0 END) as occupied,
                    SUM(CASE WHEN rm.status IN ('maintenance', 'cleaning') THEN 1 ELSE 0 END) as unavailable
                FROM room_types rt
                LEFT JOIN rooms rm ON rt.id = rm.room_type_id
                WHERE rt.is_active = 1 AND rt.id != 4 -- Exclude 'Common / Background' type
                GROUP BY rt.id, rt.name
                ORDER BY rt.name
            ");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Room occupancy error: " . $e->getMessage());
      return [];
    }
  }

  private function getRevenueData($start_date, $end_date)
  {
    try {
      $stmt = $this->pdo->prepare("
                SELECT
                    DATE(created_at) as date,
                    SUM(total_amount) as daily_revenue,
                    COUNT(*) as reservation_count
                FROM reservations
                WHERE status = 'completed'
                AND created_at BETWEEN ? AND ?
                GROUP BY DATE(created_at)
                ORDER BY date ASC
            ");
      $stmt->execute([$start_date, $end_date]);
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Fill in missing dates with zero revenue
      $result = [];
      $currentDate = $start_date;
      while ($currentDate <= $end_date) {
        $found = false;
        foreach ($data as $row) {
          if ($row['date'] == $currentDate) {
            $result[] = $row;
            $found = true;
            break;
          }
        }
        if (!$found) {
          $result[] = [
            'date' => $currentDate,
            'daily_revenue' => 0,
            'reservation_count' => 0
          ];
        }
        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
      }

      return $result;
    } catch (PDOException $e) {
      error_log("Revenue data error: " . $e->getMessage());
      return [];
    }
  }

  private function getTodayCheckins()
  {
    try {
      $today = date('Y-m-d');
      $stmt = $this->pdo->prepare("
                SELECT
                    r.*,
                    u.first_name,
                    u.last_name,
                    u.email,
                    rm.room_number,
                    rt.name as room_type,
                    r.check_in_time
                FROM reservations r
                LEFT JOIN users u ON r.user_id = u.id
                LEFT JOIN rooms rm ON r.room_id = rm.id
                LEFT JOIN room_types rt ON rm.room_type_id = rt.id
                WHERE r.check_in = ?
                AND r.status IN ('confirmed', 'pending')
                ORDER BY r.check_in_time ASC
                LIMIT 5
            ");
      $stmt->execute([$today]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Today checkins error: " . $e->getMessage());
      return [];
    }
  }

  private function getTodayCheckouts()
  {
    try {
      $today = date('Y-m-d');
      $stmt = $this->pdo->prepare("
                SELECT
                    r.*,
                    u.first_name,
                    u.last_name,
                    u.email,
                    rm.room_number,
                    rt.name as room_type,
                    r.check_out_time
                FROM reservations r
                LEFT JOIN users u ON r.user_id = u.id
                LEFT JOIN rooms rm ON r.room_id = rm.id
                LEFT JOIN room_types rt ON rm.room_type_id = rt.id
                WHERE r.check_out = ?
                AND r.status IN ('checked_in', 'confirmed')
                ORDER BY r.check_out_time ASC
                LIMIT 5
            ");
      $stmt->execute([$today]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Today checkouts error: " . $e->getMessage());
      return [];
    }
  }
}
