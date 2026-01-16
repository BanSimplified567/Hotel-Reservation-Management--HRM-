<?php
// app/views/public/room-search.php
// Note: $available_rooms, $room_types, $all_amenities, $check_in, $check_out, $guests, $page_title are passed from controller
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary to-secondary text-white py-12">
  <div class="container mx-auto px-4">
    <h1 class="text-4xl font-bold mb-2">Search Rooms</h1>
    <p class="text-xl text-gray-100">Find the perfect room for your stay</p>
  </div>
</section>

<!-- Search Form -->
<section class="py-8 bg-gray-50">
  <div class="container mx-auto px-4">
    <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
      <form method="GET" action="index.php?action=room-search" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="hidden" name="action" value="room-search">

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Check-in Date</label>
          <input type="date" name="check_in"
            value="<?php echo htmlspecialchars($check_in ?? ''); ?>"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
            required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Check-out Date</label>
          <input type="date" name="check_out"
            value="<?php echo htmlspecialchars($check_out ?? ''); ?>"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
            required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Number of Guests</label>
          <select name="guests" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
            <?php for ($i = 1; $i <= 6; $i++): ?>
              <option value="<?php echo $i; ?>" <?php echo ($guests ?? 1) == $i ? 'selected' : ''; ?>>
                <?php echo $i; ?> <?php echo $i == 1 ? 'Guest' : 'Guests'; ?>
              </option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="flex items-end">
          <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white px-6 py-2 rounded-lg font-semibold transition duration-300">
            <i class="fas fa-search mr-2"></i> Search
          </button>
        </div>
      </form>

      <!-- Advanced Filters -->
      <div class="mt-6 pt-6 border-t">
        <button id="toggleFilters" class="text-primary hover:underline font-semibold mb-4">
          <i class="fas fa-filter mr-2"></i> Advanced Filters
        </button>
        <div id="advancedFilters" class="hidden grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Room Type</label>
            <select name="room_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
              <option value="">All Types</option>
              <?php foreach ($room_types ?? [] as $type): ?>
                <option value="<?php echo htmlspecialchars($type); ?>" <?php echo ($room_type ?? '') === $type ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($type); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Min Price</label>
            <input type="number" name="min_price" value="<?php echo htmlspecialchars($min_price ?? '0'); ?>"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Max Price</label>
            <input type="number" name="max_price" value="<?php echo htmlspecialchars($max_price ?? '1000'); ?>"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Results Section -->
<section class="py-16">
  <div class="container mx-auto px-4">
    <?php if (empty($check_in) || empty($check_out)): ?>
      <div class="text-center py-12">
        <i class="fas fa-calendar-alt text-gray-400 text-6xl mb-4"></i>
        <h3 class="text-2xl font-semibold text-gray-800 mb-2">Start Your Search</h3>
        <p class="text-gray-600">Please select your check-in and check-out dates to search for available rooms</p>
      </div>
    <?php elseif (empty($available_rooms)): ?>
      <div class="text-center py-12">
        <i class="fas fa-bed text-gray-400 text-6xl mb-4"></i>
        <h3 class="text-2xl font-semibold text-gray-800 mb-2">No Rooms Available</h3>
        <p class="text-gray-600 mb-6">Sorry, no rooms are available for the selected dates. Please try different dates.</p>
        <a href="index.php?action=room-search" class="bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-lg font-semibold transition duration-300 inline-block">
          Search Again
        </a>
      </div>
    <?php else: ?>
      <div class="mb-6 flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold text-gray-800"><?php echo count($available_rooms); ?> Rooms Available</h2>
          <p class="text-gray-600">
            <?php echo date('M j', strtotime($check_in)); ?> - <?php echo date('M j, Y', strtotime($check_out)); ?>
            • <?php echo $guests ?? 1; ?> guest<?php echo ($guests ?? 1) > 1 ? 's' : ''; ?>
          </p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($available_rooms as $room): ?>
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

              <?php
              $checkInDate = new DateTime($check_in);
              $checkOutDate = new DateTime($check_out);
              $nights = $checkOutDate->diff($checkInDate)->days;
              $totalPrice = ($room['price_per_night'] ?? 0) * $nights;
              ?>

              <div class="border-t pt-4 mb-4">
                <div class="flex justify-between items-center">
                  <span class="text-gray-600 text-sm">Total for <?php echo $nights; ?> nights:</span>
                  <span class="text-primary font-bold text-lg">$<?php echo number_format($totalPrice, 2); ?></span>
                </div>
              </div>

              <a href="index.php?action=book-room&room_id=<?php echo $room['id']; ?>&check_in=<?php echo urlencode($check_in); ?>&check_out=<?php echo urlencode($check_out); ?>&guests=<?php echo $guests; ?>"
                class="w-full bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg font-semibold transition duration-300 text-center block">
                <i class="fas fa-calendar-check mr-2"></i> Book Now
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<script>
  document.getElementById('toggleFilters')?.addEventListener('click', function() {
    const filters = document.getElementById('advancedFilters');
    filters.classList.toggle('hidden');
  });
</script>
