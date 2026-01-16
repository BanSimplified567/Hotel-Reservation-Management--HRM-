<?php
// app/controllers/RoomSearchController.php
require_once __DIR__ . '/Path/BaseController.php';

class RoomSearchController extends BaseController
{
    public function __construct($pdo)
    {
        parent::__construct($pdo);
    }

    public function index()
    {
        // Get search parameters
        $check_in = $_GET['check_in'] ?? '';
        $check_out = $_GET['check_out'] ?? '';
        $guests = intval($_GET['guests'] ?? 1);
        $room_type = $_GET['room_type'] ?? '';
        $min_price = floatval($_GET['min_price'] ?? 0);
        $max_price = floatval($_GET['max_price'] ?? 1000);
        $amenities = $_GET['amenities'] ?? [];
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 12;

        $available_rooms = [];
        $total_rooms = 0;
        $total_pages = 1;

        if (!empty($check_in) && !empty($check_out)) {
            // Validate dates
            $check_in_date = new DateTime($check_in);
            $check_out_date = new DateTime($check_out);
            $today = new DateTime();

            if ($check_in_date >= $today && $check_out_date > $check_in_date) {
                $available_rooms = $this->searchAvailableRooms(
                    $check_in, $check_out, $guests, $room_type,
                    $min_price, $max_price, $amenities, $page, $perPage
                );

                $total_rooms = $this->countAvailableRooms(
                    $check_in, $check_out, $guests, $room_type,
                    $min_price, $max_price, $amenities
                );

                $total_pages = ceil($total_rooms / $perPage);
            }
        }

        // Get room types for filter
        $room_types = $this->getRoomTypes();

        // Get amenities for filter
        $all_amenities = $this->getAllAmenities();

        $data = [
            'available_rooms' => $available_rooms,
            'total_rooms' => $total_rooms,
            'total_pages' => $total_pages,
            'room_types' => $room_types,
            'all_amenities' => $all_amenities,
            'check_in' => $check_in,
            'check_out' => $check_out,
            'guests' => $guests,
            'room_type' => $room_type,
            'min_price' => $min_price,
            'max_price' => $max_price,
            'amenities' => $amenities,
            'page' => $page,
            'page_title' => 'Search Rooms'
        ];

        $this->render('public/room-search', $data);
    }

    private function searchAvailableRooms($check_in, $check_out, $guests, $room_type,
                                          $min_price, $max_price, $amenities, $page, $perPage)
    {
        try {
            $offset = ($page - 1) * $perPage;

            // Base query for available rooms
            $query = "
                SELECT DISTINCT r.*
                FROM rooms r
                WHERE r.status = 'available'
                AND r.capacity >= ?
                AND r.price_per_night BETWEEN ? AND ?
            ";

            $params = [$guests, $min_price, $max_price];

            // Add room type filter
            if (!empty($room_type)) {
                $query .= " AND r.type = ?";
                $params[] = $room_type;
            }

            // Add amenities filter
            if (!empty($amenities)) {
                foreach ($amenities as $amenity) {
                    $query .= " AND JSON_CONTAINS(r.amenities, ?)";
                    $params[] = json_encode($amenity);
                }
            }

            // Exclude rooms with conflicting reservations
            $query .= " AND r.id NOT IN (
                SELECT room_id FROM reservations
                WHERE status IN ('confirmed', 'checked_in')
                AND (
                    (check_in <= ? AND check_out >= ?) OR
                    (check_in <= ? AND check_out >= ?) OR
                    (check_in >= ? AND check_out <= ?)
                )
            )";

            $params = array_merge($params, [
                $check_out, $check_in,
                $check_in, $check_out,
                $check_in, $check_out
            ]);

            // Add sorting and pagination
            $query .= " ORDER BY r.price_per_night ASC, r.type ASC LIMIT ? OFFSET ?";
            $params[] = $perPage;
            $params[] = $offset;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Decode amenities JSON
            foreach ($rooms as &$room) {
                if (!empty($room['amenities'])) {
                    $room['amenities'] = json_decode($room['amenities'], true);
                } else {
                    $room['amenities'] = [];
                }
            }

            return $rooms;
        } catch (PDOException $e) {
            error_log("Search rooms error: " . $e->getMessage());
            return [];
        }
    }

    private function countAvailableRooms($check_in, $check_out, $guests, $room_type,
                                         $min_price, $max_price, $amenities)
    {
        try {
            $query = "
                SELECT COUNT(DISTINCT r.id) as total
                FROM rooms r
                WHERE r.status = 'available'
                AND r.capacity >= ?
                AND r.price_per_night BETWEEN ? AND ?
            ";

            $params = [$guests, $min_price, $max_price];

            if (!empty($room_type)) {
                $query .= " AND r.type = ?";
                $params[] = $room_type;
            }

            if (!empty($amenities)) {
                foreach ($amenities as $amenity) {
                    $query .= " AND JSON_CONTAINS(r.amenities, ?)";
                    $params[] = json_encode($amenity);
                }
            }

            $query .= " AND r.id NOT IN (
                SELECT room_id FROM reservations
                WHERE status IN ('confirmed', 'checked_in')
                AND (
                    (check_in <= ? AND check_out >= ?) OR
                    (check_in <= ? AND check_out >= ?) OR
                    (check_in >= ? AND check_out <= ?)
                )
            )";

            $params = array_merge($params, [
                $check_out, $check_in,
                $check_in, $check_out,
                $check_in, $check_out
            ]);

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
        // This is a predefined list. In a real app, you might want to store this in the database.
        return [
            'WiFi', 'TV', 'Air Conditioning', 'Mini Bar', 'Safe', 'Hair Dryer',
            'Coffee Maker', 'Iron', 'Room Service', 'Balcony', 'Ocean View',
            'Bathtub', 'Kitchenette', 'Jacuzzi', 'Fireplace', 'Pool View',
            'Garden View', 'City View', 'Mountain View', 'Private Pool'
        ];
    }

    public function quickSearch()
    {
        // This can be called via AJAX for quick search
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $check_in = $_POST['check_in'] ?? '';
            $check_out = $_POST['check_out'] ?? '';
            $guests = intval($_POST['guests'] ?? 1);

            if (empty($check_in) || empty($check_out)) {
                echo json_encode(['success' => false, 'message' => 'Please select dates']);
                exit();
            }

            // Validate dates
            $check_in_date = new DateTime($check_in);
            $check_out_date = new DateTime($check_out);
            $today = new DateTime();

            if ($check_in_date < $today) {
                echo json_encode(['success' => false, 'message' => 'Check-in date cannot be in the past']);
                exit();
            }

            if ($check_out_date <= $check_in_date) {
                echo json_encode(['success' => false, 'message' => 'Check-out date must be after check-in date']);
                exit();
            }

            try {
                // Count available rooms
                $query = "
                    SELECT COUNT(DISTINCT r.id) as available_rooms,
                           MIN(r.price_per_night) as min_price,
                           MAX(r.price_per_night) as max_price,
                           GROUP_CONCAT(DISTINCT r.type ORDER BY r.type) as room_types
                    FROM rooms r
                    WHERE r.status = 'available'
                    AND r.capacity >= ?
                    AND r.id NOT IN (
                        SELECT room_id FROM reservations
                        WHERE status IN ('confirmed', 'checked_in')
                        AND (
                            (check_in <= ? AND check_out >= ?) OR
                            (check_in <= ? AND check_out >= ?) OR
                            (check_in >= ? AND check_out <= ?)
                        )
                    )
                ";

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([
                    $guests,
                    $check_out, $check_in,
                    $check_in, $check_out,
                    $check_in, $check_out
                ]);

                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result['available_rooms'] > 0) {
                    echo json_encode([
                        'success' => true,
                        'available_rooms' => $result['available_rooms'],
                        'min_price' => $result['min_price'],
                        'max_price' => $result['max_price'],
                        'room_types' => explode(',', $result['room_types']),
                        'search_url' => "index.php?action=room-search&check_in=$check_in&check_out=$check_out&guests=$guests"
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'No rooms available for the selected dates'
                    ]);
                }
            } catch (PDOException $e) {
                error_log("Quick search error: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'System error']);
            }
            exit();
        }

        $this->redirect('room-search');
    }

    public function getRoomDetails($room_id)
    {
        // This can be called via AJAX
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && $room_id > 0) {
            try {
                $stmt = $this->pdo->prepare("
                    SELECT r.*,
                           (SELECT COUNT(*) FROM reservations res
                            WHERE res.room_id = r.id
                            AND res.status = 'completed') as total_bookings,
                           (SELECT AVG(total_amount) FROM reservations res
                            WHERE res.room_id = r.id
                            AND res.status = 'completed') as avg_booking_amount
                    FROM rooms r
                    WHERE r.id = ?
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

                    // Get similar rooms
                    $stmt = $this->pdo->prepare("
                        SELECT id, room_number, type, price_per_night, capacity
                        FROM rooms
                        WHERE type = ?
                        AND id != ?
                        AND status = 'available'
                        ORDER BY price_per_night ASC
                        LIMIT 3
                    ");
                    $stmt->execute([$room['type'], $room_id]);
                    $similar_rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    echo json_encode([
                        'success' => true,
                        'room' => $room,
                        'similar_rooms' => $similar_rooms
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Room not found']);
                }
            } catch (PDOException $e) {
                error_log("Get room details error: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'System error']);
            }
            exit();
        }

        $this->redirect('room-search');
    }
}
