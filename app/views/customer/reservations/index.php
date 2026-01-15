<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Reservations - Hotel Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <style>
    .reservation-card {
      transition: all 0.3s;
      border-left: 4px solid transparent;
    }

    .reservation-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .status-badge {
      font-size: 0.75rem;
      padding: 0.25rem 0.75rem;
    }

    .filter-badge {
      cursor: pointer;
    }

    .reservation-card.pending {
      border-left-color: #ffc107;
    }

    .reservation-card.confirmed {
      border-left-color: #198754;
    }

    .reservation-card.cancelled {
      border-left-color: #dc3545;
    }

    .reservation-card.completed {
      border-left-color: #6c757d;
    }
  </style>
</head>

<body>
  <?php include '../layout/customer-header.php'; ?>

  <div class="container-fluid">
    <div class="row">
      <?php include '../layout/customer-sidebar.php'; ?>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h1 class="h2 mb-1">My Reservations</h1>
            <p class="text-muted mb-0">Manage and view all your bookings</p>
          </div>
          <a href="index.php?action=customer/booking" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> New Booking
          </a>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-md-6">
                <h6 class="mb-3">Filter by Status:</h6>
                <div class="d-flex flex-wrap gap-2">
                  <a href="index.php?action=customer/reservations"
                    class="badge filter-badge <?php echo !$status ? 'bg-primary' : 'bg-light text-dark'; ?>">
                    All (<?php echo $totalReservations; ?>)
                  </a>
                  <a href="index.php?action=customer/reservations&status=confirmed"
                    class="badge filter-badge <?php echo $status === 'confirmed' ? 'bg-success' : 'bg-light text-dark'; ?>">
                    Confirmed
                  </a>
                  <a href="index.php?action=customer/reservations&status=pending"
                    class="badge filter-badge <?php echo $status === 'pending' ? 'bg-warning' : 'bg-light text-dark'; ?>">
                    Pending
                  </a>
                  <a href="index.php?action=customer/reservations&status=cancelled"
                    class="badge filter-badge <?php echo $status === 'cancelled' ? 'bg-danger' : 'bg-light text-dark'; ?>">
                    Cancelled
                  </a>
                  <a href="index.php?action=customer/reservations&status=completed"
                    class="badge filter-badge <?php echo $status === 'completed' ? 'bg-secondary' : 'bg-light text-dark'; ?>">
                    Completed
                  </a>
                </div>
              </div>
              <div class="col-md-6">
                <form method="GET" class="d-flex">
                  <input type="hidden" name="action" value="customer/reservations">
                  <?php if ($status): ?>
                    <input type="hidden" name="status" value="<?php echo $status; ?>">
                  <?php endif; ?>
                  <input type="text" name="search" class="form-control me-2"
                    placeholder="Search reservations..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                  <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-search"></i>
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Reservations List -->
        <?php if (empty($reservations)): ?>
          <div class="card">
            <div class="card-body text-center py-5">
              <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
              <h4 class="text-muted mt-3">No reservations found</h4>
              <p class="text-muted">You haven't made any reservations yet.</p>
              <a href="index.php?action=customer/booking" class="btn btn-primary mt-2">
                <i class="bi bi-plus-circle me-1"></i> Make Your First Booking
              </a>
            </div>
          </div>
        <?php else: ?>
          <div class="row">
            <?php foreach ($reservations as $reservation): ?>
              <div class="col-md-6 col-lg-4 mb-4">
                <div class="card reservation-card h-100 <?php echo $reservation['status']; ?>">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                      <div>
                        <h5 class="card-title mb-1">
                          <?php echo htmlspecialchars($reservation['room_type']); ?>
                        </h5>
                        <p class="text-muted mb-0 small">
                          Room #<?php echo $reservation['room_number']; ?>
                        </p>
                      </div>
                      <span class="badge status-badge bg-<?php
                                                          echo $reservation['status'] === 'confirmed' ? 'success' : ($reservation['status'] === 'pending' ? 'warning' : ($reservation['status'] === 'cancelled' ? 'danger' : 'secondary'));
                                                          ?>">
                        <?php echo ucfirst($reservation['status']); ?>
                      </span>
                    </div>

                    <div class="mb-3">
                      <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-calendar-check text-primary me-2"></i>
                        <span class="small">
                          <?php echo date('M d, Y', strtotime($reservation['check_in'])); ?>
                        </span>
                      </div>
                      <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-calendar-x text-primary me-2"></i>
                        <span class="small">
                          <?php echo date('M d, Y', strtotime($reservation['check_out'])); ?>
                        </span>
                      </div>
                      <div class="d-flex align-items-center">
                        <i class="bi bi-people text-primary me-2"></i>
                        <span class="small">
                          <?php echo $reservation['guests']; ?> guests
                        </span>
                      </div>
                    </div>

                    <?php if ($reservation['service_name']): ?>
                      <div class="mb-3">
                        <span class="badge bg-info bg-opacity-10 text-info">
                          <i class="bi bi-star me-1"></i>
                          <?php echo htmlspecialchars($reservation['service_name']); ?>
                        </span>
                      </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                      <div>
                        <h6 class="mb-0">$<?php echo number_format($reservation['total_amount'], 2); ?></h6>
                        <small class="text-muted">Total amount</small>
                      </div>
                      <div class="btn-group">
                        <a href="index.php?action=customer/reservations/view&id=<?php echo $reservation['id']; ?>"
                          class="btn btn-sm btn-outline-primary">
                          <i class="bi bi-eye"></i>
                        </a>
                        <?php if ($reservation['status'] === 'pending'): ?>
                          <a href="index.php?action=customer/booking/confirmation&id=<?php echo $reservation['id']; ?>"
                            class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-credit-card"></i>
                          </a>
                        <?php endif; ?>
                        <?php if (in_array($reservation['status'], ['pending', 'confirmed'])): ?>
                          <button type="button" class="btn btn-sm btn-outline-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#cancelModal<?php echo $reservation['id']; ?>">
                            <i class="bi bi-x-circle"></i>
                          </button>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Cancel Modal -->
                <?php if (in_array($reservation['status'], ['pending', 'confirmed'])): ?>
                  <div class="modal fade" id="cancelModal<?php echo $reservation['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Cancel Reservation</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="index.php?action=customer/reservations/cancel&id=<?php echo $reservation['id']; ?>">
                          <div class="modal-body">
                            <p>Are you sure you want to cancel this reservation?</p>
                            <div class="mb-3">
                              <label class="form-label">Reason for cancellation:</label>
                              <textarea class="form-control" name="cancellation_reason" rows="3"
                                placeholder="Optional reason for cancellation"></textarea>
                            </div>
                            <div class="alert alert-warning">
                              <i class="bi bi-exclamation-triangle"></i>
                              Please note that cancellation may be subject to fees depending on timing.
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Pagination -->
          <?php if ($totalPages > 1): ?>
            <nav aria-label="Reservations pagination" class="mt-4">
              <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $page == 1 ? 'disabled' : ''; ?>">
                  <a class="page-link"
                    href="index.php?action=customer/reservations&page=<?php echo $page - 1; ?><?php echo $status ? '&status=' . $status : ''; ?>">
                    Previous
                  </a>
                </li>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                  <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link"
                      href="index.php?action=customer/reservations&page=<?php echo $i; ?><?php echo $status ? '&status=' . $status : ''; ?>">
                      <?php echo $i; ?>
                    </a>
                  </li>
                <?php endfor; ?>

                <li class="page-item <?php echo $page == $totalPages ? 'disabled' : ''; ?>">
                  <a class="page-link"
                    href="index.php?action=customer/reservations&page=<?php echo $page + 1; ?><?php echo $status ? '&status=' . $status : ''; ?>">
                    Next
                  </a>
                </li>
              </ul>
            </nav>
          <?php endif; ?>
        <?php endif; ?>
      </main>
    </div>
  </div>

  <?php include '../layout/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Auto-dismiss alerts
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);
  </script>
</body>

</html>
