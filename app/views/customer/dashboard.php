<?php
// Hotel Reservation Landing Page
// app/views/hotel-reservation.php
// Note: $availableRooms, $stats are passed from DashboardController::reservationDashboard()
?>

<!-- Hero Section -->
<section class="hero-section py-4">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="hero-content">
          <h1 class="h2 fw-bold mb-3">Experience Luxury & Comfort</h1>
          <p class="mb-4">Welcome to Hotel Management System, where exceptional service meets unparalleled luxury.</p>
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="index.php?action=book-room" class="btn btn-primary px-4 py-2">
              <i class="fas fa-calendar-check me-2"></i> Book Your Stay
            </a>
          <?php else: ?>
            <a href="index.php?action=register" class="btn btn-primary px-4 py-2">
              <i class="fas fa-user-plus me-2"></i> Register to Book
            </a>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card booking-card border-0 shadow-sm">
          <div class="card-body p-3">
            <h3 class="h5 card-title mb-3">Find Your Perfect Room</h3>
            <form action="index.php?action=room-search" method="GET">
              <input type="hidden" name="action" value="room-search">
              <div class="row g-2">
                <div class="col-md-6">
                  <label class="form-label small">Check-in Date</label>
                  <input type="date" class="form-control form-control-sm" name="check_in" required
                    min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label small">Check-out Date</label>
                  <input type="date" class="form-control form-control-sm" name="check_out" required
                    min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label small">Guests</label>
                  <select class="form-select form-select-sm" name="guests">
                    <option value="1">1 Guest</option>
                    <option value="2" selected>2 Guests</option>
                    <option value="3">3 Guests</option>
                    <option value="4">4 Guests</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label small">Room Type</label>
                  <select class="form-select form-select-sm" name="room_type">
                    <option value="all">All Rooms</option>
                    <?php if (!empty($availableRooms)): ?>
                      <?php foreach ($availableRooms as $room): ?>
                        <?php if ($room['type'] !== 'Common / Background'): ?>
                          <option value="<?php echo htmlspecialchars($room['room_type_id'] ?? ''); ?>">
                            <?php echo htmlspecialchars($room['type']); ?>
                          </option>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
                <div class="col-12 mt-3">
                  <button type="submit" class="btn btn-primary btn-sm w-100 py-2">
                    <i class="fas fa-search me-1"></i> Check Availability
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Stats Section -->
<?php if (isset($stats) && !empty($stats)): ?>
  <section class="py-3 bg-light">
    <div class="container">
      <div class="row text-center">
        <div class="col-6 col-md-3 mb-2">
          <div class="p-3">
            <h3 class="h4 fw-bold text-primary mb-1"><?php echo $stats['total_reservations'] ?? 0; ?></h3>
            <p class="small text-muted mb-0">Total Reservations</p>
          </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
          <div class="p-3">
            <h3 class="h4 fw-bold text-success mb-1"><?php echo $stats['available_rooms'] ?? 0; ?></h3>
            <p class="small text-muted mb-0">Available Rooms</p>
          </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
          <div class="p-3">
            <h3 class="h4 fw-bold text-warning mb-1"><?php echo $stats['checkins_today'] ?? 0; ?></h3>
            <p class="small text-muted mb-0">Check-ins Today</p>
          </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
          <div class="p-3">
            <h3 class="h4 fw-bold text-info mb-1">$<?php echo number_format($stats['today_revenue'] ?? 0, 2); ?></h3>
            <p class="small text-muted mb-0">Today's Revenue</p>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>

<!-- Available Rooms Preview -->
<section class="py-4">
  <div class="container">
    <div class="row mb-4">
      <div class="col-lg-10 mx-auto text-center">
        <h2 class="h4 fw-bold mb-3">Our Room Types</h2>
        <p class="mb-3 small">
          Choose from our selection of luxurious rooms designed for your comfort.
        </p>
      </div>
    </div>

    <div class="row g-3">
      <?php if (!empty($availableRooms)): ?>
        <?php foreach ($availableRooms as $room): ?>
          <?php if ($room['type'] !== 'Common / Background'): ?>
            <div class="col-md-6 col-lg-4">
              <div class="card border-0 shadow-sm h-100">
                <?php if (!empty($room['images']['primary'])): ?>
                  <img src="<?php echo htmlspecialchars($room['images']['primary']); ?>"
                    class="card-img-top" alt="<?php echo htmlspecialchars($room['type']); ?>"
                    style="height: 200px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title h6 fw-bold mb-0"><?php echo htmlspecialchars($room['type']); ?></h5>
                    <span class="badge bg-primary"><?php echo $room['available_count'] ?? 0; ?> Available</span>
                  </div>
                  <p class="card-text small text-muted mb-2">
                    <?php echo htmlspecialchars(substr($room['description'] ?? '', 0, 80)); ?>...
                  </p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <span class="h5 text-primary fw-bold">$<?php echo number_format($room['price_per_night'] ?? 0, 2); ?></span>
                      <span class="text-muted small">/night</span>
                    </div>
                    <div>
                      <span class="badge bg-light text-dark me-1">
                        <i class="fas fa-user me-1"></i><?php echo $room['capacity'] ?? 2; ?>
                      </span>
                      <span class="badge bg-light text-dark">
                        <i class="fas fa-expand-arrows-alt me-1"></i><?php echo $room['size'] ?? '25 sqm'; ?>
                      </span>
                    </div>
                  </div>
                  <div class="mt-3">
                    <a href="index.php?action=room-details&id=<?php echo $room['room_type_id'] ?? ''; ?>"
                      class="btn btn-outline-primary btn-sm w-100">
                      <i class="fas fa-info-circle me-1"></i> View Details
                    </a>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center py-4">
          <i class="fas fa-bed text-secondary fa-3x mb-3"></i>
          <p class="text-muted">No rooms available at the moment.</p>
        </div>
      <?php endif; ?>
    </div>

    <?php if (!empty($availableRooms)): ?>
      <div class="text-center mt-4">
        <a href="index.php?action=rooms" class="btn btn-primary btn-sm">
          <i class="fas fa-door-open me-1"></i> View All Rooms
        </a>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- Description Section -->
<section class="py-4 bg-light">
  <div class="container">
    <div class="row">
      <div class="col-lg-10 mx-auto text-center">
        <h2 class="h4 fw-bold mb-3">Welcome to Our Hotel</h2>
        <p class="mb-3 small">
          Nestled in the heart of the city, we offer a sanctuary of elegance and comfort
          with luxurious rooms and world-class amenities.
        </p>
        <div class="row mt-3">
          <div class="col-6 col-md-3 mb-3">
            <div class="feature-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
              <i class="fas fa-wifi fa-lg"></i>
            </div>
            <h6 class="mb-0 small">Free WiFi</h6>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <div class="feature-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
              <i class="fas fa-utensils fa-lg"></i>
            </div>
            <h6 class="mb-0 small">Fine Dining</h6>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <div class="feature-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
              <i class="fas fa-spa fa-lg"></i>
            </div>
            <h6 class="mb-0 small">Spa</h6>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <div class="feature-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
              <i class="fas fa-swimming-pool fa-lg"></i>
            </div>
            <h6 class="mb-0 small">Pool</h6>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- About Section -->
<section class="py-4">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-5 mb-3 mb-md-0">
        <img src="https://images.unsplash.com/photo-1564501049418-3c27787d01e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
          alt="Hotel Lobby" class="img-fluid rounded shadow-sm">
      </div>
      <div class="col-md-7">
        <h2 class="h4 fw-bold mb-3">About Our Hotel</h2>
        <p class="mb-3 small">
          Our hotel has been redefining luxury hospitality for years.
          Our commitment to excellence has earned us numerous awards and guest loyalty.
        </p>
        <div class="row mt-2">
          <div class="col-6 mb-2">
            <div class="d-flex align-items-center">
              <i class="fas fa-check-circle text-primary me-2"></i>
              <div>
                <h6 class="mb-0 small fw-bold">Award-Winning</h6>
              </div>
            </div>
          </div>
          <div class="col-6 mb-2">
            <div class="d-flex align-items-center">
              <i class="fas fa-check-circle text-primary me-2"></i>
              <div>
                <h6 class="mb-0 small fw-bold">Prime Location</h6>
              </div>
            </div>
          </div>
          <div class="col-6 mb-2">
            <div class="d-flex align-items-center">
              <i class="fas fa-check-circle text-primary me-2"></i>
              <div>
                <h6 class="mb-0 small fw-bold">Eco-Friendly</h6>
              </div>
            </div>
          </div>
          <div class="col-6 mb-2">
            <div class="d-flex align-items-center">
              <i class="fas fa-check-circle text-primary me-2"></i>
              <div>
                <h6 class="mb-0 small fw-bold">24/7 Service</h6>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials Section -->
<section class="py-4 bg-light">
  <div class="container">
    <h2 class="h4 fw-bold text-center mb-3">What Our Guests Say</h2>
    <div class="row g-3">
      <div class="col-md-4">
        <div class="card testimonial-card h-100 border-0 shadow-sm">
          <div class="card-body p-3">
            <div class="d-flex align-items-center mb-2">
              <div class="rounded-circle overflow-hidden me-2" style="width: 40px; height: 40px;">
                <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Guest" class="img-fluid">
              </div>
              <div>
                <h6 class="mb-0 small fw-bold">Sarah Johnson</h6>
                <div class="text-warning small">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
            </div>
            <p class="card-text small mb-0">
              "Absolutely breathtaking! The service was impeccable. We'll definitely be returning."
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card testimonial-card h-100 border-0 shadow-sm">
          <div class="card-body p-3">
            <div class="d-flex align-items-center mb-2">
              <div class="rounded-circle overflow-hidden me-2" style="width: 40px; height: 40px;">
                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Guest" class="img-fluid">
              </div>
              <div>
                <h6 class="mb-0 small fw-bold">Michael Chen</h6>
                <div class="text-warning small">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
            </div>
            <p class="card-text small mb-0">
              "Perfect for business travel. Conference facilities are state-of-the-art."
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card testimonial-card h-100 border-0 shadow-sm">
          <div class="card-body p-3">
            <div class="d-flex align-items-center mb-2">
              <div class="rounded-circle overflow-hidden me-2" style="width: 40px; height: 40px;">
                <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Guest" class="img-fluid">
              </div>
              <div>
                <h6 class="mb-0 small fw-bold">Roberta Williams</h6>
                <div class="text-warning small">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
            </div>
            <p class="card-text small mb-0">
              "Our anniversary stay was magical! Attention to detail is outstanding."
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .hero-section {
    background: linear-gradient(rgba(13, 59, 102, 0.9), rgba(13, 59, 102, 0.95)),
      url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
    background-size: cover;
    background-position: center;
    color: white;
    min-height: 60vh;
    display: flex;
    align-items: center;
  }

  .booking-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(5px);
    border-radius: 8px;
  }

  .testimonial-card {
    transition: transform 0.2s ease;
    height: 100%;
  }

  .testimonial-card:hover {
    transform: translateY(-3px);
  }

  .feature-icon {
    transition: transform 0.2s ease;
  }

  .feature-icon:hover {
    transform: scale(1.05);
  }

  .btn-primary {
    background-color: #0d3b66;
    border-color: #0d3b66;
    font-weight: 600;
    transition: all 0.2s ease;
  }

  .btn-primary:hover {
    background-color: #0a2d4d;
    border-color: #0a2d4d;
    transform: translateY(-1px);
  }

  @media (max-width: 768px) {
    .hero-section {
      min-height: 50vh;
      padding: 30px 0;
    }

    .hero-content h1 {
      font-size: 1.5rem;
    }

    .hero-content p {
      font-size: 0.9rem;
    }
  }
</style>
