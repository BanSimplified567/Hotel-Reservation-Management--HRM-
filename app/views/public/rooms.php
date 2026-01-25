<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-4">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h1 class="display-4 fw-bold mb-3">Our Rooms</h1>
        <p class="lead text-light">Discover our selection of beautifully designed rooms and suites</p>
      </div>
    </div>
  </div>
</section>

<!-- Filters Section -->
<section class="py-4 bg-light">
  <div class="container">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4">
        <form method="GET" action="index.php?action=rooms">
          <input type="hidden" name="action" value="rooms">

          <div class="row g-3">
            <div class="col-md">
              <label class="form-label fw-semibold">Room Type</label>
              <select name="type" class="form-select">
                <option value="">All Types</option>
                <?php foreach ($room_types ?? [] as $room_type): ?>
                  <option value="<?php echo $room_type['id']; ?>" <?php echo ($_GET['type'] ?? '') == $room_type['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($room_type['name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md">
              <label class="form-label fw-semibold">Min Price</label>
              <input type="number" name="min_price" value="<?php echo htmlspecialchars($_GET['min_price'] ?? '0'); ?>"
                class="form-control" placeholder="0">
            </div>

            <div class="col-md">
              <label class="form-label fw-semibold">Max Price</label>
              <input type="number" name="max_price" value="<?php echo htmlspecialchars($_GET['max_price'] ?? '1000'); ?>"
                class="form-control" placeholder="1000">
            </div>

            <div class="col-md">
              <label class="form-label fw-semibold">Capacity</label>
              <select name="capacity" class="form-select">
                <option value="1">1 Guest</option>
                <option value="2" <?php echo ($_GET['capacity'] ?? '') == '2' ? 'selected' : ''; ?>>2 Guests</option>
                <option value="3" <?php echo ($_GET['capacity'] ?? '') == '3' ? 'selected' : ''; ?>>3 Guests</option>
                <option value="4" <?php echo ($_GET['capacity'] ?? '') == '4' ? 'selected' : ''; ?>>4+ Guests</option>
              </select>
            </div>

            <div class="col-md d-flex align-items-end">
              <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search me-2"></i> Filter
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Rooms Grid -->
<section class="py-5">
  <div class="container">
    <?php if (empty($rooms)): ?>
      <div class="text-center py-5">
        <div class="card border-0 shadow-sm">
          <div class="card-body py-5">
            <i class="fas fa-bed text-muted display-1 mb-4"></i>
            <h3 class="display-6 fw-semibold text-dark mb-3">No Rooms Found</h3>
            <p class="text-muted mb-4">Try adjusting your filters to see more results</p>
            <a href="index.php?action=rooms" class="btn btn-primary btn-lg">
              Clear Filters
            </a>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="display-6 fw-bold text-dark">Available Rooms (<?php echo count($rooms); ?>)</h2>
        <div class="d-flex gap-2">
          <a href="index.php?action=rooms&sort=price_asc" class="btn btn-outline-secondary btn-sm">
            Price: Low to High
          </a>
          <a href="index.php?action=rooms&sort=price_desc" class="btn btn-outline-secondary btn-sm">
            Price: High to Low
          </a>
        </div>
      </div>

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($rooms as $room): ?>
          <div class="col">
            <div class="card h-100 border-0 shadow-sm hover-card">
              <div class="position-relative">
                <img src="uploads/rooms/<?php echo $room['primary_image'] ?? 'default-room.jpg'; ?>"
                     alt="<?php echo htmlspecialchars($room['name'] ?? 'Room'); ?>"
                     class="card-img-top"
                     style="height: 250px; object-fit: cover;"
                     onerror="this.src='images/default-room.jpg'">
                <div class="position-absolute top-0 end-0 m-3 bg-white bg-opacity-90 px-3 py-2 rounded">
                  <span class="text-primary fw-bold">$<?php echo number_format($room['base_price'] ?? 0, 2); ?>/night</span>
                </div>
              </div>
              <div class="card-body p-4">
                <h3 class="card-title h5 fw-semibold mb-3 text-dark"><?php echo htmlspecialchars($room['name'] ?? 'Room'); ?></h3>
                <p class="card-text text-muted mb-4 small"><?php echo htmlspecialchars($room['description'] ?? ''); ?></p>

                <div class="d-flex gap-4 mb-4 text-muted small">
                  <span><i class="fas fa-users me-1"></i> <?php echo $room['capacity'] ?? 2; ?> Guests</span>
                  <span><i class="fas fa-bed me-1"></i> <?php echo $room['beds'] ?? '1'; ?> Bed</span>
                  <span><i class="fas fa-expand-arrows-alt me-1"></i> <?php echo $room['size'] ?? '25'; ?> m²</span>
                </div>

                <div class="d-flex align-items-center justify-content-between">
                  <a href="index.php?action=rooms&sub_action=details&id=<?php echo $room['id']; ?>"
                    class="text-primary text-decoration-none fw-semibold">
                    View Details
                  </a>
                  <a href="index.php?action=room-search&room_type_id=<?php echo $room['id']; ?>"
                    class="btn btn-primary btn-sm">
                    Book Now
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

<!-- Featured Rooms -->
<?php if (!empty($featured_rooms)): ?>
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="display-5 fw-bold mb-5 text-center text-dark">Featured Rooms</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php foreach ($featured_rooms as $room): ?>
        <div class="col">
          <div class="card h-100 border-0 shadow-sm hover-card">
            <div class="position-relative">
              <img src="uploads/rooms/<?php echo $room['primary_image'] ?? 'default-room.jpg'; ?>"
                   alt="<?php echo htmlspecialchars($room['name'] ?? 'Room'); ?>"
                   class="card-img-top"
                   style="height: 280px; object-fit: cover;"
                   onerror="this.src='images/default-room.jpg'">
              <div class="position-absolute top-0 start-0 m-3 bg-warning text-dark px-3 py-2 rounded fw-semibold">
                Featured
              </div>
            </div>
            <div class="card-body p-4">
              <h3 class="card-title h5 fw-semibold mb-3 text-dark"><?php echo htmlspecialchars($room['name'] ?? 'Room'); ?></h3>
              <p class="card-text text-muted mb-4"><?php echo htmlspecialchars($room['description'] ?? ''); ?></p>
              <div class="d-flex align-items-center justify-content-between">
                <span class="text-primary fw-bold fs-5">$<?php echo number_format($room['base_price'] ?? 0, 2); ?>/night</span>
                <a href="index.php?action=rooms&sub_action=details&id=<?php echo $room['id']; ?>"
                  class="btn btn-primary">
                  View Details
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<style>
.hero-section {
  background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-secondary) 100%);
}

.hover-card {
  transition: all 0.3s ease;
}

.hover-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.card-img-top {
  border-radius: 0.375rem 0.375rem 0 0;
}

.form-control, .form-select {
  padding: 0.5rem 0.75rem;
  border-radius: 0.375rem;
}
</style>
