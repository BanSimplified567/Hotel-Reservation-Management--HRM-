<?php
// app/views/public/rooms.php
// Note: $rooms, $room_types, $all_amenities, $featured_rooms, $page_title are passed from controller
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary to-secondary text-white py-12">
  <div class="container mx-auto px-4">
    <h1 class="text-4xl font-bold mb-2">Our Rooms</h1>
    <p class="text-xl text-gray-100">Discover our selection of beautifully designed rooms and suites</p>
  </div>
</section>

<!-- Filters Section -->
<section class="py-8 bg-gray-50">
  <div class="container mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
      <form method="GET" action="index.php?action=rooms" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <input type="hidden" name="action" value="rooms">

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Room Type</label>
          <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
            <option value="">All Types</option>
            <?php foreach ($room_types ?? [] as $type): ?>
              <option value="<?php echo htmlspecialchars($type); ?>" <?php echo ($_GET['type'] ?? '') === $type ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($type); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Min Price</label>
          <input type="number" name="min_price" value="<?php echo htmlspecialchars($_GET['min_price'] ?? '0'); ?>"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" placeholder="0">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Max Price</label>
          <input type="number" name="max_price" value="<?php echo htmlspecialchars($_GET['max_price'] ?? '1000'); ?>"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" placeholder="1000">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Capacity</label>
          <select name="capacity" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
            <option value="1">1 Guest</option>
            <option value="2" <?php echo ($_GET['capacity'] ?? '') == '2' ? 'selected' : ''; ?>>2 Guests</option>
            <option value="3" <?php echo ($_GET['capacity'] ?? '') == '3' ? 'selected' : ''; ?>>3 Guests</option>
            <option value="4" <?php echo ($_GET['capacity'] ?? '') == '4' ? 'selected' : ''; ?>>4+ Guests</option>
          </select>
        </div>

        <div class="flex items-end">
          <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white px-6 py-2 rounded-lg font-semibold transition duration-300">
            <i class="fas fa-search mr-2"></i> Filter
          </button>
        </div>
      </form>
    </div>
  </div>
</section>

<!-- Rooms Grid -->
<section class="py-16">
  <div class="container mx-auto px-4">
    <?php if (empty($rooms)): ?>
      <div class="text-center py-12">
        <i class="fas fa-bed text-gray-400 text-6xl mb-4"></i>
        <h3 class="text-2xl font-semibold text-gray-800 mb-2">No Rooms Found</h3>
        <p class="text-gray-600 mb-6">Try adjusting your filters to see more results</p>
        <a href="index.php?action=rooms" class="bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-lg font-semibold transition duration-300 inline-block">
          Clear Filters
        </a>
      </div>
    <?php else: ?>
      <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Available Rooms (<?php echo count($rooms); ?>)</h2>
        <div class="flex gap-2">
          <a href="index.php?action=rooms&sort=price_asc" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm font-semibold">
            Price: Low to High
          </a>
          <a href="index.php?action=rooms&sort=price_desc" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm font-semibold">
            Price: High to Low
          </a>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                <span><i class="fas fa-expand-arrows-alt mr-1"></i> <?php echo $room['size'] ?? '25'; ?> m²</span>
              </div>

              <div class="flex items-center justify-between">
                <a href="index.php?action=rooms&sub_action=view&id=<?php echo $room['id']; ?>"
                  class="text-primary hover:underline font-semibold">
                  View Details
                </a>
                <a href="index.php?action=room-search&room_id=<?php echo $room['id']; ?>"
                  class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg font-semibold transition duration-300">
                  Book Now
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- Featured Rooms -->
<?php if (!empty($featured_rooms)): ?>
<section class="py-16 bg-gray-50">
  <div class="container mx-auto px-4">
    <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Featured Rooms</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($featured_rooms as $room): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
          <div class="relative h-64 bg-gradient-to-br from-primary to-secondary">
            <div class="absolute top-4 left-4 bg-yellow-400 text-gray-800 px-3 py-1 rounded-lg font-semibold">
              Featured
            </div>
          </div>
          <div class="p-6">
            <h3 class="text-xl font-semibold mb-2 text-gray-800"><?php echo htmlspecialchars($room['type'] ?? 'Room'); ?></h3>
            <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($room['description'] ?? ''); ?></p>
            <div class="flex items-center justify-between">
              <span class="text-primary font-bold text-lg">$<?php echo number_format($room['price_per_night'] ?? 0, 2); ?>/night</span>
              <a href="index.php?action=rooms&sub_action=view&id=<?php echo $room['id']; ?>"
                class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg font-semibold transition duration-300">
                View Details
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
