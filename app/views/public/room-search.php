
<!-- Hero Section -->
<section class="bg-primary text-white py-5">
  <div class="container">
    <h1 class="display-4 fw-bold mb-2">Search Rooms</h1>
    <p class="lead text-light">Find the perfect room for your stay</p>
  </div>
</section>

<!-- Search Form -->
<section class="py-4 bg-light">
  <div class="container">
    <div class="bg-white rounded shadow p-4 p-md-5">
      <form method="GET" action="index.php?action=room-search" class="row g-3">
        <input type="hidden" name="action" value="room-search">

        <div class="col-md-3">
          <label class="form-label fw-semibold">Check-in Date</label>
          <input type="date" name="check_in"
            value="<?php echo htmlspecialchars($check_in ?? ''); ?>"
            class="form-control"
            required>
        </div>

        <div class="col-md-3">
          <label class="form-label fw-semibold">Check-out Date</label>
          <input type="date" name="check_out"
            value="<?php echo htmlspecialchars($check_out ?? ''); ?>"
            class="form-control"
            required>
        </div>

        <div class="col-md-3">
          <label class="form-label fw-semibold">Number of Guests</label>
          <select name="guests" class="form-select">
            <?php for ($i = 1; $i <= 6; $i++): ?>
              <option value="<?php echo $i; ?>" <?php echo ($guests ?? 1) == $i ? 'selected' : ''; ?>>
                <?php echo $i; ?> <?php echo $i == 1 ? 'Guest' : 'Guests'; ?>
              </option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-search me-2"></i> Search
          </button>
        </div>
      </form>

      <!-- Advanced Filters -->
      <div class="mt-4 pt-4 border-top">
        <button id="toggleFilters" class="btn btn-link text-primary p-0 fw-semibold mb-3">
          <i class="fas fa-filter me-2"></i> Advanced Filters
        </button>
        <div id="advancedFilters" class="d-none row g-3">
          <div class="col-md-4">
            <label class="form-label fw-semibold">Room Type</label>
            <select name="room_type" class="form-select">
              <option value="">All Types</option>
              <?php foreach ($room_types ?? [] as $type): ?>
                <option value="<?php echo htmlspecialchars($type); ?>" <?php echo ($room_type ?? '') === $type ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($type); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Min Price</label>
            <input type="number" name="min_price" value="<?php echo htmlspecialchars($min_price ?? '0'); ?>"
              class="form-control">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Max Price</label>
            <input type="number" name="max_price" value="<?php echo htmlspecialchars($max_price ?? '1000'); ?>"
              class="form-control">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Results Section -->
<section class="py-5">
  <div class="container">
    <?php if (empty($check_in) || empty($check_out)): ?>
      <div class="text-center py-5">
        <i class="fas fa-calendar-alt text-muted fs-1 mb-3"></i>
        <h3 class="h3 fw-semibold text-dark mb-2">Start Your Search</h3>
        <p class="text-muted">Please select your check-in and check-out dates to search for available rooms</p>
      </div>
    <?php elseif (empty($available_rooms)): ?>
      <div class="text-center py-5">
        <i class="fas fa-bed text-muted fs-1 mb-3"></i>
        <h3 class="h3 fw-semibold text-dark mb-2">No Rooms Available</h3>
        <p class="text-muted mb-4">Sorry, no rooms are available for the selected dates. Please try different dates.</p>
        <a href="index.php?action=room-search" class="btn btn-primary">
          Search Again
        </a>
      </div>
    <?php else: ?>
      <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
          <h2 class="h4 fw-bold text-dark"><?php echo count($available_rooms); ?> Rooms Available</h2>
          <p class="text-muted mb-0">
            <?php echo date('M j', strtotime($check_in)); ?> - <?php echo date('M j, Y', strtotime($check_out)); ?>
            • <?php echo $guests ?? 1; ?> guest<?php echo ($guests ?? 1) > 1 ? 's' : ''; ?>
          </p>
        </div>
      </div>

      <div class="row g-4">
        <?php foreach ($available_rooms as $room): ?>
          <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm">
              <div class="card-body p-0">
                <div class="bg-primary text-white p-3 position-relative" style="height: 200px;">
                  <div class="position-absolute top-0 end-0 bg-white bg-opacity-90 px-3 py-2 rounded m-3">
                    <span class="text-primary fw-bold">$<?php echo number_format($room['price_per_night'] ?? 0, 2); ?>/night</span>
                  </div>
                </div>
                <div class="p-4">
                  <h5 class="card-title fw-semibold mb-2"><?php echo htmlspecialchars($room['type'] ?? 'Room'); ?></h5>
                  <p class="card-text text-muted small mb-3"><?php echo htmlspecialchars($room['description'] ?? ''); ?></p>

                  <div class="d-flex gap-3 mb-3 small text-muted">
                    <span><i class="fas fa-users me-1"></i> <?php echo $room['capacity'] ?? 2; ?> Guests</span>
                    <span><i class="fas fa-bed me-1"></i> <?php echo $room['beds'] ?? '1'; ?> Bed</span>
                  </div>

                  <?php
                  $checkInDate = new DateTime($check_in);
                  $checkOutDate = new DateTime($check_out);
                  $nights = $checkOutDate->diff($checkInDate)->days;
                  $totalPrice = ($room['price_per_night'] ?? 0) * $nights;
                  ?>

                  <div class="border-top pt-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="text-muted small">Total for <?php echo $nights; ?> nights:</span>
                      <span class="text-primary fw-bold fs-5">$<?php echo number_format($totalPrice, 2); ?></span>
                    </div>
                  </div>

                  <a href="index.php?action=book-room&room_id=<?php echo $room['id']; ?>&check_in=<?php echo urlencode($check_in); ?>&check_out=<?php echo urlencode($check_out); ?>&guests=<?php echo $guests; ?>"
                    class="btn btn-primary w-100">
                    <i class="fas fa-calendar-check me-2"></i> Book Now
                  </a>
                </div>
              </div>
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
    filters.classList.toggle('d-none');
  });
</script>
