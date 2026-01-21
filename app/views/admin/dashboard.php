<?php
// app/views/admin/dashboard.php
?>

<div class="container-fluid px-4">
  <!-- Page Header -->
  <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4">
    <div class="mb-3 mb-md-0">
      <h1 class="h3 mb-1 text-gray-800">Dashboard</h1>
      <p class="text-muted mb-0">Welcome back, <?php echo htmlspecialchars($_SESSION['first_name'] ?? 'Admin'); ?>!</p>
      <small class="text-muted">
        <i class="fas fa-calendar-alt me-1"></i>
        <?php echo date('l, F j, Y'); ?>
      </small>
    </div>
    <div class="d-flex flex-wrap gap-2">
      <a href="index.php?action=admin/reports" class="btn btn-primary d-inline-flex align-items-center">
        <i class="fas fa-download me-2"></i> Generate Reports
      </a>
      <a href="index.php?action=admin/reservations&sub_action=create" class="btn btn-success d-inline-flex align-items-center">
        <i class="fas fa-plus-circle me-2"></i> New Reservation
      </a>
    </div>
  </div>

  <!-- Alerts/Notifications -->
  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
      <i class="fas fa-check-circle me-2"></i>
      <div class="flex-grow-1">
        <?php echo $_SESSION['success'];
        unset($_SESSION['success']); ?>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- Statistics Cards -->
  <div class="row mb-4">
    <!-- Total Revenue Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-start-primary border-3 border-top-0 border-end-0 border-bottom-0 shadow h-100">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col">
              <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                Total Revenue (Monthly)
              </div>
              <div class="d-flex align-items-baseline">
                <div class="h5 mb-0 fw-bold text-gray-800">$<?php echo number_format($stats['monthly_revenue'] ?? 0, 2); ?></div>
                <?php if (isset($stats['revenue_change']) && $stats['revenue_change'] != 0): ?>
                  <small class="ms-2 <?php echo $stats['revenue_change'] > 0 ? 'text-success' : 'text-danger'; ?>">
                    <i class="fas fa-<?php echo $stats['revenue_change'] > 0 ? 'arrow-up' : 'arrow-down'; ?> me-1"></i>
                    <?php echo abs($stats['revenue_change']); ?>%
                  </small>
                <?php endif; ?>
              </div>
              <div class="mt-2 mb-0 text-muted small">
                <i class="fas fa-calendar me-1"></i>
                <?php echo date('F Y'); ?>
              </div>
            </div>
            <div class="col-auto">
              <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                <i class="fas fa-dollar-sign fa-2x text-primary"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Reservations Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-start-success border-3 border-top-0 border-end-0 border-bottom-0 shadow h-100">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col">
              <div class="text-xs fw-bold text-success text-uppercase mb-1">
                Active Reservations
              </div>
              <div class="d-flex align-items-baseline">
                <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $stats['active_reservations'] ?? 0; ?></div>
                <?php if (isset($stats['active_change']) && $stats['active_change'] != 0): ?>
                  <small class="ms-2 <?php echo $stats['active_change'] > 0 ? 'text-success' : 'text-danger'; ?>">
                    <i class="fas fa-<?php echo $stats['active_change'] > 0 ? 'arrow-up' : 'arrow-down'; ?> me-1"></i>
                    <?php echo abs($stats['active_change']); ?>%
                  </small>
                <?php endif; ?>
              </div>
              <div class="mt-2 mb-0 text-muted small">
                <i class="fas fa-bed me-1"></i>
                <?php echo $stats['occupied_rooms'] ?? 0; ?> rooms occupied
              </div>
            </div>
            <div class="col-auto">
              <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                <i class="fas fa-calendar-check fa-2x text-success"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pending Reservations Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-start-warning border-3 border-top-0 border-end-0 border-bottom-0 shadow h-100">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col">
              <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                Pending Reservations
              </div>
              <div class="d-flex align-items-baseline">
                <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $stats['pending_reservations'] ?? 0; ?></div>
                <?php if (isset($stats['pending_change']) && $stats['pending_change'] != 0): ?>
                  <small class="ms-2 <?php echo $stats['pending_change'] > 0 ? 'text-warning' : 'text-success'; ?>">
                    <i class="fas fa-<?php echo $stats['pending_change'] > 0 ? 'arrow-up' : 'arrow-down'; ?> me-1"></i>
                    <?php echo abs($stats['pending_change']); ?>%
                  </small>
                <?php endif; ?>
              </div>
              <div class="mt-2 mb-0 text-muted small">
                <i class="fas fa-hourglass-half me-1"></i>
                Requires action
              </div>
            </div>
            <div class="col-auto">
              <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                <i class="fas fa-clock fa-2x text-warning"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Customers Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-start-info border-3 border-top-0 border-end-0 border-bottom-0 shadow h-100">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col">
              <div class="text-xs fw-bold text-info text-uppercase mb-1">
                Total Customers
              </div>
              <div class="d-flex align-items-baseline">
                <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $stats['total_customers'] ?? 0; ?></div>
                <?php if (isset($stats['customers_change']) && $stats['customers_change'] != 0): ?>
                  <small class="ms-2 <?php echo $stats['customers_change'] > 0 ? 'text-success' : 'text-danger'; ?>">
                    <i class="fas fa-<?php echo $stats['customers_change'] > 0 ? 'arrow-up' : 'arrow-down'; ?> me-1"></i>
                    <?php echo abs($stats['customers_change']); ?>%
                  </small>
                <?php endif; ?>
              </div>
              <div class="mt-2 mb-0 text-muted small">
                <i class="fas fa-user-plus me-1"></i>
                <?php echo $stats['new_customers_month'] ?? 0; ?> new this month
              </div>
            </div>
            <div class="col-auto">
              <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                <i class="fas fa-users fa-2x text-info"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Today's Activity -->
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card shadow h-100">
        <div class="card-header bg-white py-3 d-flex align-items-center">
          <i class="fas fa-sign-in-alt text-primary me-2"></i>
          <h6 class="m-0 fw-bold text-primary">Today's Check-ins</h6>
        </div>
        <div class="card-body d-flex align-items-center justify-content-center">
          <div class="text-center">
            <div class="display-4 fw-bold text-primary mb-2"><?php echo $stats['today_checkins'] ?? 0; ?></div>
            <p class="text-muted mb-0">guests arriving today</p>
            <?php if (!empty($todayCheckins)): ?>
              <div class="mt-3">
                <small class="text-muted d-block">
                  <i class="fas fa-clock me-1"></i>
                  Next check-in:
                  <?php
                  $nextCheckin = reset($todayCheckins);
                  echo date('h:i A', strtotime($nextCheckin['check_in_time'] ?? '14:00'));
                  ?>
                </small>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <?php if ($stats['today_checkins'] > 0): ?>
          <div class="card-footer bg-white py-2">
            <a href="index.php?action=admin/reservations&status=checked_in&date_from=<?php echo date('Y-m-d'); ?>"
              class="small text-decoration-none">
              <i class="fas fa-eye me-1"></i> View arrivals
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow h-100">
        <div class="card-header bg-white py-3 d-flex align-items-center">
          <i class="fas fa-sign-out-alt text-primary me-2"></i>
          <h6 class="m-0 fw-bold text-primary">Today's Check-outs</h6>
        </div>
        <div class="card-body d-flex align-items-center justify-content-center">
          <div class="text-center">
            <div class="display-4 fw-bold text-primary mb-2"><?php echo $stats['today_checkouts'] ?? 0; ?></div>
            <p class="text-muted mb-0">guests departing today</p>
            <?php if (!empty($todayCheckouts)): ?>
              <div class="mt-3">
                <small class="text-muted d-block">
                  <i class="fas fa-clock me-1"></i>
                  Next check-out:
                  <?php
                  $nextCheckout = reset($todayCheckouts);
                  echo date('h:i A', strtotime($nextCheckout['check_out_time'] ?? '12:00'));
                  ?>
                </small>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <?php if ($stats['today_checkouts'] > 0): ?>
          <div class="card-footer bg-white py-2">
            <a href="index.php?action=admin/reservations&status=checked_in&date_to=<?php echo date('Y-m-d'); ?>"
              class="small text-decoration-none">
              <i class="fas fa-eye me-1"></i> View departures
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Main Content Row -->
  <div class="row">
    <!-- Recent Reservations -->
    <div class="col-xl-8 col-lg-7 mb-4">
      <div class="card shadow h-100">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 fw-bold text-primary d-flex align-items-center">
            <i class="fas fa-calendar-alt me-2"></i>Recent Reservations
          </h6>
          <div class="dropdown">
            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-filter me-1"></i> Filter
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="index.php?action=admin/reservations&status=pending">Pending Only</a></li>
              <li><a class="dropdown-item" href="index.php?action=admin/reservations&status=confirmed">Confirmed Only</a></li>
              <li><a class="dropdown-item" href="index.php?action=admin/reservations&status=checked_in">Checked In</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="index.php?action=admin/reservations">View All</a></li>
            </ul>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-sm">
              <thead class="table-light">
                <tr>
                  <th width="80">ID</th>
                  <th>Customer</th>
                  <th>Room</th>
                  <th>Dates</th>
                  <th>Status</th>
                  <th class="text-end">Amount</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($recentReservations)): ?>
                  <?php foreach ($recentReservations as $reservation): ?>
                    <?php
                    $status_badge = '';
                    $status_icon = '';
                    switch ($reservation['status']) {
                      case 'pending':
                        $status_badge = 'warning';
                        $status_icon = 'clock';
                        break;
                      case 'confirmed':
                        $status_badge = 'success';
                        $status_icon = 'check-circle';
                        break;
                      case 'checked_in':
                        $status_badge = 'info';
                        $status_icon = 'key';
                        break;
                      case 'completed':
                        $status_badge = 'primary';
                        $status_icon = 'flag-checkered';
                        break;
                      case 'cancelled':
                        $status_badge = 'danger';
                        $status_icon = 'times-circle';
                        break;
                      default:
                        $status_badge = 'secondary';
                        $status_icon = 'question-circle';
                        break;
                    }
                    $nights = floor((strtotime($reservation['check_out']) - strtotime($reservation['check_in'])) / (60 * 60 * 24));
                    ?>
                    <tr onclick="window.location='index.php?action=admin/reservations&sub_action=view&id=<?php echo $reservation['id']; ?>'" style="cursor: pointer;">
                      <td>
                        <span class="badge bg-light text-dark border">#<?php echo str_pad($reservation['id'], 4, '0', STR_PAD_LEFT); ?></span>
                      </td>
                      <td>
                        <div class="d-flex flex-column">
                          <strong><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?></strong>
                          <small class="text-muted"><?php echo htmlspecialchars($reservation['email'] ?? ''); ?></small>
                        </div>
                      </td>
                      <td>
                        <div class="d-flex flex-column">
                          <span class="fw-bold"><?php echo htmlspecialchars($reservation['room_number']); ?></span>
                          <small class="text-muted"><?php echo htmlspecialchars($reservation['room_type']); ?></small>
                        </div>
                      </td>
                      <td>
                        <div class="d-flex flex-column">
                          <small><?php echo date('M d', strtotime($reservation['check_in'])); ?></small>
                          <small class="text-muted"><?php echo $nights; ?> night<?php echo $nights != 1 ? 's' : ''; ?></small>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-<?php echo $status_badge; ?> d-inline-flex align-items-center">
                          <i class="fas fa-<?php echo $status_icon; ?> me-1"></i>
                          <?php echo ucfirst($reservation['status']); ?>
                        </span>
                      </td>
                      <td class="text-end">
                        <div class="d-flex flex-column align-items-end">
                          <strong class="text-success">$<?php echo number_format($reservation['total_amount'], 2); ?></strong>
                          <small class="text-muted"><?php echo $reservation['payment_status'] == 'paid' ? 'Paid' : 'Pending'; ?></small>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center py-4">
                      <div class="text-muted mb-2">
                        <i class="fas fa-calendar-times fa-2x"></i>
                      </div>
                      <p class="mb-0">No recent reservations</p>
                      <a href="index.php?action=admin/reservations&sub_action=create" class="btn btn-sm btn-primary mt-2">
                        <i class="fas fa-plus me-1"></i> Create Reservation
                      </a>
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer bg-white py-2">
          <a href="index.php?action=admin/reservations" class="small text-decoration-none d-flex align-items-center">
            View all reservations <i class="fas fa-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
    </div>

    <!-- Right Column -->
    <div class="col-xl-4 col-lg-5">
      <!-- Room Occupancy -->
      <div class="card shadow mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 fw-bold text-primary d-flex align-items-center">
            <i class="fas fa-bed me-2"></i>Room Occupancy
          </h6>
          <span class="badge bg-primary">
            <?php
            $totalRooms = 0;
            $occupiedRooms = 0;
            foreach ($roomOccupancy as $room) {
              $totalRooms += $room['total_rooms'];
              $occupiedRooms += $room['occupied'];
            }
            echo $totalRooms - $occupiedRooms . '/' . $totalRooms . ' available';
            ?>
          </span>
        </div>
        <div class="card-body">
          <?php if (!empty($roomOccupancy)): ?>
            <div class="mb-4">
              <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Overall Occupancy</span>
                <span class="fw-bold"><?php echo round(($occupiedRooms / $totalRooms) * 100, 1); ?>%</span>
              </div>
              <div class="progress" style="height: 10px;">
                <div class="progress-bar <?php
                                          $rate = ($occupiedRooms / $totalRooms) * 100;
                                          echo $rate > 80 ? 'bg-danger' : ($rate > 60 ? 'bg-warning' : 'bg-success');
                                          ?>" role="progressbar"
                  style="width: <?php echo min(100, $rate); ?>%"
                  aria-valuenow="<?php echo $rate; ?>"
                  aria-valuemin="0"
                  aria-valuemax="100"></div>
              </div>
            </div>

            <?php foreach ($roomOccupancy as $room): ?>
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                  <span class="fw-bold"><?php echo htmlspecialchars($room['type']); ?></span>
                  <span class="text-muted"><?php echo $room['available']; ?> of <?php echo $room['total_rooms']; ?> available</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="progress flex-grow-1 me-3" style="height: 8px;">
                    <div class="progress-bar <?php
                                              $rate = ($room['occupied'] / $room['total_rooms']) * 100;
                                              echo $rate > 80 ? 'bg-danger' : ($rate > 60 ? 'bg-warning' : 'bg-success');
                                              ?>" role="progressbar"
                      style="width: <?php echo min(100, $rate); ?>%"
                      aria-valuenow="<?php echo $rate; ?>"
                      aria-valuemin="0"
                      aria-valuemax="100"></div>
                  </div>
                  <small class="fw-bold"><?php echo round($rate, 1); ?>%</small>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-center py-4">
              <div class="text-muted mb-2">
                <i class="fas fa-bed fa-2x"></i>
              </div>
              <p class="mb-0">No room data available</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Recent Customers -->
      <?php if ($_SESSION['role'] == 'admin' && !empty($recentUsers)): ?>
        <div class="card shadow">
          <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary d-flex align-items-center">
              <i class="fas fa-user-plus me-2"></i>Recent Customers
            </h6>
            <span class="badge bg-info"><?php echo count($recentUsers); ?> new</span>
          </div>
          <div class="card-body p-0">
            <div class="list-group list-group-flush">
              <?php foreach ($recentUsers as $user): ?>
                <a href="index.php?action=admin/users&sub_action=edit&id=<?php echo $user['id']; ?>"
                  class="list-group-item list-group-item-action border-0 py-3 px-4">
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                      <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="fas fa-user text-muted"></i>
                      </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <div class="d-flex justify-content-between align-items-start">
                        <h6 class="mb-1"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h6>
                        <small class="text-muted"><?php echo date('M d', strtotime($user['created_at'])); ?></small>
                      </div>
                      <p class="mb-0 text-muted small"><?php echo htmlspecialchars($user['email']); ?></p>
                      <?php if ($user['total_bookings'] > 0): ?>
                        <small class="text-primary">
                          <i class="fas fa-calendar-check me-1"></i>
                          <?php echo $user['total_bookings']; ?> booking<?php echo $user['total_bookings'] != 1 ? 's' : ''; ?>
                        </small>
                      <?php endif; ?>
                    </div>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="card-footer bg-white py-2">
            <a href="index.php?action=admin/users" class="small text-decoration-none d-flex align-items-center">
              View all customers <i class="fas fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Revenue Chart -->
  <div class="row mt-4">
    <div class="col-12">
      <div class="card shadow">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 fw-bold text-primary d-flex align-items-center">
            <i class="fas fa-chart-line me-2"></i>Revenue Overview (Last 30 Days)
          </h6>
          <div class="dropdown">
            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-calendar me-1"></i> Period
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="?period=7">Last 7 days</a></li>
              <li><a class="dropdown-item" href="?period=30">Last 30 days</a></li>
              <li><a class="dropdown-item" href="?period=90">Last 90 days</a></li>
              <li><a class="dropdown-item" href="?period=365">Last year</a></li>
            </ul>
          </div>
        </div>
        <div class="card-body">
          <?php if (!empty($revenueData)): ?>
            <div class="chart-container" style="position: relative; height: 300px;">
              <canvas id="revenueChart"></canvas>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
              document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('revenueChart').getContext('2d');
                const revenueChart = new Chart(ctx, {
                  type: 'line',
                  data: {
                    labels: [<?php
                              foreach ($revenueData as $data) {
                                echo '"' . date('M d', strtotime($data['date'])) . '",';
                              }
                              ?>],
                    datasets: [{
                      label: 'Daily Revenue',
                      data: [<?php
                              foreach ($revenueData as $data) {
                                echo $data['daily_revenue'] . ',';
                              }
                              ?>],
                      borderColor: '#4e73df',
                      backgroundColor: 'rgba(78, 115, 223, 0.1)',
                      borderWidth: 2,
                      fill: true,
                      tension: 0.4,
                      pointBackgroundColor: '#4e73df',
                      pointBorderColor: '#ffffff',
                      pointBorderWidth: 2,
                      pointRadius: 4,
                      pointHoverRadius: 6
                    }]
                  },
                  options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                      tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                          label: function(context) {
                            return '$' + context.parsed.y.toFixed(2);
                          }
                        }
                      },
                      legend: {
                        display: false
                      }
                    },
                    scales: {
                      y: {
                        beginAtZero: true,
                        grid: {
                          drawBorder: false
                        },
                        ticks: {
                          callback: function(value) {
                            return '$' + value.toLocaleString();
                          }
                        }
                      },
                      x: {
                        grid: {
                          display: false
                        }
                      }
                    },
                    interaction: {
                      intersect: false,
                      mode: 'nearest'
                    }
                  }
                });
              });
            </script>
          <?php else: ?>
            <div class="text-center py-5">
              <div class="text-muted mb-3">
                <i class="fas fa-chart-bar fa-3x"></i>
              </div>
              <h5 class="text-muted">No revenue data available</h5>
              <p class="text-muted small">Revenue data will appear here once you have reservations</p>
            </div>
          <?php endif; ?>
        </div>
        <div class="card-footer bg-white">
          <div class="row text-center">
            <div class="col-md-3">
              <div class="text-muted small">Total Revenue</div>
              <div class="fw-bold text-success">$<?php echo number_format(array_sum(array_column($revenueData, 'daily_revenue')) ?? 0, 2); ?></div>
            </div>
            <div class="col-md-3">
              <div class="text-muted small">Average Daily</div>
              <div class="fw-bold text-primary">$<?php echo number_format((array_sum(array_column($revenueData, 'daily_revenue')) / count($revenueData)) ?? 0, 2); ?></div>
            </div>
            <div class="col-md-3">
              <div class="text-muted small">Highest Day</div>
              <div class="fw-bold text-warning">$<?php echo number_format(max(array_column($revenueData, 'daily_revenue')) ?? 0, 2); ?></div>
            </div>
            <div class="col-md-3">
              <div class="text-muted small">Reservations</div>
              <div class="fw-bold text-info"><?php echo $stats['reservations_month'] ?? 0; ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);

    // Make table rows clickable
    const tableRows = document.querySelectorAll('tr[onclick]');
    tableRows.forEach(row => {
      row.style.cursor = 'pointer';
      row.addEventListener('mouseenter', function() {
        this.style.backgroundColor = 'rgba(0, 123, 255, 0.05)';
      });
      row.addEventListener('mouseleave', function() {
        this.style.backgroundColor = '';
      });
    });
  });
</script>

<style>
  .card {
    border: none;
    border-radius: 0.5rem;
  }

  .card-header {
    border-bottom: 1px solid rgba(0, 0, 0, .125);
  }

  .border-start-primary {
    border-left-color: #4e73df !important;
  }

  .border-start-success {
    border-left-color: #1cc88a !important;
  }

  .border-start-warning {
    border-left-color: #f6c23e !important;
  }

  .border-start-info {
    border-left-color: #36b9cc !important;
  }

  .progress {
    border-radius: 0.375rem;
  }

  .list-group-item:hover {
    background-color: rgba(0, 123, 255, 0.05);
  }

  .table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
  }

  .bg-opacity-10 {
    --bs-bg-opacity: 0.1;
  }
</style>
