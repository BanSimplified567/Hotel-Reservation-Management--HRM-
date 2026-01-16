<?php
// app/views/public/room-details.php
// Note: $room, $similar_rooms, $page_title are passed from controller
?>

<?php if (empty($room)): ?>
  <div class="container mx-auto px-4 py-16 text-center">
    <i class="fas fa-exclamation-triangle text-gray-400 text-6xl mb-4"></i>
    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Room Not Found</h2>
    <p class="text-gray-600 mb-6">The room you're looking for doesn't exist or has been removed.</p>
    <a href="index.php?action=rooms" class="bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-lg font-semibold transition duration-300 inline-block">
      View All Rooms
    </a>
  </div>
<?php else: ?>
  <!-- Hero Section -->
  <section class="bg-gradient-to-r from-primary to-secondary text-white py-12">
    <div class="container mx-auto px-4">
      <a href="index.php?action=rooms" class="text-white hover:text-gray-200 mb-4 inline-block">
        <i class="fas fa-arrow-left mr-2"></i> Back to Rooms
      </a>
      <h1 class="text-4xl font-bold mb-2"><?php echo htmlspecialchars($room['type'] ?? 'Room'); ?></h1>
      <p class="text-xl text-gray-100"><?php echo htmlspecialchars($room['room_number'] ?? ''); ?></p>
    </div>
  </section>

  <!-- Room Details -->
  <section class="py-16">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
          <!-- Room Image -->
          <div class="bg-gradient-to-br from-primary to-secondary rounded-lg h-96 mb-6 flex items-center justify-center">
            <i class="fas fa-bed text-white text-8xl"></i>
          </div>

          <!-- Description -->
          <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Room Description</h2>
            <p class="text-gray-600 leading-relaxed"><?php echo htmlspecialchars($room['description'] ?? 'No description available.'); ?></p>
          </div>

          <!-- Amenities -->
          <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Amenities</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
              <?php
              $amenities = !empty($room['amenities']) ? (is_string($room['amenities']) ? json_decode($room['amenities'], true) : $room['amenities']) : [];
              $defaultAmenities = ['WiFi', 'TV', 'Air Conditioning', 'Mini Bar', 'Safe', 'Hair Dryer'];
              $displayAmenities = !empty($amenities) ? $amenities : $defaultAmenities;
              foreach ($displayAmenities as $amenity):
              ?>
                <div class="flex items-center text-gray-700">
                  <i class="fas fa-check text-primary mr-2"></i>
                  <span><?php echo htmlspecialchars($amenity); ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Room Specifications -->
          <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Room Specifications</h2>
            <div class="grid grid-cols-2 gap-6">
              <div>
                <p class="text-gray-600 mb-1">Room Size</p>
                <p class="font-semibold text-gray-800"><?php echo $room['size'] ?? '25'; ?> m²</p>
              </div>
              <div>
                <p class="text-gray-600 mb-1">Capacity</p>
                <p class="font-semibold text-gray-800"><?php echo $room['capacity'] ?? '2'; ?> Guests</p>
              </div>
              <div>
                <p class="text-gray-600 mb-1">Beds</p>
                <p class="font-semibold text-gray-800"><?php echo $room['beds'] ?? '1'; ?> Bed(s)</p>
              </div>
              <div>
                <p class="text-gray-600 mb-1">Room Number</p>
                <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($room['room_number'] ?? 'N/A'); ?></p>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div>
          <!-- Booking Card -->
          <div class="bg-white rounded-lg shadow-md p-6 mb-6 sticky top-20">
            <div class="text-center mb-6">
              <div class="text-4xl font-bold text-primary mb-2">$<?php echo number_format($room['price_per_night'] ?? 0, 2); ?></div>
              <p class="text-gray-600">per night</p>
            </div>

            <a href="index.php?action=room-search&room_id=<?php echo $room['id']; ?>"
              class="w-full bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-lg font-semibold transition duration-300 text-center block mb-4">
              <i class="fas fa-calendar-check mr-2"></i> Book Now
            </a>

            <div class="border-t pt-4">
              <h3 class="font-semibold text-gray-800 mb-3">Quick Info</h3>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">Check-in:</span>
                  <span class="font-semibold text-gray-800">3:00 PM</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Check-out:</span>
                  <span class="font-semibold text-gray-800">11:00 AM</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Status:</span>
                  <span class="font-semibold text-green-600"><?php echo ($room['status'] ?? 'available') === 'available' ? 'Available' : 'Unavailable'; ?></span>
                </div>
              </div>
            </div>
          </div>

          <!-- Contact Info -->
          <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="font-semibold text-gray-800 mb-3">Need Help?</h3>
            <p class="text-sm text-gray-600 mb-4">Contact our reservation team for assistance</p>
            <a href="index.php?action=contact" class="text-primary hover:underline text-sm font-semibold">
              <i class="fas fa-phone mr-2"></i> Contact Us
            </a>
          </div>
        </div>
      </div>

      <!-- Similar Rooms -->
      <?php if (!empty($similar_rooms)): ?>
      <div class="mt-16">
        <h2 class="text-3xl font-bold mb-8 text-gray-800">Similar Rooms</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <?php foreach ($similar_rooms as $similar): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
              <div class="h-48 bg-gradient-to-br from-primary to-secondary"></div>
              <div class="p-6">
                <h3 class="text-xl font-semibold mb-2 text-gray-800"><?php echo htmlspecialchars($similar['type'] ?? 'Room'); ?></h3>
                <p class="text-gray-600 mb-4 text-sm">Room #<?php echo htmlspecialchars($similar['room_number'] ?? ''); ?></p>
                <div class="flex items-center justify-between">
                  <span class="text-primary font-bold">$<?php echo number_format($similar['price_per_night'] ?? 0, 2); ?>/night</span>
                  <a href="index.php?action=rooms&sub_action=view&id=<?php echo $similar['id']; ?>"
                    class="text-primary hover:underline font-semibold text-sm">
                    View Details
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </section>
<?php endif; ?>
