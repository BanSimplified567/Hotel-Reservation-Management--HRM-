<?php
$old = $_SESSION['old'] ?? [];
$error = $_SESSION['error'] ?? '';
$user = $old ?: $user;
unset($_SESSION['old']);
unset($_SESSION['error']);
?>

<div class="container-fluid px-3">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h1 class="h5 mb-1 text-dark fw-bold">
        <i class="fas fa-edit text-primary me-2"></i>Edit User
      </h1>
      <small class="text-muted">ID: #<?php echo $user['id']; ?></small>
    </div>
    <div>
      <a href="index.php?action=admin/users&sub_action=view&id=<?php echo $user['id']; ?>" class="btn btn-info btn-sm me-1">
        <i class="fas fa-eye me-1"></i> View
      </a>
      <a href="index.php?action=admin/users" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
      </a>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-8">
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-white py-2 border-bottom d-flex justify-content-between align-items-center">
          <h6 class="mb-0 text-dark">
            <i class="fas fa-user-edit text-primary me-1"></i>Edit User Information
          </h6>
          <span class="badge status-<?php echo $user['is_active'] ? 'active' : 'inactive'; ?> py-1 px-2">
            <i class="fas fa-<?php echo $user['is_active'] ? 'check-circle' : 'minus-circle'; ?> me-1"></i>
            <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
          </span>
        </div>
        <div class="card-body p-3">
          <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show p-2 mb-3" role="alert">
              <i class="fas fa-exclamation-circle me-2"></i>
              <small><?php echo $error; ?></small>
              <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <form method="POST" action="index.php?action=admin/users&sub_action=edit&id=<?php echo $user['id']; ?>">
            <div class="row g-2 mb-2">
              <div class="col-md-6">
                <label for="first_name" class="form-label small fw-medium">First Name *</label>
                <input type="text" class="form-control form-control-sm" id="first_name" name="first_name"
                  value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
              </div>
              <div class="col-md-6">
                <label for="last_name" class="form-label small fw-medium">Last Name *</label>
                <input type="text" class="form-control form-control-sm" id="last_name" name="last_name"
                  value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
              </div>
            </div>

            <div class="row g-2 mb-2">
              <div class="col-md-6">
                <label for="username" class="form-label small fw-medium">Username *</label>
                <input type="text" class="form-control form-control-sm" id="username" name="username"
                  value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
                <small class="text-muted d-block mt-1">Must be unique</small>
              </div>
              <div class="col-md-6">
                <label for="email" class="form-label small fw-medium">Email *</label>
                <input type="email" class="form-control form-control-sm" id="email" name="email"
                  value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
              </div>
            </div>

            <div class="row g-2 mb-2">
              <div class="col-md-6">
                <label for="phone" class="form-label small fw-medium">Phone</label>
                <input type="tel" class="form-control form-control-sm" id="phone" name="phone"
                  value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
              </div>
              <div class="col-md-6">
                <label for="role" class="form-label small fw-medium">Role *</label>
                <select class="form-control form-control-sm" id="role" name="role" required>
                  <option value="customer" <?php echo ($user['role'] ?? '') == 'customer' ? 'selected' : ''; ?>>Customer</option>
                  <option value="staff" <?php echo ($user['role'] ?? '') == 'staff' ? 'selected' : ''; ?>>Staff</option>
                  <option value="admin" <?php echo ($user['role'] ?? '') == 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
              </div>
            </div>

            <div class="mb-2">
              <label for="address" class="form-label small fw-medium">Address</label>
              <textarea class="form-control form-control-sm" id="address" name="address" rows="2"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                  <?php echo ($user['is_active'] ?? 0) ? 'checked' : ''; ?>>
                <label class="form-check-label small" for="is_active">
                  Active Account
                </label>
              </div>
            </div>

            <hr class="my-3">

            <!-- Password Change Section -->
            <div class="mb-3">
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="change_password" name="change_password">
                <label class="form-check-label small fw-medium" for="change_password">
                  Change Password
                </label>
              </div>

              <div id="passwordFields" class="mt-2" style="display: none;">
                <div class="row g-2">
                  <div class="col-md-6">
                    <label for="password" class="form-label small fw-medium">New Password *</label>
                    <input type="password" class="form-control form-control-sm" id="password" name="password">
                    <small class="text-muted d-block mt-1">Minimum 6 characters</small>
                    <div id="passwordStrength" class="mt-1"></div>
                  </div>
                  <div class="col-md-6">
                    <label for="confirm_password" class="form-label small fw-medium">Confirm Password *</label>
                    <input type="password" class="form-control form-control-sm" id="confirm_password" name="confirm_password">
                    <div id="passwordMatch" class="mt-1"></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-between pt-2 border-top">
              <a href="index.php?action=admin/users&sub_action=toggle-status&id=<?php echo $user['id']; ?>"
                class="btn btn-<?php echo $user['is_active'] ? 'warning' : 'success'; ?> btn-sm">
                <i class="fas fa-<?php echo $user['is_active'] ? 'ban' : 'check'; ?> me-1"></i>
                <?php echo $user['is_active'] ? 'Deactivate' : 'Activate'; ?>
              </a>
              <div>
                <button type="reset" class="btn btn-outline-secondary btn-sm me-2">Reset</button>
                <button type="submit" class="btn btn-primary btn-sm">Update User</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <!-- User Info -->
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-white py-2 border-bottom">
          <h6 class="mb-0 text-dark">
            <i class="fas fa-user-circle text-primary me-1"></i>User Info
          </h6>
        </div>
        <div class="card-body p-3">
          <div class="text-center mb-3">
            <div class="avatar-circle-sm mb-2">
              <span class="initials-sm"><?php echo substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1); ?></span>
            </div>
            <h6 class="mb-1"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h6>
            <small class="text-muted"><?php echo htmlspecialchars($user['email']); ?></small>
          </div>

          <div class="list-group list-group-flush small">
            <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-0">
              <span>User ID</span>
              <span class="badge bg-secondary">#<?php echo $user['id']; ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-0">
              <span>Member Since</span>
              <span class="text-dark"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-0">
              <span>Last Updated</span>
              <span class="text-dark"><?php echo !empty($user['updated_at']) ? date('M d, Y', strtotime($user['updated_at'])) : 'Never'; ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Danger Zone -->
      <div class="card shadow-sm border-danger">
        <div class="card-header bg-danger text-white py-2">
          <h6 class="mb-0">
            <i class="fas fa-exclamation-triangle me-1"></i>Danger Zone
          </h6>
        </div>
        <div class="card-body p-3">
          <p class="small text-muted mb-3">Once deleted, user data cannot be recovered.</p>
          <?php if ($user['id'] != $_SESSION['user_id']): ?>
            <button type="button" class="btn btn-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
              <i class="fas fa-trash me-1"></i> Delete User
            </button>
          <?php else: ?>
            <button type="button" class="btn btn-danger btn-sm w-100" disabled>
              <i class="fas fa-trash me-1"></i> Delete User
            </button>
            <small class="text-muted d-block mt-1 text-center">Cannot delete your own account</small>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
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
    font-size: 18px;
    font-weight: bold;
  }

  .form-control-sm {
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
  }

  .form-label {
    font-size: 0.875rem;
  }

  /* Badge Styles */
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

  .btn-close-sm {
    padding: 0.25rem;
    font-size: 0.75rem;
  }

  /* Password strength indicator */
  .password-strength-bar {
    height: 4px;
    background-color: #e9ecef;
    border-radius: 2px;
    overflow: hidden;
    margin-top: 2px;
  }

  .password-strength-fill {
    height: 100%;
    transition: width 0.3s ease;
  }

  /* Compact alerts */
  .alert {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
  }

  /* Smaller buttons */
  .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
  }

  /* Compact card */
  .card-body.p-3 {
    padding: 1rem !important;
  }

  .list-group-item {
    padding: 0.5rem 0;
  }
</style>

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
        document.getElementById('password').value = '';
        document.getElementById('confirm_password').value = '';
        document.getElementById('passwordStrength').innerHTML = '';
        document.getElementById('passwordMatch').innerHTML = '';
      }
    });

    // Password strength indicator
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
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

        document.getElementById('passwordStrength').innerHTML = `
          <div class="password-strength-bar">
            <div class="password-strength-fill bg-${color}" style="width: ${strength * 25}%"></div>
          </div>
          <small class="text-${color} d-block mt-1">${message}</small>
        `;
      });

      // Confirm password validation
      const confirmInput = document.getElementById('confirm_password');
      confirmInput.addEventListener('input', function() {
        if (passwordInput.value === '') {
          this.classList.remove('is-invalid', 'is-valid');
          document.getElementById('passwordMatch').innerHTML = '';
          return;
        }

        if (this.value !== passwordInput.value) {
          this.classList.add('is-invalid');
          this.classList.remove('is-valid');
          document.getElementById('passwordMatch').innerHTML = `
            <small class="text-danger d-block mt-1">Passwords do not match</small>
          `;
        } else {
          this.classList.remove('is-invalid');
          this.classList.add('is-valid');
          document.getElementById('passwordMatch').innerHTML = `
            <small class="text-success d-block mt-1">Passwords match</small>
          `;
        }
      });
    }

    // Form validation
    const form = document.querySelector('form');
    if (form) {
      form.addEventListener('submit', function(e) {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');

        if (changePasswordCheckbox.checked) {
          if (password.value.length < 6) {
            e.preventDefault();
            alert('Password must be at least 6 characters long.');
            password.focus();
            return false;
          }

          if (password.value !== confirmPassword.value) {
            e.preventDefault();
            alert('Passwords do not match.');
            confirmPassword.focus();
            return false;
          }
        }
      });
    }
  });
</script>
