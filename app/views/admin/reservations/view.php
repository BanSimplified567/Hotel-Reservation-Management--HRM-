<?php
require_once '../../layout/admin-header.php';
require_once '../../layout/admin-sidebar.php';

$check_in = new DateTime($reservation['check_in']);
$check_out = new DateTime($reservation['check_out']);
$nights = $check_in->diff($check_out)->days;
$room_total = $reservation['price_per_night'] * $nights;
$services_total = $reservation['total_services_price'] ?? 0;
$grand_total = $room_total + $services_total;
?>

<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Reservation Details</h1>
    <div>
      <a href="index.php?action=admin/reservations&sub_action=edit&id=<?php echo $reservation['id']; ?>"
        class="btn btn-warning shadow-sm mr-2">
        <i class="fas fa-edit fa-sm text-white-50"></i> Edit
      </a>
      <a href="index.php?action=admin/reservations" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Reservations
      </a>
    </div>
  </div>

  <!-- Reservation Details -->
  <div class="row">
    <div class="col-lg-8">
      <!-- Reservation Info Card -->
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Reservation Information</h6>
          <span class="badge badge-<?php
                                    echo $reservation['status'] == 'confirmed' ? 'success' : ($reservation['status'] == 'pending' ? 'warning' : ($reservation['status'] == 'checked_in' ? 'info' : ($reservation['status'] == 'completed' ? 'primary' : ($reservation['status'] == 'cancelled' ? 'secondary' : 'dark'))));
                                    ?> p-2">
            <?php echo ucfirst(str_replace('_', ' ', $reservation['status'])); ?>
          </span>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h6 class="font-weight-bold text-primary mb-3">Reservation Details</h6>
              <table class="table table-sm">
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
                    <small class="text-muted">(<?php echo date('l', strtotime($reservation['check_in'])); ?>)</small>
                  </td>
                </tr>
                <tr>
                  <td><strong>Check-out:</strong></td>
                  <td>
                    <?php echo date('M d, Y', strtotime($reservation['check_out'])); ?>
                    <small class="text-muted">(<?php echo date('l', strtotime($reservation['check_out'])); ?>)</small>
                  </td>
                </tr>
                <tr>
                  <td><strong>Duration:</strong></td>
                  <td><?php echo $nights; ?> night<?php echo $nights > 1 ? 's' : ''; ?></td>
                </tr>
                <tr>
                  <td><strong>Guests:</strong></td>
                  <td><?php echo $reservation['guests']; ?> person<?php echo $reservation['guests'] > 1 ? 's' : ''; ?></td>
                </tr>
              </table>
            </div>

            <div class="col-md-6">
              <h6 class="font-weight-bold text-primary mb-3">Room Details</h6>
              <table class="table table-sm">
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
                  <td>$<?php echo number_format($reservation['price_per_night'], 2); ?></td>
                </tr>
                <tr>
                  <td><strong>Room Total:</strong></td>
                  <td>$<?php echo number_format($room_total, 2); ?></td>
                </tr>
              </table>
            </div>
          </div>

          <?php if (!empty($reservation['special_requests'])): ?>
            <div class="mt-4">
              <h6 class="font-weight-bold text-primary">Special Requests</h6>
              <div class="alert alert-light border">
                <?php echo nl2br(htmlspecialchars($reservation['special_requests'])); ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Customer Information -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <table class="table table-sm">
                <tr>
                  <td width="40%"><strong>Name:</strong></td>
                  <td><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?></td>
                </tr>
                <tr>
                  <td><strong>Email:</strong></td>
                  <td>
                    <a href="mailto:<?php echo htmlspecialchars($reservation['email']); ?>">
                      <?php echo htmlspecialchars($reservation['email']); ?>
                    </a>
                  </td>
                </tr>
                <?php if (!empty($reservation['phone'])): ?>
                  <tr>
                    <td><strong>Phone:</strong></td>
                    <td>
                      <a href="tel:<?php echo htmlspecialchars($reservation['phone']); ?>">
                        <?php echo htmlspecialchars($reservation['phone']); ?>
                      </a>
                    </td>
                  </tr>
                <?php endif; ?>
              </table>
            </div>
            <div class="col-md-6">
              <div class="d-flex flex-column align-items-center justify-content-center h-100">
                <a href="index.php?action=admin/users&sub_action=view&id=<?php echo $reservation['user_id']; ?>"
                  class="btn btn-outline-primary mb-2">
                  <i class="fas fa-user"></i> View Customer Profile
                </a>
                <a href="index.php?action=admin/reservations&search=<?php echo urlencode($reservation['email']); ?>"
                  class="btn btn-outline-info">
                  <i class="fas fa-history"></i> View Booking History
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
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
        </div>
        <div class="card-body">
          <div class="d-grid gap-2">
            <button type="button" class="btn btn-primary update-status"
              data-id="<?php echo $reservation['id']; ?>"
              data-status="<?php echo $reservation['status']; ?>">
              <i class="fas fa-sync-alt"></i> Update Status
            </button>

            <a href="index.php?action=admin/reservations&sub_action=edit&id=<?php echo $reservation['id']; ?>"
              class="btn btn-warning">
              <i class="fas fa-edit"></i> Edit Reservation
            </a>

            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#sendMessageModal">
              <i class="fas fa-envelope"></i> Send Message
            </button>

            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#printInvoiceModal">
              <i class="fas fa-print"></i> Print Invoice
            </button>

            <button type="button" class="btn btn-danger delete-reservation"
              data-id="<?php echo $reservation['id']; ?>"
              data-customer="<?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?>">
              <i class="fas fa-trash"></i> Delete Reservation
            </button>
          </div>
        </div>
      </div>

      <!-- Services Card -->
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Additional Services</h6>
          <span class="badge badge-info"><?php echo count($services ?? []); ?> service(s)</span>
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
                  <span class="badge badge-primary badge-pill">
                    $<?php echo number_format($service['service_price'], 2); ?>
                  </span>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="mt-3 text-right">
              <strong>Services Total: $<?php echo number_format($services_total, 2); ?></strong>
            </div>
          <?php else: ?>
            <p class="text-center text-muted">No additional services</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Payment Summary -->
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Payment Summary</h6>
        </div>
        <div class="card-body">
          <table class="table table-sm">
            <tr>
              <td>Room Charges (<?php echo $nights; ?> nights)</td>
              <td class="text-right">$<?php echo number_format($room_total, 2); ?></td>
            </tr>
            <tr>
              <td>Additional Services</td>
              <td class="text-right">$<?php echo number_format($services_total, 2); ?></td>
            </tr>
            <tr class="table-active">
              <td><strong>Total Amount</strong></td>
              <td class="text-right">
                <strong>$<?php echo number_format($grand_total, 2); ?></strong>
              </td>
            </tr>
            <tr>
              <td>Paid Amount</td>
              <td class="text-right text-success">
                <strong>$<?php echo number_format($reservation['total_amount'], 2); ?></strong>
              </td>
            </tr>
            <?php if ($grand_total > $reservation['total_amount']): ?>
              <tr class="table-warning">
                <td>Balance Due</td>
                <td class="text-right text-danger">
                  <strong>$<?php echo number_format($grand_total - $reservation['total_amount'], 2); ?></strong>
                </td>
              </tr>
            <?php endif; ?>
          </table>

          <?php if (!empty($reservation['admin_notes'])): ?>
            <div class="mt-3">
              <h6 class="font-weight-bold text-primary">Admin Notes</h6>
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

<!-- Modals -->
<!-- Update Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="statusModalLabel">Update Reservation Status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="index.php?action=admin/reservations&sub_action=update-status&id=<?php echo $reservation['id']; ?>">
        <div class="modal-body">
          <div class="form-group">
            <label for="status">Status *</label>
            <select class="form-control" id="status" name="status" required>
              <option value="pending" <?php echo $reservation['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
              <option value="confirmed" <?php echo $reservation['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
              <option value="checked_in" <?php echo $reservation['status'] == 'checked_in' ? 'selected' : ''; ?>>Checked-in</option>
              <option value="completed" <?php echo $reservation['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
              <option value="cancelled" <?php echo $reservation['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
            </select>
          </div>

          <div class="form-group">
            <label for="notes">Notes (Optional)</label>
            <textarea class="form-control" id="notes" name="notes" rows="3"
              placeholder="Add any notes about this status change..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Status</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Send Message Modal -->
<div class="modal fade" id="sendMessageModal" tabindex="-1" role="dialog" aria-labelledby="sendMessageModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sendMessageModalLabel">Send Message to Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="">
        <div class="modal-body">
          <div class="form-group">
            <label for="message_subject">Subject *</label>
            <input type="text" class="form-control" id="message_subject" name="subject" required>
          </div>

          <div class="form-group">
            <label for="message_body">Message *</label>
            <textarea class="form-control" id="message_body" name="message" rows="5" required></textarea>
          </div>

          <div class="alert alert-info">
            <small>
              <i class="fas fa-info-circle"></i>
              This message will be sent to: <?php echo htmlspecialchars($reservation['email']); ?>
            </small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Send Message</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Print Invoice Modal -->
<div class="modal fade" id="printInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="printInvoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="printInvoiceModalLabel">Print Invoice</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Select invoice format:</p>
        <div class="d-grid gap-2">
          <a href="#" class="btn btn-outline-primary">
            <i class="fas fa-file-pdf"></i> PDF Format
          </a>
          <a href="#" class="btn btn-outline-success">
            <i class="fas fa-file-excel"></i> Excel Format
          </a>
          <a href="#" class="btn btn-outline-info">
            <i class="fas fa-print"></i> Print Directly
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete reservation #<?php echo $reservation['id']; ?> for:
        <strong id="deleteCustomerName"></strong>?
        <div class="alert alert-warning mt-2">
          <small><i class="fas fa-exclamation-triangle"></i> This action cannot be undone. All reservation data will be permanently deleted.</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <a href="index.php?action=admin/reservations&sub_action=delete&id=<?php echo $reservation['id']; ?>"
          class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Update status
    document.querySelector('.update-status').addEventListener('click', function() {
      $('#statusModal').modal('show');
    });

    // Delete confirmation
    document.querySelector('.delete-reservation').addEventListener('click', function() {
      const customerName = this.getAttribute('data-customer');
      document.getElementById('deleteCustomerName').textContent = customerName;
      $('#deleteModal').modal('show');
    });
  });
</script>

<style>
  .table td {
    vertical-align: middle;
  }
</style>

<?php
require_once '../../layout/admin-footer.php';
?>
