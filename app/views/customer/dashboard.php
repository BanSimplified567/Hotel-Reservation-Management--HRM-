<?php
// app/views/dashboard.php - Hotel Reservation Customer Dashboard
// Note: Header and footer are automatically included by BaseController
?>

<div class="container-fluid">
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $title ?? 'Hotel Dashboard'; ?></h1>
    <a href="index.php?action=book-room" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
      <i class="bi bi-calendar-plus me-1"></i> Book a Room
    </a>
  </div>

  <!-- Display success/error messages -->
  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['success'];
      unset($_SESSION['success']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['error'];
      unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- Welcome Card -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-left-primary shadow">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Welcome Back, <?php echo htmlspecialchars($user['first_name'] ?? 'Guest'); ?>!
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                Hotel Reservation System Dashboard
              </div>
              <div class="text-muted">
                <i class="bi bi-calendar me-1"></i> <?php echo date('l, F j, Y'); ?>
                <span class="mx-2">|</span>
                <i class="bi bi-clock me-1"></i> <?php echo date('h:i A'); ?>
                <?php if (!empty($user['membership_tier'])): ?>
                  <span class="mx-2">|</span>
                  <i class="bi bi-award me-1"></i> <?php echo htmlspecialchars($user['membership_tier']); ?> Member
                <?php endif; ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-building fs-1 text-primary"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Stats Cards Row 1 -->
  <div class="row">
    <!-- Total Reservations -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Total Reservations
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo $reservation_stats['total'] ?? 0; ?>
              </div>
              <div class="mt-2 mb-0 text-muted text-xs">
                <span class="text-success mr-2">
                  <i class="bi bi-check-circle"></i> <?php echo $reservation_stats['completed'] ?? 0; ?> completed
                </span>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-calendar-check fs-2 text-primary"></i>
            </div>
          </div>
          <div class="mt-3">
            <div class="progress progress-sm">
              <?php
              $total = $reservation_stats['total'] ?? 1;
              $completed_pct = ($reservation_stats['completed'] ?? 0) / $total * 100;
              ?>
              <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo min(100, $completed_pct); ?>%"></div>
            </div>
            <div class="small text-muted mt-1">
              <span class="text-success">● Completed</span>
              <span class="text-warning mx-2">● Upcoming</span>
              <span class="text-info">● Active</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Upcoming Stays -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Upcoming Stays
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo $reservation_stats['upcoming'] ?? 0; ?>
              </div>
              <div class="mt-2 mb-0 text-muted text-xs">
                <span class="text-warning mr-2">
                  <i class="bi bi-clock-history"></i> Next check-in:
                  <?php if (!empty($next_reservation)): ?>
                    <?php echo date('M d', strtotime($next_reservation['check_in'])); ?>
                  <?php else: ?>
                    No upcoming stays
                  <?php endif; ?>
                </span>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-clock-history fs-2 text-warning"></i>
            </div>
          </div>
          <div class="mt-3">
            <?php if (!empty($next_reservation)): ?>
              <div class="small text-muted">
                <strong><?php echo htmlspecialchars($next_reservation['room_type'] ?? 'Room'); ?></strong><br>
                <i class="bi bi-calendar me-1"></i>
                <?php echo date('M d', strtotime($next_reservation['check_in'])); ?> -
                <?php echo date('M d', strtotime($next_reservation['check_out'])); ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Loyalty Points -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Loyalty Points
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo number_format($user['loyalty_points'] ?? 0); ?>
              </div>
              <div class="mt-2 mb-0 text-muted text-xs">
                <span class="text-success mr-2">
                  <i class="bi bi-gift"></i> <?php echo ($user['loyalty_points'] ?? 0) / 100; ?> free nights available
                </span>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-award fs-2 text-success"></i>
            </div>
          </div>
          <div class="mt-3">
            <div class="progress progress-sm">
              <?php
              $points_needed = 1000; // Points needed for next tier
              $current_points = $user['loyalty_points'] ?? 0;
              $points_pct = min(100, ($current_points % $points_needed) / $points_needed * 100);
              ?>
              <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $points_pct; ?>%"></div>
            </div>
            <div class="small text-muted mt-1">
              <?php echo $points_needed - ($current_points % $points_needed); ?> points to next reward
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total Spent -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                Total Spent
              </div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                    $<?php echo number_format($total_spent ?? 0, 2); ?>
                  </div>
                </div>
                <div class="col">
                  <div class="progress progress-sm mr-2">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 65%"></div>
                  </div>
                </div>
              </div>
              <div class="mt-2 mb-0 text-muted text-xs">
                <span class="mr-2">This year: $<?php echo number_format($yearly_spent ?? 0, 2); ?></span>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-currency-dollar fs-2 text-info"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Stats Cards Row 2 -->
  <div class="row">
    <!-- Recent Bookings -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-secondary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                Recent Bookings
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo $reservation_stats['last_30_days'] ?? 0; ?>
              </div>
              <div class="mt-2 mb-0 text-muted text-xs">
                Last 30 days
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-calendar-week fs-2 text-secondary"></i>
            </div>
          </div>
          <div class="mt-3">
            <div class="small text-muted">
              Avg. booking value: $<?php echo number_format($avg_booking_value ?? 0, 2); ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Preferred Room Type -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-purple shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-purple text-uppercase mb-1">
                Preferred Room
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo htmlspecialchars($preferred_room['type'] ?? 'Not set'); ?>
              </div>
              <div class="mt-2 mb-0 text-muted text-xs">
                <?php if (!empty($preferred_room)): ?>
                  <span class="text-purple mr-2"><?php echo $preferred_room['count'] ?? 0; ?> bookings</span>
                <?php endif; ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-house-door fs-2 text-purple"></i>
            </div>
          </div>
          <div class="mt-3">
            <?php if (!empty($preferred_room)): ?>
              <div class="small text-muted">
                Last booked: <?php echo date('M d, Y', strtotime($preferred_room['last_booking'] ?? 'N/A')); ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Membership Status -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-gold shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-gold text-uppercase mb-1">
                Membership Tier
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo htmlspecialchars($user['membership_tier'] ?? 'Standard'); ?>
              </div>
              <div class="mt-2 mb-0 text-muted text-xs">
                <span class="text-gold mr-2">
                  <i class="bi bi-star"></i>
                  <?php
                  $tier = $user['membership_tier'] ?? 'Standard';
                  echo ($tier == 'Platinum' ? '★★★★★' : ($tier == 'Gold' ? '★★★★☆' : '★★★☆☆'));
                  ?>
                </span>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-stars fs-2 text-gold"></i>
            </div>
          </div>
          <div class="mt-3">
            <div class="small text-muted">
              Benefits: <?php echo $membership_benefits ?? 'Standard benefits'; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Quick Actions
              </div>
              <div class="h6 mb-2 text-gray-800">
                Hotel Services
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-lightning fs-2 text-primary"></i>
            </div>
          </div>
          <div class="mt-3">
            <div class="d-grid gap-2">
              <a href="index.php?action=book-room" class="btn btn-sm btn-primary">
                <i class="bi bi-calendar-plus me-1"></i> Book Room
              </a>
              <a href="index.php?action=my-reservations" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-list-ul me-1"></i> My Reservations
              </a>
              <a href="index.php?action=profile" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-person-circle me-1"></i> Edit Profile
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Reservations & Upcoming Stays -->
  <div class="row">
    <!-- Upcoming Stays -->
    <div class="col-lg-6 mb-4">
      <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-calendar-check me-2"></i>Upcoming Stays
          </h6>
          <a href="index.php?action=my-reservations" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body">
          <?php if (!empty($upcoming_reservations)): ?>
            <?php foreach ($upcoming_reservations as $reservation): ?>
              <div class="reservation-item mb-3 p-3 border rounded">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div>
                    <h6 class="mb-1"><?php echo htmlspecialchars($reservation['room_type']); ?> - Room <?php echo htmlspecialchars($reservation['room_number']); ?></h6>
                    <span class="badge bg-<?php
                      echo ($reservation['status'] == 'confirmed') ? 'success' :
                           (($reservation['status'] == 'pending') ? 'warning' :
                           (($reservation['status'] == 'checked_in') ? 'primary' : 'secondary'));
                    ?>">
                      <?php echo ucfirst($reservation['status']); ?>
                    </span>
                  </div>
                  <div class="text-end">
                    <div class="h6 mb-0">$<?php echo number_format($reservation['total_amount'], 2); ?></div>
                    <small class="text-muted"><?php echo $reservation['total_nights']; ?> nights</small>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <small class="text-muted d-block">
                      <i class="bi bi-calendar me-1"></i> Check-in: <?php echo date('M d, Y', strtotime($reservation['check_in'])); ?>
                    </small>
                  </div>
                  <div class="col-md-6">
                    <small class="text-muted d-block">
                      <i class="bi bi-calendar me-1"></i> Check-out: <?php echo date('M d, Y', strtotime($reservation['check_out'])); ?>
                    </small>
                  </div>
                </div>
                <div class="mt-2">
                  <div class="btn-group btn-group-sm" role="group">
                    <a href="index.php?action=view-reservation&id=<?php echo $reservation['id']; ?>" class="btn btn-outline-primary">
                      <i class="bi bi-eye me-1"></i> Details
                    </a>
                    <?php if ($reservation['status'] == 'confirmed' && strtotime($reservation['check_in']) > time()): ?>
                      <a href="index.php?action=cancel-reservation&id=<?php echo $reservation['id']; ?>" class="btn btn-outline-danger">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                      </a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-center py-4">
              <i class="bi bi-calendar-x text-muted display-6"></i>
              <p class="mt-2 text-muted">No upcoming stays</p>
              <a href="index.php?action=book-room" class="btn btn-sm btn-primary mt-2">
                <i class="bi bi-calendar-plus me-1"></i> Book a Room
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Recent Activity & Notifications -->
    <div class="col-lg-6 mb-4">
      <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-bell me-2"></i>Notifications & Offers
          </h6>
          <span class="badge bg-primary"><?php echo count($notifications ?? []); ?> new</span>
        </div>
        <div class="card-body">
          <div class="row">
            <!-- Special Offers -->
            <div class="col-md-6 mb-3 mb-md-0">
              <h6 class="border-bottom pb-2 mb-3">
                <i class="bi bi-tag text-success me-2"></i>Special Offers
              </h6>
              <?php if (!empty($special_offers)): ?>
                <?php foreach ($special_offers as $offer): ?>
                  <div class="offer-item mb-3 p-2 border rounded">
                    <div class="d-flex align-items-start">
                      <div class="flex-shrink-0">
                        <span class="badge bg-danger">-<?php echo $offer['discount']; ?>%</span>
                      </div>
                      <div class="flex-grow-1 ms-2">
                        <h6 class="mb-1"><?php echo htmlspecialchars($offer['title']); ?></h6>
                        <p class="small text-muted mb-2"><?php echo htmlspecialchars($offer['description']); ?></p>
                        <small class="text-muted">
                          <i class="bi bi-clock me-1"></i> Valid until <?php echo date('M d', strtotime($offer['valid_until'])); ?>
                        </small>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="text-center py-3">
                  <i class="bi bi-tag text-muted"></i>
                  <p class="small text-muted mt-2">No current offers</p>
                </div>
              <?php endif; ?>
            </div>

            <!-- Recent Notifications -->
            <div class="col-md-6">
              <h6 class="border-bottom pb-2 mb-3">
                <i class="bi bi-bell text-warning me-2"></i>Recent Notifications
              </h6>
              <div class="notification-list">
                <?php if (!empty($notifications)): ?>
                  <?php foreach ($notifications as $notification): ?>
                    <div class="notification-item mb-2 p-2 border-start border-3 border-<?php
                      echo ($notification['type'] == 'booking') ? 'success' :
                           (($notification['type'] == 'payment') ? 'info' :
                           (($notification['type'] == 'reminder') ? 'warning' : 'secondary'));
                    ?>">
                      <div class="d-flex justify-content-between align-items-start">
                        <div>
                          <h6 class="mb-1 small"><?php echo htmlspecialchars($notification['title']); ?></h6>
                          <p class="small text-muted mb-0"><?php echo htmlspecialchars($notification['message']); ?></p>
                        </div>
                        <small class="text-muted"><?php echo date('h:i A', strtotime($notification['created_at'])); ?></small>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div class="text-center py-3">
                    <i class="bi bi-bell-slash text-muted"></i>
                    <p class="small text-muted mt-2">No new notifications</p>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recommended Rooms & Statistics -->
  <div class="row">
    <div class="col-12">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-house-heart me-2"></i>Recommended For You
          </h6>
        </div>
        <div class="card-body">
          <div class="row">
            <?php if (!empty($recommended_rooms)): ?>
              <?php foreach ($recommended_rooms as $room): ?>
                <div class="col-md-3 mb-4">
                  <div class="card h-100 border shadow-sm">
                    <?php if (!empty($room['image'])): ?>
                      <img src="<?php echo htmlspecialchars($room['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($room['name']); ?>" style="height: 150px; object-fit: cover;">
                    <?php else: ?>
                      <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                        <i class="bi bi-image text-muted fs-1"></i>
                      </div>
                    <?php endif; ?>
                    <div class="card-body">
                      <h6 class="card-title"><?php echo htmlspecialchars($room['name']); ?></h6>
                      <p class="card-text small text-muted mb-2">
                        <i class="bi bi-person me-1"></i> Sleeps <?php echo $room['capacity']; ?><br>
                        <i class="bi bi-rulers me-1"></i> <?php echo $room['size']; ?> sq. ft.
                      </p>
                      <div class="d-flex justify-content-between align-items-center">
                        <span class="h6 mb-0 text-primary">$<?php echo number_format($room['price'], 2); ?>/night</span>
                        <a href="index.php?action=book-room&type=<?php echo $room['id']; ?>" class="btn btn-sm btn-outline-primary">
                          <i class="bi bi-calendar-plus"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="col-12 text-center py-4">
                <i class="bi bi-house text-muted display-6"></i>
                <p class="mt-2 text-muted">Loading recommendations...</p>
              </div>
            <?php endif; ?>
          </div>
          <div class="row mt-4">
            <div class="col-md-3 text-center mb-3">
              <div class="stat-circle bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                <i class="bi bi-moon-stars fs-3 text-white"></i>
              </div>
              <h4 class="mt-2 mb-0"><?php echo $reservation_stats['total_nights'] ?? 0; ?></h4>
              <p class="text-muted mb-0">Total Nights</p>
            </div>
            <div class="col-md-3 text-center mb-3">
              <div class="stat-circle bg-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                <i class="bi bi-star fs-3 text-white"></i>
              </div>
              <h4 class="mt-2 mb-0"><?php echo number_format($avg_rating ?? 0, 1); ?></h4>
              <p class="text-muted mb-0">Avg. Rating</p>
            </div>
            <div class="col-md-3 text-center mb-3">
              <div class="stat-circle bg-warning rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                <i class="bi bi-clock-history fs-3 text-white"></i>
              </div>
              <h4 class="mt-2 mb-0"><?php echo $reservation_stats['cancelled'] ?? 0; ?></h4>
              <p class="text-muted mb-0">Cancellations</p>
            </div>
            <div class="col-md-3 text-center mb-3">
              <div class="stat-circle bg-info rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                <i class="bi bi-arrow-repeat fs-3 text-white"></i>
              </div>
              <h4 class="mt-2 mb-0"><?php echo $repeat_rate ?? '0'; ?>%</h4>
              <p class="text-muted mb-0">Repeat Rate</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .border-left-purple {
    border-left: 0.25rem solid #6f42c1 !important;
  }

  .border-left-gold {
    border-left: 0.25rem solid #ffd700 !important;
  }

  .text-purple {
    color: #6f42c1 !important;
  }

  .text-gold {
    color: #ffd700 !important;
  }

  .reservation-item:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
    transition: all 0.2s;
  }

  .offer-item:hover {
    background-color: #e8f5e9;
  }

  .notification-item:hover {
    background-color: #f8f9fa;
  }

  .stat-circle {
    transition: transform 0.3s;
  }

  .stat-circle:hover {
    transform: scale(1.1);
  }

  .card.shadow-sm:hover {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
      var alerts = document.querySelectorAll('.alert');
      alerts.forEach(function(alert) {
        var bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);

    // Add animation to stats cards
    const statsCards = document.querySelectorAll('.card');
    statsCards.forEach((card, index) => {
      card.style.animationDelay = (index * 0.1) + 's';
    });
  });
</script>
