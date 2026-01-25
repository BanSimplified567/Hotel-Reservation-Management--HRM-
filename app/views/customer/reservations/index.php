<div class="container py-4">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="display-5 fw-bold mb-2 text-dark">My Reservations</h1>
      <p class="lead text-muted">Manage and view all your bookings</p>
    </div>
    <a href="index.php?action=book-room" class="btn btn-primary btn-lg fw-semibold">
      <i class="fas fa-plus-circle me-2"></i> New Booking
    </a>
  </div>

  <!-- Filters -->
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
      <div class="row g-4">
        <div class="col-lg-6">
          <h6 class="fw-semibold text-dark mb-3">Filter by Status:</h6>
          <div class="d-flex flex-wrap gap-2">
            <a href="index.php?action=my-reservations"
              class="btn btn-sm px-3 py-2 fw-semibold <?php echo !$status ? 'btn-primary' : 'btn-outline-primary'; ?>">
              All (<?php echo $totalReservations ?? 0; ?>)
            </a>
            <a href="index.php?action=my-reservations&status=confirmed"
              class="btn btn-sm px-3 py-2 fw-semibold <?php echo $status === 'confirmed' ? 'btn-success' : 'btn-outline-success'; ?>">
              Confirmed
            </a>
            <a href="index.php?action=my-reservations&status=pending"
              class="btn btn-sm px-3 py-2 fw-semibold <?php echo $status === 'pending' ? 'btn-warning' : 'btn-outline-warning'; ?>">
              Pending
            </a>
            <a href="index.php?action=my-reservations&status=cancelled"
              class="btn btn-sm px-3 py-2 fw-semibold <?php echo $status === 'cancelled' ? 'btn-danger' : 'btn-outline-danger'; ?>">
              Cancelled
            </a>
            <a href="index.php?action=my-reservations&status=checked_out"
              class="btn btn-sm px-3 py-2 fw-semibold <?php echo $status === 'checked_out' ? 'btn-secondary' : 'btn-outline-secondary'; ?>">
              Completed
            </a>
          </div>
        </div>
        <div class="col-lg-6">
          <form method="GET" class="d-flex gap-2">
            <input type="hidden" name="action" value="my-reservations">
            <?php if ($status): ?>
              <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
            <?php endif; ?>
            <input type="text" name="search"
                   class="form-control"
                   placeholder="Search reservations..."
                   value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-search"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Reservations List -->
  <?php if (empty($reservations)): ?>
    <div class="card border-0 shadow-sm text-center py-5">
      <div class="card-body p-5">
        <i class="fas fa-calendar-times text-muted display-1 mb-4"></i>
        <h4 class="display-6 fw-semibold text-dark mb-3">No reservations found</h4>
        <p class="text-muted mb-4">You haven't made any reservations yet.</p>
        <a href="index.php?action=book-room" class="btn btn-primary btn-lg fw-semibold">
          <i class="fas fa-plus-circle me-2"></i> Make Your First Booking
        </a>
      </div>
    </div>
  <?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php foreach ($reservations as $reservation): ?>
        <div class="col">
          <div class="card h-100 border-0 shadow-sm hover-card">
            <div class="card-body p-4 border-start border-5
              <?php echo $reservation['status'] === 'confirmed' ? 'border-success' :
                ($reservation['status'] === 'pending' ? 'border-warning' :
                ($reservation['status'] === 'cancelled' ? 'border-danger' : 'border-secondary')); ?>">

              <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                  <h5 class="card-title fw-bold mb-2 text-dark">
                    <?php echo htmlspecialchars($reservation['room_type'] ?? 'Room'); ?>
                  </h5>
                  <p class="text-muted small mb-0">
                    Room #<?php echo htmlspecialchars($reservation['room_number'] ?? 'N/A'); ?>
                  </p>
                </div>
                <span class="badge
                  <?php echo $reservation['status'] === 'confirmed' ? 'bg-success' :
                    ($reservation['status'] === 'pending' ? 'bg-warning' :
                    ($reservation['status'] === 'cancelled' ? 'bg-danger' : 'bg-secondary')); ?>">
                  <?php echo ucfirst($reservation['status'] ?? 'Unknown'); ?>
                </span>
              </div>

              <div class="mb-4">
                <div class="d-flex align-items-center mb-2">
                  <i class="fas fa-calendar-check text-primary me-3"></i>
                  <span class="text-muted"><?php echo date('M d, Y', strtotime($reservation['check_in'] ?? 'now')); ?></span>
                </div>
                <div class="d-flex align-items-center mb-2">
                  <i class="fas fa-calendar-times text-primary me-3"></i>
                  <span class="text-muted"><?php echo date('M d, Y', strtotime($reservation['check_out'] ?? 'now')); ?></span>
                </div>
                <div class="d-flex align-items-center">
                  <i class="fas fa-users text-primary me-3"></i>
                  <span class="text-muted"><?php echo ($reservation['adults'] ?? 1) + ($reservation['children'] ?? 0); ?> guests</span>
                </div>
              </div>

              <div class="border-top pt-3 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="fw-bold text-dark mb-0">$<?php echo number_format($reservation['total_amount'] ?? 0, 2); ?></h6>
                    <small class="text-muted">Total amount</small>
                  </div>
                </div>
              </div>

              <div class="d-flex gap-2">
                <a href="index.php?action=my-reservations&sub_action=view&id=<?php echo $reservation['id']; ?>"
                  class="btn btn-primary btn-sm flex-grow-1">
                  <i class="fas fa-eye me-1"></i> View
                </a>
                <?php if (in_array($reservation['status'] ?? '', ['pending', 'confirmed'])): ?>
                  <a href="index.php?action=my-reservations&sub_action=cancel&id=<?php echo $reservation['id']; ?>"
                    class="btn btn-danger btn-sm">
                    <i class="fas fa-times me-1"></i> Cancel
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if (($totalPages ?? 1) > 1): ?>
      <nav class="mt-5">
        <ul class="pagination justify-content-center">
          <li class="page-item <?php echo ($page ?? 1) == 1 ? 'disabled' : ''; ?>">
            <a class="page-link"
               href="index.php?action=my-reservations&page=<?php echo max(1, ($page ?? 1) - 1); ?><?php echo $status ? '&status=' . urlencode($status) : ''; ?>">
              Previous
            </a>
          </li>

          <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
            <li class="page-item <?php echo ($page ?? 1) == $i ? 'active' : ''; ?>">
              <a class="page-link"
                 href="index.php?action=my-reservations&page=<?php echo $i; ?><?php echo $status ? '&status=' . urlencode($status) : ''; ?>">
                <?php echo $i; ?>
              </a>
            </li>
          <?php endfor; ?>

          <li class="page-item <?php echo ($page ?? 1) == ($totalPages ?? 1) ? 'disabled' : ''; ?>">
            <a class="page-link"
               href="index.php?action=my-reservations&page=<?php echo min(($totalPages ?? 1), ($page ?? 1) + 1); ?><?php echo $status ? '&status=' . urlencode($status) : ''; ?>">
              Next
            </a>
          </li>
        </ul>
      </nav>
    <?php endif; ?>
  <?php endif; ?>
</div>

<style>
.hover-card {
  transition: all 0.3s ease;
}

.hover-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.card-body.border-start {
  border-left-width: 5px !important;
}

.badge {
  padding: 0.5em 0.8em;
  font-size: 0.85em;
}
</style>
