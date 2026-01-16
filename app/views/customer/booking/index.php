<?php
// app/views/customer/booking/index.php
// Note: $rooms, $services, $selectedRoom, $room_id, $page_title are passed from controller
?>

<div class="container mx-auto px-4 py-8">
  <div class="mb-6">
    <h1 class="text-3xl font-bold mb-2 text-gray-800">Book a Room</h1>
    <p class="text-gray-600">Find and book the perfect room for your stay</p>
  </div>

  <!-- Search Form -->
  <div class="bg-gradient-to-r from-primary to-secondary rounded-lg p-6 md:p-8 mb-8 text-white">
    <form method="GET" action="index.php">
      <input type="hidden" name="action" value="book-room">

      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-white mb-2">Check-in Date</label>
          <input type="date" name="check_in"
            value="<?php echo htmlspecialchars(is_string($checkIn ?? '') ? $checkIn : ''); ?>"
            class="w-full px-4 py-2 rounded-lg text-gray-800 focus:ring-2 focus:ring-white"
            required>
        </div>

        <div>
          <label class="block text-sm font-medium text-white mb-2">Check-out Date</label>
          <input type="date" name="check_out"
            value="<?php echo htmlspecialchars(is_string($checkOut ?? '') ? $checkOut : ''); ?>"
            class="w-full px-4 py-2 rounded-lg text-gray-800 focus:ring-2 focus:ring-white"
            required>
        </div>

        <div>
          <label class="block text-sm font-medium text-white mb-2">Guests</label>
          <select name="guests" class="w-full px-4 py-2 rounded-lg text-gray-800 focus:ring-2 focus:ring-white" required>
            <?php for ($i = 1; $i <= 6; $i++): ?>
              <option value="<?php echo $i; ?>" <?php echo ($guests ?? 1) == $i ? 'selected' : ''; ?>>
                <?php echo $i; ?> <?php echo $i == 1 ? 'Guest' : 'Guests'; ?>
              </option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="flex items-end">
          <button type="submit" class="w-full bg-white text-primary px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
            <i class="fas fa-search mr-2"></i> Search Rooms
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- Results -->
  <?php if (!empty($rooms)): ?>
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-gray-800 mb-2"><?php echo count($rooms); ?> Rooms Available</h2>
      <p class="text-gray-600">Select a room to proceed with booking</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="roomsContainer">
      <?php foreach ($rooms as $room): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
          <div class="relative h-48 bg-gradient-to-br from-primary to-secondary">
            <div class="absolute top-4 right-4 bg-white/90 px-3 py-1 rounded-lg">
              <span class="text-primary font-bold">$<?php echo number_format($room['price_per_night'] ?? 0, 2); ?>/night</span>
            </div>
          </div>
          <div class="p-6">
            <h3 class="text-xl font-semibold mb-2 text-gray-800"><?php echo htmlspecialchars($room['type'] ?? 'Room'); ?></h3>
            <p class="text-gray-600 mb-4 text-sm"><?php echo htmlspecialchars($room['description'] ?? ''); ?></p>

            <div class="flex items-center gap-4 mb-4 text-sm text-gray-600">
              <span><i class="fas fa-users mr-1"></i> <?php echo $room['capacity'] ?? 2; ?> Guests</span>
              <span><i class="fas fa-bed mr-1"></i> <?php echo $room['beds'] ?? '1'; ?> Bed</span>
            </div>

            <a href="index.php?action=book-room&room_id=<?php echo $room['id']; ?>"
              class="w-full bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg font-semibold transition duration-300 text-center block">
              <i class="fas fa-calendar-check mr-2"></i> Book Now
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
      <i class="fas fa-calendar-alt text-gray-400 text-6xl mb-4"></i>
      <h3 class="text-2xl font-semibold text-gray-800 mb-2">Start Your Booking</h3>
      <p class="text-gray-600 mb-6">Select your check-in and check-out dates to see available rooms</p>
      <div class="max-w-2xl mx-auto">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-left">
          <i class="fas fa-info-circle text-blue-500 mr-2"></i>
          <strong class="text-blue-800">Need help choosing?</strong>
          <ul class="mt-2 text-blue-700 text-sm space-y-1">
            <li>• Standard rooms: Perfect for solo travelers or couples</li>
            <li>• Deluxe rooms: Extra space with premium amenities</li>
            <li>• Suite: Spacious living area with separate bedroom</li>
            <li>• All rooms include free WiFi, breakfast, and parking</li>
          </ul>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
