<?php
// app/views/public/room-details.php
// Note: $room, $similar_rooms, $individual_rooms, $reviews, $can_review are passed from controller
?>

<?php if (empty($room)): ?>
  <div class="container py-5 text-center">
    <div class="py-5">
      <i class="fas fa-exclamation-triangle text-secondary fa-4x mb-3"></i>
      <h2 class="h3 fw-bold text-dark mb-2">Room Not Found</h2>
      <p class="text-muted mb-4">The room you're looking for doesn't exist or has been removed.</p>
      <a href="index.php?action=rooms" class="btn btn-primary px-4">
        <i class="fas fa-door-open me-2"></i> View All Rooms
      </a>
    </div>
  </div>
<?php else: ?>
  <!-- Hero Section -->
  <section class="bg-primary text-white py-4">
    <div class="container">
      <a href="index.php?action=rooms" class="text-white-50 text-decoration-none mb-3 d-inline-block">
        <i class="fas fa-arrow-left me-2"></i> Back to Rooms
      </a>
      <h1 class="h2 fw-bold mb-2"><?php echo htmlspecialchars($room['name'] ?? 'Room'); ?></h1>
      <p class="lead mb-0">
        <?php echo $room['available_count'] ?? 0; ?> Rooms Available |
        $<?php echo number_format($room['base_price'] ?? 0, 2); ?>/night
      </p>
    </div>
  </section>

  <!-- Room Details -->
  <section class="py-4">
    <div class="container">
      <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 mb-4 mb-lg-0">
          <!-- Room Images -->
          <?php if (!empty($room['images'])): ?>
            <div id="roomCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
              <div class="carousel-inner rounded" style="height: 400px; overflow: hidden;">
                <?php
                $is_first = true;
                foreach ($room['images'] as $index => $image):
                  if (!is_string($index)): // Skip the 'primary' key
                ?>
                    <div class="carousel-item <?php echo $is_first ? 'active' : ''; ?>">
                      <img src="<?php echo htmlspecialchars($image); ?>"
                        class="d-block w-100 h-100 object-fit-cover"
                        alt="<?php echo htmlspecialchars($room['name']); ?>">
                    </div>
                <?php
                    $is_first = false;
                  endif;
                endforeach;
                ?>
              </div>
              <?php if (count($room['images']) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <div class="bg-light rounded mb-4 d-flex align-items-center justify-content-center" style="height: 300px;">
              <i class="fas fa-bed text-primary fa-6x"></i>
            </div>
          <?php endif; ?>

          <!-- Description -->
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
              <h3 class="h5 fw-bold mb-3">Room Description</h3>
              <p class="text-muted mb-0"><?php echo htmlspecialchars($room['description'] ?? 'No description available.'); ?></p>
            </div>
          </div>

          <!-- Amenities -->
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
              <h3 class="h5 fw-bold mb-3">Amenities</h3>
              <div class="row">
                <?php
                if (!empty($room['amenities']) && is_array($room['amenities'])):
                  foreach ($room['amenities'] as $amenity => $value):
                    if ($value === true || $value === 'true' || $value === 1):
                      $amenityName = ucfirst(str_replace('_', ' ', $amenity));
                ?>
                      <div class="col-6 col-md-4 mb-2">
                        <div class="d-flex align-items-center">
                          <i class="fas fa-check text-primary me-2 small"></i>
                          <span class="small"><?php echo htmlspecialchars($amenityName); ?></span>
                        </div>
                      </div>
                    <?php
                    endif;
                  endforeach;
                else:
                  // Default amenities if none specified
                  $defaultAmenities = ['WiFi', 'TV', 'Air Conditioning', 'Mini Bar', 'Safe', 'Hair Dryer'];
                  foreach ($defaultAmenities as $amenity):
                    ?>
                    <div class="col-6 col-md-4 mb-2">
                      <div class="d-flex align-items-center">
                        <i class="fas fa-check text-primary me-2 small"></i>
                        <span class="small"><?php echo htmlspecialchars($amenity); ?></span>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Room Specifications -->
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
              <h3 class="h5 fw-bold mb-3">Room Specifications</h3>
              <div class="row">
                <div class="col-6 col-md-4 mb-3">
                  <p class="text-muted small mb-1">Room Size</p>
                  <p class="fw-semibold mb-0"><?php echo $room['size'] ?? '25 sqm'; ?></p>
                </div>
                <div class="col-6 col-md-4 mb-3">
                  <p class="text-muted small mb-1">Capacity</p>
                  <p class="fw-semibold mb-0"><?php echo $room['capacity'] ?? '2'; ?> Guests</p>
                </div>
                <div class="col-6 col-md-4 mb-3">
                  <p class="text-muted small mb-1">Price</p>
                  <p class="fw-semibold mb-0 text-primary">$<?php echo number_format($room['base_price'] ?? 0, 2); ?>/night</p>
                </div>
                <div class="col-6 col-md-4 mb-3">
                  <p class="text-muted small mb-1">Available Rooms</p>
                  <p class="fw-semibold mb-0"><?php echo $room['available_count'] ?? 0; ?> Available</p>
                </div>
                <?php if (!empty($room['available_rooms'])): ?>
                  <div class="col-12 mb-3">
                    <p class="text-muted small mb-1">Room Numbers</p>
                    <p class="fw-semibold mb-0"><?php echo htmlspecialchars($room['available_rooms']); ?></p>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Individual Rooms -->
          <?php if (!empty($individual_rooms)): ?>
            <div class="card border-0 shadow-sm">
              <div class="card-body">
                <h3 class="h5 fw-bold mb-3">Individual Rooms</h3>
                <div class="table-responsive">
                  <table class="table table-sm table-hover">
                    <thead>
                      <tr>
                        <th class="small">Room #</th>
                        <th class="small">Floor</th>
                        <th class="small">View</th>
                        <th class="small">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($individual_rooms as $individual): ?>
                        <tr>
                          <td class="small fw-semibold"><?php echo htmlspecialchars($individual['room_number']); ?></td>
                          <td class="small"><?php echo htmlspecialchars($individual['floor'] ?? '1'); ?></td>
                          <td class="small"><?php echo htmlspecialchars(ucfirst($individual['view_type'] ?? 'City')); ?></td>
                          <td>
                            <span class="badge <?php
                                                echo $individual['status'] === 'available' ? 'bg-success' : ($individual['status'] === 'occupied' ? 'bg-danger' : ($individual['status'] === 'reserved' ? 'bg-warning' : 'bg-secondary'));
                                                ?> small">
                              <?php echo ucfirst($individual['status'] ?? 'available'); ?>
                            </span>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
          <!-- Booking Card -->
          <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px;">
            <div class="card-body">
              <div class="text-center mb-4">
                <div class="h3 fw-bold text-primary mb-1">$<?php echo number_format($room['base_price'] ?? 0, 2); ?></div>
                <p class="text-muted small">per night</p>
              </div>

              <?php if (($room['available_count'] ?? 0) > 0): ?>
                <a href="index.php?action=book-room&room_type_id=<?php echo $room['id']; ?>"
                  class="btn btn-primary w-100 mb-3">
                  <i class="fas fa-calendar-check me-2"></i> Book Now
                </a>
              <?php else: ?>
                <button class="btn btn-secondary w-100 mb-3" disabled>
                  <i class="fas fa-times me-2"></i> Currently Unavailable
                </button>
              <?php endif; ?>

              <div class="border-top pt-3">
                <h4 class="h6 fw-bold mb-2">Quick Info</h4>
                <div class="row small">
                  <div class="col-12 mb-1">
                    <div class="d-flex justify-content-between">
                      <span class="text-muted">Check-in:</span>
                      <span class="fw-semibold">3:00 PM</span>
                    </div>
                  </div>
                  <div class="col-12 mb-1">
                    <div class="d-flex justify-content-between">
                      <span class="text-muted">Check-out:</span>
                      <span class="fw-semibold">11:00 AM</span>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-flex justify-content-between">
                      <span class="text-muted">Availability:</span>
                      <span class="fw-semibold <?php echo ($room['available_count'] ?? 0) > 0 ? 'text-success' : 'text-danger'; ?>">
                        <?php echo ($room['available_count'] ?? 0) > 0 ? 'Available' : 'Sold Out'; ?>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Contact Info -->
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <h4 class="h6 fw-bold mb-2">Need Help?</h4>
              <p class="small text-muted mb-3">Contact our reservation team for assistance</p>
              <a href="index.php?action=contact" class="btn btn-outline-primary btn-sm w-100">
                <i class="fas fa-phone me-2"></i> Contact Us
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Similar Rooms -->
      <?php if (!empty($similar_rooms)): ?>
        <div class="mt-5">
          <h2 class="h4 fw-bold mb-4">Similar Rooms</h2>
          <div class="row g-3">
            <?php foreach ($similar_rooms as $similar): ?>
              <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                  <div class="position-relative" style="height: 180px; overflow: hidden;">
                    <?php if (!empty($similar['primary_image'])): ?>
                      <img src="images/<?php echo htmlspecialchars(basename($similar['primary_image'])); ?>"
                        class="img-fluid w-100 h-100 object-fit-cover"
                        alt="<?php echo htmlspecialchars($similar['name'] ?? 'Room'); ?>">
                    <?php else: ?>
                      <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                        <i class="fas fa-bed text-primary fa-4x"></i>
                      </div>
                    <?php endif; ?>
                  </div>
                  <div class="card-body">
                    <h5 class="h6 fw-bold mb-2"><?php echo htmlspecialchars($similar['name'] ?? 'Room'); ?></h5>
                    <p class="text-muted small mb-2"><?php echo $similar['available_count'] ?? 0; ?> Available</p>
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="text-primary fw-bold">$<?php echo number_format($similar['base_price'] ?? 0, 2); ?>/night</span>
                      <a href="index.php?action=rooms&sub_action=view&id=<?php echo $similar['id']; ?>"
                        class="btn btn-outline-primary btn-sm">
                        View Details
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <style>
    .object-fit-cover {
      object-fit: cover;
    }

    .sticky-top {
      position: sticky;
      z-index: 10;
    }

    .card {
      transition: transform 0.2s ease;
    }

    .card:hover {
      transform: translateY(-3px);
    }
  </style>
<?php endif; ?>
