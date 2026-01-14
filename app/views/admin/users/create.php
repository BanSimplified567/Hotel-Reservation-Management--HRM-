<?php
require_once '../../layout/admin-header.php';
require_once '../../layout/admin-sidebar.php';

$old = $_SESSION['old'] ?? [];
$error = $_SESSION['error'] ?? '';
unset($_SESSION['old']);
unset($_SESSION['error']);
?>

<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Add New User</h1>
    <a href="index.php?action=admin/users" class="btn btn-secondary shadow-sm">
      <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Users
    </a>
  </div>

  <!-- Create User Form -->
  <div class="row">
    <div class="col-lg-8">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
        </div>
        <div class="card-body">
          <?php if ($error): ?>
            <div class="alert alert-danger">
              <?php echo $error; ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="index.php?action=admin/users&sub_action=create">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="first_name" class="form-label">First Name *</label>
                <input type="text" class="form-control" id="first_name" name="first_name"
                  value="<?php echo htmlspecialchars($old['first_name'] ?? ''); ?>" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="last_name" class="form-label">Last Name *</label>
                <input type="text" class="form-control" id="last_name" name="last_name"
                  value="<?php echo htmlspecialchars($old['last_name'] ?? ''); ?>" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="username" class="form-label">Username *</label>
                <input type="text" class="form-control" id="username" name="username"
                  value="<?php echo htmlspecialchars($old['username'] ?? ''); ?>" required>
                <small class="text-muted">Must be unique</small>
              </div>
              <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control" id="email" name="email"
                  value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password *</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <small class="text-muted">Minimum 6 characters</small>
              </div>
              <div class="col-md-6 mb-3">
                <label for="confirm_password" class="form-label">Confirm Password *</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="tel" class="form-control" id="phone" name="phone"
                  value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label for="role" class="form-label">Role *</label>
                <select class="form-control" id="role" name="role" required>
                  <option value="customer" <?php echo ($old['role'] ?? '') == 'customer' ? 'selected' : ''; ?>>Customer</option>
                  <option value="staff" <?php echo ($old['role'] ?? '') == 'staff' ? 'selected' : ''; ?>>Staff</option>
                  <option value="admin" <?php echo ($old['role'] ?? '') == 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label for="address" class="form-label">Address</label>
              <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($old['address'] ?? ''); ?></textarea>
            </div>

            <div class="mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                <label class="form-check-label" for="is_active">
                  Active Account
                </label>
              </div>
            </div>

            <div class="d-flex justify-content-between">
              <button type="reset" class="btn btn-secondary">Reset</button>
              <button type="submit" class="btn btn-primary">Create User</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <!-- Help Card -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Guidelines</h6>
        </div>
        <div class="card-body">
          <ul class="list-unstyled">
            <li class="mb-2">
              <i class="fas fa-info-circle text-primary mr-2"></i>
              All fields marked with * are required
            </li>
            <li class="mb-2">
              <i class="fas fa-user-shield text-warning mr-2"></i>
              Choose role carefully based on permissions needed
            </li>
            <li class="mb-2">
              <i class="fas fa-key text-danger mr-2"></i>
              Password must be at least 6 characters
            </li>
            <li class="mb-2">
              <i class="fas fa-envelope text-success mr-2"></i>
              Email must be unique and valid
            </li>
            <li>
              <i class="fas fa-user-check text-info mr-2"></i>
              Inactive accounts cannot login
            </li>
          </ul>
        </div>
      </div>

      <!-- Recent Users -->
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Recently Added</h6>
        </div>
        <div class="card-body">
          <div class="list-group list-group-flush">
            <a href="#" class="list-group-item list-group-item-action">
              <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">John Doe</h6>
                <small>2 days ago</small>
              </div>
              <p class="mb-1">john@example.com</p>
              <small class="text-muted">Customer</small>
            </a>
            <a href="#" class="list-group-item list-group-item-action">
              <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Jane Smith</h6>
                <small>3 days ago</small>
              </div>
              <p class="mb-1">jane@example.com</p>
              <small class="text-muted">Staff</small>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Password strength indicator
    const passwordInput = document.getElementById('password');
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
  });
</script>

<?php
require_once '../../layout/admin-footer.php';
?>
