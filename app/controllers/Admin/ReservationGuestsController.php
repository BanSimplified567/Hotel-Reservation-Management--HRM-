<?php
// app/controllers/Admin/ReservationGuestsController.php

require_once __DIR__ . '/../Path/BaseController.php';

class ReservationGuestsController extends BaseController
{
    public function __construct($pdo)
    {
        parent::__construct($pdo);
    }

    public function index()
    {
        // Check authorization
        $this->requireLogin();
        if (!in_array(strtolower($_SESSION['role'] ?? ''), ['admin', 'staff'])) {
            $this->redirect('403');
        }

        // Get filter parameters
        $search = $_GET['search'] ?? '';
        $reservation_id = $_GET['reservation_id'] ?? '';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 15;

        $query = "
            SELECT rg.*,
                   r.check_in, r.check_out, r.status as reservation_status,
                   u.first_name as user_first_name, u.last_name as user_last_name,
                   rm.room_number
            FROM reservation_guests rg
            JOIN reservations r ON rg.reservation_id = r.id
            JOIN users u ON r.user_id = u.id
            JOIN rooms rm ON r.room_id = rm.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (rg.guest_name LIKE ? OR rg.guest_email LIKE ? OR rg.guest_phone LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if (!empty($reservation_id)) {
            $query .= " AND rg.reservation_id = ?";
            $params[] = $reservation_id;
        }

        $query .= " ORDER BY rg.created_at DESC";

        // Get total count
        $countQuery = str_replace("SELECT rg.*,
                   r.check_in, r.check_out, r.status as reservation_status,
                   u.first_name as user_first_name, u.last_name as user_last_name,
                   rm.room_number", "SELECT COUNT(*)", $query);
        $stmt = $this->pdo->prepare($countQuery);
        $stmt->execute($params);
        $total = $stmt->fetchColumn();
        $totalPages = ceil($total / $perPage);

        // Add pagination
        $query .= " LIMIT " . (($page - 1) * $perPage) . ", $perPage";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $guests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'guests' => $guests,
            'search' => $search,
            'reservation_id' => $reservation_id,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'page_title' => 'Reservation Guests'
        ];

        $this->render('admin/reservation-guests/index', $data);
    }

    public function create()
    {
        $this->requireLogin();
        if (!in_array(strtolower($_SESSION['role'] ?? ''), ['admin', 'staff'])) {
            $this->redirect('403');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreate();
        } else {
            // Get reservations for dropdown
            $stmt = $this->pdo->query("
                SELECT r.id, r.check_in, r.check_out, u.first_name, u.last_name, rm.room_number
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                JOIN rooms rm ON r.room_id = rm.id
                WHERE r.status IN ('pending', 'confirmed', 'checked_in')
                ORDER BY r.check_in DESC
            ");
            $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $data = [
                'reservations' => $reservations,
                'page_title' => 'Add Reservation Guest'
            ];

            $this->render('admin/reservation-guests/create', $data);
        }
    }

    private function handleCreate()
    {
        $reservation_id = $_POST['reservation_id'] ?? '';
        $guest_name = trim($_POST['guest_name'] ?? '');
        $guest_email = trim($_POST['guest_email'] ?? '');
        $guest_phone = trim($_POST['guest_phone'] ?? '');
        $guest_address = trim($_POST['guest_address'] ?? '');
        $id_type = trim($_POST['id_type'] ?? '');
        $id_number = trim($_POST['id_number'] ?? '');

        $errors = [];

        if (empty($reservation_id)) {
            $errors[] = "Reservation is required.";
        }

        if (empty($guest_name)) {
            $errors[] = "Guest name is required.";
        }

        if (empty($guest_email)) {
            $errors[] = "Guest email is required.";
        } elseif (!filter_var($guest_email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            $_SESSION['old'] = $_POST;
            $this->redirect('admin/reservation-guests/create');
        }

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO reservation_guests
                (reservation_id, guest_name, guest_email, guest_phone, guest_address, id_type, id_number, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $reservation_id, $guest_name, $guest_email, $guest_phone,
                $guest_address, $id_type, $id_number
            ]);

            $_SESSION['success'] = "Reservation guest added successfully.";
            $this->redirect('admin/reservation-guests');
        } catch (PDOException $e) {
            error_log("Create reservation guest error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to add reservation guest.";
            $this->redirect('admin/reservation-guests/create');
        }
    }

    public function edit($id)
    {
        $this->requireLogin();
        if (!in_array(strtolower($_SESSION['role'] ?? ''), ['admin', 'staff'])) {
            $this->redirect('403');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleUpdate($id);
        } else {
            $this->showEditForm($id);
        }
    }

    private function showEditForm($id)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT rg.*,
                       r.check_in, r.check_out,
                       u.first_name, u.last_name, rm.room_number
                FROM reservation_guests rg
                JOIN reservations r ON rg.reservation_id = r.id
                JOIN users u ON r.user_id = u.id
                JOIN rooms rm ON r.room_id = rm.id
                WHERE rg.id = ?
            ");
            $stmt->execute([$id]);
            $guest = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$guest) {
                $_SESSION['error'] = "Reservation guest not found.";
                $this->redirect('admin/reservation-guests');
            }

            // Get reservations for dropdown
            $stmt = $this->pdo->query("
                SELECT r.id, r.check_in, r.check_out, u.first_name, u.last_name, rm.room_number
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                JOIN rooms rm ON r.room_id = rm.id
                WHERE r.status IN ('pending', 'confirmed', 'checked_in')
                ORDER BY r.check_in DESC
            ");
            $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $data = [
                'guest' => $guest,
                'reservations' => $reservations,
                'page_title' => 'Edit Reservation Guest'
            ];

            $this->render('admin/reservation-guests/edit', $data);
        } catch (PDOException $e) {
            error_log("Show edit form error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load edit form.";
            $this->redirect('admin/reservation-guests');
        }
    }

    private function handleUpdate($id)
    {
        $reservation_id = $_POST['reservation_id'] ?? '';
        $guest_name = trim($_POST['guest_name'] ?? '');
        $guest_email = trim($_POST['guest_email'] ?? '');
        $guest_phone = trim($_POST['guest_phone'] ?? '');
        $guest_address = trim($_POST['guest_address'] ?? '');
        $id_type = trim($_POST['id_type'] ?? '');
        $id_number = trim($_POST['id_number'] ?? '');

        $errors = [];

        if (empty($reservation_id)) {
            $errors[] = "Reservation is required.";
        }

        if (empty($guest_name)) {
            $errors[] = "Guest name is required.";
        }

        if (empty($guest_email)) {
            $errors[] = "Guest email is required.";
        } elseif (!filter_var($guest_email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            $_SESSION['old'] = $_POST;
            $this->redirect('admin/reservation-guests/edit?id=' . $id);
        }

        try {
            $stmt = $this->pdo->prepare("
                UPDATE reservation_guests SET
                reservation_id = ?, guest_name = ?, guest_email = ?, guest_phone = ?,
                guest_address = ?, id_type = ?, id_number = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([
                $reservation_id, $guest_name, $guest_email, $guest_phone,
                $guest_address, $id_type, $id_number, $id
            ]);

            $_SESSION['success'] = "Reservation guest updated successfully.";
            $this->redirect('admin/reservation-guests');
        } catch (PDOException $e) {
            error_log("Update reservation guest error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to update reservation guest.";
            $this->redirect('admin/reservation-guests/edit?id=' . $id);
        }
    }

    public function view($id)
    {
        $this->requireLogin();
        if (!in_array(strtolower($_SESSION['role'] ?? ''), ['admin', 'staff'])) {
            $this->redirect('403');
        }

        try {
            $stmt = $this->pdo->prepare("
                SELECT rg.*,
                       r.check_in, r.check_out, r.status as reservation_status,
                       u.first_name as user_first_name, u.last_name as user_last_name,
                       rm.room_number, rt.name as room_type
                FROM reservation_guests rg
                JOIN reservations r ON rg.reservation_id = r.id
                JOIN users u ON r.user_id = u.id
                JOIN rooms rm ON r.room_id = rm.id
                JOIN room_types rt ON rm.room_type_id = rt.id
                WHERE rg.id = ?
            ");
            $stmt->execute([$id]);
            $guest = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$guest) {
                $_SESSION['error'] = "Reservation guest not found.";
                $this->redirect('admin/reservation-guests');
            }

            $data = [
                'guest' => $guest,
                'page_title' => 'View Reservation Guest'
            ];

            $this->render('admin/reservation-guests/view', $data);
        } catch (PDOException $e) {
            error_log("View reservation guest error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load reservation guest details.";
            $this->redirect('admin/reservation-guests');
        }
    }

    public function delete($id)
    {
        $this->requireLogin();
        if (!in_array(strtolower($_SESSION['role'] ?? ''), ['admin', 'staff'])) {
            $this->redirect('403');
        }

        try {
            $stmt = $this->pdo->prepare("DELETE FROM reservation_guests WHERE id = ?");
            $stmt->execute([$id]);

            $_SESSION['success'] = "Reservation guest deleted successfully.";
        } catch (PDOException $e) {
            error_log("Delete reservation guest error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to delete reservation guest.";
        }

        $this->redirect('admin/reservation-guests');
    }
}
