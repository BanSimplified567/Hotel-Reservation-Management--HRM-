<?php
// app/views/Auth/reset-password.php
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
$token = $_GET['token'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Bannie State Of Cebu System | Set New Password</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --ph-blue: #003893;
      --ph-red: #ce1126;
      --ph-yellow: #fcd116;
      --ph-dark-blue: #002a6e;
      --ph-light-blue: #e8f1ff;
    }

    body {
      background: linear-gradient(135deg,
          rgba(0, 56, 147, 0.05) 0%,
          rgba(206, 17, 38, 0.05) 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }

    .reset-container {
      max-width: 500px;
      width: 100%;
      background-color: white;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 56, 147, 0.15);
      overflow: hidden;
      animation: fadeInUp 0.6s ease-out;
      border: 1px solid rgba(0, 56, 147, 0.1);
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .reset-header {
      background: linear-gradient(135deg, var(--ph-blue) 0%, var(--ph-dark-blue) 100%);
      color: white;
      padding: 40px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .reset-header::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
      background-size: 20px 20px;
      opacity: 0.1;
    }

    .reset-logo {
      width: 100px;
      height: 100px;
      margin: 0 auto 20px;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      border: 4px solid var(--ph-yellow);
    }

    .reset-logo img {
      width: 70%;
      height: auto;
      border-radius: 50%;
      object-fit: cover;
    }

    .reset-title {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .reset-subtitle {
      opacity: 0.9;
      font-size: 1.1rem;
    }

    .reset-body {
      padding: 40px;
    }

    .alert {
      border-radius: 10px;
      border: none;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      margin-bottom: 25px;
    }

    .alert-success {
      background-color: #e8f7ef;
      color: #006442;
      border-left: 4px solid #28a745;
    }

    .alert-danger {
      background-color: #ffe6e9;
      color: #b00020;
      border-left: 4px solid var(--ph-red);
    }

    .form-label {
      color: var(--ph-dark-blue);
      font-weight: 600;
      margin-bottom: 8px;
      font-size: 0.95rem;
    }

    .input-group {
      box-shadow: 0 3px 6px rgba(0, 56, 147, 0.05);
      border-radius: 10px;
      overflow: hidden;
      border: 2px solid #e0e6f0;
      transition: all 0.3s ease;
    }

    .input-group:focus-within {
      border-color: var(--ph-blue);
      box-shadow: 0 0 0 0.25rem rgba(0, 56, 147, 0.15);
    }

    .form-control {
      border: none;
      padding: 14px 15px;
      font-size: 1rem;
    }

    .form-control:focus {
      box-shadow: none;
    }

    .input-icon {
      color: var(--ph-blue);
      width: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #f8fafd;
      border-right: 1px solid #e0e6f0;
    }

    .input-group-text {
      background-color: white;
      border: none;
      padding: 0 15px;
      cursor: pointer;
      color: var(--ph-blue);
    }

    .btn-reset {
      background: linear-gradient(135deg, var(--ph-blue) 0%, var(--ph-dark-blue) 100%);
      color: white;
      width: 100%;
      padding: 15px;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      font-size: 1.1rem;
      transition: all 0.3s ease;
      margin-top: 10px;
      box-shadow: 0 5px 15px rgba(0, 56, 147, 0.2);
      letter-spacing: 0.5px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .btn-reset:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0, 56, 147, 0.3);
    }

    .btn-back {
      background: transparent;
      color: var(--ph-blue);
      width: 100%;
      padding: 12px;
      border: 2px solid var(--ph-blue);
      border-radius: 10px;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.3s ease;
      margin-top: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .btn-back:hover {
      background: var(--ph-light-blue);
      transform: translateY(-2px);
    }

    .form-text {
      color: #6c757d;
      font-size: 0.875rem;
      margin-top: 5px;
    }

    .password-strength {
      margin-top: 5px;
      font-size: 0.875rem;
    }

    /* Password strength indicators */
    .strength-weak {
      color: #dc3545;
    }

    .strength-fair {
      color: #fd7e14;
    }

    .strength-good {
      color: #20c997;
    }

    .strength-strong {
      color: #198754;
    }

    .pulse {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% {
        box-shadow: 0 0 0 0 rgba(0, 56, 147, 0.2);
      }

      70% {
        box-shadow: 0 0 0 10px rgba(0, 56, 147, 0);
      }

      100% {
        box-shadow: 0 0 0 0 rgba(0, 56, 147, 0);
      }
    }

    @media (max-width: 576px) {
      .reset-container {
        border-radius: 15px;
      }

      .reset-header {
        padding: 30px 20px;
      }

      .reset-logo {
        width: 80px;
        height: 80px;
      }

      .reset-title {
        font-size: 1.6rem;
      }

      .reset-body {
        padding: 30px 25px;
      }

      body {
        padding: 15px;
      }
    }
  </style>
</head>

<body>
  <div class="reset-container">
    <div class="reset-header">
      <div class="reset-logo">
        <img src="../assets/Sibonga.jpg" alt="Sibonga Barangay Seal">
      </div>
      <h1 class="reset-title">Set New Password</h1>
      <p class="reset-subtitle">Barangay Sibonga Management System</p>
    </div>

    <div class="reset-body">
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <div><?php echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']); ?></div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i>
          <div><?php echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']); ?></div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <div class="text-center mt-4">
          <a href="index.php?action=login" class="btn btn-reset">
            <i class="bi bi-box-arrow-in-right me-2"></i>Login Now
          </a>
        </div>
      <?php else: ?>
        <p class="text-center text-muted mb-4">
          <i class="bi bi-info-circle me-2"></i>
          Please enter your new password below.
        </p>

        <form action="index.php?action=reset-password" method="POST" id="resetForm">
          <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

          <div class="mb-4">
            <label for="password" class="form-label">New Password <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-icon">
                <i class="bi bi-lock-fill"></i>
              </span>
              <input type="password" class="form-control" id="password" name="password"
                required minlength="6" autocomplete="new-password"
                placeholder="Enter new password">
              <button class="btn input-group-text" type="button" id="togglePassword">
                <i class="bi bi-eye"></i>
              </button>
            </div>
            <div class="password-strength" id="passwordStrength">
              <small>Password strength: <span class="strength-weak">Weak</span></small>
            </div>
          </div>

          <div class="mb-4">
            <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-icon">
                <i class="bi bi-lock-fill"></i>
              </span>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                required minlength="6" autocomplete="new-password"
                placeholder="Confirm new password">
              <button class="btn input-group-text" type="button" id="toggleConfirmPassword">
                <i class="bi bi-eye"></i>
              </button>
            </div>
            <div id="passwordMatch" class="form-text"></div>
          </div>

          <div class="mb-4">
            <div class="form-text">
              <i class="bi bi-shield-check me-1"></i>
              Password must be at least 6 characters long.
            </div>
          </div>

          <button type="submit" class="btn btn-reset pulse">
            <i class="bi bi-key-fill me-2"></i>Reset Password
          </button>

          <button type="button" class="btn btn-back" onclick="window.location.href='index.php?action=login'">
            <i class="bi bi-arrow-left me-2"></i>Back to Login
          </button>
        </form>
      <?php endif; ?>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Toggle password visibility
      const togglePassword = document.querySelector('#togglePassword');
      const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
      const password = document.querySelector('#password');
      const confirmPassword = document.querySelector('#confirm_password');

      function setupPasswordToggle(button, field) {
        if (button && field) {
          button.addEventListener('click', function() {
            const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', type);
            const icon = this.querySelector('i');
            if (icon) {
              icon.classList.toggle('bi-eye');
              icon.classList.toggle('bi-eye-slash');
            }
          });
        }
      }

      setupPasswordToggle(togglePassword, password);
      setupPasswordToggle(toggleConfirmPassword, confirmPassword);

      // Password strength and match validation
      function updatePasswordStrength(value) {
        const hasUpperCase = /[A-Z]/.test(value);
        const hasNumber = /[0-9]/.test(value);
        const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(value);
        const isLongEnough = value.length >= 6;
        const isVeryLong = value.length >= 12;

        let strength = 0;
        if (isLongEnough) strength += 1;
        if (hasUpperCase) strength += 1;
        if (hasNumber) strength += 1;
        if (hasSpecial) strength += 1;
        if (isVeryLong) strength += 1;

        const strengthLevels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
        const strengthClasses = ['strength-weak', 'strength-weak', 'strength-fair', 'strength-good', 'strength-strong', 'strength-strong'];

        const strengthDiv = document.getElementById('passwordStrength');
        if (strengthDiv) {
          strengthDiv.innerHTML = `<small>Password strength: <span class="${strengthClasses[strength]}">${strengthLevels[strength]}</span></small>`;
        }
      }

      function validatePassword() {
        const passwordValue = password.value;
        const confirmValue = confirmPassword.value;
        const matchDiv = document.getElementById('passwordMatch');

        // Update password strength
        updatePasswordStrength(passwordValue);

        // Check match
        if (confirmValue) {
          if (passwordValue === confirmValue) {
            matchDiv.innerHTML = '<span class="text-success">✓ Passwords match</span>';
          } else {
            matchDiv.innerHTML = '<span class="text-danger">✗ Passwords do not match</span>';
          }
        } else {
          matchDiv.innerHTML = '<span class="text-muted">Enter password confirmation</span>';
        }
      }

      if (password) password.addEventListener('input', validatePassword);
      if (confirmPassword) confirmPassword.addEventListener('input', validatePassword);

      // Initialize password strength
      if (password) updatePasswordStrength(password.value);

      // Form submission animation
      const resetForm = document.getElementById('resetForm');
      if (resetForm) {
        resetForm.addEventListener('submit', function(e) {
          const submitBtn = this.querySelector('.btn-reset');
          if (submitBtn) {
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Resetting Password...';
            submitBtn.disabled = true;
            submitBtn.classList.remove('pulse');
          }
        });
      }

      // Auto-focus on password field
      const passwordField = document.querySelector('#password');
      if (passwordField) {
        passwordField.focus();
      }
    });
  </script>
</body>

</html>
