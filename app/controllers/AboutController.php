<?php
// app/controllers/AboutController.php

class AboutController extends BaseController
{
  public function index()
  {
    // Get hotel information
    $hotel_info = $this->getHotelInfo();

    // Get team members from users table (staff/manager roles)
    $team_members = $this->getTeamMembers();

    // Get hotel statistics based on actual data
    $statistics = $this->getHotelStatistics();

    $data = [
      'hotel_info' => $hotel_info,
      'team_members' => $team_members,
      'statistics' => $statistics
    ];

    $this->render('public/about', $data);
  }

  private function getHotelInfo()
  {
    // Using static data since you don't have a hotel_info/settings table
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
      'employees' => '50+',
      'awards' => 'Best Luxury Hotel 2023, Hospitality Excellence Award 2022'
    ];
  }

  private function getTeamMembers()
  {
    try {
      // Get staff members from users table (role: staff or manager)
      $stmt = $this->pdo->prepare("
                SELECT
                    CONCAT(first_name, ' ', last_name) as name,
                    role as position,
                    CONCAT('Professional ', role, ' with expertise in hotel management') as bio,
                    profile_image as photo,
                    '5+ years' as experience_years
                FROM users
                WHERE role IN ('staff', 'manager')
                AND is_active = 1
                ORDER BY id
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
        'experience_years' => '20'
      ],
      [
        'name' => 'Sarah Johnson',
        'position' => 'Head of Operations',
        'bio' => 'Expert in hotel operations and guest services.',
        'photo' => null,
        'experience_years' => '15'
      ],
      [
        'name' => 'Michael Brown',
        'position' => 'Executive Chef',
        'bio' => 'Award-winning chef with international experience.',
        'photo' => null,
        'experience_years' => '12'
      ],
      [
        'name' => 'Emily Davis',
        'position' => 'Guest Relations Manager',
        'bio' => 'Dedicated to ensuring exceptional guest experiences.',
        'photo' => null,
        'experience_years' => '10'
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

      return $stats;
    } catch (PDOException $e) {
      error_log("Get hotel statistics error: " . $e->getMessage());
      return [
        'total_rooms' => 120,
        'room_types' => 4,
        'total_reservations' => 2500,
        'total_guests' => 5000,
        'avg_price' => '3,450.00'
      ];
    }
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

  public function amenities()
  {
    $amenities = $this->getHotelAmenities();
    $this->render('public/amenities', ['amenities' => $amenities]);
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

  public function gallery()
  {
    $gallery_images = $this->getGalleryImages();
    $this->render('public/gallery', ['gallery_images' => $gallery_images]);
  }

  private function getGalleryImages()
  {
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
