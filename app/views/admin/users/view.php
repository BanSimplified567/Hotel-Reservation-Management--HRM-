<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">User Details</h1>
    <div>
      <a href="index.php?action=admin/users&sub_action=edit&id=<?php echo $user['id']; ?>" class="btn btn-warning shadow-sm mr-2">
        <i class="fas fa-edit fa-sm text-white-50"></i> Edit
      </a>
      <a href="index.php?action=admin/users" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Users
      </a>
    </div>
  </div>

  <!-- User Details -->
  <div class="row">
    <div class="col-lg-4">
      <!-- Profile Card -->
      <div class="card shadow mb-4">
        <div class="card-body text-center">
          <div class="avatar-circle mb-3">
            <span class="initials"><?php echo substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1); ?></span>
          </div>
          <h4><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h4>
          <p class="text-muted mb-2"><?php echo htmlspecialchars($user['email']); ?></p>
          <span class="badge badge-<?php
                                    echo $user['role'] == 'admin' ? 'danger' : ($user['role'] == 'staff' ? 'warning' : 'info');
                                    ?> mb-3">
            <?php echo ucfirst($user['role']); ?>
          </span>

          <div class="mt-4">
            <span class="badge badge-<?php echo $user['is_active'] ? 'success' : 'secondary'; ?> p-2">
              <i class="fas fa-<?php echo $user['is_active'] ? 'check-circle' : 'times-circle'; ?>"></i>
              <?php echo $user['is_active'] ? 'Active Account' : 'Inactive Account'; ?>
            </span>
          </div>
        </div>
        <div class="card-footer">
          <div class="row text-center">
            <div class="col-6">
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['total_reservations'] ?? 0; ?></div>
              <small class="text-muted">Reservations</small>
            </div>
            <div class="col-6">
              <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo number_format($stats['total_spent'] ?? 0, 2); ?></div>
              <small class="text-muted">Total Spent</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Information -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Contact Information</h6>
        </div>
        <div class="card-body">
          <ul class="list-unstyled">
            <li class="mb-3">
              <i class="fas fa-envelope text-primary mr-2"></i>
              <strong>Email:</strong><br>
              <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>">
                <?php echo htmlspecialchars($user['email']); ?>
              </a>
            </li>
            <?php if (!empty($user['phone'])): ?>
              <li class="mb-3">
                <i class="fas fa-phone text-success mr-2"></i>
                <strong>Phone:</strong><br>
                <a href="tel:<?php echo htmlspecialchars($user['phone']); ?>">
                  <?php echo htmlspecialchars($user['phone']); ?>
                </a>
              </li>
            <?php endif; ?>
            <?php if (!empty($user['address'])): ?>
              <li>
                <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                <strong>Address:</strong><br>
                <?php echo nl2br(htmlspecialchars($user['address'])); ?>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>

      <!-- Account Information -->
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Account Information</h6>
        </div>
        <div class="card-body">
          <div class="list-group list-group-flush">
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              User ID
              <span class="badge badge-primary badge-pill">#<?php echo $user['id']; ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              Username
              <span><?php echo htmlspecialchars($user['username']); ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              Member Since
              <span><?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              Last Updated
              <span><?php echo !empty($user['updated_at']) ? date('M d, Y', strtotime($user['updated_at'])) : 'Never'; ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <!-- User Actions Card -->
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
          <div class="btn-group">
            <a href="index.php?action=admin/users&sub_action=edit&id=<?php echo $user['id']; ?>"
              class="btn btn-warning btn-sm">
              <i class="fas fa-edit"></i> Edit
            </a>
            <a href="index.php?action=admin/users&sub_action=toggle-status&id=<?php echo $user['id']; ?>"
              class="btn btn-<?php echo $user['is_active'] ? 'warning' : 'success'; ?> btn-sm">
              <i class="fas fa-<?php echo $user['is_active'] ? 'ban' : 'check'; ?>"></i>
              <?php echo $user['is_active'] ? 'Deactivate' : 'Activate'; ?>
            </a>
            <?php if ($user['id'] != $_SESSION['user_id']): ?>
              <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal">
                <i class="fas fa-trash"></i> Delete
              </button>
            <?php endif; ?>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>" class="btn btn-outline-primary btn-block">
                <i class="fas fa-envelope"></i> Send Email
              </a>
            </div>
            <div class="col-md-6 mb-3">
              <a href="#" class="btn btn-outline-success btn-block" data-toggle="modal" data-target="#resetPasswordModal">
                <i class="fas fa-key"></i> Reset Password
              </a>
            </div>
            <div class="col-md-6 mb-3">
              <a href="index.php?action=admin/reservations&search=<?php echo urlencode($user['email']); ?>"
                class="btn btn-outline-info btn-block">
                <i class="fas fa-calendar-alt"></i> View Reservations
              </a>
            </div>
            <div class="col-md-6 mb-3">
              <a href="#" class="btn btn-outline-warning btn-block">
                <i class="fas fa-file-invoice"></i> Generate Invoice
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Reservations -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Recent Reservations</h6>
        </div>
        <div class="card-body">
          <?php if (!empty($reservations)): ?>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Room</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Amount</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($reservations as $reservation): ?>
                    <tr>
                      <td>
                        <a href="index.php?action=admin/reservations&sub_action=view&id=<?php echo $reservation['id']; ?>">
                          #<?php echo $reservation['id']; ?>
                        </a>
                      </td>
                      <td><?php echo htmlspecialchars($reservation['room_number'] . ' (' . $reservation['room_type'] . ')'); ?></td>
                      <td><?php echo date('M d, Y', strtotime($reservation['check_in'])); ?></td>
                      <td><?php echo date('M d, Y', strtotime($reservation['check_out'])); ?></td>
                      <td>$<?php echo number_format($reservation['total_amount'], 2); ?></td>
                      <td>
                        <span class="badge badge-<?php
                                                  echo $reservation['status'] == 'confirmed' ? 'success' : ($reservation['status'] == 'pending' ? 'warning' : ($reservation['status'] == 'checked_in' ? 'info' : ($reservation['status'] == 'completed' ? 'primary' : 'secondary')));
                                                  ?>">
                          <?php echo ucfirst($reservation['status']); ?>
                        </span>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <div class="text-center">
              <a href="index.php?action=admin/reservations&search=<?php echo urlencode($user['email']); ?>"
                class="btn btn-primary">
                View All Reservations
              </a>
            </div>
          <?php else: ?>
            <p class="text-center text-muted">No reservations found</p>
            <?php if ($user['role'] == 'customer'): ?>
              <div class="text-center mt-3">
                <a href="index.php?action=admin/reservations&sub_action=create&user_id=<?php echo $user['id']; ?>"
                  class="btn btn-success">
                  <i class="fas fa-plus"></i> Create Reservation
                </a>
              </div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- Activity Log -->
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
        </div>
        <div class="card-body">
          <div class="timeline">
            <?php if (!empty($activities)): ?>
              <?php foreach ($activities as $activity): ?>
                <div class="timeline-item mb-3">
                  <div class="timeline-marker"></div>
                  <div class="timeline-content">
                    <h6 class="mb-1"><?php echo htmlspecialchars($activity['action']); ?></h6>
                    <small class="text-muted">
                      <i class="fas fa-clock"></i> <?php echo date('M d, Y H:i', strtotime($activity['created_at'])); ?>
                    </small>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-center text-muted">No recent activity</p>
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
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to reset password for <strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong>?</p>
        <p class="text-muted">A password reset link will be sent to their email address: <?php echo htmlspecialchars($user['email']); ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <a href="index.php?action=admin/users&sub_action=reset-password&id=<?php echo $user['id']; ?>" class="btn btn-primary">Send Reset Link</a>
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<?php if ($user['id'] != $_SESSION['user_id']): ?>
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
          Are you sure you want to delete user: <strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong>?
          <div class="alert alert-danger mt-2">
            <small><i class="fas fa-exclamation-triangle"></i> This action cannot be undone. All user data will be permanently deleted.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <a href="index.php?action=admin/users&sub_action=delete&id=<?php echo $user['id']; ?>" class="btn btn-danger">Delete</a>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<style>
  .avatar-circle {
    width: 100px;
    height: 100px;
    background-color: #4e73df;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
  }

  .initials {
    color: white;
    font-size: 36px;
    font-weight: bold;
  }

  .timeline {
    position: relative;
    padding-left: 30px;
  }

  .timeline-item {
    position: relative;
  }

  .timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #4e73df;
  }

  .timeline-content {
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
  }

  .timeline-item:last-child .timeline-content {
    border-bottom: none;
  }
</style>
