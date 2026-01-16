<?php
// app/controllers/DashboardController.php
require_once __DIR__ . '/Path/BaseController.php';

class DashboardController extends BaseController
{
  // app/controllers/DashboardController.php
  public function index()
  {
    $this->requireLogin('customer');

    $userId = $_SESSION['user_id'];

    // Get user details
    $user = $this->getUserDetails($userId);

    // Get upcoming reservations
    $upcomingReservations = $this->getUpcomingReservations($userId);

    // Get past reservations
    $pastReservations = $this->getPastReservations($userId);

    // Get loyalty points or rewards if applicable
    $loyaltyInfo = $this->getLoyaltyInfo($userId);

    // Get available rooms for display
    $availableRooms = $this->getAvailableRooms();

    // Get statistics for display
    $stats = $this->getReservationStats();

    // Prepare data for view
    $data = [
      'user' => $user,
      'upcomingReservations' => $upcomingReservations,
      'pastReservations' => $pastReservations,
      'loyaltyInfo' => $loyaltyInfo,
      'availableRooms' => $availableRooms,
      'stats' => $stats,
      'page_title' => 'Customer Dashboard'
    ];

    // Render the view
    $this->render('customer/dashboard', $data);
  }
  public function reservationDashboard()
  {
    $this->requireLogin(); // Allow any logged-in user

    // Get statistics for the reservation dashboard
    $stats = $this->getReservationStats();
    $recentReservations = $this->getRecentReservations();
    $availableRooms = $this->getAvailableRooms();

    $data = [
      'stats' => $stats,
      'recentReservations' => $recentReservations,
      'availableRooms' => $availableRooms,
      'page_title' => 'Hotel Reservation System'
    ];

    $this->render('hotel-reservation', $data);
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
                       rm.room_number,
                       rt.name as room_type,
                       rt.base_price as price_per_night,
                       rt.description as room_description
                FROM reservations r
                JOIN rooms rm ON r.room_id = rm.id
                JOIN room_types rt ON rm.room_type_id = rt.id
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
                       rm.room_number,
                       rt.name as room_type,
                       rt.base_price as price_per_night,
                       rt.description as room_description
                FROM reservations r
                JOIN rooms rm ON r.room_id = rm.id
                JOIN room_types rt ON rm.room_type_id = rt.id
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

  private function getReservationStats()
  {
    try {
      $stmt = $this->pdo->query("
                SELECT
                    (SELECT COUNT(*) FROM reservations) as total_reservations,
                    (SELECT COUNT(*) FROM rooms WHERE status = 'available') as available_rooms,
                    (SELECT COUNT(*) FROM reservations
                     WHERE DATE(check_in) = CURDATE()
                     AND status IN ('confirmed', 'pending')) as checkins_today,
                    (SELECT SUM(total_amount) FROM reservations
                     WHERE DATE(created_at) = CURDATE()) as today_revenue
            ");
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Reservation stats error: " . $e->getMessage());
      return [
        'total_reservations' => 0,
        'available_rooms' => 0,
        'checkins_today' => 0,
        'today_revenue' => 0
      ];
    }
  }

  private function getRecentReservations($limit = 10)
  {
    try {
      $stmt = $this->pdo->prepare("
                SELECT r.*,
                       u.first_name, u.last_name,
                       rt.name as room_type
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                JOIN rooms rm ON r.room_id = rm.id
                JOIN room_types rt ON rm.room_type_id = rt.id
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

  private function getAvailableRooms()
  {
    try {
      $stmt = $this->pdo->query("
              SELECT
                  rt.id as room_type_id,
                  rt.name as type,
                  rt.description,
                  rt.base_price as price_per_night,
                  rt.capacity,
                  rt.size,
                  rt.amenities,
                  COUNT(r.id) as available_count,
                  -- Get primary image for each room type
                  (
                      SELECT ri.image_url
                      FROM room_images ri
                      WHERE ri.room_type_id = rt.id
                      AND ri.is_primary = 1
                      LIMIT 1
                  ) as primary_image,
                  -- Get all images for each room type
                  (
                      SELECT JSON_ARRAYAGG(ri.image_url)
                      FROM room_images ri
                      WHERE ri.room_type_id = rt.id
                  ) as all_images
              FROM rooms r
              JOIN room_types rt ON r.room_type_id = rt.id
              WHERE r.status = 'available'
              AND rt.name != 'Common / Background'  -- Exclude non-bookable rooms
              GROUP BY rt.id, rt.name, rt.description, rt.base_price,
                       rt.capacity, rt.size, rt.amenities
              ORDER BY rt.base_price ASC
          ");

      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Process the results to ensure proper image paths
      foreach ($result as &$room) {
        // Add full path for images (assuming images are in app/views/images/)
        $room['images'] = $this->processRoomImages($room);
      }

      return $result ?: [];
    } catch (PDOException $e) {
      error_log("Available rooms error: " . $e->getMessage());
      return [];
    }
  }




  // Helper method to process room images
  private function processRoomImages($room)
  {
    $images = [];
    $imagePath = 'images/'; // Relative path from view directory

    // Handle primary image - check if it's already a full path or just filename
    if (!empty($room['primary_image'])) {
      $primaryImg = $room['primary_image'];
      // If it already contains a path separator, use as-is, otherwise prepend imagePath
      if (strpos($primaryImg, '/') !== false || strpos($primaryImg, '\\') !== false) {
        // Already has a path, check if it's uploads path and convert
        if (strpos($primaryImg, 'uploads/room_images/') !== false) {
          // Extract filename and use images/ path
          $filename = basename($primaryImg);
          $images['primary'] = $imagePath . $filename;
        } else {
          $images['primary'] = $primaryImg;
        }
      } else {
        // Just filename, prepend imagePath
        $images['primary'] = $imagePath . $primaryImg;
      }
    }

    // Parse all_images JSON if exists
    if (!empty($room['all_images'])) {
      $allImages = json_decode($room['all_images'], true);
      if (is_array($allImages)) {
        foreach ($allImages as $image) {
          // Handle path same way as primary image
          if (strpos($image, '/') !== false || strpos($image, '\\') !== false) {
            if (strpos($image, 'uploads/room_images/') !== false) {
              $filename = basename($image);
              $fullPath = $imagePath . $filename;
            } else {
              $fullPath = $image;
            }
          } else {
            $fullPath = $imagePath . $image;
          }

          if (!in_array($fullPath, $images)) {
            $images[] = $fullPath;
          }
        }
      }
    }

    // If no images found, use a default image
    if (empty($images)) {
      $images['primary'] = $imagePath . 'default-room.jpg';
    }

    return $images;
  }
}
