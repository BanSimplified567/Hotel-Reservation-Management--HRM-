
<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <div class="d-flex">
      <a href="index.php?action=admin/reports" class="btn btn-primary shadow-sm mr-2">
        <i class="fas fa-download fa-sm text-white-50"></i> Generate Reports
      </a>
      <a href="index.php?action=admin/reservations&sub_action=create" class="btn btn-success shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> New Reservation
      </a>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="row">
    <!-- Total Revenue Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Total Revenue (Monthly)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo number_format($stats['monthly_revenue'] ?? 0, 2); ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Reservations Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Active Reservations</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['active_reservations'] ?? 0; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pending Reservations Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Pending Reservations</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['pending_reservations'] ?? 0; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-clock fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Customers Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Customers
              </div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $stats['total_customers'] ?? 0; ?></div>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Check-in/Check-out Today -->
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Today's Check-ins</h6>
        </div>
        <div class="card-body">
          <div class="text-center">
            <h1 class="display-4"><?php echo $stats['today_checkins'] ?? 0; ?></h1>
            <p class="text-muted">guests checking in today</p>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Today's Check-outs</h6>
        </div>
        <div class="card-body">
          <div class="text-center">
            <h1 class="display-4"><?php echo $stats['today_checkouts'] ?? 0; ?></h1>
            <p class="text-muted">guests checking out today</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Reservations & Recent Users -->
  <div class="row">
    <!-- Recent Reservations -->
    <div class="col-xl-8 col-lg-7">
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Recent Reservations</h6>
          <a href="index.php?action=admin/reservations" class="btn btn-sm btn-primary">View All</a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Customer</th>
                  <th>Room</th>
                  <th>Check-in</th>
                  <th>Status</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($recentReservations)): ?>
                  <?php foreach ($recentReservations as $reservation): ?>
                    <tr>
                      <td>#<?php echo $reservation['id']; ?></td>
                      <td><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?></td>
                      <td><?php echo htmlspecialchars($reservation['room_number'] . ' (' . $reservation['room_type'] . ')'); ?></td>
                      <td><?php echo date('M d, Y', strtotime($reservation['check_in'])); ?></td>
                      <td>
                        <span class="badge badge-<?php
                                                  echo $reservation['status'] == 'confirmed' ? 'success' : ($reservation['status'] == 'pending' ? 'warning' : ($reservation['status'] == 'checked_in' ? 'info' : ($reservation['status'] == 'completed' ? 'primary' : 'secondary')));
                                                  ?>">
                          <?php echo ucfirst($reservation['status']); ?>
                        </span>
                      </td>
                      <td>$<?php echo number_format($reservation['total_amount'], 2); ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center">No recent reservations</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Room Occupancy -->
    <div class="col-xl-4 col-lg-5">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Room Occupancy</h6>
        </div>
        <div class="card-body">
          <?php if (!empty($roomOccupancy)): ?>
            <?php foreach ($roomOccupancy as $room): ?>
              <div class="mb-3">
                <div class="d-flex justify-content-between">
                  <span class="font-weight-bold"><?php echo htmlspecialchars($room['type']); ?></span>
                  <span class="text-muted"><?php echo $room['available']; ?> of <?php echo $room['total_rooms']; ?> available</span>
                </div>
                <div class="progress">
                  <div class="progress-bar bg-<?php
                                              $rate = ($room['occupied'] / $room['total_rooms']) * 100;
                                              echo $rate > 80 ? 'danger' : ($rate > 60 ? 'warning' : 'success');
                                              ?>" role="progressbar"
                    style="width: <?php echo min(100, ($room['occupied'] / $room['total_rooms']) * 100); ?>%"
                    aria-valuenow="<?php echo ($room['occupied'] / $room['total_rooms']) * 100; ?>"
                    aria-valuemin="0" aria-valuemax="100">
                    <?php echo round(($room['occupied'] / $room['total_rooms']) * 100, 1); ?>%
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-center text-muted">No room data available</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Recent Users (Admin Only) -->
      <?php if ($_SESSION['role'] == 'admin' && !empty($recentUsers)): ?>
        <div class="card shadow">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recent Customers</h6>
          </div>
          <div class="card-body">
            <div class="list-group">
              <?php foreach ($recentUsers as $user): ?>
                <a href="index.php?action=admin/users&sub_action=edit&id=<?php echo $user['id']; ?>"
                  class="list-group-item list-group-item-action">
                  <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h6>
                    <small><?php echo date('M d', strtotime($user['created_at'])); ?></small>
                  </div>
                  <p class="mb-1"><?php echo htmlspecialchars($user['email']); ?></p>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Revenue Chart -->
  <div class="row">
    <div class="col-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Revenue Overview (Last 30 Days)</h6>
        </div>
        <div class="card-body">
          <?php if (!empty($revenueData)): ?>
            <div class="chart-area">
              <canvas id="revenueChart"></canvas>
            </div>
            <script>
              document.addEventListener('DOMContentLoaded', function() {
                var ctx = document.getElementById('revenueChart').getContext('2d');
                var revenueChart = new Chart(ctx, {
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
                      backgroundColor: 'rgba(78, 115, 223, 0.05)',
                      fill: true,
                      tension: 0.4
                    }]
                  },
                  options: {
                    scales: {
                      y: {
                        beginAtZero: true,
                        ticks: {
                          callback: function(value) {
                            return '$' + value;
                          }
                        }
                      }
                    }
                  }
                });
              });
            </script>
          <?php else: ?>
            <p class="text-center text-muted">No revenue data available</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
