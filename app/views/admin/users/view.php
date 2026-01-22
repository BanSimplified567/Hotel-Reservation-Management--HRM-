<div class="container-fluid px-3">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h1 class="h5 mb-1 text-dark fw-bold">
        <i class="fas fa-user text-primary me-2"></i>User Details
      </h1>
      <small class="text-muted">ID: #<?php echo $user['id']; ?></small>
    </div>
    <div>
      <a href="index.php?action=admin/users&sub_action=edit&id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm me-1">
        <i class="fas fa-edit me-1"></i>Edit
      </a>
      <a href="index.php?action=admin/users" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Back
      </a>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-4">
      <!-- Profile Card -->
      <div class="card shadow-sm mb-3">
        <div class="card-body p-3 text-center">
          <div class="avatar-circle-sm mb-2">
            <span class="initials-sm"><?php echo substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1); ?></span>
          </div>
          <h5 class="mb-1"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h5>
          <small class="text-muted d-block mb-2"><?php echo htmlspecialchars($user['email']); ?></small>

          <div class="mb-3">
            <span class="badge badge-role-<?php echo $user['role']; ?> py-1 px-2 me-1">
              <i class="fas fa-<?php echo $user['role'] == 'admin' ? 'crown' : ($user['role'] == 'staff' ? 'concierge-bell' : 'user'); ?> me-1"></i>
              <?php echo ucfirst($user['role']); ?>
            </span>
            <span class="badge status-<?php echo $user['is_active'] ? 'active' : 'inactive'; ?> py-1 px-2">
              <i class="fas fa-<?php echo $user['is_active'] ? 'check-circle' : 'minus-circle'; ?> me-1"></i>
              <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
            </span>
          </div>
        </div>
        <div class="card-footer bg-white p-2">
          <div class="row text-center">
            <div class="col-6 border-end">
              <div class="h6 mb-0 fw-bold text-dark"><?php echo $stats['total_reservations'] ?? 0; ?></div>
              <small class="text-muted">Reservations</small>
            </div>
            <div class="col-6">
              <div class="h6 mb-0 fw-bold text-dark">$<?php echo number_format($stats['total_spent'] ?? 0, 0); ?></div>
              <small class="text-muted">Total Spent</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Information -->
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-white py-2 border-bottom">
          <h6 class="mb-0 text-dark">
            <i class="fas fa-address-card text-primary me-1"></i>Contact Info
          </h6>
        </div>
        <div class="card-body p-3">
          <div class="mb-2">
            <small class="text-muted d-block mb-1">Email</small>
            <div class="d-flex align-items-center">
              <i class="fas fa-envelope text-muted me-2" style="width: 16px;"></i>
              <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>" class="small">
                <?php echo htmlspecialchars($user['email']); ?>
              </a>
            </div>
          </div>

          <?php if (!empty($user['phone'])): ?>
            <div class="mb-2">
              <small class="text-muted d-block mb-1">Phone</small>
              <div class="d-flex align-items-center">
                <i class="fas fa-phone text-muted me-2" style="width: 16px;"></i>
                <a href="tel:<?php echo htmlspecialchars($user['phone']); ?>" class="small">
                  <?php echo htmlspecialchars($user['phone']); ?>
                </a>
              </div>
            </div>
          <?php endif; ?>

          <?php if (!empty($user['address'])): ?>
            <div>
              <small class="text-muted d-block mb-1">Address</small>
              <div class="d-flex">
                <i class="fas fa-map-marker-alt text-muted me-2 mt-1" style="width: 16px;"></i>
                <span class="small"><?php echo nl2br(htmlspecialchars($user['address'])); ?></span>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Account Information -->
      <div class="card shadow-sm">
        <div class="card-header bg-white py-2 border-bottom">
          <h6 class="mb-0 text-dark">
            <i class="fas fa-info-circle text-primary me-1"></i>Account Info
          </h6>
        </div>
        <div class="card-body p-0">
          <div class="list-group list-group-flush small">
            <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
              <span>Username</span>
              <span class="text-dark">@<?php echo htmlspecialchars($user['username']); ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
              <span>Member Since</span>
              <span class="text-dark"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
              <span>Last Updated</span>
              <span class="text-dark"><?php echo !empty($user['updated_at']) ? date('M d, Y', strtotime($user['updated_at'])) : 'Never'; ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <!-- Quick Actions -->
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-white py-2 border-bottom">
          <h6 class="mb-0 text-dark">
            <i class="fas fa-bolt text-primary me-1"></i>Quick Actions
          </h6>
        </div>
        <div class="card-body p-3">
          <div class="row g-2">
            <div class="col-sm-6 col-md-3">
              <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>" class="btn btn-outline-primary btn-sm w-100">
                <i class="fas fa-envelope me-1"></i>Email
              </a>
            </div>
            <div class="col-sm-6 col-md-3">
              <a href="#" class="btn btn-outline-success btn-sm w-100" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                <i class="fas fa-key me-1"></i>Reset Pass
              </a>
            </div>
            <div class="col-sm-6 col-md-3">
              <a href="index.php?action=admin/reservations&search=<?php echo urlencode($user['email']); ?>"
                class="btn btn-outline-info btn-sm w-100">
                <i class="fas fa-calendar-alt me-1"></i>Reservations
              </a>
            </div>
            <div class="col-sm-6 col-md-3">
              <a href="#" class="btn btn-outline-warning btn-sm w-100">
                <i class="fas fa-file-invoice me-1"></i>Invoice
              </a>
            </div>
          </div>

          <div class="mt-3 pt-2 border-top">
            <div class="btn-group w-100" role="group">
              <a href="index.php?action=admin/users&sub_action=toggle-status&id=<?php echo $user['id']; ?>"
                class="btn btn-<?php echo $user['is_active'] ? 'warning' : 'success'; ?> btn-sm">
                <i class="fas fa-<?php echo $user['is_active'] ? 'ban' : 'check'; ?> me-1"></i>
                <?php echo $user['is_active'] ? 'Deactivate' : 'Activate'; ?>
              </a>
              <?php if ($user['id'] != $_SESSION['user_id']): ?>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                  <i class="fas fa-trash me-1"></i> Delete
                </button>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Reservations -->
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-white py-2 border-bottom d-flex justify-content-between align-items-center">
          <h6 class="mb-0 text-dark">
            <i class="fas fa-calendar-alt text-primary me-1"></i>Recent Reservations
          </h6>
          <a href="index.php?action=admin/reservations&search=<?php echo urlencode($user['email']); ?>"
            class="btn btn-primary btn-sm">
            <i class="fas fa-eye me-1"></i>View All
          </a>
        </div>
        <div class="card-body p-0">
          <?php if (!empty($reservations)): ?>
            <div class="table-responsive">
              <table class="table table-sm table-hover mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="ps-3"><small>ID</small></th>
                    <th><small>Room</small></th>
                    <th><small>Check-in</small></th>
                    <th><small>Amount</small></th>
                    <th class="pe-3"><small>Status</small></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($reservations as $reservation): ?>
                    <tr>
                      <td class="ps-3">
                        <small>
                          <a href="index.php?action=admin/reservations&sub_action=view&id=<?php echo $reservation['id']; ?>">
                            #<?php echo $reservation['id']; ?>
                          </a>
                        </small>
                      </td>
                      <td>
                        <small><?php echo htmlspecialchars($reservation['room_number']); ?></small><br>
                        <small class="text-muted"><?php echo htmlspecialchars($reservation['room_type']); ?></small>
                      </td>
                      <td><small><?php echo date('M d, Y', strtotime($reservation['check_in'])); ?></small></td>
                      <td><small>$<?php echo number_format($reservation['total_amount'], 0); ?></small></td>
                      <td class="pe-3">
                        <small class="badge badge-status-<?php echo $reservation['status']; ?>">
                          <?php echo ucfirst($reservation['status']); ?>
                        </small>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="text-center py-4">
              <i class="fas fa-calendar-times fa-lg text-muted mb-2"></i>
              <p class="small text-muted mb-3">No reservations found</p>
              <?php if ($user['role'] == 'customer'): ?>
                <a href="index.php?action=admin/reservations&sub_action=create&user_id=<?php echo $user['id']; ?>"
                  class="btn btn-success btn-sm">
                  <i class="fas fa-plus me-1"></i>Create Reservation
                </a>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Activity Log -->
      <div class="card shadow-sm">
        <div class="card-header bg-white py-2 border-bottom d-flex justify-content-between align-items-center">
          <h6 class="mb-0 text-dark">
            <i class="fas fa-history text-primary me-1"></i>Recent Activity
          </h6>
        </div>
        <div class="card-body p-0">
          <div class="list-group list-group-flush">
            <?php if (!empty($activities)): ?>
              <?php foreach ($activities as $activity): ?>
                <div class="list-group-item py-2 px-3">
                  <div class="d-flex justify-content-between align-items-start">
                    <div>
                      <small class="text-dark d-block mb-1"><?php echo htmlspecialchars($activity['action']); ?></small>
                      <small class="text-muted">
                        <i class="fas fa-clock me-1"></i><?php echo date('M d, H:i', strtotime($activity['created_at'])); ?>
                      </small>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="text-center py-4">
                <i class="fas fa-history fa-lg text-muted mb-2"></i>
                <p class="small text-muted mb-0">No recent activity</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modals -->
<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content border-0 shadow-sm">
      <div class="modal-header bg-success text-white py-2">
        <h6 class="modal-title mb-0" id="resetPasswordModalLabel">
          <i class="fas fa-key me-1"></i>Reset Password
        </h6>
        <button type="button" class="btn-close btn-close-white btn-close-sm" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-3">
        <div class="text-center mb-2">
          <i class="fas fa-key fa-lg text-success mb-2"></i>
          <p class="small mb-1">Send password reset link to:</p>
          <p class="fw-bold mb-2"><?php echo htmlspecialchars($user['email']); ?></p>
        </div>
      </div>
      <div class="modal-footer py-2">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
          Cancel
        </button>
        <a href="index.php?action=admin/users&sub_action=reset-password&id=<?php echo $user['id']; ?>" class="btn btn-success btn-sm">
          Send Link
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<?php if ($user['id'] != $_SESSION['user_id']): ?>
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
      <div class="modal-content border-0 shadow-sm">
        <div class="modal-header bg-danger text-white py-2">
          <h6 class="modal-title mb-0" id="deleteModalLabel">
            <i class="fas fa-trash me-1"></i>Delete User
          </h6>
          <button type="button" class="btn-close btn-close-white btn-close-sm" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-3">
          <div class="text-center mb-2">
            <i class="fas fa-user-slash fa-lg text-danger mb-2"></i>
            <p class="small mb-1">Delete <strong><?php echo htmlspecialchars($user['first_name']); ?></strong>?</p>
            <small class="text-muted d-block">All user data will be permanently deleted.</small>
          </div>
        </div>
        <div class="modal-footer py-2">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
            Cancel
          </button>
          <a href="index.php?action=admin/users&sub_action=delete&id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm">
            Delete
          </a>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<style>
  /* Compact Styles */
  .container-fluid {
    max-width: 1200px;
    margin: 0 auto;
  }

  .avatar-circle-sm {
    width: 60px;
    height: 60px;
    background-color: #4e73df;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
  }

  .initials-sm {
    color: white;
    font-size: 20px;
    font-weight: bold;
  }

  /* Badge Styles */
  .badge-role-admin {
    background-color: rgba(220, 53, 69, 0.08);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.2);
    font-size: 11px;
  }

  .badge-role-staff {
    background-color: rgba(255, 193, 7, 0.08);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
    font-size: 11px;
  }

  .badge-role-customer {
    background-color: rgba(13, 110, 253, 0.08);
    color: #0d6efd;
    border: 1px solid rgba(13, 110, 253, 0.2);
    font-size: 11px;
  }

  .status-active {
    background-color: rgba(25, 135, 84, 0.08);
    color: #198754;
    border: 1px solid rgba(25, 135, 84, 0.2);
    font-size: 11px;
  }

  .status-inactive {
    background-color: rgba(108, 117, 125, 0.08);
    color: #6c757d;
    border: 1px solid rgba(108, 117, 125, 0.2);
    font-size: 11px;
  }

  /* Reservation status badges */
  .badge-status-confirmed {
    background-color: rgba(25, 135, 84, 0.08);
    color: #198754;
    border: 1px solid rgba(25, 135, 84, 0.2);
    font-size: 11px;
  }

  .badge-status-pending {
    background-color: rgba(255, 193, 7, 0.08);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
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
    background-color: rgba(108, 117, 125, 0.08);
    color: #6c757d;
    border: 1px solid rgba(108, 117, 125, 0.2);
    font-size: 11px;
  }

  /* Compact modals */
  .modal-sm {
    max-width: 300px;
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

  /* Compact buttons */
  .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });
  });
</script>
