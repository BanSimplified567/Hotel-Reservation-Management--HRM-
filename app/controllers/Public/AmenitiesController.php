<?php
// app/controllers/Public/AmenitiesController.php
require_once __DIR__ . '/../Path/BaseController.php';

class AmenitiesController extends BaseController
{

  public function index()
  {
    try {
      // Define hotel amenities categories
      $amenities = [
        'room_amenities' => [
          'title' => 'Room Amenities',
          'items' => [
            ['icon' => 'wifi', 'name' => 'Free High-Speed WiFi', 'description' => 'Stay connected with complimentary high-speed internet'],
            ['icon' => 'tv', 'name' => 'Smart TV', 'description' => 'Entertainment with streaming services'],
            ['icon' => 'air-con', 'name' => 'Air Conditioning', 'description' => 'Individual climate control'],
            ['icon' => 'mini-bar', 'name' => 'Mini Bar', 'description' => 'Well-stocked refreshments'],
            ['icon' => 'safe', 'name' => 'In-room Safe', 'description' => 'Secure your valuables'],
            ['icon' => 'coffee', 'name' => 'Coffee/Tea Maker', 'description' => 'In-room beverage facilities']
          ]
        ],
        'hotel_facilities' => [
          'title' => 'Hotel Facilities',
          'items' => [
            ['icon' => 'pool', 'name' => 'Swimming Pool', 'description' => 'Outdoor heated pool'],
            ['icon' => 'spa', 'name' => 'Spa & Wellness Center', 'description' => 'Relaxation treatments'],
            ['icon' => 'gym', 'name' => 'Fitness Center', 'description' => '24/7 gym access'],
            ['icon' => 'restaurant', 'name' => 'Restaurant & Bar', 'description' => 'Fine dining options'],
            ['icon' => 'parking', 'name' => 'Free Parking', 'description' => 'Secure parking space'],
            ['icon' => 'business', 'name' => 'Business Center', 'description' => 'Meeting facilities']
          ]
        ],
        'services' => [
          'title' => 'Guest Services',
          'items' => [
            ['icon' => 'concierge', 'name' => '24/7 Concierge', 'description' => 'Personal assistance anytime'],
            ['icon' => 'room-service', 'name' => 'Room Service', 'description' => '24-hour dining in your room'],
            ['icon' => 'laundry', 'name' => 'Laundry Service', 'description' => 'Dry cleaning & laundry'],
            ['icon' => 'airport', 'name' => 'Airport Shuttle', 'description' => 'Transportation service'],
            ['icon' => 'tour', 'name' => 'Tour Desk', 'description' => 'Local attraction bookings'],
            ['icon' => 'currency', 'name' => 'Currency Exchange', 'description' => 'Foreign currency service']
          ]
        ]
      ];

      // Get additional services from database
      $additionalServices = $this->getAdditionalServices();

      $data = [
        'amenities' => $amenities,
        'additional_services' => $additionalServices,
        'page_title' => 'Amenities'
      ];

      $this->render('public/amenities', $data);
    } catch (Exception $e) {
      error_log("AmenitiesController error: " . $e->getMessage());
      $this->render('public/amenities', ['page_title' => 'Amenities']);
    }
  }

  private function getAdditionalServices()
  {
    $sql = "SELECT * FROM services
                WHERE is_available = 1
                ORDER BY category, name";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
