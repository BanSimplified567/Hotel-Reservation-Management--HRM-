<?php
// app/views/customer/booking/index.php
// Note: $rooms, $services, $selectedRoom, $room_id, $page_title are passed from controller
?>

<div class="container mx-auto px-4 py-8">
  <div class="mb-6">
    <h1 class="text-3xl font-bold mb-2 text-gray-800">Book a Room</h1>
    <p class="text-gray-600">Find and book the perfect room for your stay</p>
  </div>

  <!-- Booking Form (Updated for POST submission) -->
  <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <form method="POST" action="index.php?action=book-room">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Check-in Date</label>
          <input type="date" name="check_in" required
            value="<?php echo htmlspecialchars($_POST['check_in'] ?? (isset($_GET['check_in']) ? $_GET['check_in'] : '')); ?>"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary"
            min="<?php echo date('Y-m-d'); ?>">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Check-out Date</label>
          <input type="date" name="check_out" required
            value="<?php echo htmlspecialchars($_POST['check_out'] ?? (isset($_GET['check_out']) ? $_GET['check_out'] : '')); ?>"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary"
            min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Guests</label>
          <select name="guests" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary" required>
            <?php for ($i = 1; $i <= 6; $i++): ?>
              <option value="<?php echo $i; ?>" <?php echo (($_POST['guests'] ?? (isset($_GET['guests']) ? $_GET['guests'] : 1)) == $i) ? 'selected' : ''; ?>>
                <?php echo $i; ?> <?php echo $i == 1 ? 'Guest' : 'Guests'; ?>
              </option>
            <?php endfor; ?>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
          <button type="submit" class="w-full bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary/90 transition duration-300">
            <i class="fas fa-search mr-2"></i> Check Availability
          </button>
        </div>
      </div>
    </form>
  </div>

  <?php if (!empty($rooms)): ?>
    <!-- Services Section -->
    <div class="mb-6 bg-white rounded-lg p-6 shadow-md">
      <h2 class="text-xl font-bold text-gray-800 mb-4">Additional Services</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php foreach ($services as $service): ?>
          <div class="border rounded-lg p-4 hover:border-primary transition duration-300">
            <div class="flex items-start">
              <input type="checkbox"
                name="services[]"
                value="<?php echo $service['id']; ?>"
                id="service_<?php echo $service['id']; ?>"
                class="mt-1 mr-3">
              <div>
                <label for="service_<?php echo $service['id']; ?>" class="font-medium text-gray-800 cursor-pointer">
                  <?php echo htmlspecialchars($service['name']); ?>
                </label>
                <p class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($service['description'] ?? ''); ?></p>
                <p class="text-primary font-bold mt-2">$<?php echo number_format($service['price'], 2); ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Special Requests -->
    <div class="mb-6 bg-white rounded-lg p-6 shadow-md">
      <h2 class="text-xl font-bold text-gray-800 mb-4">Special Requests</h2>
      <textarea name="special_requests"
        rows="3"
        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary"
        placeholder="Any special requests or requirements..."><?php echo htmlspecialchars($_POST['special_requests'] ?? ''); ?></textarea>
    </div>

    <!-- Rooms Selection -->
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-gray-800 mb-2"><?php echo count($rooms); ?> Rooms Available</h2>
      <p class="text-gray-600 mb-4">Select a room to proceed with booking</p>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($rooms as $room): ?>
          <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300 border-2 <?php echo ($room_id == $room['id']) ? 'border-primary' : 'border-transparent'; ?>">
            <div class="relative h-48 bg-gradient-to-br from-primary to-secondary">
              <div class="absolute top-4 right-4 bg-white/90 px-3 py-1 rounded-lg">
                <span class="text-primary font-bold">$<?php echo number_format($room['price_per_night'] ?? 0, 2); ?>/night</span>
              </div>
              <?php if ($room_id == $room['id']): ?>
                <div class="absolute top-4 left-4 bg-green-500 text-white px-3 py-1 rounded-lg">
                  <i class="fas fa-check mr-1"></i> Selected
                </div>
              <?php endif; ?>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-semibold mb-2 text-gray-800"><?php echo htmlspecialchars($room['type'] ?? 'Room'); ?></h3>
              <p class="text-gray-600 mb-4 text-sm line-clamp-2"><?php echo htmlspecialchars($room['description'] ?? ''); ?></p>

              <div class="flex items-center gap-4 mb-4 text-sm text-gray-600">
                <span><i class="fas fa-users mr-1"></i> <?php echo $room['capacity'] ?? 2; ?> Guests</span>
                <?php if (isset($room['size'])): ?>
                  <span><i class="fas fa-expand-arrows-alt mr-1"></i> <?php echo $room['size']; ?></span>
                <?php endif; ?>
              </div>

              <?php if (!empty($room['amenities'])):
                $amenities = is_string($room['amenities']) ? json_decode($room['amenities'], true) : $room['amenities'];
                if ($amenities): ?>
                  <div class="flex flex-wrap gap-1 mb-4">
                    <?php foreach (array_slice($amenities, 0, 3) as $key => $value):
                      if ($value === true || $value === 'true' || $value === 1):
                        $amenityIcons = [
                          'tv' => 'fa-tv',
                          'wifi' => 'fa-wifi',
                          'aircon' => 'fa-snowflake',
                          'balcony' => 'fa-door-open',
                          'minibar' => 'fa-wine-bottle',
                          'private_pool' => 'fa-swimming-pool'
                        ];
                    ?>
                        <span class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded">
                          <i class="fas <?php echo $amenityIcons[$key] ?? 'fa-check'; ?> mr-1"></i>
                          <?php echo ucfirst(str_replace('_', ' ', $key)); ?>
                        </span>
                    <?php endif;
                    endforeach; ?>
                  </div>
              <?php endif;
              endif; ?>

              <form method="POST" action="index.php?action=book-room" class="room-booking-form">
                <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                <input type="hidden" name="check_in" value="<?php echo htmlspecialchars($_POST['check_in'] ?? (isset($_GET['check_in']) ? $_GET['check_in'] : '')); ?>">
                <input type="hidden" name="check_out" value="<?php echo htmlspecialchars($_POST['check_out'] ?? (isset($_GET['check_out']) ? $_GET['check_out'] : '')); ?>">
                <input type="hidden" name="guests" value="<?php echo htmlspecialchars($_POST['guests'] ?? (isset($_GET['guests']) ? $_GET['guests'] : 1)); ?>">
                <input type="hidden" name="special_requests" value="<?php echo htmlspecialchars($_POST['special_requests'] ?? ''); ?>">

                <?php if (!empty($_POST['services'])): ?>
                  <?php foreach ($_POST['services'] as $service_id): ?>
                    <input type="hidden" name="services[]" value="<?php echo $service_id; ?>">
                  <?php endforeach; ?>
                <?php endif; ?>

                <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white px-4 py-3 rounded-lg font-semibold transition duration-300">
                  <i class="fas fa-calendar-check mr-2"></i> Book Now
                </button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php else: ?>
    <!-- No Rooms Available -->
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

<script>
  // Set minimum dates for check-in/out
  document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const checkInInput = document.querySelector('input[name="check_in"]');
    const checkOutInput = document.querySelector('input[name="check_out"]');

    if (checkInInput) {
      checkInInput.min = today;
      checkInInput.addEventListener('change', function() {
        if (checkOutInput) {
          const checkInDate = new Date(this.value);
          checkInDate.setDate(checkInDate.getDate() + 1);
          checkOutInput.min = checkInDate.toISOString().split('T')[0];
          if (checkOutInput.value && checkOutInput.value <= this.value) {
            checkOutInput.value = checkInDate.toISOString().split('T')[0];
          }
        }
      });
    }

    // Copy form data to all room booking forms
    document.querySelector('form[method="POST"]')?.addEventListener('submit', function(e) {
      if (this.classList.contains('room-booking-form')) {
        return; // Allow room booking forms to submit directly
      }

      e.preventDefault();

      // Collect form data
      const formData = new FormData(this);
      const services = [];
      const checkboxes = document.querySelectorAll('input[name="services[]"]:checked');

      checkboxes.forEach(cb => services.push(cb.value));

      // Update all room booking forms
      document.querySelectorAll('.room-booking-form').forEach(form => {
        form.querySelector('input[name="check_in"]').value = formData.get('check_in');
        form.querySelector('input[name="check_out"]').value = formData.get('check_out');
        form.querySelector('input[name="guests"]').value = formData.get('guests');
        form.querySelector('input[name="special_requests"]').value = formData.get('special_requests');

        // Clear existing service inputs
        form.querySelectorAll('input[name="services[]"]').forEach(input => input.remove());

        // Add new service inputs
        services.forEach(serviceId => {
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'services[]';
          input.value = serviceId;
          form.appendChild(input);
        });
      });

      // Submit the form for availability check
      this.submit();
    });
  });
</script>
