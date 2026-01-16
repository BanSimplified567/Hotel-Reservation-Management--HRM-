<?php
// app/controllers/RoomController.php
require_once __DIR__ . '/Path/BaseController.php';

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
        $max_price = floatval($_GET['max_price'] ?? 1000);
        $capacity = intval($_GET['capacity'] ?? 1);
        $amenities = $_GET['amenities'] ?? [];
        $sort = $_GET['sort'] ?? 'price_asc'; // price_asc, price_desc, name_asc
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 12;

        // Get all available rooms with filters
        $rooms = $this->getRooms($type, $min_price, $max_price, $capacity, $amenities, $sort, $page, $perPage);
        $total_rooms = $this->countRooms($type, $min_price, $max_price, $capacity, $amenities);
        $total_pages = ceil($total_rooms / $perPage);

        // Get room types for filter
        $room_types = $this->getRoomTypes();

        // Get amenities for filter
        $all_amenities = $this->getAllAmenities();

        // Get featured rooms
        $featured_rooms = $this->getFeaturedRooms();

        $data = [
            'rooms' => $rooms,
            'total_rooms' => $total_rooms,
            'total_pages' => $total_pages,
            'room_types' => $room_types,
            'all_amenities' => $all_amenities,
            'featured_rooms' => $featured_rooms,
            'type' => $type,
            'min_price' => $min_price,
            'max_price' => $max_price,
            'capacity' => $capacity,
            'amenities' => $amenities,
            'sort' => $sort,
            'page' => $page,
            'page_title' => 'Our Rooms'
        ];

        $this->render('public/rooms', $data);
    }

    public function view($id)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*,
                       (SELECT COUNT(*) FROM reservations res
                        WHERE res.room_id = r.id
                        AND res.status = 'completed') as total_bookings,
                       (SELECT AVG(rating) FROM reviews rev
                        JOIN reservations res ON rev.reservation_id = res.id
                        WHERE res.room_id = r.id) as avg_rating
                FROM rooms r
                WHERE r.id = ? AND r.status = 'available'
            ");
            $stmt->execute([$id]);
            $room = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$room) {
                $_SESSION['error'] = "Room not found or not available.";
                $this->redirect('rooms');
            }

            // Decode amenities
            if (!empty($room['amenities'])) {
                $room['amenities'] = json_decode($room['amenities'], true);
            } else {
                $room['amenities'] = [];
            }

            // Get similar rooms
            $similar_rooms = $this->getSimilarRooms($room['type'], $id);

            // Get room reviews
            $reviews = $this->getRoomReviews($id);

            // Check if user can review this room
            $can_review = $this->canUserReviewRoom($id);

            $data = [
                'room' => $room,
                'similar_rooms' => $similar_rooms,
                'reviews' => $reviews,
                'can_review' => $can_review,
                'page_title' => 'Room Details'
            ];

            $this->render('public/room-details', $data);
        } catch (PDOException $e) {
            error_log("View room error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load room details.";
            $this->redirect('rooms');
        }
    }

    private function getRooms($type, $min_price, $max_price, $capacity, $amenities, $sort, $page, $perPage)
    {
        try {
            $offset = ($page - 1) * $perPage;

            $query = "
                SELECT r.*
                FROM rooms r
                WHERE r.status = 'available'
                AND r.price_per_night BETWEEN ? AND ?
                AND r.capacity >= ?
            ";

            $params = [$min_price, $max_price, $capacity];

            if (!empty($type)) {
                $query .= " AND r.type = ?";
                $params[] = $type;
            }

            if (!empty($amenities)) {
                foreach ($amenities as $amenity) {
                    $query .= " AND JSON_CONTAINS(r.amenities, ?)";
                    $params[] = json_encode($amenity);
                }
            }

            // Add sorting
            switch ($sort) {
                case 'price_desc':
                    $query .= " ORDER BY r.price_per_night DESC";
                    break;
                case 'name_asc':
                    $query .= " ORDER BY r.type ASC, r.room_number ASC";
                    break;
                case 'name_desc':
                    $query .= " ORDER BY r.type DESC, r.room_number DESC";
                    break;
                default: // price_asc
                    $query .= " ORDER BY r.price_per_night ASC";
                    break;
            }

            // Add pagination
            $query .= " LIMIT ? OFFSET ?";
            $params[] = $perPage;
            $params[] = $offset;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Decode amenities for each room
            foreach ($rooms as &$room) {
                if (!empty($room['amenities'])) {
                    $room['amenities'] = json_decode($room['amenities'], true);
                } else {
                    $room['amenities'] = [];
                }

                // Add booking count and rating
                $room['total_bookings'] = $this->getRoomBookingCount($room['id']);
                $room['avg_rating'] = $this->getRoomAverageRating($room['id']);
            }

            return $rooms;
        } catch (PDOException $e) {
            error_log("Get rooms error: " . $e->getMessage());
            return [];
        }
    }

    private function countRooms($type, $min_price, $max_price, $capacity, $amenities)
    {
        try {
            $query = "
                SELECT COUNT(*) as total
                FROM rooms r
                WHERE r.status = 'available'
                AND r.price_per_night BETWEEN ? AND ?
                AND r.capacity >= ?
            ";

            $params = [$min_price, $max_price, $capacity];

            if (!empty($type)) {
                $query .= " AND r.type = ?";
                $params[] = $type;
            }

            if (!empty($amenities)) {
                foreach ($amenities as $amenity) {
                    $query .= " AND JSON_CONTAINS(r.amenities, ?)";
                    $params[] = json_encode($amenity);
                }
            }

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Count rooms error: " . $e->getMessage());
            return 0;
        }
    }

    private function getRoomTypes()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT DISTINCT type
                FROM rooms
                WHERE status = 'available'
                ORDER BY type
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Get room types error: " . $e->getMessage());
            return [];
        }
    }

    private function getAllAmenities()
    {
        // This could be fetched from a database table
        // For now, return a predefined list
        return [
            'WiFi', 'TV', 'Air Conditioning', 'Mini Bar', 'Safe', 'Hair Dryer',
            'Coffee Maker', 'Iron', 'Room Service', 'Balcony', 'Ocean View',
            'Bathtub', 'Kitchenette', 'Jacuzzi', 'Fireplace', 'Pool View',
            'Garden View', 'City View', 'Mountain View', 'Private Pool'
        ];
    }

    private function getFeaturedRooms($limit = 6)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*,
                       (SELECT COUNT(*) FROM reservations res
                        WHERE res.room_id = r.id
                        AND res.status = 'completed') as booking_count
                FROM rooms r
                WHERE r.status = 'available'
                ORDER BY booking_count DESC, r.created_at DESC
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Decode amenities
            foreach ($rooms as &$room) {
                if (!empty($room['amenities'])) {
                    $room['amenities'] = json_decode($room['amenities'], true);
                } else {
                    $room['amenities'] = [];
                }
            }

            return $rooms;
        } catch (PDOException $e) {
            error_log("Get featured rooms error: " . $e->getMessage());
            return [];
        }
    }

    private function getSimilarRooms($room_type, $exclude_id, $limit = 4)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, room_number, type, price_per_night, capacity, description
                FROM rooms
                WHERE type = ?
                AND id != ?
                AND status = 'available'
                ORDER BY price_per_night ASC
                LIMIT ?
            ");
            $stmt->execute([$room_type, $exclude_id, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get similar rooms error: " . $e->getMessage());
            return [];
        }
    }

    private function getRoomBookingCount($room_id)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as total
                FROM reservations
                WHERE room_id = ?
                AND status = 'completed'
            ");
            $stmt->execute([$room_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Get room booking count error: " . $e->getMessage());
            return 0;
        }
    }

    private function getRoomAverageRating($room_id)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT AVG(r.rating) as avg_rating
                FROM reviews r
                JOIN reservations res ON r.reservation_id = res.id
                WHERE res.room_id = ?
                AND r.status = 'approved'
            ");
            $stmt->execute([$room_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return round($result['avg_rating'] ?? 0, 1);
        } catch (PDOException $e) {
            error_log("Get room average rating error: " . $e->getMessage());
            return 0;
        }
    }

    private function getRoomReviews($room_id, $limit = 5)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*, u.username, u.first_name, u.last_name
                FROM reviews r
                JOIN reservations res ON r.reservation_id = res.id
                JOIN users u ON res.user_id = u.id
                WHERE res.room_id = ?
                AND r.status = 'approved'
                ORDER BY r.created_at DESC
                LIMIT ?
            ");
            $stmt->execute([$room_id, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get room reviews error: " . $e->getMessage());
            return [];
        }
    }

    private function canUserReviewRoom($room_id)
    {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        $user_id = $_SESSION['user_id'];

        try {
            // Check if user has completed a reservation for this room
            $stmt = $this->pdo->prepare("
                SELECT r.id
                FROM reservations r
                WHERE r.user_id = ?
                AND r.room_id = ?
                AND r.status = 'completed'
                AND NOT EXISTS (
                    SELECT 1 FROM reviews rev
                    WHERE rev.reservation_id = r.id
                )
                LIMIT 1
            ");
            $stmt->execute([$user_id, $room_id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Can user review room error: " . $e->getMessage());
            return false;
        }
    }

    public function submitReview($room_id)
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
                $this->redirect('rooms', ['sub_action' => 'view', 'id' => $room_id]);
            }

            if (empty($title)) {
                $_SESSION['error'] = "Review title is required.";
                $this->redirect('rooms', ['sub_action' => 'view', 'id' => $room_id]);
            }

            if (empty($comment)) {
                $_SESSION['error'] = "Review comment is required.";
                $this->redirect('rooms', ['sub_action' => 'view', 'id' => $room_id]);
            }

            // Check if user can review this room
            if (!$this->canUserReviewRoom($room_id)) {
                $_SESSION['error'] = "You can only review rooms you have stayed in.";
                $this->redirect('rooms', ['sub_action' => 'view', 'id' => $room_id]);
            }

            try {
                // Get the reservation ID for this room and user
                $stmt = $this->pdo->prepare("
                    SELECT id FROM reservations
                    WHERE user_id = ?
                    AND room_id = ?
                    AND status = 'completed'
                    AND NOT EXISTS (
                        SELECT 1 FROM reviews WHERE reservation_id = reservations.id
                    )
                    LIMIT 1
                ");
                $stmt->execute([$user_id, $room_id]);
                $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$reservation) {
                    $_SESSION['error'] = "No eligible reservation found for review.";
                    $this->redirect('rooms', ['sub_action' => 'view', 'id' => $room_id]);
                }

                // Submit review
                $stmt = $this->pdo->prepare("
                    INSERT INTO reviews
                    (reservation_id, rating, title, comment, status, created_at)
                    VALUES (?, ?, ?, ?, 'pending', NOW())
                ");
                $stmt->execute([$reservation['id'], $rating, $title, $comment]);

                // Log the action
                $this->logAction($user_id, "Submitted review for room #$room_id");

                $_SESSION['success'] = "Review submitted successfully. It will be visible after approval.";
                $this->redirect('rooms', ['sub_action' => 'view', 'id' => $room_id]);

            } catch (PDOException $e) {
                error_log("Submit review error: " . $e->getMessage());
                $_SESSION['error'] = "Failed to submit review. Please try again.";
                $this->redirect('rooms', ['sub_action' => 'view', 'id' => $room_id]);
            }
        }

        $this->redirect('rooms', ['sub_action' => 'view', 'id' => $room_id]);
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
            $this->redirect('rooms');
        }

        $rooms = [];
        foreach ($room_ids as $room_id) {
            $room = $this->getRoomForComparison($room_id);
            if ($room) {
                $rooms[] = $room;
            }
        }

        if (empty($rooms)) {
            $_SESSION['error'] = "No valid rooms selected for comparison.";
            $this->redirect('rooms');
        }

        $data = [
            'rooms' => $rooms,
            'page_title' => 'Compare Rooms'
        ];

        $this->render('public/room-compare', $data);
    }

    private function getRoomForComparison($room_id)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*,
                       (SELECT COUNT(*) FROM reservations res
                        WHERE res.room_id = r.id
                        AND res.status = 'completed') as total_bookings,
                       (SELECT AVG(rating) FROM reviews rev
                        JOIN reservations res ON rev.reservation_id = res.id
                        WHERE res.room_id = r.id
                        AND rev.status = 'approved') as avg_rating
                FROM rooms r
                WHERE r.id = ? AND r.status = 'available'
            ");
            $stmt->execute([$room_id]);
            $room = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($room) {
                // Decode amenities
                if (!empty($room['amenities'])) {
                    $room['amenities'] = json_decode($room['amenities'], true);
                } else {
                    $room['amenities'] = [];
                }
            }

            return $room;
        } catch (PDOException $e) {
            error_log("Get room for comparison error: " . $e->getMessage());
            return null;
        }
    }
}
