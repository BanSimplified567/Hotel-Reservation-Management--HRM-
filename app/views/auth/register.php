<?php
// app/views/Auth/register.php
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Reservation System | Register</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --primary-blue: #003893;
      --primary-red: #ce1126;
      --primary-yellow: #fcd116;
      --dark-blue: #002a6e;
      --light-blue: #e8f1ff;
    }

    body {
      background: linear-gradient(135deg,
          rgba(0, 56, 147, 0.05) 0%,
          rgba(206, 17, 38, 0.05) 100%),
        url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="white"/><path d="M0,0 L100,100 M100,0 L0,100" stroke="rgba(0,56,147,0.03)" stroke-width="2"/></svg>');
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 15px;
      font-size: 14px;
    }

    .register-grid-container {
      max-width: 1000px;
      width: 100%;
      background-color: white;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 56, 147, 0.12);
      overflow: hidden;
      animation: fadeInUp 0.6s ease-out;
      border: 1px solid rgba(0, 56, 147, 0.08);
      display: grid;
      grid-template-columns: 1fr 1fr;
      min-height: 600px;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Left Side - Welcome/Info Section */
    .register-welcome {
      background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
      color: white;
      padding: 35px 30px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .register-welcome::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
      background-size: 18px 18px;
      opacity: 0.08;
    }

    .welcome-logo-container {
      position: relative;
      width: 120px;
      height: 120px;
      margin-bottom: 30px;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
      border: 4px solid var(--primary-yellow);
      z-index: 1;
    }

    .welcome-logo-container img {
      width: 75%;
      height: auto;
      border-radius: 50%;
      object-fit: cover;
    }

    .welcome-text {
      position: relative;
      z-index: 1;
    }

    .welcome-title {
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 10px;
      letter-spacing: 0.3px;
    }

    .hotel-name-large {
      font-size: 1.2rem;
      opacity: 0.95;
      margin-bottom: 25px;
      font-weight: 400;
    }

    .welcome-benefits {
      margin-top: 35px;
      text-align: left;
      width: 100%;
      max-width: 300px;
    }

    .benefit-item {
      display: flex;
      align-items: center;
      margin-bottom: 18px;
      padding: 12px 15px;
      background: rgba(255, 255, 255, 0.08);
      border-radius: 10px;
      backdrop-filter: blur(4px);
      border: 1px solid rgba(255, 255, 255, 0.15);
      transition: transform 0.2s ease;
    }

    .benefit-item:hover {
      transform: translateX(3px);
      background: rgba(255, 255, 255, 0.12);
    }

    .benefit-icon {
      font-size: 1.4rem;
      color: var(--primary-yellow);
      margin-right: 15px;
      width: 40px;
      text-align: center;
    }

    .benefit-text {
      flex: 1;
    }

    .benefit-title {
      font-weight: 600;
      margin-bottom: 4px;
      font-size: 0.95rem;
    }

    .benefit-desc {
      opacity: 0.85;
      font-size: 0.8rem;
      line-height: 1.3;
    }

    /* Right Side - Registration Form */
    .register-form-container {
      padding: 40px 35px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      background-color: white;
    }

    .form-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .form-title {
      font-size: 1.6rem;
      color: var(--dark-blue);
      font-weight: 700;
      margin-bottom: 8px;
    }

    .form-subtitle {
      color: #666;
      font-size: 0.9rem;
    }

    .alert {
      border-radius: 8px;
      border: none;
      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.04);
      margin-bottom: 20px;
      padding: 12px 15px;
      font-size: 0.85rem;
    }

    .alert-danger {
      background-color: #ffe6e9;
      color: #b00020;
      border-left: 3px solid var(--primary-red);
    }

    .alert-success {
      background-color: #e8f7ef;
      color: #006442;
      border-left: 3px solid #28a745;
    }

    .form-label {
      color: var(--dark-blue);
      font-weight: 600;
      margin-bottom: 6px;
      font-size: 0.85rem;
    }

    .input-group {
      box-shadow: 0 2px 4px rgba(0, 56, 147, 0.04);
      border-radius: 8px;
      overflow: hidden;
      border: 1.5px solid #e0e6f0;
      transition: all 0.25s ease;
    }

    .input-group:focus-within {
      border-color: var(--primary-blue);
      box-shadow: 0 0 0 0.2rem rgba(0, 56, 147, 0.1);
    }

    .form-control {
      border: none;
      padding: 10px 12px;
      font-size: 0.9rem;
      height: 40px;
    }

    .form-control:focus {
      box-shadow: none;
    }

    .input-group-text {
      background-color: white;
      border: none;
      padding: 0 12px;
      cursor: pointer;
      color: var(--primary-blue);
      font-size: 0.9rem;
    }

    .btn-register {
      background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
      color: white;
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.95rem;
      transition: all 0.25s ease;
      margin-top: 8px;
      box-shadow: 0 4px 10px rgba(0, 56, 147, 0.15);
      letter-spacing: 0.3px;
      height: 44px;
    }

    .btn-register:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(0, 56, 147, 0.25);
    }

    .btn-register:active {
      transform: translateY(0);
    }

    .register-footer {
      text-align: center;
      margin-top: 25px;
      padding-top: 20px;
      border-top: 1px solid #eaeff5;
    }

    .register-footer a {
      color: var(--primary-blue);
      text-decoration: none;
      font-weight: 500;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      font-size: 0.85rem;
    }

    .register-footer a:hover {
      color: var(--primary-red);
      text-decoration: underline;
    }

    .register-footer p {
      margin-bottom: 10px;
      color: #666;
      font-size: 0.8rem;
    }

    .form-check {
      margin-top: 12px;
    }

    .form-check-input:checked {
      background-color: var(--primary-blue);
      border-color: var(--primary-blue);
    }

    .form-check-label {
      color: #555;
      font-size: 0.85rem;
    }

    .input-icon {
      color: var(--primary-blue);
      width: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #f8fafd;
      border-right: 1px solid #e0e6f0;
      font-size: 0.9rem;
    }

    .form-text {
      color: #666;
      font-size: 0.8rem;
      margin-top: 4px;
    }

    .password-strength {
      margin-top: 5px;
      font-size: 0.8rem;
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

    /* Responsive adjustments */
    @media (max-width: 992px) {
      .register-grid-container {
        grid-template-columns: 1fr;
        max-width: 500px;
        min-height: auto;
      }

      .register-welcome {
        padding: 30px 25px;
      }

      .welcome-title {
        font-size: 1.4rem;
      }

      .register-form-container {
        padding: 30px 25px;
        max-height: none;
      }

      body {
        padding: 15px;
        font-size: 13px;
      }
    }

    @media (max-width: 576px) {
      .register-grid-container {
        border-radius: 12px;
        max-width: 100%;
      }

      .register-welcome {
        padding: 25px 20px;
      }

      .welcome-logo-container {
        width: 100px;
        height: 100px;
        margin-bottom: 20px;
      }

      .welcome-title {
        font-size: 1.3rem;
      }

      .register-form-container {
        padding: 25px 20px;
      }

      .form-title {
        font-size: 1.3rem;
      }

      body {
        padding: 10px;
      }

      .form-control {
        padding: 8px 10px;
        height: 38px;
      }

      .btn-register {
        padding: 10px;
        height: 40px;
        font-size: 0.9rem;
      }
    }

    /* Custom animations */
    .pulse {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% {
        box-shadow: 0 0 0 0 rgba(0, 56, 147, 0.15);
      }

      70% {
        box-shadow: 0 0 0 8px rgba(0, 56, 147, 0);
      }

      100% {
        box-shadow: 0 0 0 0 rgba(0, 56, 147, 0);
      }
    }

    /* Benefit icons animation */
    @keyframes float {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-3px);
      }
    }

    .benefit-icon {
      animation: float 3s ease-in-out infinite;
    }

    .benefit-item:nth-child(2) .benefit-icon {
      animation-delay: 0.5s;
    }

    .benefit-item:nth-child(3) .benefit-icon {
      animation-delay: 1s;
    }

    .benefit-item:nth-child(4) .benefit-icon {
      animation-delay: 1.5s;
    }
  </style>
</head>

<body>
  <div class="register-grid-container">
    <!-- Left Column: Welcome/Benefits Section -->
    <div class="register-welcome">
      <div class="welcome-logo-container">
        <i class="bi bi-building fs-1" style="color: var(--primary-blue);"></i>
      </div>

      <div class="welcome-text">
        <h1 class="welcome-title">Join Our Hotel</h1>
        <p class="hotel-name-large">Hotel Reservation System</p>
      </div>

      <div class="welcome-benefits">
        <div class="benefit-item">
          <div class="benefit-icon">
            <i class="bi bi-shield-check"></i>
          </div>
          <div class="benefit-text">
            <div class="benefit-title">Secure Registration</div>
            <div class="benefit-desc">Your personal information is protected with industry-grade security</div>
          </div>
        </div>

        <div class="benefit-item">
          <div class="benefit-icon">
            <i class="bi bi-speedometer2"></i>
          </div>
          <div class="benefit-text">
            <div class="benefit-title">Quick Booking</div>
            <div class="benefit-desc">Fast and easy room reservation process</div>
          </div>
        </div>

        <div class="benefit-item">
          <div class="benefit-icon">
            <i class="bi bi-bell-fill"></i>
          </div>
          <div class="benefit-text">
            <div class="benefit-title">Stay Updated</div>
            <div class="benefit-desc">Receive booking confirmations and special offers</div>
          </div>
        </div>

        <div class="benefit-item">
          <div class="benefit-icon">
            <i class="bi bi-star-fill"></i>
          </div>
          <div class="benefit-text">
            <div class="benefit-title">Exclusive Benefits</div>
            <div class="benefit-desc">Access to member-only discounts and promotions</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: Registration Form -->
    <div class="register-form-container">
      <div class="form-header">
        <h2 class="form-title">Create Account</h2>
        <p class="form-subtitle">Register to book rooms and manage reservations</p>
      </div>

      <!-- Success or Error Messages -->
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i>
          <div><?php echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']); ?></div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <div><?php echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']); ?></div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <!-- Registration Form -->
      <form action="index.php?action=register" method="POST" id="registerForm">
        <!-- First Name Field -->
        <div class="mb-4">
          <label for="first_name" class="form-label">
            First Name <span class="text-danger">*</span>
          </label>
          <div class="input-group">
            <span class="input-group-text d-flex align-items-center justify-content-center">
              <i class="bi bi-person-fill"></i>
            </span>
            <input type="text"
              class="form-control"
              id="first_name"
              name="first_name"
              value="<?php echo htmlspecialchars($old['first_name'] ?? ''); ?>"
              autocomplete="given-name"
              required
              minlength="2"
              placeholder="Enter your first name">
          </div>
        </div>

        <!-- Last Name Field -->
        <div class="mb-4">
          <label for="last_name" class="form-label">
            Last Name <span class="text-danger">*</span>
          </label>
          <div class="input-group">
            <span class="input-group-text d-flex align-items-center justify-content-center">
              <i class="bi bi-person-fill"></i>
            </span>
            <input type="text"
              class="form-control"
              id="last_name"
              name="last_name"
              value="<?php echo htmlspecialchars($old['last_name'] ?? ''); ?>"
              autocomplete="family-name"
              required
              minlength="2"
              placeholder="Enter your last name">
          </div>
        </div>

        <!-- Email Field -->
        <div class="mb-4">
          <label for="email" class="form-label">
            Email Address <span class="text-danger">*</span>
          </label>
          <div class="input-group">
            <span class="input-group-text d-flex align-items-center justify-content-center">
              <i class="bi bi-envelope-fill"></i>
            </span>
            <input type="email"
              class="form-control"
              id="email"
              name="email"
              value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"
              autocomplete="email"
              required
              placeholder="your.email@example.com">
          </div>
          <div class="form-text">
            We'll never share your email with anyone else
          </div>
        </div>

        <!-- Phone Field (Optional) -->
        <div class="mb-4">
          <label for="phone" class="form-label">
            Phone Number
          </label>
          <div class="input-group">
            <span class="input-group-text d-flex align-items-center justify-content-center">
              <i class="bi bi-telephone-fill"></i>
            </span>
            <input type="tel"
              class="form-control"
              id="phone"
              name="phone"
              value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>"
              autocomplete="tel"
              placeholder="(123) 456-7890">
          </div>
        </div>

        <!-- Password Field -->
        <div class="mb-4">
          <label for="password" class="form-label">
            Password <span class="text-danger">*</span>
          </label>
          <div class="input-group">
            <span class="input-group-text d-flex align-items-center justify-content-center">
              <i class="bi bi-lock-fill"></i>
            </span>
            <input type="password"
              class="form-control"
              id="password"
              name="password"
              autocomplete="new-password"
              required
              minlength="6"
              placeholder="Create a strong password">

            <button class="btn input-group-text d-flex align-items-center justify-content-center"
              type="button"
              id="togglePassword">
              <i class="bi bi-eye"></i>
            </button>
          </div>
          <div class="password-strength" id="passwordStrength">
            <small>Password strength: <span class="strength-weak">Weak</span></small>
          </div>
        </div>

        <!-- Confirm Password Field -->
        <div class="mb-4">
          <label for="confirm_password" class="form-label">
            Confirm Password <span class="text-danger">*</span>
          </label>
          <div class="input-group">
            <span class="input-group-text d-flex align-items-center justify-content-center">
              <i class="bi bi-lock-fill"></i>
            </span>
            <input type="password"
              class="form-control"
              id="confirm_password"
              name="confirm_password"
              autocomplete="new-password"
              required
              minlength="6"
              placeholder="Confirm your password">

            <button class="btn input-group-text d-flex align-items-center justify-content-center"
              type="button"
              id="toggleConfirmPassword">
              <i class="bi bi-eye"></i>
            </button>
          </div>
          <div id="passwordMatch" class="form-text"></div>
        </div>

        <!-- Terms Agreement -->
        <div class="form-check mb-4">
          <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
          <label class="form-check-label" for="terms">
            I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
          </label>
        </div>

        <button type="submit" class="btn btn-register pulse">
          <i class="bi bi-person-plus-fill me-2"></i>Create Account
        </button>
      </form>

      <div class="register-footer">
        <p class="mb-3">
          Already have an account?
          <a href="index.php?action=login">
            <i class="bi bi-box-arrow-in-right me-1"></i>Login here
          </a>
        </p>
        <p class="mb-0">
          <a href="index.php?action=forgot-password">
            <i class="bi bi-key-fill me-1"></i>Forgot Password?
          </a>
        </p>
      </div>
    </div>
  </div>

  <!-- Terms and Conditions Modal -->
  <div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Hotel Reservation System Terms and Conditions</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>By creating an account in the Hotel Reservation System, you agree to:</p>
          <ul>
            <li>Provide accurate, current, and complete information during registration</li>
            <li>Maintain and promptly update your information to keep it accurate</li>
            <li>Keep your password secure and confidential</li>
            <li>Accept responsibility for all activities that occur under your account</li>
            <li>Use the system only for legitimate hotel reservation purposes</li>
            <li>Not engage in any unlawful activities through the system</li>
            <li>Respect the hotel's cancellation and reservation policies</li>
            <li>Allow the hotel to use your information for reservation and service purposes</li>
          </ul>
          <p class="mt-3"><small>The hotel reserves the right to suspend or terminate accounts that violate these terms.</small></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
        </div>
      </div>
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
        const isLongEnough = value.length >= 8;
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

      // Form validation
      const form = document.getElementById('registerForm');
      if (form) {
        form.addEventListener('submit', function(e) {
          const terms = document.getElementById('terms');
          if (!terms.checked) {
            e.preventDefault();
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-warning alert-dismissible fade show mt-3';
            alertDiv.innerHTML = `
              <i class="bi bi-exclamation-triangle-fill me-2"></i>
              You must agree to the Terms and Conditions to continue.
              <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            `;
            const formHeader = document.querySelector('.form-header');
            if (formHeader.nextElementSibling && formHeader.nextElementSibling.classList.contains('alert')) {
              formHeader.nextElementSibling.after(alertDiv);
            } else {
              formHeader.after(alertDiv);
            }
            terms.focus();
            return;
          }

          // Client-side validation
          const passwordValue = password.value;
          const confirmValue = confirmPassword.value;

          if (passwordValue !== confirmValue) {
            e.preventDefault();
            alert('Passwords do not match! Please check your password confirmation.');
            confirmPassword.focus();
            return;
          }

          // Additional password strength validation
          if (passwordValue.length < 6) {
            e.preventDefault();
            alert('Password must be at least 6 characters long.');
            password.focus();
            return;
          }

          // Button animation on submit
          const submitBtn = this.querySelector('.btn-register');
          if (submitBtn) {
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Creating Account...';
            submitBtn.disabled = true;
            submitBtn.classList.remove('pulse');
          }
        });
      }

      // Auto-focus on first field
      const firstNameField = document.querySelector('#first_name');
      if (firstNameField) {
        firstNameField.focus();
      }

      // Benefit items hover effect
      const benefitItems = document.querySelectorAll('.benefit-item');
      benefitItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
          const icon = this.querySelector('.benefit-icon');
          icon.style.animationPlayState = 'paused';
        });

        item.addEventListener('mouseleave', function() {
          const icon = this.querySelector('.benefit-icon');
          icon.style.animationPlayState = 'running';
        });
      });

      // Modal close button fix for dark header
      const modalCloseBtn = document.querySelector('.btn-close-white');
      if (modalCloseBtn) {
        modalCloseBtn.addEventListener('click', function() {
          const modal = document.querySelector('#termsModal');
          if (modal) {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) bsModal.hide();
          }
        });
      }
    });
  </script>
</body>

</html>
