<?php
// app/views/customer/booking/index.php
// Note: $rooms, $services, $selectedRoom, $room_id, $page_title are passed from controller
?>

<div class="container py-5">
  <div class="mb-4">
    <h1 class="h2 fw-bold mb-2">Book a Room</h1>
    <p class="text-muted">Find and book the perfect room for your stay</p>
  </div>

  <!-- Booking Form (Updated for POST submission to update dates) -->
  <div class="bg-white rounded shadow p-4 mb-4">
    <form method="POST" action="index.php?action=book-room">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label fw-semibold">Check-in Date</label>
          <input type="date" name="check_in" required
            value="<?php echo htmlspecialchars($_POST['check_in'] ?? $_GET['check_in'] ?? ''); ?>"
            class="form-control"
            min="<?php echo date('Y-m-d'); ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Check-out Date</label>
          <input type="date" name="check_out" required
            value="<?php echo htmlspecialchars($_POST['check_out'] ?? $_GET['check_out'] ?? ''); ?>"
            class="form-control"
            min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Guests</label>
          <select name="guests" class="form-select" required>
            <?php for ($i = 1; $i <= 6; $i++): ?>
              <option value="<?php echo $i; ?>" <?php echo (($_POST['guests'] ?? $_GET['guests'] ?? 1) == $i) ? 'selected' : ''; ?>>
                <?php echo $i; ?> <?php echo $i == 1 ? 'Guest' : 'Guests'; ?>
              </option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-search me-2"></i> Update
          </button>
        </div>
      </div>
    </form>
  </div>

  <?php if (!empty($rooms)): ?>
    <!-- Services Section -->
    <div class="mb-4 bg-white rounded shadow p-4">
      <h2 class="h4 fw-bold mb-3">Additional Services</h2>
      <div class="row g-3">
        <?php foreach ($services as $service): ?>
          <div class="col-md-6 col-lg-3">
            <div class="border rounded p-3">
              <div class="form-check">
                <input type="checkbox"
                  name="services[]"
                  value="<?php echo $service['id']; ?>"
                  id="service_<?php echo $service['id']; ?>"
                  class="form-check-input">
                <label for="service_<?php echo $service['id']; ?>" class="form-check-label fw-semibold">
                  <?php echo htmlspecialchars($service['name']); ?>
                </label>
                <p class="small text-muted mt-1"><?php echo htmlspecialchars($service['description'] ?? ''); ?></p>
                <p class="text-primary fw-bold mt-2">$<?php echo number_format($service['price'], 2); ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Special Requests -->
    <div class="mb-4 bg-white rounded shadow p-4">
      <h2 class="h4 fw-bold mb-3">Special Requests</h2>
      <textarea name="special_requests"
        rows="3"
        class="form-control"
        placeholder="Any special requests or requirements..."><?php echo htmlspecialchars($_POST['special_requests'] ?? ''); ?></textarea>
    </div>

    <!-- Rooms Selection -->
    <div class="mb-4">
      <h2 class="h4 fw-bold mb-2"><?php echo count($rooms); ?> Rooms Available</h2>
      <p class="text-muted mb-3">Select a room to proceed with booking</p>

      <div class="row g-4">
        <?php foreach ($rooms as $room): ?>
          <div class="col-lg-4 col-md-6">
            <div class="card h-100 <?php echo ($room_id == $room['id']) ? 'border-primary' : ''; ?>">
              <div class="card-body p-0">
                <div class="bg-primary text-white p-3 position-relative" style="height: 200px;">
                  <div class="position-absolute top-0 end-0 bg-white bg-opacity-90 px-3 py-2 rounded m-3">
                    <span class="text-primary fw-bold">$<?php echo number_format($room['price_per_night'] ?? 0, 2); ?>/night</span>
                  </div>
                  <?php if ($room_id == $room['id']): ?>
                    <div class="position-absolute top-0 start-0 bg-success text-white px-3 py-2 rounded m-3">
                      <i class="fas fa-check me-1"></i> Selected
                    </div>
                  <?php endif; ?>
                </div>
                <div class="p-4">
                  <h5 class="card-title fw-semibold"><?php echo htmlspecialchars($room['type'] ?? 'Room'); ?></h5>
                  <p class="card-text text-muted small"><?php echo htmlspecialchars($room['description'] ?? ''); ?></p>

                  <div class="d-flex gap-3 mb-3 small text-muted">
                    <span><i class="fas fa-users me-1"></i> <?php echo $room['capacity'] ?? 2; ?> Guests</span>
                    <?php if (isset($room['size'])): ?>
                      <span><i class="fas fa-expand-arrows-alt me-1"></i> <?php echo $room['size']; ?></span>
                    <?php endif; ?>
                  </div>

                  <?php if (!empty($room['amenities'])):
                    $amenities = is_string($room['amenities']) ? json_decode($room['amenities'], true) : $room['amenities'];
                    if ($amenities): ?>
                      <div class="d-flex flex-wrap gap-1 mb-3">
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
                            <span class="badge bg-light text-dark">
                              <i class="fas <?php echo $amenityIcons[$key] ?? 'fa-check'; ?> me-1"></i>
                              <?php echo ucfirst(str_replace('_', ' ', $key)); ?>
                            </span>
                        <?php endif;
                        endforeach; ?>
                      </div>
                  <?php endif;
                  endif; ?>

                  <form method="POST" action="index.php?action=book-room" class="room-booking-form">
                    <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                    <input type="hidden" name="check_in" value="<?php echo htmlspecialchars($_POST['check_in'] ?? $_GET['check_in'] ?? ''); ?>">
                    <input type="hidden" name="check_out" value="<?php echo htmlspecialchars($_POST['check_out'] ?? $_GET['check_out'] ?? ''); ?>">
                    <input type="hidden" name="guests" value="<?php echo htmlspecialchars($_POST['guests'] ?? $_GET['guests'] ?? 1); ?>">
                    <input type="hidden" name="special_requests" value="<?php echo htmlspecialchars($_POST['special_requests'] ?? ''); ?>">

                    <?php if (!empty($_POST['services'])): ?>
                      <?php foreach ($_POST['services'] as $service_id): ?>
                        <input type="hidden" name="services[]" value="<?php echo $service_id; ?>">
                      <?php endforeach; ?>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary w-100">
                      <i class="fas fa-calendar-check me-2"></i> Book Now
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php else: ?>
    <!-- No Rooms Available -->
    <div class="bg-white rounded shadow p-5 text-center">
      <i class="fas fa-calendar-alt text-muted fs-1 mb-3"></i>
      <h3 class="h3 fw-semibold mb-2">Start Your Booking</h3>
      <p class="text-muted mb-4">Select your check-in and check-out dates to see available rooms</p>
      <div class="mx-auto" style="max-width: 500px;">
        <div class="bg-light border rounded p-3 text-start">
          <i class="fas fa-info-circle text-primary me-2"></i>
          <strong class="text-dark">Need help choosing?</strong>
          <ul class="mt-2 text-muted small mb-0">
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
