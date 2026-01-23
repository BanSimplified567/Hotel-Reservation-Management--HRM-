-- Table for reservation guests
CREATE TABLE IF NOT EXISTS `reservation_guests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reservation_id` int NOT NULL,
  `guest_name` varchar(255) NOT NULL,
  `guest_email` varchar(255) NOT NULL,
  `guest_phone` varchar(20) DEFAULT NULL,
  `guest_address` text,
  `id_type` enum('passport','drivers_license','national_id','other') DEFAULT NULL,
  `id_number` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reservation_id` (`reservation_id`),
  CONSTRAINT `reservation_guests_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Sample data (optional)
-- INSERT INTO `reservation_guests` (`reservation_id`, `guest_name`, `guest_email`, `guest_phone`, `guest_address`, `id_type`, `id_number`) VALUES
-- (1, 'John Doe', 'john@example.com', '+1234567890', '123 Main St, City, Country', 'passport', 'P123456789');