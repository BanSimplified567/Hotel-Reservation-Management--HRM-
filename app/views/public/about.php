<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10 text-center">
        <h1 class="display-4 fw-bold mb-4">About Our Hotel</h1>
        <p class="lead mb-5 text-light"><?php echo htmlspecialchars($hotel_info['description'] ?? 'Experience luxury and comfort at our premier hotel.'); ?></p>
        <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
          <a href="#history" class="btn btn-light btn-lg px-5 py-3 fw-semibold text-primary">
            Our History
          </a>
          <a href="#team" class="btn btn-outline-light btn-lg px-5 py-3 fw-semibold">
            Meet Our Team
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center display-5 fw-bold mb-5 text-dark">Hotel Statistics</h2>
    <div class="row g-4">
      <div class="col-6 col-md-3">
        <div class="card h-100 border-0 shadow-sm text-center hover-shadow">
          <div class="card-body p-4">
            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
              <i class="fas fa-bed text-primary fs-3"></i>
            </div>
            <h3 class="text-primary fw-bold display-6 mb-2"><?php echo $statistics['total_rooms'] ?? 0; ?></h3>
            <p class="text-muted fw-semibold">Total Rooms</p>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card h-100 border-0 shadow-sm text-center hover-shadow">
          <div class="card-body p-4">
            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
              <i class="fas fa-home text-primary fs-3"></i>
            </div>
            <h3 class="text-primary fw-bold display-6 mb-2"><?php echo $statistics['room_types'] ?? 0; ?></h3>
            <p class="text-muted fw-semibold">Room Types</p>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card h-100 border-0 shadow-sm text-center hover-shadow">
          <div class="card-body p-4">
            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
              <i class="fas fa-users text-primary fs-3"></i>
            </div>
            <h3 class="text-primary fw-bold display-6 mb-2"><?php echo $statistics['total_guests'] ?? 0; ?>+</h3>
            <p class="text-muted fw-semibold">Happy Guests</p>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card h-100 border-0 shadow-sm text-center hover-shadow">
          <div class="card-body p-4">
            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
              <i class="fas fa-calendar-check text-primary fs-3"></i>
            </div>
            <h3 class="text-primary fw-bold display-6 mb-2"><?php echo $statistics['total_reservations'] ?? 0; ?></h3>
            <p class="text-muted fw-semibold">Reservations</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- History Section -->
<section id="history" class="py-5">
  <div class="container">
    <h2 class="text-center display-5 fw-bold mb-5 text-dark">Our History</h2>
    <div class="row g-5">
      <div class="col-lg-6">
        <div class="timeline">
          <div class="d-flex mb-5">
            <div class="flex-shrink-0">
              <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 80px; height: 80px;">
                <?php echo htmlspecialchars($hotel_info['established'] ?? '2005'); ?>
              </div>
            </div>
            <div class="flex-grow-1 ms-4">
              <h4 class="fw-semibold mb-2 fs-4">Foundation</h4>
              <p class="text-muted">Our hotel was established with a vision to provide exceptional hospitality services.</p>
            </div>
          </div>
          <div class="d-flex mb-5">
            <div class="flex-shrink-0">
              <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 80px; height: 80px;">2010</div>
            </div>
            <div class="flex-grow-1 ms-4">
              <h4 class="fw-semibold mb-2 fs-4">First Expansion</h4>
              <p class="text-muted">Expanded our facilities and added new room types to accommodate growing demand.</p>
            </div>
          </div>
          <div class="d-flex mb-5">
            <div class="flex-shrink-0">
              <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 80px; height: 80px;">2018</div>
            </div>
            <div class="flex-grow-1 ms-4">
              <h4 class="fw-semibold mb-2 fs-4">Renovation Complete</h4>
              <p class="text-muted">Completed major renovations with modern amenities and eco-friendly features.</p>
            </div>
          </div>
          <div class="d-flex">
            <div class="flex-shrink-0">
              <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 80px; height: 80px;">2023</div>
            </div>
            <div class="flex-grow-1 ms-4">
              <h4 class="fw-semibold mb-2 fs-4">Award Winning</h4>
              <p class="text-muted">Received "Best Luxury Hotel" award for exceptional service and facilities.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="row g-4 mb-4">
          <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body p-4">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                  <i class="fas fa-bullseye text-primary fs-4"></i>
                </div>
                <h3 class="fw-semibold mb-3 fs-5">Our Mission</h3>
                <p class="text-muted"><?php echo htmlspecialchars($hotel_info['mission'] ?? 'To provide exceptional hospitality services.'); ?></p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body p-4">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                  <i class="fas fa-eye text-primary fs-4"></i>
                </div>
                <h3 class="fw-semibold mb-3 fs-5">Our Vision</h3>
                <p class="text-muted"><?php echo htmlspecialchars($hotel_info['vision'] ?? 'To be the most preferred luxury hotel brand globally.'); ?></p>
              </div>
            </div>
          </div>
        </div>
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <h4 class="fw-semibold mb-4 fs-5">Hotel Information</h4>
            <ul class="list-unstyled mb-0">
              <li class="d-flex align-items-center mb-3 text-muted">
                <i class="fas fa-map-marker-alt text-primary me-3 fs-5"></i>
                <?php echo htmlspecialchars($hotel_info['address'] ?? '123 Luxury Street, City Center'); ?>
              </li>
              <li class="d-flex align-items-center mb-3 text-muted">
                <i class="fas fa-phone text-primary me-3 fs-5"></i>
                <?php echo htmlspecialchars($hotel_info['phone'] ?? '+1 (123) 456-7890'); ?>
              </li>
              <li class="d-flex align-items-center mb-3 text-muted">
                <i class="fas fa-envelope text-primary me-3 fs-5"></i>
                <?php echo htmlspecialchars($hotel_info['email'] ?? 'info@luxuryhotel.com'); ?>
              </li>
              <li class="d-flex align-items-center mb-3 text-muted">
                <i class="fas fa-building text-primary me-3 fs-5"></i>
                Established: <?php echo htmlspecialchars($hotel_info['established'] ?? '2005'); ?>
              </li>
              <li class="d-flex align-items-center text-muted">
                <i class="fas fa-trophy text-primary me-3 fs-5"></i>
                Awards: <?php echo htmlspecialchars($hotel_info['awards'] ?? 'Best Luxury Hotel 2023'); ?>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Team Section -->
<section id="team" class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center display-5 fw-bold mb-5 text-dark">Meet Our Team</h2>
    <div class="row g-4">
      <?php foreach ($team_members ?? [] as $member): ?>
        <div class="col-md-6 col-lg-3">
          <div class="card border-0 shadow-sm h-100 hover-shadow">
            <div class="bg-primary position-relative" style="height: 250px;">
              <?php if (!empty($member['photo'])): ?>
                <img src="<?php echo htmlspecialchars($member['photo']); ?>"
                     alt="<?php echo htmlspecialchars($member['name']); ?>"
                     class="img-fluid w-100 h-100 object-fit-cover">
              <?php else: ?>
                <div class="d-flex align-items-center justify-content-center h-100">
                  <i class="fas fa-user-circle text-white display-1"></i>
                </div>
              <?php endif; ?>
            </div>
            <div class="card-body p-4">
              <h5 class="card-title fw-semibold mb-1"><?php echo htmlspecialchars($member['name'] ?? 'Team Member'); ?></h5>
              <div class="text-primary fw-semibold mb-2 small"><?php echo htmlspecialchars($member['position'] ?? 'Staff'); ?></div>
              <p class="card-text text-muted small mb-4"><?php echo htmlspecialchars($member['bio'] ?? ''); ?></p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-primary small">
                  <i class="fas fa-clock me-1"></i>
                  <?php echo $member['experience_years'] ?? '0'; ?> exp.
                </span>
                <div class="d-flex gap-2">
                  <a href="#" class="text-muted hover-primary">
                    <i class="fab fa-linkedin fs-5"></i>
                  </a>
                  <a href="#" class="text-muted hover-primary">
                    <i class="fab fa-twitter fs-5"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10 text-center">
        <h2 class="display-5 fw-bold mb-4">Experience Luxury Hospitality</h2>
        <p class="lead mb-5 text-light">Book your stay with us and experience world-class service and comfort.</p>
        <a href="index.php?action=rooms" class="btn btn-light btn-lg px-5 py-3 fw-semibold text-primary">
          <i class="fas fa-calendar-check me-2"></i> Book Now
        </a>
      </div>
    </div>
  </div>
</section>

<style>
.hover-shadow {
  transition: all 0.3s ease;
}
.hover-shadow:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
.hover-primary:hover {
  color: var(--bs-primary) !important;
}
.object-fit-cover {
  object-fit: cover;
}
.hero-section {
  background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-secondary) 100%);
}
</style>
