<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change Password - Hotel Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <style>
    .password-card {
      max-width: 500px;
      margin: 0 auto;
      border: none;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .password-strength {
      height: 5px;
      border-radius: 5px;
      margin-top: 5px;
      transition: all 0.3s;
    }

    .strength-weak {
      background: #dc3545;
      width: 25%;
    }

    .strength-fair {
      background: #ffc107;
      width: 50%;
    }

    .strength-good {
      background: #20c997;
      width: 75%;
    }

    .strength-strong {
      background: #198754;
      width: 100%;
    }
  </style>
</head>

<body>
  <?php include '../layout/customer-header.php'; ?>

  <div class="container-fluid">
    <div class="row">
      <?php include '../layout/customer-sidebar.php'; ?>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        <!-- Page Header -->
        <div class="mb-4">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="index.php?action=customer/profile">Profile</a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Change Password</li>
            </ol>
          </nav>
          <h1 class="h2 mb-2">Change Password</h1>
          <p class="text-muted">Keep your account secure with a strong password</p>
        </div>

        <div class="row justify-content-center">
          <div class="col-md-8 col-lg-6">
            <div class="card password-card">
              <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Update Your Password</h5>
              </div>
              <div class="card-body">
                <form method="POST" action="index.php?action=customer/profile/change-password" id="passwordForm">
                  <!-- Current Password -->
                  <div class="mb-4">
                    <label class="form-label">Current Password *</label>
                    <div class="input-group">
                      <input type="password"
                        class="form-control"
                        name="current_password"
                        id="currentPassword"
                        required>
                      <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('currentPassword')">
                        <i class="bi bi-eye"></i>
                      </button>
                    </div>
                    <div class="form-text">
                      Enter your current password to verify it's you
                    </div>
                  </div>

                  <!-- New Password -->
                  <div class="mb-4">
                    <label class="form-label">New Password *</label>
                    <div class="input-group">
                      <input type="password"
                        class="form-control"
                        name="new_password"
                        id="newPassword"
                        required
                        onkeyup="checkPasswordStrength()">
                      <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('newPassword')">
                        <i class="bi bi-eye"></i>
                      </button>
                    </div>
                    <div class="password-strength" id="passwordStrength"></div>
                    <div class="form-text">
                      Must be at least 6 characters with one uppercase letter and one number
                    </div>
                    <div class="mt-2">
                      <small class="text-muted">Password must include:</small>
                      <ul class="list-unstyled small mt-1">
                        <li id="lengthCheck" class="text-danger">
                          <i class="bi bi-x-circle"></i> At least 6 characters
                        </li>
                        <li id="upperCheck" class="text-danger">
                          <i class="bi bi-x-circle"></i> One uppercase letter
                        </li>
                        <li id="numberCheck" class="text-danger">
                          <i class="bi bi-x-circle"></i> One number
                        </li>
                      </ul>
                    </div>
                  </div>

                  <!-- Confirm New Password -->
                  <div class="mb-4">
                    <label class="form-label">Confirm New Password *</label>
                    <div class="input-group">
                      <input type="password"
                        class="form-control"
                        name="confirm_password"
                        id="confirmPassword"
                        required
                        onkeyup="checkPasswordMatch()">
                      <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmPassword')">
                        <i class="bi bi-eye"></i>
                      </button>
                    </div>
                    <div id="passwordMatch" class="form-text"></div>
                  </div>

                  <!-- Password Requirements -->
                  <div class="alert alert-info">
                    <h6><i class="bi bi-key me-1"></i>Password Security Tips:</h6>
                    <ul class="mb-0 small">
                      <li>Use a unique password not used elsewhere</li>
                      <li>Combine letters, numbers, and special characters</li>
                      <li>Avoid personal information like birthdays</li>
                      <li>Consider using a password manager</li>
                    </ul>
                  </div>

                  <!-- Form Actions -->
                  <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                      <i class="bi bi-check-circle me-1"></i> Update Password
                    </button>
                    <a href="index.php?action=customer/profile" class="btn btn-outline-secondary">
                      <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                  </div>
                </form>
              </div>
            </div>

            <!-- Security Information -->
            <div class="card mt-4">
              <div class="card-body">
                <h6><i class="bi bi-shield-check me-2"></i>Account Security</h6>
                <p class="small text-muted mb-3">
                  Regularly updating your password helps protect your account.
                </p>
                <div class="row">
                  <div class="col-md-6">
                    <div class="d-flex align-items-center mb-2">
                      <i class="bi bi-clock-history text-primary me-2"></i>
                      <span class="small">Last password change: <?php echo 'Recently'; ?></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="d-flex align-items-center mb-2">
                      <i class="bi bi-device-phone text-primary me-2"></i>
                      <span class="small">Active sessions: 1</span>
                    </div>
                  </div>
                </div>
                <div class="mt-3">
                  <a href="index.php?action=logout" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout All Devices
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <?php include '../layout/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Password strength checker
    function checkPasswordStrength() {
      const password = document.getElementById('newPassword').value;
      const strengthBar = document.getElementById('passwordStrength');
      const lengthCheck = document.getElementById('lengthCheck');
      const upperCheck = document.getElementById('upperCheck');
      const numberCheck = document.getElementById('numberCheck');
      const submitBtn = document.getElementById('submitBtn');

      // Check individual requirements
      const hasLength = password.length >= 6;
      const hasUpper = /[A-Z]/.test(password);
      const hasNumber = /\d/.test(password);

      // Update requirement checks
      lengthCheck.className = hasLength ? 'text-success' : 'text-danger';
      lengthCheck.innerHTML = (hasLength ? '<i class="bi bi-check-circle"></i>' : '<i class="bi bi-x-circle"></i>') + ' At least 6 characters';

      upperCheck.className = hasUpper ? 'text-success' : 'text-danger';
      upperCheck.innerHTML = (hasUpper ? '<i class="bi bi-check-circle"></i>' : '<i class="bi bi-x-circle"></i>') + ' One uppercase letter';

      numberCheck.className = hasNumber ? 'text-success' : 'text-danger';
      numberCheck.innerHTML = (hasNumber ? '<i class="bi bi-check-circle"></i>' : '<i class="bi bi-x-circle"></i>') + ' One number';

      // Calculate strength
      let strength = 0;
      if (hasLength) strength += 1;
      if (hasUpper) strength += 1;
      if (hasNumber) strength += 1;
      if (password.length >= 8) strength += 1;
      if (/[!@#$%^&*]/.test(password)) strength += 1;

      // Update strength bar
      strengthBar.className = 'password-strength ';
      if (strength <= 1) {
        strengthBar.className += 'strength-weak';
      } else if (strength <= 2) {
        strengthBar.className += 'strength-fair';
      } else if (strength <= 3) {
        strengthBar.className += 'strength-good';
      } else {
        strengthBar.className += 'strength-strong';
      }

      // Enable/disable submit button
      const allValid = hasLength && hasUpper && hasNumber;
      submitBtn.disabled = !allValid;
    }

    // Password match checker
    function checkPasswordMatch() {
      const password = document.getElementById('newPassword').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      const matchText = document.getElementById('passwordMatch');
      const submitBtn = document.getElementById('submitBtn');

      if (confirmPassword === '') {
        matchText.innerHTML = '';
        return;
      }

      if (password === confirmPassword) {
        matchText.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> Passwords match</span>';
        submitBtn.disabled = false;
      } else {
        matchText.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle"></i> Passwords do not match</span>';
        submitBtn.disabled = true;
      }
    }

    // Toggle password visibility
    function togglePassword(inputId) {
      const input = document.getElementById(inputId);
      const button = input.parentNode.querySelector('button');
      const icon = button.querySelector('i');

      if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
      } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
      }
    }

    // Form validation
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
      const currentPassword = document.getElementById('currentPassword').value;
      const newPassword = document.getElementById('newPassword').value;
      const confirmPassword = document.getElementById('confirmPassword').value;

      if (!currentPassword) {
        e.preventDefault();
        alert('Please enter your current password');
        return;
      }

      if (newPassword.length < 6) {
        e.preventDefault();
        alert('New password must be at least 6 characters');
        return;
      }

      if (!/[A-Z]/.test(newPassword) || !/\d/.test(newPassword)) {
        e.preventDefault();
        alert('New password must contain at least one uppercase letter and one number');
        return;
      }

      if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('New passwords do not match');
        return;
      }

      // All validations passed
      return true;
    });

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
