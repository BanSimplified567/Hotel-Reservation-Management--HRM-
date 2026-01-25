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

<!-- Browse by Property Type Section (From Image) -->
<section class="py-4">
  <div class="container">
    <div class="row mb-4">
      <div class="col-12">
        <h2 class="h4 fw-bold mb-3">Browse by property type</h2>
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <h3 class="h5 fw-bold text-primary mb-3">Hotels</h3>
            <p class="text-muted mb-4">Explore these popular destinations that have a lot to offer</p>

            <div class="row g-3">
              <div class="col-6 col-md-4 col-lg-2">
                <div class="destination-card text-center">
                  <div class="destination-icon mb-2">
                    <i class="fas fa-city fa-2x text-primary"></i>
                  </div>
                  <h6 class="fw-bold mb-1">Manila</h6>
                  <p class="text-muted small mb-0">14,250 properties</p>
                </div>
              </div>
              <div class="col-6 col-md-4 col-lg-2">
                <div class="destination-card text-center">
                  <div class="destination-icon mb-2">
                    <i class="fas fa-umbrella-beach fa-2x text-primary"></i>
                  </div>
                  <h6 class="fw-bold mb-1">Cebu City</h6>
                  <p class="text-muted small mb-0">2,181 properties</p>
                </div>
              </div>
              <div class="col-6 col-md-4 col-lg-2">
                <div class="destination-card text-center">
                  <div class="destination-icon mb-2">
                    <i class="fas fa-mountain fa-2x text-primary"></i>
                  </div>
                  <h6 class="fw-bold mb-1">Tagaytay</h6>
                  <p class="text-muted small mb-0">870 properties</p>
                </div>
              </div>
              <div class="col-6 col-md-4 col-lg-2">
                <div class="destination-card text-center">
                  <div class="destination-icon mb-2">
                    <i class="fas fa-tree fa-2x text-primary"></i>
                  </div>
                  <h6 class="fw-bold mb-1">Baguio</h6>
                  <p class="text-muted small mb-0">1,554 properties</p>
                </div>
              </div>
              <div class="col-6 col-md-4 col-lg-2">
                <div class="destination-card text-center">
                  <div class="destination-icon mb-2">
                    <i class="fas fa-water fa-2x text-primary"></i>
                  </div>
                  <h6 class="fw-bold mb-1">Boracay</h6>
                  <p class="text-muted small mb-0">505 properties</p>
                </div>
              </div>
              <div class="col-6 col-md-4 col-lg-2">
                <div class="destination-card text-center">
                  <div class="destination-icon mb-2">
                    <i class="fas fa-building fa-2x text-primary"></i>
                  </div>
                  <h6 class="fw-bold mb-1">Davao City</h6>
                  <p class="text-muted small mb-0">1,531 properties</p>
                </div>
              </div>
            </div>

            <div class="text-center mt-4">
              <a href="index.php?action=destinations" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-compass me-1"></i> Explore More Destinations
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Quick and Easy Trip Planner (From Image) -->
<section class="py-4 bg-light">
  <div class="container">
    <div class="row mb-4">
      <div class="col-12">
        <h2 class="h4 fw-bold mb-3">Quick and easy trip planner</h2>
        <p class="text-muted mb-4">Pick a vibe and explore the top destinations</p>

        <!-- Vibe Categories -->
        <div class="row g-2 mb-4">
          <div class="col-auto">
            <a href="#" class="btn btn-sm btn-outline-primary">Cultural Exploration</a>
          </div>
          <div class="col-auto">
            <a href="#" class="btn btn-sm btn-outline-primary">City Tours</a>
          </div>
          <div class="col-auto">
            <a href="#" class="btn btn-sm btn-outline-primary">Festivals</a>
          </div>
          <div class="col-auto">
            <a href="#" class="btn btn-sm btn-outline-primary">Adventures & Water</a>
          </div>
          <div class="col-auto">
            <a href="#" class="btn btn-sm btn-outline-primary">Beach Getaways</a>
          </div>
          <div class="col-auto">
            <a href="#" class="btn btn-sm btn-outline-primary">Wellness</a>
          </div>
          <div class="col-auto">
            <a href="#" class="btn btn-sm btn-outline-primary">Photography</a>
          </div>
          <div class="col-auto">
            <a href="#" class="btn btn-sm btn-outline-primary">Island Exploration</a>
          </div>
        </div>

        <!-- Destination Distances -->
        <div class="row g-3">
          <?php
          $destinations = [
            ['name' => 'Cebu City', 'distance' => '3.2 km away', 'icon' => 'city'],
            ['name' => 'Bacolod', 'distance' => '110 km away', 'icon' => 'landmark'],
            ['name' => 'Siquijor', 'distance' => '132 km away', 'icon' => 'island'],
            ['name' => 'Ilolo City', 'distance' => '151 km away', 'icon' => 'city'],
            ['name' => 'Davao City', 'distance' => '410 km away', 'icon' => 'building'],
            ['name' => 'Manila', 'distance' => '570 km away', 'icon' => 'metropolis']
          ];
          ?>

          <?php foreach ($destinations as $destination): ?>
            <div class="col-6 col-md-4 col-lg-2">
              <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-3">
                  <div class="mb-2">
                    <i class="fas fa-<?php echo $destination['icon']; ?> fa-2x text-primary"></i>
                  </div>
                  <h6 class="fw-bold mb-1"><?php echo $destination['name']; ?></h6>
                  <p class="text-muted small mb-0"><?php echo $destination['distance']; ?></p>
                  <a href="#" class="btn btn-link btn-sm mt-2">Explore</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
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

<!-- Weekend Deals Section (From Image) -->
<section class="py-4">
  <div class="container">
    <h2 class="h4 fw-bold mb-3">Deals for the weekend</h2>
    <p class="text-muted mb-4">Save on trips for <?php echo date('d F', strtotime('next Friday')); ?> - <?php echo date('d F', strtotime('next Sunday')); ?></p>

    <div class="row g-3">
      <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h6 class="fw-bold mb-0">Kaua Beach Park Capital</h6>
              <span class="badge bg-warning">Luxury Resort</span>
            </div>
            <p class="text-muted small mb-3">Experience premium beachfront living</p>
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <span class="h5 text-primary fw-bold">₱2,800</span>
                <span class="text-muted small">for 2 nights</span>
              </div>
              <a href="#" class="btn btn-primary btn-sm">View More</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h6 class="fw-bold mb-0">Pearl Resort Mactan</h6>
              <span class="badge bg-warning">Beachfront Resort</span>
            </div>
            <p class="text-muted small mb-3">Luxury meets nature at this beautiful resort</p>
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <span class="h5 text-primary fw-bold">₱3,200</span>
                <span class="text-muted small">for 2 nights</span>
              </div>
              <a href="#" class="btn btn-primary btn-sm">View More</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h6 class="fw-bold mb-0">Private Villas Collection</h6>
              <span class="badge bg-warning">Private Villa</span>
            </div>
            <p class="text-muted small mb-3">Exclusive villas with private amenities</p>
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <span class="h5 text-primary fw-bold">₱4,500</span>
                <span class="text-muted small">for 2 nights</span>
              </div>
              <a href="#" class="btn btn-primary btn-sm">View More</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

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

<!-- Explore Our Rooms Section with Carousel and Grid Fallback -->
<section class="py-4 bg-light">
  <div class="container">
    <div class="row mb-3">
      <div class="col text-center">
        <h2 class="h4 fw-bold mb-2">Explore Our Rooms</h2>
        <p class="text-muted small">
          A visual preview of our premium accommodations
        </p>
      </div>
    </div>

    <?php
    // Get carousel images from available rooms or use default images
    $carouselImages = isset($carouselImages) ? $carouselImages : [];

    // If no carousel images are provided, create from available rooms
    if (empty($carouselImages) && !empty($availableRooms)) {
        foreach ($availableRooms as $room) {
            if ($room['type'] !== 'Common / Background' && !empty($room['images']['primary'])) {
                $carouselImages[] = $room['images']['primary'];
            }
            // Limit to 5 images for carousel
            if (count($carouselImages) >= 5) break;
        }
    }

    // If still no images, use default hotel room images
    if (empty($carouselImages)) {
        $carouselImages = [
            'https://images.unsplash.com/photo-1611892440504-42a792e24d32?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1582719508461-905c673771fd?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1591088398332-8a7791972843?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'
        ];
    }
    ?>

    <?php if (count($carouselImages) > 1): ?>
      <!-- Carousel for multiple images -->
      <div id="roomCarousel" class="carousel slide shadow-sm rounded overflow-hidden" data-bs-ride="carousel">
        <!-- Indicators -->
        <div class="carousel-indicators">
          <?php foreach ($carouselImages as $index => $img): ?>
            <button type="button"
                    data-bs-target="#roomCarousel"
                    data-bs-slide-to="<?php echo $index; ?>"
                    class="<?php echo $index === 0 ? 'active' : ''; ?>"
                    aria-label="Slide <?php echo $index + 1; ?>">
            </button>
          <?php endforeach; ?>
        </div>

        <!-- Slides -->
        <div class="carousel-inner">
          <?php foreach ($carouselImages as $index => $img): ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
              <img
                src="<?php echo htmlspecialchars($img); ?>"
                class="d-block w-100 carousel-img"
                alt="Room Image <?php echo $index + 1; ?>"
                style="height: 400px; object-fit: cover;">
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    <?php else: ?>
      <!-- Grid Layout Fallback if only one image or carousel fails -->
      <div class="row g-3 image-grid-4">
        <?php
        // Use the first 4 images from carouselImages or available rooms
        $gridImages = array_slice($carouselImages, 0, 4);
        foreach ($gridImages as $index => $img): ?>
          <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
              <img src="<?php echo htmlspecialchars($img); ?>"
                   class="card-img-top"
                   alt="Room Preview <?php echo $index + 1; ?>"
                   style="height: 200px; object-fit: cover;">
              <div class="card-body">
                <h6 class="fw-bold mb-1">Premium Room <?php echo $index + 1; ?></h6>
                <p class="text-muted small mb-0">Luxury accommodations</p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- Homes Guests Love (From Image) -->
<section class="py-4 bg-light">
  <div class="container">
    <h2 class="h4 fw-bold mb-3">Homes guests love</h2>
    <p class="text-muted mb-4">Discover unique homes and apartments</p>

    <div class="row g-3">
      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
          <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
            class="card-img-top" alt="Apartment" style="height: 150px; object-fit: cover;">
          <div class="card-body">
            <h6 class="fw-bold mb-1">Apartment in Madai</h6>
            <p class="text-muted small mb-2">Modern 2-bedroom apartment</p>
            <div class="d-flex justify-content-between align-items-center">
              <span class="badge bg-light text-dark">
                <i class="fas fa-bed me-1"></i> 2 Bedrooms
              </span>
              <a href="#" class="btn btn-link btn-sm">View More</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
          <img src="https://images.unsplash.com/photo-1518780664697-55e3ad937233?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
            class="card-img-top" alt="House" style="height: 150px; object-fit: cover;">
          <div class="card-body">
            <h6 class="fw-bold mb-1">Gema Ledes Villa</h6>
            <p class="text-muted small mb-2">Spacious 3-bedroom villa</p>
            <div class="d-flex justify-content-between align-items-center">
              <span class="badge bg-light text-dark">
                <i class="fas fa-bed me-1"></i> 3 Bedrooms
              </span>
              <a href="#" class="btn btn-link btn-sm">View More</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
          <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
            class="card-img-top" alt="Penthouse" style="height: 150px; object-fit: cover;">
          <div class="card-body">
            <h6 class="fw-bold mb-1">Thomas Apartments</h6>
            <p class="text-muted small mb-2">Luxury 2-bedroom penthouse</p>
            <div class="d-flex justify-content-between align-items-center">
              <span class="badge bg-light text-dark">
                <i class="fas fa-bed me-1"></i> 2 Bedrooms
              </span>
              <a href="#" class="btn btn-link btn-sm">View More</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-start mb-2">
              <div class="me-3">
                <i class="fas fa-home fa-2x text-primary"></i>
              </div>
              <div>
                <h6 class="fw-bold mb-0">Hana Florentine Villa</h6>
                <p class="text-muted small mb-2">Elegant 2-bedroom villa with garden</p>
              </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="badge bg-light text-dark">
                <i class="fas fa-bed me-1"></i> 2 Bedrooms
              </span>
              <span class="badge bg-light text-dark">
                <i class="fas fa-bath me-1"></i> 2 Bathrooms
              </span>
            </div>
            <div class="border-top pt-2">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <div class="h5 text-primary fw-bold">₱9,452</div>
                  <div class="text-muted small">Total price</div>
                </div>
                <div class="text-end">
                  <div class="h6 fw-bold">₱4,611</div>
                  <div class="text-muted small">Per night</div>
                </div>
              </div>
              <a href="#" class="btn btn-primary btn-sm w-100 mt-2">Book Now</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Why Choose Us Section (From Booking.com style) -->
<section class="py-4">
  <div class="container">
    <h2 class="h4 fw-bold text-center mb-4">Why Choose Our Platform?</h2>

    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="text-center p-3">
          <div class="mb-3">
            <i class="fas fa-calendar-times fa-3x text-primary"></i>
          </div>
          <h5 class="h6 fw-bold mb-2">Free Cancellation</h5>
          <p class="small text-muted mb-0">Book now, pay at the property with FREE cancellation on most rooms</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="text-center p-3">
          <div class="mb-3">
            <i class="fas fa-star fa-3x text-primary"></i>
          </div>
          <h5 class="h6 fw-bold mb-2">Verified Reviews</h5>
          <p class="small text-muted mb-0">Thousands of verified reviews from fellow travellers</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="text-center p-3">
          <div class="mb-3">
            <i class="fas fa-globe-asia fa-3x text-primary"></i>
          </div>
          <h5 class="h6 fw-bold mb-2">Wide Selection</h5>
          <p class="small text-muted mb-0">Hotels, resorts, apartments, villas, and unique properties worldwide</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="text-center p-3">
          <div class="mb-3">
            <i class="fas fa-headset fa-3x text-primary"></i>
          </div>
          <h5 class="h6 fw-bold mb-2">24/7 Support</h5>
          <p class="small text-muted mb-0">Trusted customer service you can rely on, available around the clock</p>
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

  .destination-card {
    padding: 15px;
    border-radius: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
  }

  .destination-card:hover {
    background-color: #f8f9fa;
    transform: translateY(-5px);
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

  .btn-outline-primary {
    color: #0d3b66;
    border-color: #0d3b66;
  }

  .btn-outline-primary:hover {
    background-color: #0d3b66;
    border-color: #0d3b66;
  }

  .carousel-img {
    height: 400px;
    object-fit: cover;
    border-radius: 8px;
  }

  .carousel {
    border-radius: 8px;
    overflow: hidden;
  }

  .carousel-indicators button {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin: 0 5px;
  }

  .image-grid-4 .card {
    transition: transform 0.3s ease;
  }

  .image-grid-4 .card:hover {
    transform: translateY(-5px);
  }
</style>
