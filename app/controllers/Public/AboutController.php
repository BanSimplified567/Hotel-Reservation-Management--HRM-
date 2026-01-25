<?php
// app/controllers/Public/AboutController.php
require_once __DIR__ . '/../Path/BaseController.php';

class AboutController extends BaseController
{
    public function index()
    {
        try {
            // Get all required data
            $data = [
                'hotel_info' => $this->getHotelInfo(),
                'team_members' => $this->getTeamMembers(),
                'statistics' => $this->getHotelStatistics(),
                'awards' => $this->getAwards(),
                'amenities' => $this->getHotelAmenities()
            ];

            $this->render('public/about', $data);
        } catch (Exception $e) {
            error_log("AboutController error: " . $e->getMessage());
            // Fallback data in case of error
            $this->render('public/about', [
                'hotel_info' => $this->getHotelInfo(),
                'team_members' => $this->getDefaultTeam(),
                'statistics' => $this->getDefaultStatistics(),
                'awards' => [],
                'amenities' => $this->getHotelAmenities()
            ]);
        }
    }

    private function getHotelInfo()
    {
        // Using static data since there's no hotel_info/settings table
        return [
            'name' => 'Luxury Hotel & Resort',
            'description' => 'Experience luxury and comfort at our 5-star hotel located in the heart of the city. We offer world-class amenities and exceptional service.',
            'history' => 'Established in 2005, our hotel has been serving guests with exceptional hospitality for over 15 years. We pride ourselves on creating memorable experiences.',
            'mission' => 'To provide exceptional hospitality services that exceed our guests\' expectations through personalized attention and superior quality.',
            'vision' => 'To be the most preferred luxury hotel brand globally, known for our commitment to excellence and sustainable practices.',
            'address' => '123 Luxury Street, City Center, 12345',
            'phone' => '+1 (123) 456-7890',
            'email' => 'info@luxuryhotel.com',
            'established' => '2005',
            'rooms' => $this->countRooms(),
            'employees' => $this->countStaff(),
            'guests_served' => $this->countGuestsServed()
        ];
    }

    private function getTeamMembers()
    {
        try {
            // Get staff members from users table (role: staff, manager, or admin)
            $stmt = $this->pdo->prepare("
                SELECT
                    CONCAT(first_name, ' ', last_name) as name,
                    CASE
                        WHEN role = 'admin' THEN 'Administrator'
                        WHEN role = 'manager' THEN 'Manager'
                        WHEN role = 'staff' THEN 'Staff Member'
                        ELSE 'Team Member'
                    END as position,
                    CONCAT('Professional with expertise in hotel management') as bio,
                    profile_image as photo,
                    email
                FROM users
                WHERE role IN ('admin', 'manager', 'staff')
                AND is_active = 1
                ORDER BY
                    FIELD(role, 'admin', 'manager', 'staff'),
                    id
                LIMIT 4
            ");
            $stmt->execute();
            $team_members = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // If no staff in database, return default team
            if (empty($team_members)) {
                return $this->getDefaultTeam();
            }

            return $team_members;
        } catch (PDOException $e) {
            error_log("Get team members error: " . $e->getMessage());
            return $this->getDefaultTeam();
        }
    }

    private function getDefaultTeam()
    {
        return [
            [
                'name' => 'John Smith',
                'position' => 'General Manager',
                'bio' => 'With over 20 years of experience in hospitality management.',
                'photo' => null,
                'email' => 'john@hotel.com'
            ],
            [
                'name' => 'Sarah Johnson',
                'position' => 'Head of Operations',
                'bio' => 'Expert in hotel operations and guest services.',
                'photo' => null,
                'email' => 'sarah@hotel.com'
            ],
            [
                'name' => 'Michael Brown',
                'position' => 'Executive Chef',
                'bio' => 'Award-winning chef with international experience.',
                'photo' => null,
                'email' => 'michael@hotel.com'
            ],
            [
                'name' => 'Emily Davis',
                'position' => 'Guest Relations Manager',
                'bio' => 'Dedicated to ensuring exceptional guest experiences.',
                'photo' => null,
                'email' => 'emily@hotel.com'
            ]
        ];
    }

    private function getHotelStatistics()
    {
        try {
            $stats = [];

            // Total rooms from rooms table
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM rooms");
            $stmt->execute();
            $stats['total_rooms'] = $stmt->fetchColumn();

            // Total room types
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM room_types WHERE is_active = 1");
            $stmt->execute();
            $stats['room_types'] = $stmt->fetchColumn();

            // Total reservations
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM reservations");
            $stmt->execute();
            $stats['total_reservations'] = $stmt->fetchColumn() ?: 0;

            // Total users (guests)
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM users WHERE role = 'customer'");
            $stmt->execute();
            $stats['total_guests'] = $stmt->fetchColumn();

            // Average room price
            $stmt = $this->pdo->prepare("SELECT AVG(base_price) as avg_price FROM room_types WHERE is_active = 1");
            $stmt->execute();
            $avg_price = $stmt->fetchColumn();
            $stats['avg_price'] = $avg_price ? number_format($avg_price, 2) : '0.00';

            // Years in operation (calculate from established date)
            $stats['years_in_operation'] = date('Y') - 2005; // Since 2005

            return $stats;
        } catch (PDOException $e) {
            error_log("Get hotel statistics error: " . $e->getMessage());
            return $this->getDefaultStatistics();
        }
    }

    private function getDefaultStatistics()
    {
        return [
            'total_rooms' => 120,
            'room_types' => 4,
            'total_reservations' => 2500,
            'total_guests' => 5000,
            'avg_price' => '3,450.00',
            'years_in_operation' => 15
        ];
    }

    private function countRooms()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM rooms");
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return '120';
        }
    }

    private function countStaff()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM users WHERE role IN ('admin', 'staff', 'manager') AND is_active = 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            return '50+';
        }
    }

    private function countGuestsServed()
    {
        try {
            $sql = "SELECT COUNT(DISTINCT user_id) as total FROM reservations WHERE status IN ('confirmed', 'checked_in', 'checked_out')";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 1500;
        } catch (PDOException $e) {
            return '5000+';
        }
    }

    private function getAwards()
    {
        // Static awards since there's no awards table
        return [
            ['year' => 2024, 'award' => 'Best Luxury Hotel - Travel Awards'],
            ['year' => 2023, 'award' => '5-Star Excellence Award'],
            ['year' => 2022, 'award' => 'Best Customer Service - Hospitality Awards'],
            ['year' => 2021, 'award' => 'Sustainable Hotel of the Year'],
            ['year' => 2020, 'award' => 'Best Business Hotel']
        ];
    }

    private function getHotelAmenities()
    {
        return [
            [
                'name' => 'Swimming Pool',
                'description' => 'Olympic-sized pool with temperature control',
                'icon' => 'fas fa-swimming-pool'
            ],
            [
                'name' => 'Free WiFi',
                'description' => 'High-speed internet throughout the hotel',
                'icon' => 'fas fa-wifi'
            ],
            [
                'name' => 'Fitness Center',
                'description' => '24/7 gym with modern equipment',
                'icon' => 'fas fa-dumbbell'
            ],
            [
                'name' => 'Room Service',
                'description' => '24-hour room service available',
                'icon' => 'fas fa-concierge-bell'
            ],
            [
                'name' => 'Parking',
                'description' => 'Secure underground parking facilities',
                'icon' => 'fas fa-parking'
            ],
            [
                'name' => 'Spa & Wellness',
                'description' => 'Full-service spa with professional therapists',
                'icon' => 'fas fa-spa'
            ]
        ];
    }

    public function amenities()
    {
        $amenities = $this->getHotelAmenities();
        $this->render('public/amenities', ['amenities' => $amenities]);
    }

    public function gallery()
    {
        $gallery_images = $this->getGalleryImages();
        $this->render('public/gallery', ['gallery_images' => $gallery_images]);
    }

    private function getGalleryImages()
    {
        // You might want to fetch these from a database table later
        return [
            ['src' => 'gallery1.jpg', 'alt' => 'Lobby Area', 'category' => 'common-areas'],
            ['src' => 'gallery2.jpg', 'alt' => 'Deluxe Room', 'category' => 'rooms'],
            ['src' => 'gallery3.jpg', 'alt' => 'Restaurant', 'category' => 'dining'],
            ['src' => 'gallery4.jpg', 'alt' => 'Swimming Pool', 'category' => 'amenities'],
            ['src' => 'gallery5.jpg', 'alt' => 'Spa', 'category' => 'amenities'],
            ['src' => 'gallery6.jpg', 'alt' => 'Conference Room', 'category' => 'facilities']
        ];
    }
}
?>
