<?php
// app/views/admin/reservations/view.php

// Check if reservation data exists
if (!isset($reservation)) {
  die("ERROR: Reservation data not found!");
}

$check_in = new DateTime($reservation['check_in']);
$check_out = new DateTime($reservation['check_out']);
$nights = $check_in->diff($check_out)->days;
$room_total = $reservation['price_per_night'] * $nights;

// Calculate services total
$services_total = 0;
if (!empty($services)) {
  foreach ($services as $service) {
    $services_total += $service['total_price'] ?? $service['price'];
  }
}

$grand_total = $room_total + $services_total;
?>

<div class="container-fluid px-4">
  <!-- Page Header -->
  <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4">
    <div class="mb-3 mb-md-0">
      <h1 class="h3 mb-1 text-gray-800">Reservation Details</h1>
      <p class="text-muted mb-0">
        Code: <strong><?php echo htmlspecialchars($reservation['reservation_code']); ?></strong>
        • Created: <?php echo date('M d, Y', strtotime($reservation['created_at'])); ?>
      </p>
    </div>
    <div class="d-flex flex-wrap gap-2">
      <a href="index.php?action=admin/reservations&sub_action=edit&id=<?php echo $reservation['id']; ?>"
        class="btn btn-warning d-inline-flex align-items-center">
        <i class="fas fa-edit me-2"></i> Edit
      </a>
      <a href="index.php?action=admin/reservations" class="btn btn-secondary d-inline-flex align-items-center">
        <i class="fas fa-arrow-left me-2"></i> Back to Reservations
      </a>
    </div>
  </div>

  <!-- Alerts -->
  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
      <i class="fas fa-check-circle me-2"></i>
      <div>
        <?php echo $_SESSION['success'];
        unset($_SESSION['success']); ?>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i>
      <div>
        <?php echo $_SESSION['error'];
        unset($_SESSION['error']); ?>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="row">
    <div class="col-lg-8">
      <!-- Reservation Info Card -->
      <div class="card shadow mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 fw-bold text-primary d-flex align-items-center">
            <i class="fas fa-calendar-alt me-2"></i>Reservation Information
          </h6>
          <span class="badge bg-<?php
                                switch ($reservation['status']) {
                                  case 'pending':
                                    echo 'warning';
                                    break;
                                  case 'confirmed':
                                    echo 'info';
                                    break;
                                  case 'checked_in':
                                    echo 'success';
                                    break;
                                  case 'completed':
                                    echo 'primary';
                                    break;
                                  case 'cancelled':
                                    echo 'danger';
                                    break;
                                  default:
                                    echo 'secondary';
                                }
                                ?> d-inline-flex align-items-center">
            <i class="fas fa-<?php
                              switch ($reservation['status']) {
                                case 'pending':
                                  echo 'clock';
                                  break;
                                case 'confirmed':
                                  echo 'check-circle';
                                  break;
                                case 'checked_in':
                                  echo 'key';
                                  break;
                                case 'completed':
                                  echo 'flag-checkered';
                                  break;
                                case 'cancelled':
                                  echo 'times-circle';
                                  break;
                                default:
                                  echo 'question-circle';
                              }
                              ?> me-1"></i>
            <?php echo ucfirst($reservation['status']); ?>
          </span>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h6 class="fw-bold text-primary mb-3">Reservation Details</h6>
              <div class="table-responsive">
                <table class="table table-sm">
                  <tbody>
                    <tr>
                      <td width="40%"><strong>Reservation ID:</strong></td>
                      <td>#<?php echo $reservation['id']; ?></td>
                    </tr>
                    <tr>
                      <td><strong>Booking Date:</strong></td>
                      <td><?php echo date('M d, Y H:i', strtotime($reservation['created_at'])); ?></td>
                    </tr>
                    <tr>
                      <td><strong>Check-in:</strong></td>
                      <td>
                        <?php echo date('M d, Y', strtotime($reservation['check_in'])); ?>
                        <small class="text-muted d-block"><?php echo date('l', strtotime($reservation['check_in'])); ?></small>
                      </td>
                    </tr>
                    <tr>
                      <td><strong>Check-out:</strong></td>
                      <td>
                        <?php echo date('M d, Y', strtotime($reservation['check_out'])); ?>
                        <small class="text-muted d-block"><?php echo date('l', strtotime($reservation['check_out'])); ?></small>
                      </td>
                    </tr>
                    <tr>
                      <td><strong>Duration:</strong></td>
                      <td><?php echo $nights; ?> night<?php echo $nights > 1 ? 's' : ''; ?></td>
                    </tr>
                    <tr>
                      <td><strong>Guests:</strong></td>
                      <td>
                        <?php echo $reservation['guests']; ?> person<?php echo $reservation['guests'] > 1 ? 's' : ''; ?>
                        <small class="text-muted d-block">(<?php echo $reservation['adults'] ?? 1; ?> adults, <?php echo $reservation['children'] ?? 0; ?> children)</small>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="col-md-6">
              <h6 class="fw-bold text-primary mb-3">Room Details</h6>
              <div class="table-responsive">
                <table class="table table-sm">
                  <tbody>
                    <tr>
                      <td width="40%"><strong>Room Number:</strong></td>
                      <td><?php echo htmlspecialchars($reservation['room_number']); ?></td>
                    </tr>
                    <tr>
                      <td><strong>Room Type:</strong></td>
                      <td><?php echo htmlspecialchars($reservation['room_type']); ?></td>
                    </tr>
                    <tr>
                      <td><strong>Price per Night:</strong></td>
                      <td>₱<?php echo number_format($reservation['price_per_night'], 2); ?></td>
                    </tr>
                    <tr>
                      <td><strong>Room Total:</strong></td>
                      <td>₱<?php echo number_format($room_total, 2); ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <?php if (!empty($reservation['special_requests'])): ?>
            <div class="mt-4">
              <h6 class="fw-bold text-primary mb-2">Special Requests</h6>
              <div class="alert alert-light border">
                <?php echo nl2br(htmlspecialchars($reservation['special_requests'])); ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Customer Information -->
      <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
          <h6 class="m-0 fw-bold text-primary d-flex align-items-center">
            <i class="fas fa-user me-2"></i>Customer Information
          </h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="table-responsive">
                <table class="table table-sm">
                  <tbody>
                    <tr>
                      <td width="40%"><strong>Name:</strong></td>
                      <td><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?></td>
                    </tr>
                    <tr>
                      <td><strong>Email:</strong></td>
                      <td>
                        <a href="mailto:<?php echo htmlspecialchars($reservation['email']); ?>" class="text-decoration-none">
                          <?php echo htmlspecialchars($reservation['email']); ?>
                        </a>
                      </td>
                    </tr>
                    <?php if (!empty($reservation['phone'])): ?>
                      <tr>
                        <td><strong>Phone:</strong></td>
                        <td>
                          <a href="tel:<?php echo htmlspecialchars($reservation['phone']); ?>" class="text-decoration-none">
                            <?php echo htmlspecialchars($reservation['phone']); ?>
                          </a>
                        </td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex flex-column align-items-center justify-content-center h-100">
                <a href="index.php?action=admin/users&sub_action=view&id=<?php echo $reservation['user_id']; ?>"
                  class="btn btn-outline-primary mb-2 w-100">
                  <i class="fas fa-user me-1"></i> View Customer Profile
                </a>
                <a href="index.php?action=admin/reservations&search=<?php echo urlencode($reservation['email']); ?>"
                  class="btn btn-outline-info w-100">
                  <i class="fas fa-history me-1"></i> View Booking History
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <!-- Actions Card -->
      <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
          <h6 class="m-0 fw-bold text-primary d-flex align-items-center">
            <i class="fas fa-cogs me-2"></i>Actions
          </h6>
        </div>
        <div class="card-body">
          <div class="d-grid gap-2">
            <button type="button" class="btn btn-primary update-status"
              data-id="<?php echo $reservation['id']; ?>"
              data-bs-toggle="modal" data-bs-target="#statusModal">
              <i class="fas fa-sync-alt me-1"></i> Update Status
            </button>

            <a href="index.php?action=admin/reservations&sub_action=edit&id=<?php echo $reservation['id']; ?>"
              class="btn btn-warning">
              <i class="fas fa-edit me-1"></i> Edit Reservation
            </a>

            <?php if ($reservation['status'] == 'checked_in'): ?>
              <a href="index.php?action=admin/reservations&sub_action=checkout&id=<?php echo $reservation['id']; ?>"
                class="btn btn-success"
                onclick="return confirm('Are you sure you want to check out this reservation?')">
                <i class="fas fa-sign-out-alt me-1"></i> Check Out
              </a>
            <?php endif; ?>

            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#sendMessageModal">
              <i class="fas fa-envelope me-1"></i> Send Message
            </button>

            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#printInvoiceModal">
              <i class="fas fa-print me-1"></i> Print Invoice
            </button>

            <?php if ($_SESSION['role'] == 'admin'): ?>
              <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="fas fa-trash me-1"></i> Delete Reservation
              </button>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Services Card -->
      <div class="card shadow mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 fw-bold text-primary d-flex align-items-center">
            <i class="fas fa-concierge-bell me-2"></i>Additional Services
          </h6>
          <span class="badge bg-info"><?php echo count($services ?? []); ?> service(s)</span>
        </div>
        <div class="card-body">
          <?php if (!empty($services)): ?>
            <div class="list-group list-group-flush">
              <?php foreach ($services as $service): ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="mb-1"><?php echo htmlspecialchars($service['name']); ?></h6>
                    <?php if (!empty($service['description'])): ?>
                      <small class="text-muted"><?php echo htmlspecialchars($service['description']); ?></small>
                    <?php endif; ?>
                  </div>
                  <span class="badge bg-primary badge-pill">
                    ₱<?php echo number_format($service['total_price'] ?? $service['price'], 2); ?>
                  </span>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="mt-3 text-end">
              <strong>Services Total: ₱<?php echo number_format($services_total, 2); ?></strong>
            </div>
          <?php else: ?>
            <p class="text-center text-muted mb-0">No additional services</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Payment Summary -->
      <div class="card shadow">
        <div class="card-header bg-white py-3">
          <h6 class="m-0 fw-bold text-primary d-flex align-items-center">
            <i class="fas fa-money-bill-wave me-2"></i>Payment Summary
          </h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-sm">
              <tbody>
                <tr>
                  <td>Room Charges (<?php echo $nights; ?> nights)</td>
                  <td class="text-end">₱<?php echo number_format($room_total, 2); ?></td>
                </tr>
                <tr>
                  <td>Additional Services</td>
                  <td class="text-end">₱<?php echo number_format($services_total, 2); ?></td>
                </tr>
                <tr class="table-active">
                  <td><strong>Total Amount</strong></td>
                  <td class="text-end">
                    <strong>₱<?php echo number_format($grand_total, 2); ?></strong>
                  </td>
                </tr>
                <tr>
                  <td>Paid Amount</td>
                  <td class="text-end text-success">
                    <strong>₱<?php echo number_format($reservation['total_amount'], 2); ?></strong>
                  </td>
                </tr>
                <?php if ($grand_total > $reservation['total_amount']): ?>
                  <tr class="table-warning">
                    <td>Balance Due</td>
                    <td class="text-end text-danger">
                      <strong>₱<?php echo number_format($grand_total - $reservation['total_amount'], 2); ?></strong>
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <!-- Payment Status -->
          <div class="mt-3">
            <div class="d-flex justify-content-between align-items-center">
              <span><strong>Payment Status:</strong></span>
              <span class="badge bg-<?php echo $reservation['payment_status'] == 'paid' ? 'success' : 'warning'; ?>">
                <?php echo ucfirst($reservation['payment_status'] ?? 'pending'); ?>
              </span>
            </div>
          </div>

          <?php if (!empty($reservation['admin_notes'])): ?>
            <div class="mt-3">
              <h6 class="fw-bold text-primary mb-2">Admin Notes</h6>
              <div class="alert alert-warning">
                <?php echo nl2br(htmlspecialchars($reservation['admin_notes'])); ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="statusModalLabel">Update Reservation Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="index.php?action=admin/reservations&sub_action=update-status&id=<?php echo $reservation['id']; ?>">
        <div class="modal-body">
          <div class="mb-3">
            <label for="status" class="form-label">Status *</label>
            <select class="form-select" id="status" name="status" required>
              <option value="pending" <?php echo $reservation['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
              <option value="confirmed" <?php echo $reservation['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
              <option value="checked_in" <?php echo $reservation['status'] == 'checked_in' ? 'selected' : ''; ?>>Checked-in</option>
              <option value="completed" <?php echo $reservation['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
              <option value="cancelled" <?php echo $reservation['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
              <option value="no_show" <?php echo $reservation['status'] == 'no_show' ? 'selected' : ''; ?>>No Show</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="notes" class="form-label">Notes (Optional)</label>
            <textarea class="form-control" id="notes" name="notes" rows="3"
              placeholder="Add any notes about this status change..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Status</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete reservation #<?php echo $reservation['id']; ?> for:</p>
        <p class="fw-bold"><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?></p>
        <div class="alert alert-warning mt-2">
          <small><i class="fas fa-exclamation-triangle me-1"></i> This action cannot be undone. All reservation data will be permanently deleted.</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="index.php?action=admin/reservations&sub_action=delete&id=<?php echo $reservation['id']; ?>"
          class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

<style>
  a .table td {
    vertical-align: middle;
  }

  .list-group-item:hover {
    background-color: rgba(0, 123, 255, 0.05);
  }

  /* Consistent card styling */
  .card {
    border: none;
    border-radius: 0.5rem;
  }

  .card-header {
    border-bottom: 1px solid rgba(0, 0, 0, .125);
  }

  .badge {
    font-weight: 500;
  }

  .btn {
    border-radius: 0.375rem;
  }

  .modal-content {
    border-radius: 0.5rem;
  }

  .table td {
    vertical-align: middle;
  }

  .list-group-item:hover {
    background-color: rgba(0, 123, 255, 0.05);
  }
</style>
