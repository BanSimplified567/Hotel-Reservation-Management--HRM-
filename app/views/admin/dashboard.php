<?php
// app/views/admin/dashboard.php
?>

<div class="container-fluid px-3">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h1 class="h5 mb-1 text-dark fw-bold">
        <i class="fas fa-tachometer-alt text-primary me-2"></i>Dashboard
      </h1>
      <small class="text-muted">
        Welcome, <?php echo htmlspecialchars($_SESSION['first_name'] ?? 'Admin'); ?> •
        <i class="fas fa-calendar-alt me-1"></i><?php echo date('M d, Y'); ?>
      </small>
    </div>
    <div>
      <a href="index.php?action=admin/reports" class="btn btn-primary btn-sm me-1">
        <i class="fas fa-download me-1"></i>Reports
      </a>
      <a href="index.php?action=admin/reservations&sub_action=create" class="btn btn-success btn-sm">
        <i class="fas fa-plus me-1"></i>New Booking
      </a>
    </div>
  </div>

  <!-- Alerts -->
  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show p-2 mb-3" role="alert">
      <i class="fas fa-check-circle me-2"></i>
      <small class="flex-grow-1"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></small>
      <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- Quick Stats -->
  <div class="row g-2 mb-3">
    <div class="col-md-3 col-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-2">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <small class="text-muted d-block mb-1">Monthly Revenue</small>
              <div class="d-flex align-items-baseline">
                <h6 class="mb-0 fw-bold text-dark">$<?php echo number_format($stats['monthly_revenue'] ?? 0, 0); ?></h6>
                <?php if (isset($stats['revenue_change']) && $stats['revenue_change'] != 0): ?>
                  <small class="ms-1 <?php echo $stats['revenue_change'] > 0 ? 'text-success' : 'text-danger'; ?>">
                    <i class="fas fa-<?php echo $stats['revenue_change'] > 0 ? 'arrow-up' : 'arrow-down'; ?>"></i>
                    <?php echo abs($stats['revenue_change']); ?>%
                  </small>
                <?php endif; ?>
              </div>
            </div>
            <div class="ps-2">
              <div class="bg-primary bg-opacity-10 p-2 rounded">
                <i class="fas fa-dollar-sign text-primary"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-2">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <small class="text-muted d-block mb-1">Active Bookings</small>
              <div class="d-flex align-items-baseline">
                <h6 class="mb-0 fw-bold text-dark"><?php echo $stats['active_reservations'] ?? 0; ?></h6>
                <?php if (isset($stats['active_change']) && $stats['active_change'] != 0): ?>
                  <small class="ms-1 <?php echo $stats['active_change'] > 0 ? 'text-success' : 'text-danger'; ?>">
                    <i class="fas fa-<?php echo $stats['active_change'] > 0 ? 'arrow-up' : 'arrow-down'; ?>"></i>
                    <?php echo abs($stats['active_change']); ?>%
                  </small>
                <?php endif; ?>
              </div>
              <small class="text-muted d-block mt-1"><?php echo $stats['occupied_rooms'] ?? 0; ?> rooms occupied</small>
            </div>
            <div class="ps-2">
              <div class="bg-success bg-opacity-10 p-2 rounded">
                <i class="fas fa-calendar-check text-success"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-2">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <small class="text-muted d-block mb-1">Pending</small>
              <div class="d-flex align-items-baseline">
                <h6 class="mb-0 fw-bold text-dark"><?php echo $stats['pending_reservations'] ?? 0; ?></h6>
                <?php if (isset($stats['pending_change']) && $stats['pending_change'] != 0): ?>
                  <small class="ms-1 <?php echo $stats['pending_change'] > 0 ? 'text-warning' : 'text-success'; ?>">
                    <i class="fas fa-<?php echo $stats['pending_change'] > 0 ? 'arrow-up' : 'arrow-down'; ?>"></i>
                    <?php echo abs($stats['pending_change']); ?>%
                  </small>
                <?php endif; ?>
              </div>
              <small class="text-muted d-block mt-1">Requires action</small>
            </div>
            <div class="ps-2">
              <div class="bg-warning bg-opacity-10 p-2 rounded">
                <i class="fas fa-clock text-warning"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-2">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <small class="text-muted d-block mb-1">Total Customers</small>
              <div class="d-flex align-items-baseline">
                <h6 class="mb-0 fw-bold text-dark"><?php echo $stats['total_customers'] ?? 0; ?></h6>
                <?php if (isset($stats['customers_change']) && $stats['customers_change'] != 0): ?>
                  <small class="ms-1 <?php echo $stats['customers_change'] > 0 ? 'text-success' : 'text-danger'; ?>">
                    <i class="fas fa-<?php echo $stats['customers_change'] > 0 ? 'arrow-up' : 'arrow-down'; ?>"></i>
                    <?php echo abs($stats['customers_change']); ?>%
                  </small>
                <?php endif; ?>
              </div>
              <small class="text-muted d-block mt-1">+<?php echo $stats['new_customers_month'] ?? 0; ?> this month</small>
            </div>
            <div class="ps-2">
              <div class="bg-info bg-opacity-10 p-2 rounded">
                <i class="fas fa-users text-info"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Today's Activity -->
  <div class="row g-2 mb-3">
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-white py-2 border-bottom">
          <h6 class="mb-0 text-dark">
            <i class="fas fa-sign-in-alt text-primary me-1"></i>Today's Check-ins
          </h6>
        </div>
        <div class="card-body p-3">
          <div class="text-center">
            <div class="display-6 fw-bold text-primary mb-1"><?php echo $stats['today_checkins'] ?? 0; ?></div>
            <small class="text-muted d-block mb-2">guests arriving today</small>
            <?php if ($stats['today_checkins'] > 0 && !empty($todayCheckins)): ?>
              <?php $nextCheckin = reset($todayCheckins); ?>
              <small class="text-muted d-block">
                <i class="fas fa-clock me-1"></i>
                Next: <?php echo date('h:i A', strtotime($nextCheckin['check_in_time'] ?? '14:00')); ?>
              </small>
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
      <div class="card shadow-sm h-100">
        <div class="card-header bg-white py-2 border-bottom">
          <h6 class="mb-0 text-dark">
            <i class="fas fa-sign-out-alt text-primary me-1"></i>Today's Check-outs
          </h6>
        </div>
        <div class="card-body p-3">
          <div class="text-center">
            <div class="display-6 fw-bold text-primary mb-1"><?php echo $stats['today_checkouts'] ?? 0; ?></div>
            <small class="text-muted d-block mb-2">guests departing today</small>
            <?php if ($stats['today_checkouts'] > 0 && !empty($todayCheckouts)): ?>
              <?php $nextCheckout = reset($todayCheckouts); ?>
              <small class="text-muted d-block">
                <i class="fas fa-clock me-1"></i>
                Next: <?php echo date('h:i A', strtotime($nextCheckout['check_out_time'] ?? '12:00')); ?>
              </small>
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

  <!-- Main Content -->
  <div class="row">
    <!-- Recent Reservations -->
    <div class="col-lg-8 mb-3">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-white py-2 border-bottom d-flex justify-content-between align-items-center">
          <h6 class="mb-0 text-dark">
            <i class="fas fa-calendar-alt text-primary me-1"></i>Recent Reservations
          </h6>
          <div class="dropdown">
            <button class="btn btn-outline-primary btn-sm dropdown-toggle py-1 px-2" type="button"
              data-bs-toggle="dropdown">
              <i class="fas fa-filter"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item small" href="index.php?action=admin/reservations&status=pending">Pending</a></li>
              <li><a class="dropdown-item small" href="index.php?action=admin/reservations&status=confirmed">Confirmed</a></li>
              <li><a class="dropdown-item small" href="index.php?action=admin/reservations&status=checked_in">Checked In</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item small" href="index.php?action=admin/reservations">View All</a></li>
            </ul>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
              <thead class="bg-light">
                <tr>
                  <th class="ps-3"><small>ID</small></th>
                  <th><small>Customer</small></th>
                  <th><small>Room</small></th>
                  <th><small>Dates</small></th>
                  <th><small>Status</small></th>
                  <th class="pe-3 text-end"><small>Amount</small></th>
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
                    <tr style="cursor: pointer;" onclick="window.location='index.php?action=admin/reservations&sub_action=view&id=<?php echo $reservation['id']; ?>'">
                      <td class="ps-3">
                        <small class="text-muted">#<?php echo str_pad($reservation['id'], 4, '0', STR_PAD_LEFT); ?></small>
                      </td>
                      <td>
                        <small class="d-block fw-medium"><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?></small>
                        <small class="text-muted d-block"><?php echo htmlspecialchars($reservation['email'] ?? ''); ?></small>
                      </td>
                      <td>
                        <small class="fw-medium d-block"><?php echo htmlspecialchars($reservation['room_number']); ?></small>
                        <small class="text-muted d-block"><?php echo htmlspecialchars($reservation['room_type']); ?></small>
                      </td>
                      <td>
                        <small class="d-block"><?php echo date('M d', strtotime($reservation['check_in'])); ?></small>
                        <small class="text-muted d-block"><?php echo $nights; ?> night<?php echo $nights != 1 ? 's' : ''; ?></small>
                      </td>
                      <td>
                        <span class="badge badge-status-<?php echo $reservation['status']; ?> py-1 px-2">
                          <i class="fas fa-<?php echo $status_icon; ?> me-1"></i>
                          <?php echo ucfirst($reservation['status']); ?>
                        </span>
                      </td>
                      <td class="pe-3 text-end">
                        <small class="fw-bold text-success d-block">$<?php echo number_format($reservation['total_amount'], 0); ?></small>
                        <small class="text-muted d-block"><?php echo $reservation['payment_status'] == 'paid' ? 'Paid' : 'Pending'; ?></small>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center py-4">
                      <i class="fas fa-calendar-times fa-lg text-muted mb-2"></i>
                      <p class="small text-muted mb-2">No recent reservations</p>
                      <a href="index.php?action=admin/reservations&sub_action=create" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Create Booking
                      </a>
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer bg-white py-2">
          <a href="index.php?action=admin/reservations" class="small text-decoration-none">
            <i class="fas fa-arrow-right me-1"></i>View all reservations
          </a>
        </div>
      </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
      <!-- Room Occupancy -->
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-white py-2 border-bottom d-flex justify-content-between align-items-center">
          <h6 class="mb-0 text-dark">
            <i class="fas fa-bed text-primary me-1"></i>Room Occupancy
          </h6>
          <span class="badge bg-primary">
            <?php
            $totalRooms = 0;
            $occupiedRooms = 0;
            foreach ($roomOccupancy as $room) {
              $totalRooms += $room['total_rooms'];
              $occupiedRooms += $room['occupied'];
            }
            echo $totalRooms - $occupiedRooms . '/' . $totalRooms . ' avail';
            ?>
          </span>
        </div>
        <div class="card-body">
          <?php if (!empty($roomOccupancy)): ?>
            <div class="mb-3">
              <div class="d-flex justify-content-between mb-1">
                <small class="text-muted">Overall</small>
                <small class="fw-bold"><?php echo round(($occupiedRooms / $totalRooms) * 100, 1); ?>%</small>
              </div>
              <div class="progress" style="height: 6px;">
                <div class="progress-bar <?php
                                          $rate = ($occupiedRooms / $totalRooms) * 100;
                                          echo $rate > 80 ? 'bg-danger' : ($rate > 60 ? 'bg-warning' : 'bg-success');
                                          ?>" role="progressbar"
                  style="width: <?php echo min(100, $rate); ?>%">
                </div>
              </div>
            </div>

            <?php foreach ($roomOccupancy as $room): ?>
              <div class="mb-2">
                <div class="d-flex justify-content-between mb-1">
                  <small class="fw-medium"><?php echo htmlspecialchars($room['type']); ?></small>
                  <small class="text-muted"><?php echo $room['available']; ?>/<?php echo $room['total_rooms']; ?></small>
                </div>
                <div class="d-flex align-items-center">
                  <div class="progress flex-grow-1 me-2" style="height: 4px;">
                    <div class="progress-bar <?php
                                              $rate = ($room['occupied'] / $room['total_rooms']) * 100;
                                              echo $rate > 80 ? 'bg-danger' : ($rate > 60 ? 'bg-warning' : 'bg-success');
                                              ?>" role="progressbar"
                      style="width: <?php echo min(100, $rate); ?>%">
                    </div>
                  </div>
                  <small class="fw-bold"><?php echo round($rate, 1); ?>%</small>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-center py-3">
              <i class="fas fa-bed fa-lg text-muted mb-2"></i>
              <p class="small text-muted mb-0">No room data available</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Recent Customers -->
      <?php if ($_SESSION['role'] == 'admin' && !empty($recentUsers)): ?>
        <div class="card shadow-sm">
          <div class="card-header bg-white py-2 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-dark">
              <i class="fas fa-user-plus text-primary me-1"></i>Recent Customers
            </h6>
            <span class="badge bg-info"><?php echo count($recentUsers); ?> new</span>
          </div>
          <div class="card-body p-0">
            <div class="list-group list-group-flush">
              <?php foreach ($recentUsers as $user): ?>
                <a href="index.php?action=admin/users&sub_action=edit&id=<?php echo $user['id']; ?>"
                  class="list-group-item list-group-item-action border-0 py-2 px-3">
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                      <div class="avatar-circle-sm bg-light">
                        <span class="avatar-text-sm">
                          <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                        </span>
                      </div>
                    </div>
                    <div class="flex-grow-1 ms-2">
                      <div class="d-flex justify-content-between align-items-start">
                        <small class="fw-medium d-block"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></small>
                        <small class="text-muted"><?php echo date('M d', strtotime($user['created_at'])); ?></small>
                      </div>
                      <small class="text-muted d-block"><?php echo htmlspecialchars($user['email']); ?></small>
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
            <a href="index.php?action=admin/users" class="small text-decoration-none">
              <i class="fas fa-arrow-right me-1"></i>View all customers
            </a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Revenue Chart -->
  <div class="row mt-3">
    <div class="col-12">
      <div class="card shadow-sm">
        <div class="card-header bg-white py-2 border-bottom d-flex justify-content-between align-items-center">
          <h6 class="mb-0 text-dark">
            <i class="fas fa-chart-line text-primary me-1"></i>Revenue (30 Days)
          </h6>
          <div class="dropdown">
            <button class="btn btn-outline-primary btn-sm dropdown-toggle py-1 px-2" type="button"
              data-bs-toggle="dropdown">
              <i class="fas fa-calendar"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item small" href="?period=7">7 days</a></li>
              <li><a class="dropdown-item small" href="?period=30">30 days</a></li>
              <li><a class="dropdown-item small" href="?period=90">90 days</a></li>
            </ul>
          </div>
        </div>
        <div class="card-body p-2">
          <?php if (!empty($revenueData)): ?>
            <div style="height: 200px;">
              <canvas id="revenueChart"></canvas>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
              document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('revenueChart').getContext('2d');
                new Chart(ctx, {
                  type: 'line',
                  data: {
                    labels: [<?php
                              foreach ($revenueData as $data) {
                                echo '"' . date('M d', strtotime($data['date'])) . '",';
                              }
                              ?>],
                    datasets: [{
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
                      pointRadius: 2,
                      pointHoverRadius: 4
                    }]
                  },
                  options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                      y: {
                        beginAtZero: true,
                        ticks: { callback: v => '$' + v },
                        grid: { drawBorder: false }
                      },
                      x: { grid: { display: false } }
                    }
                  }
                });
              });
            </script>
          <?php else: ?>
            <div class="text-center py-4">
              <i class="fas fa-chart-bar fa-lg text-muted mb-2"></i>
              <p class="small text-muted mb-0">No revenue data available</p>
            </div>
          <?php endif; ?>
        </div>
        <div class="card-footer bg-white py-2">
          <div class="row text-center g-2">
            <div class="col-3">
              <small class="text-muted d-block">Total</small>
              <small class="fw-bold text-success">$<?php echo number_format(array_sum(array_column($revenueData, 'daily_revenue')) ?? 0, 0); ?></small>
            </div>
            <div class="col-3">
              <small class="text-muted d-block">Avg/Day</small>
              <small class="fw-bold text-primary">$<?php echo number_format((array_sum(array_column($revenueData, 'daily_revenue')) / count($revenueData)) ?? 0, 0); ?></small>
            </div>
            <div class="col-3">
              <small class="text-muted d-block">Peak</small>
              <small class="fw-bold text-warning">$<?php echo number_format(max(array_column($revenueData, 'daily_revenue')) ?? 0, 0); ?></small>
            </div>
            <div class="col-3">
              <small class="text-muted d-block">Bookings</small>
              <small class="fw-bold text-info"><?php echo $stats['reservations_month'] ?? 0; ?></small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Compact Styles */
  .container-fluid {
    max-width: 1200px;
    margin: 0 auto;
  }

  .avatar-circle-sm {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #e9ecef;
  }

  .avatar-text-sm {
    font-size: 12px;
    font-weight: bold;
    color: #6c757d;
  }

  /* Badge Styles */
  .badge-status-pending {
    background-color: rgba(255, 193, 7, 0.08);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
    font-size: 11px;
  }

  .badge-status-confirmed {
    background-color: rgba(25, 135, 84, 0.08);
    color: #198754;
    border: 1px solid rgba(25, 135, 84, 0.2);
    font-size: 11px;
  }

  .badge-status-checked_in {
    background-color: rgba(13, 110, 253, 0.08);
    color: #0d6efd;
    border: 1px solid rgba(13, 110, 253, 0.2);
    font-size: 11px;
  }

  .badge-status-completed {
    background-color: rgba(111, 66, 193, 0.08);
    color: #6f42c1;
    border: 1px solid rgba(111, 66, 193, 0.2);
    font-size: 11px;
  }

  .badge-status-cancelled {
    background-color: rgba(220, 53, 69, 0.08);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.2);
    font-size: 11px;
  }

  .btn-close-sm {
    padding: 0.25rem;
    font-size: 0.75rem;
  }

  /* Smaller table */
  .table-sm th,
  .table-sm td {
    padding: 0.5rem;
    font-size: 0.875rem;
  }

  /* Compact card headers */
  .card-header {
    padding: 0.5rem 1rem;
  }

  .card-body {
    padding: 1rem;
  }

  /* Smaller buttons */
  .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
  }

  /* Progress bars */
  .progress {
    border-radius: 2px;
  }

  /* List group items */
  .list-group-item {
    padding: 0.5rem;
  }

  .bg-opacity-10 {
    --bs-bg-opacity: 0.1;
  }

  /* Hover effects */
  tr:hover {
    background-color: rgba(0, 123, 255, 0.02) !important;
  }

  .list-group-item:hover {
    background-color: rgba(0, 123, 255, 0.02);
  }
</style>

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
  });
</script>
