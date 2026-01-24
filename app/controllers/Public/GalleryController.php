<?php
// app/controllers/Public/GalleryController.php
require_once __DIR__ . '/../Path/BaseController.php';

class GalleryController extends BaseController
{

  public function index()
  {
    try {
      // Get gallery images by category
      $gallery = [
        'rooms' => $this->getRoomGallery(),
        'facilities' => $this->getFacilitiesGallery(),
        'restaurant' => $this->getRestaurantGallery(),
        'events' => $this->getEventsGallery()
      ];

      $data = [
        'gallery' => $gallery,
        'page_title' => 'Gallery'
      ];

      $this->render('public/gallery', $data);
    } catch (Exception $e) {
      error_log("GalleryController error: " . $e->getMessage());
      $this->render('public/gallery', ['page_title' => 'Gallery']);
    }
  }

  private function getRoomGallery()
  {
    $sql = "SELECT ri.*, rt.name as room_type_name
                FROM room_images ri
                JOIN room_types rt ON ri.room_type_id = rt.id
                WHERE rt.is_active = 1
                AND rt.id != 4
                ORDER BY rt.id, ri.is_primary DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  private function getFacilitiesGallery()
  {
    // Static facilities images (you can make this dynamic by creating a facilities table)
    return [
      ['image_url' => 'pool-1.jpg', 'title' => 'Swimming Pool', 'description' => 'Outdoor heated pool with loungers'],
      ['image_url' => 'spa-1.jpg', 'title' => 'Spa Center', 'description' => 'Relaxing spa treatments'],
      ['image_url' => 'gym-1.jpg', 'title' => 'Fitness Center', 'description' => '24/7 gym facility'],
      ['image_url' => 'lobby-1.jpg', 'title' => 'Hotel Lobby', 'description' => 'Grand entrance lobby'],
      ['image_url' => 'garden-1.jpg', 'title' => 'Garden Area', 'description' => 'Beautiful hotel gardens'],
      ['image_url' => 'terrace-1.jpg', 'title' => 'Sunset Terrace', 'description' => 'Evening relaxation area']
    ];
  }

  private function getRestaurantGallery()
  {
    return [
      ['image_url' => 'restaurant-1.jpg', 'title' => 'Main Restaurant', 'description' => 'Fine dining experience'],
      ['image_url' => 'bar-1.jpg', 'title' => 'Sky Bar', 'description' => 'Rooftop cocktails'],
      ['image_url' => 'breakfast-1.jpg', 'title' => 'Breakfast Buffet', 'description' => 'Morning delights'],
      ['image_url' => 'chef-1.jpg', 'title' => 'Chef Specials', 'description' => 'Gourmet creations'],
      ['image_url' => 'wine-1.jpg', 'title' => 'Wine Cellar', 'description' => 'Premium selection'],
      ['image_url' => 'patio-1.jpg', 'title' => 'Outdoor Dining', 'description' => 'Al fresco meals']
    ];
  }

  private function getEventsGallery()
  {
    return [
      ['image_url' => 'wedding-1.jpg', 'title' => 'Wedding Events', 'description' => 'Beautiful wedding celebrations'],
      ['image_url' => 'conference-1.jpg', 'title' => 'Conference Hall', 'description' => 'Business meetings'],
      ['image_url' => 'party-1.jpg', 'title' => 'Birthday Parties', 'description' => 'Special celebrations'],
      ['image_url' => 'team-1.jpg', 'title' => 'Team Building', 'description' => 'Corporate events'],
      ['image_url' => 'gala-1.jpg', 'title' => 'Gala Dinners', 'description' => 'Formal events'],
      ['image_url' => 'seminar-1.jpg', 'title' => 'Seminars', 'description' => 'Educational gatherings']
    ];
  }
}
