<?php
// app/controllers/AboutController.php

class AboutController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        // Get hotel information from database or use default
        $hotel_info = $this->getHotelInfo();

        // Get team members (if you have a team table)
        $team_members = $this->getTeamMembers();

        // Get hotel statistics
        $statistics = $this->getHotelStatistics();

        require_once '../app/views/public/about.php';
    }

    private function getHotelInfo()
    {
        // In a real app, you might store this in a settings table
        // For now, return default information
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
            'rooms' => '120',
            'employees' => '200+',
            'awards' => 'Best Luxury Hotel 2023, Hospitality Excellence Award 2022'
        ];
    }

    private function getTeamMembers()
    {
        try {
            // If you have a team table
            $stmt = $this->pdo->prepare("
                SELECT name, position, bio, photo, experience_years
                FROM team_members
                WHERE is_active = 1
                ORDER BY display_order
                LIMIT 6
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // If no team table, return default team
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
                'photo' => 'team1.jpg',
                'experience_years' => '20'
            ],
            [
                'name' => 'Sarah Johnson',
                'position' => 'Head of Operations',
                'bio' => 'Expert in hotel operations and guest services.',
                'photo' => 'team2.jpg',
                'experience_years' => '15'
            ],
            [
                'name' => 'Michael Brown',
                'position' => 'Executive Chef',
                'bio' => 'Award-winning chef with international experience.',
                'photo' => 'team3.jpg',
                'experience_years' => '12'
            ],
            [
                'name' => 'Emily Davis',
                'position' => 'Guest Relations Manager',
                'bio' => 'Dedicated to ensuring exceptional guest experiences.',
                'photo' => 'team4.jpg',
                'experience_years' => '10'
            ]
        ];
    }

    private function getHotelStatistics()
    {
        try {
            $stats = [];

            // Total rooms
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM rooms");
            $stmt->execute();
            $stats['total_rooms'] = $stmt->fetchColumn();

            // Total guests served (approximate)
            $stmt = $this->pdo->prepare("SELECT SUM(guests) as total FROM reservations WHERE status = 'completed'");
            $stmt->execute();
            $stats['guests_served'] = $stmt->fetchColumn() ?: 0;

            // Total reservations
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM reservations");
            $stmt->execute();
            $stats['total_reservations'] = $stmt->fetchColumn();

            // Average guest rating (if you have ratings table)
            $stmt = $this->pdo->prepare("SELECT AVG(rating) as avg_rating FROM guest_ratings");
            $stmt->execute();
            $stats['avg_rating'] = round($stmt->fetchColumn(), 1) ?: 4.8;

            return $stats;
        } catch (PDOException $e) {
            error_log("Get hotel statistics error: " . $e->getMessage());
            return [
                'total_rooms' => 120,
                'guests_served' => 5000,
                'total_reservations' => 2500,
                'avg_rating' => 4.8
            ];
        }
    }

    public function amenities()
    {
        // Show hotel amenities page
        $amenities = $this->getHotelAmenities();

        require_once '../app/views/public/amenities.php';
    }

    private function getHotelAmenities()
    {
        // In a real app, fetch from database
        return [
            [
                'name' => 'Swimming Pool',
                'description' => 'Olympic-sized pool with temperature control',
                'icon' => 'fas fa-swimming-pool'
            ],
            [
                'name' => 'Spa & Wellness',
                'description' => 'Full-service spa with professional therapists',
                'icon' => 'fas fa-spa'
            ],
            [
                'name' => 'Fitness Center',
                'description' => '24/7 gym with modern equipment',
                'icon' => 'fas fa-dumbbell'
            ],
            [
                'name' => 'Fine Dining',
                'description' => 'Multiple restaurants offering international cuisine',
                'icon' => 'fas fa-utensils'
            ],
            [
                'name' => 'Conference Rooms',
                'description' => 'State-of-the-art meeting and event facilities',
                'icon' => 'fas fa-users'
            ],
            [
                'name' => 'Free WiFi',
                'description' => 'High-speed internet throughout the hotel',
                'icon' => 'fas fa-wifi'
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
            ]
        ];
    }

    public function gallery()
    {
        // Show hotel gallery
        $gallery_images = $this->getGalleryImages();

        require_once '../app/views/public/gallery.php';
    }

    private function getGalleryImages()
    {
        // In a real app, fetch from database
        return [
            ['src' => 'gallery1.jpg', 'alt' => 'Lobby Area', 'category' => 'common-areas'],
            ['src' => 'gallery2.jpg', 'alt' => 'Deluxe Room', 'category' => 'rooms'],
            ['src' => 'gallery3.jpg', 'alt' => 'Restaurant', 'category' => 'dining'],
            ['src' => 'gallery4.jpg', 'alt' => 'Swimming Pool', 'category' => 'amenities'],
            ['src' => 'gallery5.jpg', 'alt' => 'Spa', 'category' => 'amenities'],
            ['src' => 'gallery6.jpg', 'alt' => 'Conference Room', 'category' => 'facilities'],
            ['src' => 'gallery7.jpg', 'alt' => 'Suite Room', 'category' => 'rooms'],
            ['src' => 'gallery8.jpg', 'alt' => 'Bar', 'category' => 'dining']
        ];
    }
}
