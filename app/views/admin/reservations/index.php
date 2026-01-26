<?php
// app/views/admin/reservations/index.php
?>

<div class="container-fluid px-3">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h1 class="h5 mb-1 text-dark fw-bold">
        <i class="fas fa-calendar-alt text-primary me-2"></i>Reservations
      </h1>
      <small class="text-muted">Manage hotel bookings</small>
    </div>
    <a href="index.php?action=admin/reservations&sub_action=create" class="btn btn-primary btn-sm">
      <i class="fas fa-plus me-1"></i> New Booking
    </a>
  </div>

  <!-- Alerts -->
  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show p-2 mb-3" role="alert">
      <i class="fas fa-check-circle me-2"></i>
      <small class="flex-grow-1"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></small>
      <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show p-2 mb-3" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i>
      <small class="flex-grow-1"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></small>
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
              <small class="text-muted d-block mb-1">Total</small>
              <h6 class="mb-0 fw-bold text-dark"><?php echo $totalReservations ?? '0'; ?></h6>
            </div>
            <div class="ps-2">
              <div class="bg-primary bg-opacity-10 p-2 rounded">
                <i class="fas fa-calendar-alt text-primary"></i>
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
              <h6 class="mb-0 fw-bold text-dark"><?php echo $pendingCount ?? '0'; ?></h6>
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
              <small class="text-muted d-block mb-1">Checked In</small>
              <h6 class="mb-0 fw-bold text-dark"><?php echo $checkedInCount ?? '0'; ?></h6>
            </div>
            <div class="ps-2">
              <div class="bg-success bg-opacity-10 p-2 rounded">
                <i class="fas fa-key text-success"></i>
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
              <small class="text-muted d-block mb-1">Today's Revenue</small>
              <h6 class="mb-0 fw-bold text-dark">₱<?php echo number_format($todayRevenue ?? 0, 0); ?></h6>
            </div>
            <div class="ps-2">
              <div class="bg-info bg-opacity-10 p-2 rounded">
                <i class="fas fa-money-bill text-info"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Search and Filter -->
  <div class="card shadow-sm mb-3">
    <div class="card-header bg-white py-2 border-bottom">
      <div class="d-flex justify-content-between align-items-center">
        <h6 class="mb-0 text-dark">
          <i class="fas fa-filter text-primary me-1"></i>Search & Filter
        </h6>
        <button class="btn btn-outline-secondary btn-sm py-1 px-2" type="button" data-bs-toggle="collapse"
          data-bs-target="#filterCollapse">
          <i class="fas fa-chevron-down"></i>
        </button>
      </div>
    </div>
    <div class="collapse show" id="filterCollapse">
      <div class="card-body p-3">
        <form method="GET" action="index.php" class="row g-2">
          <input type="hidden" name="action" value="admin/reservations">

          <div class="col-lg-3 col-md-6">
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
              <input type="text" class="form-control" name="search" placeholder="Search..."
                value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>
          </div>

          <div class="col-lg-2 col-md-6">
            <select class="form-control form-control-sm" name="status">
              <option value="">All Status</option>
              <option value="pending" <?php echo ($status ?? '') == 'pending' ? 'selected' : ''; ?>>Pending</option>
              <option value="confirmed" <?php echo ($status ?? '') == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
              <option value="checked_in" <?php echo ($status ?? '') == 'checked_in' ? 'selected' : ''; ?>>Checked In</option>
              <option value="completed" <?php echo ($status ?? '') == 'completed' ? 'selected' : ''; ?>>Completed</option>
              <option value="cancelled" <?php echo ($status ?? '') == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
            </select>
          </div>

          <div class="col-lg-2 col-md-6">
            <input type="date" class="form-control form-control-sm" name="date_from" placeholder="From"
              value="<?php echo htmlspecialchars($date_from ?? ''); ?>">
          </div>

          <div class="col-lg-2 col-md-6">
            <input type="date" class="form-control form-control-sm" name="date_to" placeholder="To"
              value="<?php echo htmlspecialchars($date_to ?? ''); ?>">
          </div>

          <div class="col-lg-3 col-md-6 d-flex">
            <button type="submit" class="btn btn-primary btn-sm flex-grow-1 me-1">
              <i class="fas fa-search me-1"></i> Search
            </button>
            <a href="index.php?action=admin/reservations" class="btn btn-outline-secondary btn-sm">
              <i class="fas fa-redo"></i>
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Reservations Table -->
  <div class="card shadow-sm">
    <div class="card-header bg-white py-2 border-bottom d-flex justify-content-between align-items-center">
      <div>
        <h6 class="mb-0 text-dark">
          <i class="fas fa-list text-primary me-1"></i>Bookings
        </h6>
        <small class="text-muted">Total: <?php echo $totalReservations ?? '0'; ?></small>
      </div>
      <div class="dropdown">
        <button class="btn btn-outline-primary btn-sm dropdown-toggle py-1 px-2" type="button"
          data-bs-toggle="dropdown">
          <i class="fas fa-download"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item small" href="#"><i class="fas fa-file-csv me-1"></i>CSV</a></li>
          <li><a class="dropdown-item small" href="#"><i class="fas fa-file-excel me-1"></i>Excel</a></li>
        </ul>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
          <thead class="bg-light">
            <tr>
              <th class="ps-3"><small>ID</small></th>
              <th><small>Guest</small></th>
              <th><small>Room</small></th>
              <th><small>Check-in</small></th>
              <th><small>Nights</small></th>
              <th><small>Status</small></th>
              <th class="pe-3"><small>Amount</small></th>
              <th class="pe-3 text-center"><small>Actions</small></th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($reservations)): ?>
              <tr>
                <td colspan="8" class="text-center py-4">
                  <i class="fas fa-calendar-times fa-lg text-muted mb-2"></i>
                  <p class="small text-muted mb-2">No reservations found</p>
                  <a href="index.php?action=admin/reservations&sub_action=create" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Create Booking
                  </a>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($reservations as $reservation): ?>
                <?php
                $nights = floor((strtotime($reservation['check_out']) - strtotime($reservation['check_in'])) / (60 * 60 * 24));
                $status_badge = '';
                $status_icon = '';

                switch ($reservation['status']) {
                  case 'pending':
                    $status_badge = 'warning';
                    $status_icon = 'clock';
                    break;
                  case 'confirmed':
                    $status_badge = 'info';
                    $status_icon = 'check-circle';
                    break;
                  case 'checked_in':
                    $status_badge = 'success';
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
                ?>
                <tr onclick="window.location='index.php?action=admin/reservations&sub_action=view&id=<?php echo $reservation['id']; ?>'" style="cursor: pointer;">
                  <td class="ps-3">
                    <small class="text-muted">#<?php echo str_pad($reservation['id'], 4, '0', STR_PAD_LEFT); ?></small>
                  </td>
                  <td>
                    <small class="d-block fw-medium">
                      <?php
                      if ($reservation['user_id']) {
                        echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']);
                      } else {
                        echo htmlspecialchars(($reservation['guest_first_name'] ?? '') . ' ' . ($reservation['guest_last_name'] ?? ''));
                      }
                      ?>
                    </small>
                    <small class="text-muted d-block">
                      <?php
                      $email = $reservation['user_id'] ? $reservation['email'] : $reservation['guest_email'];
                      echo htmlspecialchars($email ?? '');
                      ?>
                    </small>
                  </td>
                  <td>
                    <small class="fw-medium d-block"><?php echo htmlspecialchars($reservation['room_number']); ?></small>
                    <small class="text-muted d-block"><?php echo htmlspecialchars($reservation['room_type']); ?></small>
                  </td>
                  <td>
                    <small class="d-block"><?php echo date('M d', strtotime($reservation['check_in'])); ?></small>
                    <small class="text-muted d-block"><?php echo date('h:i A', strtotime($reservation['check_in_time'] ?? '14:00:00')); ?></small>
                  </td>
                  <td>
                    <span class="badge bg-light text-dark border py-1 px-2"><?php echo $nights; ?> night<?php echo $nights != 1 ? 's' : ''; ?></span>
                  </td>
                  <td>
                    <span class="badge badge-status-<?php echo $reservation['status']; ?> py-1 px-2">
                      <i class="fas fa-<?php echo $status_icon; ?> me-1"></i>
                      <?php echo ucfirst($reservation['status']); ?>
                    </span>
                  </td>
                  <td class="pe-3">
                    <small class="fw-bold text-success d-block">₱<?php echo number_format($reservation['total_amount'], 0); ?></small>
                    <small class="text-muted d-block"><?php echo $reservation['payment_status'] == 'paid' ? 'Paid' : 'Pending'; ?></small>
                  </td>
                  <td class="pe-3 text-center">
                    <div class="btn-group btn-group-sm">
                      <a href="index.php?action=admin/reservations&sub_action=view&id=<?php echo $reservation['id']; ?>"
                        class="btn btn-outline-info border py-1 px-2" title="View">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="index.php?action=admin/reservations&sub_action=edit&id=<?php echo $reservation['id']; ?>"
                        class="btn btn-outline-warning border py-1 px-2" title="Edit">
                        <i class="fas fa-edit"></i>
                      </a>
                      <?php if ($reservation['status'] == 'checked_in'): ?>
                        <a href="index.php?action=admin/reservations&sub_action=checkout&id=<?php echo $reservation['id']; ?>"
                          class="btn btn-outline-success border py-1 px-2" title="Check Out">
                          <i class="fas fa-sign-out-alt"></i>
                        </a>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
        <div class="card-footer bg-white py-2">
          <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm justify-content-center mb-0">
              <li class="page-item <?php echo ($currentPage ?? 1) <= 1 ? 'disabled' : ''; ?>">
                <a class="page-link border"
                  href="index.php?action=admin/reservations&page=<?php echo ($currentPage ?? 1) - 1; ?>&search=<?php echo urlencode($search ?? ''); ?>&status=<?php echo urlencode($status ?? ''); ?>&date_from=<?php echo urlencode($date_from ?? ''); ?>&date_to=<?php echo urlencode($date_to ?? ''); ?>">
                  <i class="fas fa-chevron-left"></i>
                </a>
              </li>

              <?php
              $startPage = max(1, ($currentPage ?? 1) - 2);
              $endPage = min($totalPages, $startPage + 4);
              $startPage = max(1, $endPage - 4);

              for ($i = $startPage; $i <= $endPage; $i++):
              ?>
                <li class="page-item <?php echo ($currentPage ?? 1) == $i ? 'active' : ''; ?>">
                  <a class="page-link border"
                    href="index.php?action=admin/reservations&page=<?php echo $i; ?>&search=<?php echo urlencode($search ?? ''); ?>&status=<?php echo urlencode($status ?? ''); ?>&date_from=<?php echo urlencode($date_from ?? ''); ?>&date_to=<?php echo urlencode($date_to ?? ''); ?>">
                    <small><?php echo $i; ?></small>
                  </a>
                </li>
              <?php endfor; ?>

              <li class="page-item <?php echo ($currentPage ?? 1) >= $totalPages ? 'disabled' : ''; ?>">
                <a class="page-link border"
                  href="index.php?action=admin/reservations&page=<?php echo ($currentPage ?? 1) + 1; ?>&search=<?php echo urlencode($search ?? ''); ?>&status=<?php echo urlencode($status ?? ''); ?>&date_from=<?php echo urlencode($date_from ?? ''); ?>&date_to=<?php echo urlencode($date_to ?? ''); ?>">
                  <i class="fas fa-chevron-right"></i>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<style>
  /* Compact Styles */
  .container-fluid {
    max-width: 1200px;
    margin: 0 auto;
  }

  /* Badge Styles */
  .badge-status-pending {
    background-color: rgba(255, 193, 7, 0.08);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
    font-size: 11px;
  }

  .badge-status-confirmed {
    background-color: rgba(13, 110, 253, 0.08);
    color: #0d6efd;
    border: 1px solid rgba(13, 110, 253, 0.2);
    font-size: 11px;
  }

  .badge-status-checked_in {
    background-color: rgba(25, 135, 84, 0.08);
    color: #198754;
    border: 1px solid rgba(25, 135, 84, 0.2);
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

  .btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
  }

  .bg-opacity-10 {
    --bs-bg-opacity: 0.1;
  }

  /* Form controls */
  .form-control-sm {
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
  }

  /* Hover effects */
  tr:hover {
    background-color: rgba(0, 123, 255, 0.02) !important;
  }

  /* Pagination */
  .pagination-sm .page-link {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
  }

  /* Compact dropdown */
  .dropdown-menu {
    font-size: 0.875rem;
    min-width: 120px;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .table th:nth-child(4),
    .table td:nth-child(4) {
      display: none;
    }
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

    // Make table rows clickable
    document.querySelectorAll('tbody tr').forEach(row => {
      row.addEventListener('click', function(e) {
        // Don't trigger if clicking on action buttons
        if (!e.target.closest('.btn-group') && !e.target.closest('a')) {
          const link = this.getAttribute('onclick');
          if (link) {
            const url = link.match(/window\.location='([^']+)'/);
            if (url && url[1]) {
              window.location = url[1];
            }
          }
        }
      });
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });
  });
</script>
