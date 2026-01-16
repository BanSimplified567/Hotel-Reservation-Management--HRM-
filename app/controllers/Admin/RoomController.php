<?php
// app/controllers/Admin/RoomController.php
require_once __DIR__ . '/../Path/BaseController.php';

class RoomController extends BaseController
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

        // Get filter parameters
        $search = $_GET['search'] ?? '';
        $type = $_GET['type'] ?? '';
        $status = $_GET['status'] ?? '';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 15;

        // Build query
        $query = "SELECT * FROM rooms WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (room_number LIKE ? OR description LIKE ?)";
            $searchTerm = "%$search%";
            $params = array_merge($params, [$searchTerm, $searchTerm]);
        }

        if (!empty($type)) {
            $query .= " AND type = ?";
            $params[] = $type;
        }

        if (!empty($status)) {
            $query .= " AND status = ?";
            $params[] = $status;
        }

        // Get total count
        $countQuery = "SELECT COUNT(*) FROM (" . $query . ") as total";
        $countStmt = $this->pdo->prepare($countQuery);
        $countStmt->execute($params);
        $totalRooms = $countStmt->fetchColumn();
        $totalPages = ceil($totalRooms / $perPage);

        // Add pagination
        $offset = ($page - 1) * $perPage;
        $query .= " ORDER BY room_number ASC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;

        // Execute query
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get room types for filter
        $typeStmt = $this->pdo->prepare("SELECT DISTINCT type FROM rooms ORDER BY type");
        $typeStmt->execute();
        $roomTypes = $typeStmt->fetchAll(PDO::FETCH_COLUMN);

        $data = [
            'rooms' => $rooms,
            'roomTypes' => $roomTypes,
            'search' => $search,
            'type' => $type,
            'status' => $status,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalRooms' => $totalRooms,
            'page_title' => 'Manage Rooms'
        ];

        $this->render('admin/rooms/index', $data);
    }

    public function create()
    {
        $this->requireLogin();

        if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
            $this->redirect('403');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreateRoom();
        } else {
            $this->showCreateForm();
        }
    }

    private function handleCreateRoom()
    {
        $errors = [];

        // Collect form data
        $room_number = trim($_POST['room_number'] ?? '');
        $type = $_POST['type'] ?? '';
        $description = trim($_POST['description'] ?? '');
        $price_per_night = floatval($_POST['price_per_night'] ?? 0);
        $capacity = intval($_POST['capacity'] ?? 1);
        $amenities = $_POST['amenities'] ?? [];
        $status = $_POST['status'] ?? 'available';

        // Validation
        if (empty($room_number)) {
            $errors[] = "Room number is required.";
        }

        if (empty($type)) {
            $errors[] = "Room type is required.";
        }

        if ($price_per_night <= 0) {
            $errors[] = "Price per night must be greater than 0.";
        }

        if ($capacity < 1) {
            $errors[] = "Capacity must be at least 1.";
        }

        // Check if room number already exists
        if (empty($errors)) {
            try {
                $stmt = $this->pdo->prepare("SELECT id FROM rooms WHERE room_number = ?");
                $stmt->execute([$room_number]);
                if ($stmt->rowCount() > 0) {
                    $errors[] = "Room number already exists.";
                }
            } catch (PDOException $e) {
                error_log("Room check error: " . $e->getMessage());
                $errors[] = "System error. Please try again.";
            }
        }

        if (empty($errors)) {
            try {
                // Convert amenities array to JSON string
                $amenities_json = !empty($amenities) ? json_encode($amenities) : null;

                $stmt = $this->pdo->prepare("
                    INSERT INTO rooms
                    (room_number, type, description, price_per_night, capacity, amenities, status, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
                ");

                $stmt->execute([
                    $room_number, $type, $description, $price_per_night,
                    $capacity, $amenities_json, $status
                ]);

                // Log the action
                $this->logAction($_SESSION['user_id'], "Created room: $room_number");

                $_SESSION['success'] = "Room created successfully.";
                $this->redirect('admin/rooms');

            } catch (PDOException $e) {
                error_log("Create room error: " . $e->getMessage());
                $errors[] = "Failed to create room. Please try again.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            $_SESSION['old'] = $_POST;
            $this->redirect('admin/rooms', ['sub_action' => 'create']);
        }
    }

    private function showCreateForm()
    {
        // Get predefined room types
        $roomTypes = ['Standard', 'Deluxe', 'Suite', 'Executive', 'Family', 'Penthouse'];

        // Get predefined amenities
        $allAmenities = [
            'WiFi', 'TV', 'Air Conditioning', 'Mini Bar', 'Safe', 'Hair Dryer',
            'Coffee Maker', 'Iron', 'Room Service', 'Balcony', 'Ocean View',
            'Bathtub', 'Kitchenette', 'Jacuzzi', 'Fireplace'
        ];

        $data = [
            'roomTypes' => $roomTypes,
            'allAmenities' => $allAmenities,
            'page_title' => 'Create Room'
        ];

        $this->render('admin/rooms/create', $data);
    }

    public function edit($id)
    {
        $this->requireLogin();

        if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
            $this->redirect('403');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEditRoom($id);
        } else {
            $this->showEditForm($id);
        }
    }

    private function handleEditRoom($id)
    {
        $errors = [];

        // Collect form data
        $room_number = trim($_POST['room_number'] ?? '');
        $type = $_POST['type'] ?? '';
        $description = trim($_POST['description'] ?? '');
        $price_per_night = floatval($_POST['price_per_night'] ?? 0);
        $capacity = intval($_POST['capacity'] ?? 1);
        $amenities = $_POST['amenities'] ?? [];
        $status = $_POST['status'] ?? 'available';

        // Validation
        if (empty($room_number)) {
            $errors[] = "Room number is required.";
        }

        if (empty($type)) {
            $errors[] = "Room type is required.";
        }

        if ($price_per_night <= 0) {
            $errors[] = "Price per night must be greater than 0.";
        }

        if ($capacity < 1) {
            $errors[] = "Capacity must be at least 1.";
        }

        // Check if room number already exists (excluding current room)
        if (empty($errors)) {
            try {
                $stmt = $this->pdo->prepare("SELECT id FROM rooms WHERE room_number = ? AND id != ?");
                $stmt->execute([$room_number, $id]);
                if ($stmt->rowCount() > 0) {
                    $errors[] = "Room number already exists.";
                }
            } catch (PDOException $e) {
                error_log("Room check error: " . $e->getMessage());
                $errors[] = "System error. Please try again.";
            }
        }

        // Check if room can be set to available (not occupied by active reservation)
        if ($status == 'available') {
            try {
                $stmt = $this->pdo->prepare("
                    SELECT COUNT(*) FROM reservations
                    WHERE room_id = ?
                    AND status IN ('confirmed', 'checked_in')
                    AND check_out > CURDATE()
                ");
                $stmt->execute([$id]);
                $activeReservations = $stmt->fetchColumn();

                if ($activeReservations > 0) {
                    $errors[] = "Cannot set room as available. It has active reservations.";
                }
            } catch (PDOException $e) {
                error_log("Room availability check error: " . $e->getMessage());
                $errors[] = "Failed to check room availability.";
            }
        }

        if (empty($errors)) {
            try {
                // Convert amenities array to JSON string
                $amenities_json = !empty($amenities) ? json_encode($amenities) : null;

                $stmt = $this->pdo->prepare("
                    UPDATE rooms SET
                    room_number = ?, type = ?, description = ?, price_per_night = ?,
                    capacity = ?, amenities = ?, status = ?, updated_at = NOW()
                    WHERE id = ?
                ");

                $stmt->execute([
                    $room_number, $type, $description, $price_per_night,
                    $capacity, $amenities_json, $status, $id
                ]);

                // Log the action
                $this->logAction($_SESSION['user_id'], "Updated room #$id: $room_number");

                $_SESSION['success'] = "Room updated successfully.";
                $this->redirect('admin/rooms');

            } catch (PDOException $e) {
                error_log("Update room error: " . $e->getMessage());
                $errors[] = "Failed to update room. Please try again.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            $_SESSION['old'] = $_POST;
            $this->redirect('admin/rooms', ['sub_action' => 'edit', 'id' => $id]);
        }
    }

    private function showEditForm($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM rooms WHERE id = ?");
            $stmt->execute([$id]);
            $room = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$room) {
                $_SESSION['error'] = "Room not found.";
                $this->redirect('admin/rooms');
            }

            // Decode amenities JSON
            if (!empty($room['amenities'])) {
                $room['amenities'] = json_decode($room['amenities'], true);
            } else {
                $room['amenities'] = [];
            }

            // Get predefined room types
            $roomTypes = ['Standard', 'Deluxe', 'Suite', 'Executive', 'Family', 'Penthouse'];

            // Get predefined amenities
            $allAmenities = [
                'WiFi', 'TV', 'Air Conditioning', 'Mini Bar', 'Safe', 'Hair Dryer',
                'Coffee Maker', 'Iron', 'Room Service', 'Balcony', 'Ocean View',
                'Bathtub', 'Kitchenette', 'Jacuzzi', 'Fireplace'
            ];

            $data = [
                'room' => $room,
                'roomTypes' => $roomTypes,
                'allAmenities' => $allAmenities,
                'page_title' => 'Edit Room'
            ];

            $this->render('admin/rooms/edit', $data);
        } catch (PDOException $e) {
            error_log("Get room error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load room.";
            $this->redirect('admin/rooms');
        }
    }

    public function delete($id)
    {
        $this->requireLogin('admin');

        try {
            // Check if room has any reservations
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE room_id = ?");
            $stmt->execute([$id]);
            $reservationCount = $stmt->fetchColumn();

            if ($reservationCount > 0) {
                $_SESSION['error'] = "Cannot delete room with existing reservations.";
                $this->redirect('admin/rooms');
            }

            // Get room number for logging
            $stmt = $this->pdo->prepare("SELECT room_number FROM rooms WHERE id = ?");
            $stmt->execute([$id]);
            $room = $stmt->fetch(PDO::FETCH_ASSOC);

            // Delete room
            $stmt = $this->pdo->prepare("DELETE FROM rooms WHERE id = ?");
            $stmt->execute([$id]);

            // Log the action
            $this->logAction($_SESSION['user_id'], "Deleted room: " . ($room['room_number'] ?? "#$id"));

            $_SESSION['success'] = "Room deleted successfully.";
        } catch (PDOException $e) {
            error_log("Delete room error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to delete room.";
        }

        $this->redirect('admin/rooms');
    }

    public function view($id)
    {
        $this->requireLogin();

        if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
            $this->redirect('403');
        }

        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*,
                       COUNT(res.id) as total_reservations,
                       SUM(CASE WHEN res.status IN ('confirmed', 'checked_in') AND res.check_out > CURDATE() THEN 1 ELSE 0 END) as active_reservations
                FROM rooms r
                LEFT JOIN reservations res ON r.id = res.room_id
                WHERE r.id = ?
                GROUP BY r.id
            ");
            $stmt->execute([$id]);
            $room = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$room) {
                $_SESSION['error'] = "Room not found.";
                $this->redirect('admin/rooms');
            }

            // Decode amenities JSON
            if (!empty($room['amenities'])) {
                $room['amenities'] = json_decode($room['amenities'], true);
            } else {
                $room['amenities'] = [];
            }

            // Get upcoming reservations for this room
            $stmt = $this->pdo->prepare("
                SELECT res.*, u.first_name, u.last_name, u.email
                FROM reservations res
                JOIN users u ON res.user_id = u.id
                WHERE res.room_id = ?
                AND res.check_in >= CURDATE()
                AND res.status IN ('pending', 'confirmed')
                ORDER BY res.check_in ASC
                LIMIT 10
            ");
            $stmt->execute([$id]);
            $upcomingReservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $data = [
                'room' => $room,
                'upcomingReservations' => $upcomingReservations,
                'page_title' => 'View Room'
            ];

            $this->render('admin/rooms/view', $data);
        } catch (PDOException $e) {
            error_log("View room error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load room.";
            $this->redirect('admin/rooms');
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
