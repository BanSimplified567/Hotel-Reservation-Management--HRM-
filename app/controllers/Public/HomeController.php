<?php
// app/controllers/Public/HomeController.php
require_once __DIR__ . '/../Path/BaseController.php';

class HomeController extends BaseController
{

  public function index()
  {
    try {
      // Get featured rooms
      $featuredRooms = $this->getFeaturedRooms();

      // Get special offers
      $specialOffers = $this->getSpecialOffers();

      // Get room types for display
      $roomTypes = $this->getRoomTypes();

      // Get latest reviews
      $reviews = $this->getLatestReviews();

      $data = [
        'featuredRooms' => $featuredRooms,
        'specialOffers' => $specialOffers,
        'roomTypes' => $roomTypes,
        'reviews' => $reviews,
        'page_title' => 'Home'
      ];

      $this->render('public/home', $data);
    } catch (Exception $e) {
      error_log("HomeController error: " . $e->getMessage());
      $this->render('public/home', ['page_title' => 'Home']);
    }
  }

  private function getFeaturedRooms()
  {
    $sql = "SELECT rt.*,
                       (SELECT image_url FROM room_images WHERE room_type_id = rt.id AND is_primary = 1 LIMIT 1) as primary_image,
                       COUNT(DISTINCT r.id) as available_rooms
                FROM room_types rt
                LEFT JOIN rooms r ON r.room_type_id = rt.id AND r.status = 'available'
                WHERE rt.is_active = 1
                AND rt.id != 4  -- Exclude 'Common / Background' type
                GROUP BY rt.id
                ORDER BY rt.base_price ASC
                LIMIT 3";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  private function getSpecialOffers()
  {
    $sql = "SELECT so.*, rt.name as room_type_name
                FROM special_offers so
                LEFT JOIN room_types rt ON so.room_type_id = rt.id
                WHERE so.is_active = 1
                AND so.start_date <= CURDATE()
                AND so.end_date >= CURDATE()
                ORDER BY so.discount_percentage DESC
                LIMIT 3";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  private function getRoomTypes()
  {
    $sql = "SELECT rt.*,
                       COUNT(DISTINCT r.id) as total_rooms,
                       SUM(CASE WHEN r.status = 'available' THEN 1 ELSE 0 END) as available_rooms
                FROM room_types rt
                LEFT JOIN rooms r ON r.room_type_id = rt.id
                WHERE rt.is_active = 1
                AND rt.id != 4
                GROUP BY rt.id
                ORDER BY rt.base_price";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  private function getLatestReviews()
  {
    $sql = "SELECT rv.*,
                       u.first_name, u.last_name,
                       rt.name as room_type_name,
                       DATE_FORMAT(rv.created_at, '%M %d, %Y') as formatted_date
                FROM reviews rv
                JOIN users u ON rv.user_id = u.id
                JOIN room_types rt ON rv.room_id = rt.id
                WHERE rv.is_approved = 1
                ORDER BY rv.created_at DESC
                LIMIT 3";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
