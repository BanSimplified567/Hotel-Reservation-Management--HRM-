<?php
$old = $_SESSION['old'] ?? [];
$error = $_SESSION['error'] ?? '';
$user = $old ?: $user;
unset($_SESSION['old']);
unset($_SESSION['error']);
?>

<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit User</h1>
    <div>
      <a href="index.php?action=admin/users&sub_action=view&id=<?php echo $user['id']; ?>" class="btn btn-info shadow-sm mr-2">
        <i class="fas fa-eye fa-sm text-white-50"></i> View
      </a>
      <a href="index.php?action=admin/users" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Users
      </a>
    </div>
  </div>

  <!-- Edit User Form -->
  <div class="row">
    <div class="col-lg-8">
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Edit User Information</h6>
          <span class="badge badge-<?php echo $user['is_active'] ? 'success' : 'secondary'; ?>">
            <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
          </span>
        </div>
        <div class="card-body">
          <?php if ($error): ?>
            <div class="alert alert-danger">
              <?php echo $error; ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="index.php?action=admin/users&sub_action=edit&id=<?php echo $user['id']; ?>">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="first_name" class="form-label">First Name *</label>
                <input type="text" class="form-control" id="first_name" name="first_name"
                  value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="last_name" class="form-label">Last Name *</label>
                <input type="text" class="form-control" id="last_name" name="last_name"
                  value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="username" class="form-label">Username *</label>
                <input type="text" class="form-control" id="username" name="username"
                  value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
                <small class="text-muted">Must be unique</small>
              </div>
              <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control" id="email" name="email"
                  value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="tel" class="form-control" id="phone" name="phone"
                  value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label for="role" class="form-label">Role *</label>
                <select class="form-control" id="role" name="role" required>
                  <option value="customer" <?php echo ($user['role'] ?? '') == 'customer' ? 'selected' : ''; ?>>Customer</option>
                  <option value="staff" <?php echo ($user['role'] ?? '') == 'staff' ? 'selected' : ''; ?>>Staff</option>
                  <option value="admin" <?php echo ($user['role'] ?? '') == 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label for="address" class="form-label">Address</label>
              <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
            </div>

            <div class="mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                  <?php echo ($user['is_active'] ?? 0) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="is_active">
                  Active Account
                </label>
              </div>
            </div>

            <hr class="my-4">

            <!-- Password Change Section -->
            <div class="mb-4">
              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="change_password" name="change_password">
                <label class="form-check-label" for="change_password">
                  Change Password
                </label>
              </div>

              <div id="passwordFields" style="display: none;">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">New Password *</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <small class="text-muted">Minimum 6 characters</small>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password *</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                  </div>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-between">
              <a href="index.php?action=admin/users&sub_action=toggle-status&id=<?php echo $user['id']; ?>"
                class="btn btn-<?php echo $user['is_active'] ? 'warning' : 'success'; ?>">
                <i class="fas fa-<?php echo $user['is_active'] ? 'ban' : 'check'; ?>"></i>
                <?php echo $user['is_active'] ? 'Deactivate' : 'Activate'; ?>
              </a>
              <div>
                <button type="reset" class="btn btn-secondary mr-2">Reset</button>
                <button type="submit" class="btn btn-primary">Update User</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <!-- User Statistics -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">User Statistics</h6>
        </div>
        <div class="card-body">
          <div class="text-center mb-4">
            <div class="avatar-circle mb-3">
              <span class="initials"><?php echo substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1); ?></span>
            </div>
            <h5><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h5>
            <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
          </div>

          <div class="list-group list-group-flush">
            <div class="list-group-item d-flex justify-content-between align-items-center">
              User ID
              <span class="badge badge-primary badge-pill">#<?php echo $user['id']; ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
              Member Since
              <span><?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
              Last Updated
              <span><?php echo !empty($user['updated_at']) ? date('M d, Y', strtotime($user['updated_at'])) : 'Never'; ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
              Account Status
              <span class="badge badge-<?php echo $user['is_active'] ? 'success' : 'secondary'; ?>">
                <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Danger Zone -->
      <div class="card shadow border-danger">
        <div class="card-header py-3 bg-danger text-white">
          <h6 class="m-0 font-weight-bold">Danger Zone</h6>
        </div>
        <div class="card-body">
          <p class="text-muted mb-3">Once you delete a user, there is no going back. Please be certain.</p>
          <?php if ($user['id'] != $_SESSION['user_id']): ?>
            <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#deleteModal">
              <i class="fas fa-trash"></i> Delete This User
            </button>
          <?php else: ?>
            <button type="button" class="btn btn-danger btn-block" disabled title="You cannot delete your own account">
              <i class="fas fa-trash"></i> Delete This User
            </button>
            <small class="text-muted d-block mt-1">You cannot delete your own account</small>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
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

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Toggle password fields
    const changePasswordCheckbox = document.getElementById('change_password');
    const passwordFields = document.getElementById('passwordFields');

    changePasswordCheckbox.addEventListener('change', function() {
      if (this.checked) {
        passwordFields.style.display = 'block';
        document.getElementById('password').required = true;
        document.getElementById('confirm_password').required = true;
      } else {
        passwordFields.style.display = 'none';
        document.getElementById('password').required = false;
        document.getElementById('confirm_password').required = false;
      }
    });

    // Password strength indicator
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
      const strengthIndicator = document.createElement('div');
      strengthIndicator.className = 'mt-1';
      passwordInput.parentNode.appendChild(strengthIndicator);

      passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        let message = '';
        let color = 'danger';

        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        switch (strength) {
          case 0:
            message = 'Very Weak';
            color = 'danger';
            break;
          case 1:
            message = 'Weak';
            color = 'danger';
            break;
          case 2:
            message = 'Fair';
            color = 'warning';
            break;
          case 3:
            message = 'Good';
            color = 'info';
            break;
          case 4:
            message = 'Strong';
            color = 'success';
            break;
        }

        strengthIndicator.innerHTML = `
                <div class="progress" style="height: 5px;">
                    <div class="progress-bar bg-${color}" role="progressbar"
                         style="width: ${strength * 25}%" aria-valuenow="${strength * 25}"
                         aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small class="text-${color}">${message}</small>
            `;
      });

      // Confirm password validation
      const confirmInput = document.getElementById('confirm_password');
      confirmInput.addEventListener('input', function() {
        if (this.value !== passwordInput.value) {
          this.classList.add('is-invalid');
          this.classList.remove('is-valid');
        } else {
          this.classList.remove('is-invalid');
          this.classList.add('is-valid');
        }
      });
    }
  });

  // Avatar styles
  const style = document.createElement('style');
  style.textContent = `
    .avatar-circle {
        width: 80px;
        height: 80px;
        background-color: #4e73df;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    .initials {
        color: white;
        font-size: 24px;
        font-weight: bold;
    }
`;
  document.head.appendChild(style);
</script>
