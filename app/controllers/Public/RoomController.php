<?php
// app/controllers/RoomController.php
require_once __DIR__ . '/../Path/BaseController.php';

class RoomController extends BaseController
{
  public function __construct($pdo)
  {
    parent::__construct($pdo);
  }

  public function index()
  {
    // Get filter parameters
    $type = $_GET['type'] ?? '';
    $min_price = floatval($_GET['min_price'] ?? 0);
    $max_price = floatval($_GET['max_price'] ?? 10000);
    $capacity = intval($_GET['capacity'] ?? 1);
    $sort = $_GET['sort'] ?? 'price_asc';
    $page = max(1, intval($_GET['page'] ?? 1));
    $perPage = 12;

    // Get all available room types with filters
    $room_types = $this->getRoomTypesWithAvailability($type, $min_price, $max_price, $capacity, $sort, $page, $perPage);
    $total_types = $this->countRoomTypes($type, $min_price, $max_price, $capacity);
    $total_pages = ceil($total_types / $perPage);

    // Get room types for filter
    $all_room_types = $this->getAllRoomTypes();

    // Get featured room types
    $featured_rooms = $this->getFeaturedRoomTypes();

    $data = [
      'rooms' => $room_types,
      'total_rooms' => $total_types,
      'total_pages' => $total_pages,
      'room_types' => $all_room_types,
      'all_amenities' => $this->getAllAmenities(),
      'featured_rooms' => $featured_rooms,
      'type' => $type,
      'min_price' => $min_price,
      'max_price' => $max_price,
      'capacity' => $capacity,
      'sort' => $sort,
      'page' => $page,
      'page_title' => 'Our Rooms'
    ];

    $this->render('public/rooms', $data);
  }

  public function view($id)
  {
    try {
      // Get room type details
      $stmt = $this->pdo->prepare("
                SELECT rt.*,
                       (SELECT COUNT(*) FROM rooms r
                        WHERE r.room_type_id = rt.id
                        AND r.status = 'available') as available_count,
                       (SELECT GROUP_CONCAT(r.room_number) FROM rooms r
                        WHERE r.room_type_id = rt.id
                        AND r.status = 'available') as available_rooms
                FROM room_types rt
                WHERE rt.id = ? AND rt.is_active = 1
            ");
      $stmt->execute([$id]);
      $room = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$room) {
        $_SESSION['error'] = "Room type not found or not available.";
        $this->redirect('index.php?action=rooms');
      }

      // Get room images
      $stmt = $this->pdo->prepare("
                SELECT * FROM room_images
                WHERE room_type_id = ?
                ORDER BY is_primary DESC
            ");
      $stmt->execute([$id]);
      $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Process images for display
      $room['images'] = $this->processRoomImages($room, $images);

      // Decode amenities JSON
      if (!empty($room['amenities'])) {
        $room['amenities'] = json_decode($room['amenities'], true);
      } else {
        $room['amenities'] = [];
      }

      // Get similar room types (same price range)
      $similar_rooms = $this->getSimilarRoomTypes($room['base_price'], $id);

      // Get individual rooms of this type
      $individual_rooms = $this->getIndividualRoomsByType($id);

      // Get reviews for this room type
      $reviews = $this->getRoomTypeReviews($id);

      // Check if user can review this room type
      $can_review = $this->canUserReviewRoomType($id);

      $data = [
        'room' => $room,
        'similar_rooms' => $similar_rooms,
        'individual_rooms' => $individual_rooms,
        'reviews' => $reviews,
        'can_review' => $can_review,
        'page_title' => $room['name'] . ' - Room Details'
      ];

      $this->render('public/room-details', $data);
    } catch (PDOException $e) {
      error_log("View room error: " . $e->getMessage());
      $_SESSION['error'] = "Failed to load room details.";
      $this->redirect('index.php?action=rooms');
    }
  }

  private function processRoomImages($room, $images)
  {
    $processed = [];
    $imagePath = 'app/views/images/'; // Updated path to match your structure

    if (!empty($images)) {
      foreach ($images as $image) {
        $imgUrl = $image['image_url'];
        // Check if it's already a full URL or relative path
        if (strpos($imgUrl, 'http') === 0) {
          $fullPath = $imgUrl;
        } elseif (strpos($imgUrl, '/') === 0 || strpos($imgUrl, '\\') === 0) {
          // Absolute path
          $fullPath = $imgUrl;
        } else {
          // Relative path
          $fullPath = $imagePath . $imgUrl;
        }

        if ($image['is_primary']) {
          $processed['primary'] = $fullPath;
        }
        $processed[] = $fullPath;
      }
    }

    // If no images, use default
    if (empty($processed)) {
      $processed['primary'] = $imagePath . 'default-room.jpg';
      $processed[] = $imagePath . 'default-room.jpg';
    }

    return $processed;
  }

  private function getRoomTypesWithAvailability($type, $min_price, $max_price, $capacity, $sort, $page, $perPage)
  {
    try {
      $offset = ($page - 1) * $perPage;

      $query = "
                SELECT rt.*,
                       COUNT(r.id) as available_count,
                       (SELECT ri.image_url FROM room_images ri
                        WHERE ri.room_type_id = rt.id AND ri.is_primary = 1 LIMIT 1) as primary_image
                FROM room_types rt
                LEFT JOIN rooms r ON rt.id = r.room_type_id
                    AND r.status = 'available'
                WHERE rt.is_active = 1
                AND rt.name != 'Common / Background'
                AND rt.base_price BETWEEN ? AND ?
                AND rt.capacity >= ?
            ";

      $params = [$min_price, $max_price, $capacity];

      if (!empty($type)) {
        $query .= " AND rt.id = ?";
        $params[] = $type;
      }

      $query .= " GROUP BY rt.id";

      // Add sorting
      switch ($sort) {
        case 'price_desc':
          $query .= " ORDER BY rt.base_price DESC";
          break;
        case 'name_asc':
          $query .= " ORDER BY rt.name ASC";
          break;
        case 'name_desc':
          $query .= " ORDER BY rt.name DESC";
          break;
        default: // price_asc
          $query .= " ORDER BY rt.base_price ASC";
          break;
      }

      // Add pagination
      $query .= " LIMIT ? OFFSET ?";
      $params[] = $perPage;
      $params[] = $offset;

      $stmt = $this->pdo->prepare($query);
      $stmt->execute($params);
      $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Process images for each room
      foreach ($rooms as &$room) {
        if (!empty($room['amenities'])) {
          $room['amenities'] = json_decode($room['amenities'], true);
        } else {
          $room['amenities'] = [];
        }

        // Get all images for this room type
        $room['images'] = $this->getRoomTypeImages($room['id']);
      }

      return $rooms;
    } catch (PDOException $e) {
      error_log("Get room types error: " . $e->getMessage());
      return [];
    }
  }

  private function getRoomTypeImages($room_type_id)
  {
    try {
      $stmt = $this->pdo->prepare("
                SELECT image_url, is_primary
                FROM room_images
                WHERE room_type_id = ?
                ORDER BY is_primary DESC
            ");
      $stmt->execute([$room_type_id]);
      $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $this->processRoomImages(['id' => $room_type_id], $images);
    } catch (PDOException $e) {
      error_log("Get room type images error: " . $e->getMessage());
      return [];
    }
  }

  private function countRoomTypes($type, $min_price, $max_price, $capacity)
  {
    try {
      $query = "
                SELECT COUNT(DISTINCT rt.id) as total
                FROM room_types rt
                WHERE rt.is_active = 1
                AND rt.name != 'Common / Background'
                AND rt.base_price BETWEEN ? AND ?
                AND rt.capacity >= ?
            ";

      $params = [$min_price, $max_price, $capacity];

      if (!empty($type)) {
        $query .= " AND rt.id = ?";
        $params[] = $type;
      }

      $stmt = $this->pdo->prepare($query);
      $stmt->execute($params);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['total'] ?? 0;
    } catch (PDOException $e) {
      error_log("Count room types error: " . $e->getMessage());
      return 0;
    }
  }

  private function getAllRoomTypes()
  {
    try {
      $stmt = $this->pdo->prepare("
                SELECT id, name, base_price, capacity
                FROM room_types
                WHERE is_active = 1
                AND name != 'Common / Background'
                ORDER BY name ASC
            ");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Get all room types error: " . $e->getMessage());
      return [];
    }
  }

  private function getAllAmenities()
  {
    // Extract amenities from all room types
    try {
      $stmt = $this->pdo->query("
                SELECT amenities
                FROM room_types
                WHERE amenities IS NOT NULL
                AND name != 'Common / Background'
            ");
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $amenities = [];
      foreach ($results as $result) {
        if (!empty($result['amenities'])) {
          $amenitiesData = json_decode($result['amenities'], true);
          if (is_array($amenitiesData)) {
            foreach ($amenitiesData as $key => $value) {
              if ($value === true && !in_array($key, $amenities)) {
                $amenities[] = ucfirst(str_replace('_', ' ', $key));
              }
            }
          }
        }
      }

      sort($amenities);
      return array_unique($amenities);
    } catch (PDOException $e) {
      error_log("Get all amenities error: " . $e->getMessage());
      return ['WiFi', 'TV', 'Air Conditioning', 'Mini Bar', 'Safe', 'Hair Dryer'];
    }
  }

  private function getFeaturedRoomTypes($limit = 6)
  {
    try {
      $stmt = $this->pdo->prepare("
                SELECT rt.*,
                       (SELECT COUNT(*) FROM reservations res
                        JOIN rooms r ON res.room_id = r.id
                        WHERE r.room_type_id = rt.id
                        AND res.status IN ('completed', 'checked_out')) as booking_count
                FROM room_types rt
                WHERE rt.is_active = 1
                AND rt.name != 'Common / Background'
                ORDER BY booking_count DESC, rt.created_at DESC
                LIMIT ?
            ");
      $stmt->execute([$limit]);
      $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($rooms as &$room) {
        if (!empty($room['amenities'])) {
          $room['amenities'] = json_decode($room['amenities'], true);
        } else {
          $room['amenities'] = [];
        }

        $room['available_count'] = $this->countAvailableRooms($room['id']);
        $room['images'] = $this->getRoomTypeImages($room['id']);
      }

      return $rooms;
    } catch (PDOException $e) {
      error_log("Get featured room types error: " . $e->getMessage());
      return [];
    }
  }

  private function countAvailableRooms($room_type_id)
  {
    try {
      $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as count
                FROM rooms
                WHERE room_type_id = ?
                AND status = 'available'
            ");
      $stmt->execute([$room_type_id]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['count'] ?? 0;
    } catch (PDOException $e) {
      error_log("Count available rooms error: " . $e->getMessage());
      return 0;
    }
  }

  private function getSimilarRoomTypes($price, $exclude_id, $limit = 4)
  {
    try {
      $price_range = $price * 0.3; // ±30% price range

      $stmt = $this->pdo->prepare("
                SELECT rt.*,
                       (SELECT ri.image_url FROM room_images ri
                        WHERE ri.room_type_id = rt.id AND ri.is_primary = 1 LIMIT 1) as primary_image,
                       COUNT(r.id) as available_count
                FROM room_types rt
                LEFT JOIN rooms r ON rt.id = r.room_type_id
                    AND r.status = 'available'
                WHERE rt.is_active = 1
                AND rt.id != ?
                AND rt.name != 'Common / Background'
                AND rt.base_price BETWEEN ? AND ?
                GROUP BY rt.id
                ORDER BY ABS(rt.base_price - ?)
                LIMIT ?
            ");
      $min_price = $price - $price_range;
      $max_price = $price + $price_range;
      $stmt->execute([$exclude_id, $min_price, $max_price, $price, $limit]);
      $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($rooms as &$room) {
        if (!empty($room['amenities'])) {
          $room['amenities'] = json_decode($room['amenities'], true);
        } else {
          $room['amenities'] = [];
        }

        // Get all images for similar rooms
        $room['images'] = $this->getRoomTypeImages($room['id']);
      }

      return $rooms;
    } catch (PDOException $e) {
      error_log("Get similar room types error: " . $e->getMessage());
      return [];
    }
  }

  private function getIndividualRoomsByType($room_type_id)
  {
    try {
      $stmt = $this->pdo->prepare("
                SELECT room_number, floor, view_type, status, features
                FROM rooms
                WHERE room_type_id = ?
                ORDER BY room_number
            ");
      $stmt->execute([$room_type_id]);
      $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Decode features JSON for each room
      foreach ($rooms as &$room) {
        if (!empty($room['features'])) {
          $room['features'] = json_decode($room['features'], true);
        } else {
          $room['features'] = [];
        }
      }

      return $rooms;
    } catch (PDOException $e) {
      error_log("Get individual rooms error: " . $e->getMessage());
      return [];
    }
  }

  private function getRoomTypeReviews($room_type_id, $limit = 5)
  {
    try {
      $stmt = $this->pdo->prepare("
                SELECT rev.*, u.username, u.first_name, u.last_name
                FROM reviews rev
                JOIN reservations res ON rev.reservation_id = res.id
                JOIN rooms r ON res.room_id = r.id
                JOIN users u ON res.user_id = u.id
                WHERE r.room_type_id = ?
                AND rev.is_approved = 1
                ORDER BY rev.created_at DESC
                LIMIT ?
            ");
      $stmt->execute([$room_type_id, $limit]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Get room type reviews error: " . $e->getMessage());
      return [];
    }
  }

  private function canUserReviewRoomType($room_type_id)
  {
    if (!isset($_SESSION['user_id'])) {
      return false;
    }

    $user_id = $_SESSION['user_id'];

    try {
      $stmt = $this->pdo->prepare("
                SELECT r.id
                FROM reservations r
                JOIN rooms rm ON r.room_id = rm.id
                WHERE r.user_id = ?
                AND rm.room_type_id = ?
                AND r.status IN ('completed', 'checked_out')
                AND NOT EXISTS (
                    SELECT 1 FROM reviews rev
                    WHERE rev.reservation_id = r.id
                )
                LIMIT 1
            ");
      $stmt->execute([$user_id, $room_type_id]);
      return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
      error_log("Can user review room type error: " . $e->getMessage());
      return false;
    }
  }

  public function submitReview($room_type_id)
  {
    $this->requireLogin();
    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $rating = intval($_POST['rating'] ?? 0);
      $title = trim($_POST['title'] ?? '');
      $comment = trim($_POST['comment'] ?? '');

      // Validation
      if ($rating < 1 || $rating > 5) {
        $_SESSION['error'] = "Please select a valid rating (1-5 stars).";
        $this->redirect('index.php?action=rooms&sub_action=view&id=' . $room_type_id);
      }

      if (empty($title)) {
        $_SESSION['error'] = "Review title is required.";
        $this->redirect('index.php?action=rooms&sub_action=view&id=' . $room_type_id);
      }

      if (empty($comment)) {
        $_SESSION['error'] = "Review comment is required.";
        $this->redirect('index.php?action=rooms&sub_action=view&id=' . $room_type_id);
      }

      // Check if user can review this room type
      if (!$this->canUserReviewRoomType($room_type_id)) {
        $_SESSION['error'] = "You can only review room types you have stayed in.";
        $this->redirect('index.php?action=rooms&sub_action=view&id=' . $room_type_id);
      }

      try {
        // Get the reservation ID for this room type and user
        $stmt = $this->pdo->prepare("
                    SELECT r.id
                    FROM reservations r
                    JOIN rooms rm ON r.room_id = rm.id
                    WHERE r.user_id = ?
                    AND rm.room_type_id = ?
                    AND r.status IN ('completed', 'checked_out')
                    AND NOT EXISTS (
                        SELECT 1 FROM reviews WHERE reservation_id = r.id
                    )
                    LIMIT 1
                ");
        $stmt->execute([$user_id, $room_type_id]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reservation) {
          $_SESSION['error'] = "No eligible reservation found for review.";
          $this->redirect('index.php?action=rooms&sub_action=view&id=' . $room_type_id);
        }

        // Submit review
        $stmt = $this->pdo->prepare("
                    INSERT INTO reviews
                    (reservation_id, user_id, room_id, rating, comment, is_approved, created_at)
                    VALUES (?, ?, ?, ?, ?, 0, NOW())
                ");

        // We need to get a specific room ID for this review
        $room_stmt = $this->pdo->prepare("
                    SELECT r.id FROM rooms r
                    JOIN reservations res ON r.id = res.room_id
                    WHERE res.id = ?
                ");
        $room_stmt->execute([$reservation['id']]);
        $room = $room_stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->execute([
          $reservation['id'],
          $user_id,
          $room['id'] ?? null,
          $rating,
          $comment
        ]);

        // Log the action
        $this->logAction($user_id, "Submitted review for room type #$room_type_id");

        $_SESSION['success'] = "Review submitted successfully. It will be visible after approval.";
        $this->redirect('index.php?action=rooms&sub_action=view&id=' . $room_type_id);
      } catch (PDOException $e) {
        error_log("Submit review error: " . $e->getMessage());
        $_SESSION['error'] = "Failed to submit review. Please try again.";
        $this->redirect('index.php?action=rooms&sub_action=view&id=' . $room_type_id);
      }
    }

    $this->redirect('index.php?action=rooms&sub_action=view&id=' . $room_type_id);
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

  public function compare()
  {
    $room_ids = $_GET['rooms'] ?? [];

    if (empty($room_ids) || count($room_ids) > 3) {
      $_SESSION['error'] = "Please select 1-3 rooms to compare.";
      $this->redirect('index.php?action=rooms');
    }

    $rooms = [];
    foreach ($room_ids as $room_id) {
      $room = $this->getRoomTypeForComparison($room_id);
      if ($room) {
        $rooms[] = $room;
      }
    }

    if (empty($rooms)) {
      $_SESSION['error'] = "No valid rooms selected for comparison.";
      $this->redirect('index.php?action=rooms');
    }

    $data = [
      'rooms' => $rooms,
      'page_title' => 'Compare Rooms'
    ];

    $this->render('public/room-compare', $data);
  }

  private function getRoomTypeForComparison($room_type_id)
  {
    try {
      $stmt = $this->pdo->prepare("
                SELECT rt.*,
                       COUNT(r.id) as available_count,
                       (SELECT COUNT(*) FROM reservations res
                        JOIN rooms rm ON res.room_id = rm.id
                        WHERE rm.room_type_id = rt.id
                        AND res.status IN ('completed', 'checked_out')) as total_bookings,
                       (SELECT AVG(rev.rating) FROM reviews rev
                        JOIN reservations res ON rev.reservation_id = res.id
                        JOIN rooms rm ON res.room_id = rm.id
                        WHERE rm.room_type_id = rt.id
                        AND rev.is_approved = 1) as avg_rating
                FROM room_types rt
                LEFT JOIN rooms r ON rt.id = r.room_type_id
                    AND r.status = 'available'
                WHERE rt.id = ? AND rt.is_active = 1
                GROUP BY rt.id
            ");
      $stmt->execute([$room_type_id]);
      $room = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($room) {
        // Decode amenities
        if (!empty($room['amenities'])) {
          $room['amenities'] = json_decode($room['amenities'], true);
        } else {
          $room['amenities'] = [];
        }

        // Get images
        $room['images'] = $this->getRoomTypeImages($room_type_id);
      }

      return $room;
    } catch (PDOException $e) {
      error_log("Get room type for comparison error: " . $e->getMessage());
      return null;
    }
  }
}
