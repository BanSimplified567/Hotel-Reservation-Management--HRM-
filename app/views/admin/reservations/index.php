<?php
// app/views/admin/reservations/index.php
?>

<div class="container-fluid px-4">
  <!-- Page Header -->
  <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4">
    <div class="mb-3 mb-md-0">
      <h1 class="h3 mb-1 text-gray-800">Reservation Management</h1>
      <p class="text-muted mb-0">Manage and monitor all hotel reservations</p>
    </div>
    <a href="index.php?action=admin/reservations&sub_action=create" class="btn btn-primary d-inline-flex align-items-center">
      <i class="fas fa-plus-circle me-2"></i> Create New Reservation
    </a>
  </div>

  <!-- Alerts -->
  <div class="row mb-4">
    <div class="col-12">
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
          <i class="fas fa-check-circle me-2"></i>
          <div>
            <?php echo $_SESSION['success'];
            unset($_SESSION['success']); ?>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
          <i class="fas fa-exclamation-circle me-2"></i>
          <div>
            <?php echo $_SESSION['error'];
            unset($_SESSION['error']); ?>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                Total Reservations
              </div>
              <div class="h5 mb-0 fw-bold text-gray-800">
                <?php echo $totalReservations ?? '0'; ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                Pending
              </div>
              <div class="h5 mb-0 fw-bold text-gray-800">
                <?php echo $pendingCount ?? '0'; ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-clock fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs fw-bold text-success text-uppercase mb-1">
                Active Check-ins
              </div>
              <div class="h5 mb-0 fw-bold text-gray-800">
                <?php echo $checkedInCount ?? '0'; ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-key fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs fw-bold text-info text-uppercase mb-1">
                Revenue (Today)
              </div>
              <div class="h5 mb-0 fw-bold text-gray-800">
                ₱<?php echo number_format($todayRevenue ?? 0, 2); ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Search and Filter Card -->
  <div class="card shadow mb-4">
    <div class="card-header bg-white py-3">
      <div class="d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-primary">
          <i class="fas fa-filter me-2"></i>Search & Filter
        </h6>
        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
          data-bs-target="#filterCollapse" aria-expanded="true" aria-controls="filterCollapse">
          <i class="fas fa-chevron-down"></i>
        </button>
      </div>
    </div>
    <div class="collapse show" id="filterCollapse">
      <div class="card-body">
        <form method="GET" action="index.php" class="row g-3">
          <input type="hidden" name="action" value="admin/reservations">

          <div class="col-md-12 col-lg-3">
            <label class="form-label small fw-bold text-muted">Search</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
              <input type="text" class="form-control" name="search"
                placeholder="Name, email, or room..."
                value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>
          </div>

          <div class="col-md-6 col-lg-2">
            <label class="form-label small fw-bold text-muted">Status</label>
            <select class="form-select" name="status">
              <option value="">All Status</option>
              <option value="pending" <?php echo ($status ?? '') == 'pending' ? 'selected' : ''; ?>>Pending</option>
              <option value="confirmed" <?php echo ($status ?? '') == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
              <option value="checked_in" <?php echo ($status ?? '') == 'checked_in' ? 'selected' : ''; ?>>Checked In</option>
              <option value="completed" <?php echo ($status ?? '') == 'completed' ? 'selected' : ''; ?>>Completed</option>
              <option value="cancelled" <?php echo ($status ?? '') == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
            </select>
          </div>

          <div class="col-md-6 col-lg-2">
            <label class="form-label small fw-bold text-muted">Check-in From</label>
            <input type="date" class="form-control" name="date_from"
              value="<?php echo htmlspecialchars($date_from ?? ''); ?>">
          </div>

          <div class="col-md-6 col-lg-2">
            <label class="form-label small fw-bold text-muted">Check-in To</label>
            <input type="date" class="form-control" name="date_to"
              value="<?php echo htmlspecialchars($date_to ?? ''); ?>">
          </div>

          <div class="col-md-6 col-lg-3 d-flex align-items-end">
            <div class="d-flex gap-2 w-100">
              <button type="submit" class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center">
                <i class="fas fa-search me-2"></i> Search
              </button>
              <a href="index.php?action=admin/reservations" class="btn btn-outline-secondary">
                <i class="fas fa-redo"></i>
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Reservations Table -->
  <div class="card shadow">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
      <h6 class="m-0 fw-bold text-primary">
        <i class="fas fa-list me-2"></i>Reservations List
        <span class="badge bg-primary ms-2"><?php echo count($reservations ?? []); ?></span>
      </h6>
      <div class="dropdown">
        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
          data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-download me-1"></i> Export
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#"><i class="fas fa-file-csv me-2"></i> CSV</a></li>
          <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i> Excel</a></li>
          <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i> PDF</a></li>
        </ul>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
          <thead class="table-light">
            <tr>
              <th width="80">ID</th>
              <th>Guest</th>
              <th>Room</th>
              <th>Check-in</th>
              <th>Check-out</th>
              <th>Nights</th>
              <th>Status</th>
              <th>Amount</th>
              <th width="140" class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($reservations)): ?>
              <tr>
                <td colspan="9" class="text-center py-5">
                  <div class="text-muted mb-3">
                    <i class="fas fa-calendar-times fa-3x"></i>
                  </div>
                  <h5 class="text-muted">No reservations found</h5>
                  <p class="text-muted small">Try adjusting your search filters</p>
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
                <tr>
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
                      <span><?php echo date('M d, Y', strtotime($reservation['check_in'])); ?></span>
                      <small class="text-muted"><?php echo date('h:i A', strtotime($reservation['check_in_time'] ?? '14:00:00')); ?></small>
                    </div>
                  </td>
                  <td>
                    <div class="d-flex flex-column">
                      <span><?php echo date('M d, Y', strtotime($reservation['check_out'])); ?></span>
                      <small class="text-muted"><?php echo date('h:i A', strtotime($reservation['check_out_time'] ?? '12:00:00')); ?></small>
                    </div>
                  </td>
                  <td>
                    <span class="badge bg-light text-dark"><?php echo $nights; ?> night<?php echo $nights != 1 ? 's' : ''; ?></span>
                  </td>
                  <td>
                    <span class="badge bg-<?php echo $status_badge; ?> d-inline-flex align-items-center">
                      <i class="fas fa-<?php echo $status_icon; ?> me-1"></i>
                      <?php echo ucfirst($reservation['status']); ?>
                    </span>
                  </td>
                  <td>
                    <div class="d-flex flex-column">
                      <strong class="text-success">₱<?php echo number_format($reservation['total_amount'], 2); ?></strong>
                      <?php if ($reservation['payment_status'] == 'paid'): ?>
                        <small class="text-success"><i class="fas fa-check-circle me-1"></i>Paid</small>
                      <?php else: ?>
                        <small class="text-warning"><i class="fas fa-clock me-1"></i>Pending</small>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td>
                    <div class="d-flex justify-content-center gap-1">
                      <a href="index.php?action=admin/reservations&sub_action=view&id=<?php echo $reservation['id']; ?>"
                        class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="View Details">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="index.php?action=admin/reservations&sub_action=edit&id=<?php echo $reservation['id']; ?>"
                        class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Edit">
                        <i class="fas fa-edit"></i>
                      </a>
                      <?php if ($reservation['status'] == 'checked_in'): ?>
                        <a href="index.php?action=admin/reservations&sub_action=checkout&id=<?php echo $reservation['id']; ?>"
                          class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Check Out">
                          <i class="fas fa-sign-out-alt"></i>
                        </a>
                      <?php endif; ?>
                      <a href="index.php?action=admin/reservations&sub_action=delete&id=<?php echo $reservation['id']; ?>"
                        class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Are you sure you want to delete reservation #<?php echo $reservation['id']; ?>?')"
                        data-bs-toggle="tooltip" title="Delete">
                        <i class="fas fa-trash"></i>
                      </a>
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
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top">
          <div class="mb-3 mb-md-0">
            <p class="small text-muted mb-0">
              Showing <?php echo (($currentPage ?? 1) - 1) * ($perPage ?? 10) + 1; ?> to
              <?php echo min(($currentPage ?? 1) * ($perPage ?? 10), $totalReservations ?? 0); ?> of
              <?php echo $totalReservations ?? 0; ?> entries
            </p>
          </div>
          <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0">
              <li class="page-item <?php echo ($currentPage ?? 1) <= 1 ? 'disabled' : ''; ?>">
                <a class="page-link"
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
                  <a class="page-link"
                    href="index.php?action=admin/reservations&page=<?php echo $i; ?>&search=<?php echo urlencode($search ?? ''); ?>&status=<?php echo urlencode($status ?? ''); ?>&date_from=<?php echo urlencode($date_from ?? ''); ?>&date_to=<?php echo urlencode($date_to ?? ''); ?>">
                    <?php echo $i; ?>
                  </a>
                </li>
              <?php endfor; ?>

              <li class="page-item <?php echo ($currentPage ?? 1) >= $totalPages ? 'disabled' : ''; ?>">
                <a class="page-link"
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
  $(document).ready(function() {
    // Initialize DataTable
    $('#dataTable').DataTable({
      "paging": false,
      "ordering": true,
      "info": false,
      "searching": false,
      "order": [
        [0, 'desc']
      ],
      "language": {
        "emptyTable": "No reservations available"
      }
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
      $('.alert').alert('close');
    }, 5000);
  });
</script>

<style>
  .table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
    cursor: pointer;
  }

  .card-header {
    border-bottom: 1px solid rgba(0, 0, 0, .125);
  }

  .badge {
    font-weight: 500;
  }

  .form-label {
    font-size: 0.85rem;
  }

  .table th {
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
  }

  /* Additional consistent styling */
  .card {
    border: none;
    border-radius: 0.5rem;
  }

  .border-left-primary {
    border-left-color: #4e73df !important;
  }

  .border-left-success {
    border-left-color: #1cc88a !important;
  }

  .border-left-warning {
    border-left-color: #f6c23e !important;
  }

  .border-left-info {
    border-left-color: #36b9cc !important;
  }

  .dropdown-menu {
    font-size: 0.875rem;
  }

  .pagination-sm .page-link {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
  }

  .table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
    cursor: pointer;
  }

  .card-header {
    border-bottom: 1px solid rgba(0, 0, 0, .125);
  }

  .badge {
    font-weight: 500;
  }

  .form-label {
    font-size: 0.85rem;
  }

  .table th {
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
  }
</style>
