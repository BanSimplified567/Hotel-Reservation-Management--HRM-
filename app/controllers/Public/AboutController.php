<?php
// app/controllers/Public/AboutController.php
require_once __DIR__ . '/../Path/BaseController.php';

class AboutController extends BaseController{


    public function index() {
        try {
            // Get hotel statistics
            $stats = $this->getHotelStats();

            // Get team members (if you have a team table, otherwise static)
            $team = $this->getTeamMembers();

            // Get hotel awards/recognition
            $awards = $this->getAwards();

            require_once '../app/views/public/about.php';
        } catch (Exception $e) {
            error_log("AboutController error: " . $e->getMessage());
            require_once '../app/views/public/about.php';
        }
    }

    private function getHotelStats() {
        return [
            'years' => 25,
            'rooms' => $this->countRooms(),
            'staff' => $this->countStaff(),
            'guests' => $this->countGuestsServed(),
            'awards' => 12
        ];
    }

    private function countRooms() {
        $sql = "SELECT COUNT(*) as total FROM rooms WHERE room_type_id != 4";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    private function countStaff() {
        $sql = "SELECT COUNT(*) as total FROM users WHERE role IN ('admin', 'staff') AND is_active = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    private function countGuestsServed() {
        $sql = "SELECT COUNT(DISTINCT user_id) as total FROM reservations WHERE status IN ('confirmed', 'checked_in', 'checked_out')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 1500;
    }

    private function getTeamMembers() {
        // Static team data - you can create a team table later
        return [
            [
                'name' => 'John Smith',
                'position' => 'General Manager',
                'photo' => 'team-1.jpg',
                'bio' => '20+ years in hospitality management',
                'email' => 'john@hotel.com'
            ],
            [
                'name' => 'Sarah Johnson',
                'position' => 'Head of Operations',
                'photo' => 'team-2.jpg',
                'bio' => 'Expert in hotel operations and guest services',
                'email' => 'sarah@hotel.com'
            ],
            [
                'name' => 'Michael Chen',
                'position' => 'Executive Chef',
                'photo' => 'team-3.jpg',
                'bio' => 'Award-winning culinary expert',
                'email' => 'michael@hotel.com'
            ],
            [
                'name' => 'Emily Wilson',
                'position' => 'Guest Relations Manager',
                'photo' => 'team-4.jpg',
                'bio' => 'Dedicated to exceptional guest experiences',
                'email' => 'emily@hotel.com'
            ]
        ];
    }

    private function getAwards() {
        return [
            ['year' => 2024, 'award' => 'Best Luxury Hotel - Travel Awards'],
            ['year' => 2023, 'award' => '5-Star Excellence Award'],
            ['year' => 2022, 'award' => 'Best Customer Service - Hospitality Awards'],
            ['year' => 2021, 'award' => 'Sustainable Hotel of the Year'],
            ['year' => 2020, 'award' => 'Best Business Hotel']
        ];
    }
}
?>
