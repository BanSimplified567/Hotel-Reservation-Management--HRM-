<?php
// app/views/auth/forgot-password.php
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Bannie State Of Cebu System | Reset Password</title>
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
          rgba(206, 17, 38, 0.05) 100%),
        url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="white"/><path d="M0,0 L100,100 M100,0 L0,100" stroke="rgba(0,56,147,0.03)" stroke-width="2"/></svg>');
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 90vh;
      margin: 0;
      padding: 15px;
      font-size: 14px;
    }

    .reset-grid-container {
      max-width: 750px;
      width: 100%;
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 56, 147, 0.1);
      overflow: hidden;
      animation: fadeInUp 0.6s ease-out;
      border: 1px solid rgba(0, 56, 147, 0.08);
      display: grid;
      grid-template-columns: 1fr 1fr;
      min-height: 500px;
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

    /* Left Side - Help/Info Section */
    .reset-help {
      background: linear-gradient(135deg, var(--ph-blue) 0%, var(--ph-dark-blue) 100%);
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

    .reset-help::before {
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

    .help-logo-container {
      position: relative;
      width: 100px;
      height: 100px;
      margin-bottom: 25px;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
      border: 4px solid var(--ph-yellow);
      z-index: 1;
    }

    .help-logo-container img {
      width: 75%;
      height: auto;
      border-radius: 50%;
      object-fit: cover;
    }

    .help-text {
      position: relative;
      z-index: 1;
    }

    .help-title {
      font-size: 1.6rem;
      font-weight: 700;
      margin-bottom: 10px;
      letter-spacing: 0.3px;
    }

    .barangay-name-large {
      font-size: 1.1rem;
      opacity: 0.95;
      margin-bottom: 20px;
      font-weight: 400;
    }

    .help-steps {
      margin-top: 35px;
      text-align: left;
      width: 100%;
      max-width: 300px;
    }

    .step-item {
      display: flex;
      align-items: flex-start;
      margin-bottom: 20px;
      padding: 12px 15px;
      background: rgba(255, 255, 255, 0.08);
      border-radius: 10px;
      backdrop-filter: blur(4px);
      border: 1px solid rgba(255, 255, 255, 0.15);
      transition: transform 0.2s ease;
    }

    .step-item:hover {
      transform: translateX(3px);
      background: rgba(255, 255, 255, 0.12);
    }

    .step-number {
      font-size: 1rem;
      color: var(--ph-yellow);
      margin-right: 15px;
      width: 32px;
      height: 32px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      flex-shrink: 0;
    }

    .step-text {
      flex: 1;
    }

    .step-title {
      font-weight: 600;
      margin-bottom: 5px;
      font-size: 0.95rem;
    }

    .step-desc {
      opacity: 0.85;
      font-size: 0.8rem;
      line-height: 1.3;
    }

    /* Right Side - Reset Form */
    .reset-form-container {
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
      color: var(--ph-dark-blue);
      font-weight: 700;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
    }

    .form-icon {
      font-size: 2rem;
      color: var(--ph-blue);
    }

    .form-subtitle {
      color: #666;
      font-size: 0.9rem;
      max-width: 350px;
      margin: 0 auto;
      line-height: 1.4;
    }

    .alert {
      border-radius: 8px;
      border: none;
      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.04);
      margin-bottom: 20px;
      padding: 12px 15px;
      font-size: 0.85rem;
    }

    .alert-success {
      background-color: #e8f7ef;
      color: #006442;
      border-left: 3px solid #28a745;
    }

    .alert-info {
      background-color: #e8f1ff;
      color: var(--ph-dark-blue);
      border-left: 3px solid var(--ph-blue);
    }

    .alert-danger {
      background-color: #ffe6e9;
      color: #b00020;
      border-left: 3px solid var(--ph-red);
    }

    .form-label {
      color: var(--ph-dark-blue);
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
      border-color: var(--ph-blue);
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

    .input-icon {
      color: var(--ph-blue);
      width: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #f8fafd;
      border-right: 1px solid #e0e6f0;
      font-size: 0.9rem;
    }

    .btn-reset {
      background: linear-gradient(135deg, var(--ph-blue) 0%, var(--ph-dark-blue) 100%);
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
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      height: 44px;
    }

    .btn-reset:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(0, 56, 147, 0.25);
    }

    .btn-reset:active {
      transform: translateY(0);
    }

    .btn-back {
      background: transparent;
      color: var(--ph-blue);
      width: 100%;
      padding: 10px;
      border: 1.5px solid var(--ph-blue);
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.9rem;
      transition: all 0.25s ease;
      margin-top: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      height: 40px;
    }

    .btn-back:hover {
      background: var(--ph-light-blue);
      transform: translateY(-1px);
    }

    .reset-footer {
      text-align: center;
      margin-top: 25px;
      padding-top: 20px;
      border-top: 1px solid #eaeff5;
    }

    .reset-footer a {
      color: var(--ph-blue);
      text-decoration: none;
      font-weight: 500;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      font-size: 0.85rem;
    }

    .reset-footer a:hover {
      color: var(--ph-red);
      text-decoration: underline;
    }

    .reset-footer p {
      margin-bottom: 10px;
      color: #666;
      font-size: 0.8rem;
    }

    .form-text {
      color: #6c757d;
      font-size: 0.8rem;
      margin-top: 4px;
    }

    .success-message {
      background: #e8f7ef;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      margin-bottom: 25px;
      border-left: 4px solid #28a745;
    }

    .success-icon {
      font-size: 2.5rem;
      color: #28a745;
      margin-bottom: 15px;
    }

    .success-title {
      font-size: 1.2rem;
      color: #006442;
      font-weight: 600;
      margin-bottom: 8px;
    }

    .success-text {
      color: #555;
      line-height: 1.5;
      font-size: 0.9rem;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
      .reset-grid-container {
        grid-template-columns: 1fr;
        max-width: 500px;
        min-height: auto;
      }

      .reset-help {
        padding: 30px 25px;
      }

      .help-title {
        font-size: 1.4rem;
      }

      .reset-form-container {
        padding: 30px 25px;
      }

      body {
        padding: 12px;
        font-size: 13px;
      }
    }

    @media (max-width: 576px) {
      .reset-grid-container {
        border-radius: 10px;
        max-width: 100%;
      }

      .reset-help {
        padding: 25px 20px;
      }

      .help-logo-container {
        width: 85px;
        height: 85px;
        margin-bottom: 20px;
      }

      .help-title {
        font-size: 1.3rem;
      }

      .reset-form-container {
        padding: 25px 20px;
      }

      .form-title {
        font-size: 1.3rem;
        gap: 10px;
      }

      .form-icon {
        font-size: 1.8rem;
      }

      body {
        padding: 10px;
      }

      .form-control {
        padding: 8px 10px;
        height: 38px;
      }

      .btn-reset {
        padding: 10px;
        height: 40px;
        font-size: 0.9rem;
      }

      .btn-back {
        padding: 9px;
        height: 38px;
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

    /* Step animation */
    @keyframes float {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-3px);
      }
    }

    .step-item:nth-child(1) .step-number {
      animation: float 3s ease-in-out infinite;
    }

    .step-item:nth-child(2) .step-number {
      animation: float 3s ease-in-out infinite 0.5s;
    }

    .step-item:nth-child(3) .step-number {
      animation: float 3s ease-in-out infinite 1s;
    }

    /* Progress indicator */
    .progress-indicator {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-bottom: 25px;
    }

    .progress-step {
      width: 10px;
      height: 10px;
      background: #e0e6f0;
      border-radius: 50%;
      transition: all 0.25s ease;
    }

    .progress-step.active {
      background: var(--ph-blue);
      transform: scale(1.1);
    }

    .progress-step.completed {
      background: #28a745;
    }

    /* Email preview */
    .email-preview {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 15px;
      margin-top: 15px;
      border-left: 3px solid var(--ph-blue);
    }

    .email-preview h6 {
      color: var(--ph-dark-blue);
      margin-bottom: 8px;
      font-size: 0.9rem;
      font-weight: 600;
    }

    .email-preview p {
      color: #666;
      font-size: 0.8rem;
      line-height: 1.4;
      margin-bottom: 5px;
    }
  </style>
</head>

<body>
  <div class="reset-grid-container">
    <!-- Left Column: Help/Steps Section -->
    <div class="reset-help">
      <div class="help-logo-container">
        <img src="../assets/Sibonga.jpg" alt="Sibonga Barangay Seal">
      </div>

      <div class="help-text">
        <h1 class="help-title">Reset Your Password</h1>
        <p class="barangay-name-large">Barangay Sibonga Management System</p>
      </div>

      <div class="help-steps">
        <div class="step-item">
          <div class="step-number">1</div>
          <div class="step-text">
            <div class="step-title">Enter Email Address</div>
            <div class="step-desc">Provide the email associated with your account</div>
          </div>
        </div>

        <div class="step-item">
          <div class="step-number">2</div>
          <div class="step-text">
            <div class="step-title">Check Your Email</div>
            <div class="step-desc">We'll send you a password reset link</div>
          </div>
        </div>

        <div class="step-item">
          <div class="step-number">3</div>
          <div class="step-text">
            <div class="step-title">Set New Password</div>
            <div class="step-desc">Create a strong new password for your account</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: Reset Form -->
    <div class="reset-form-container">
      <?php if (isset($_SESSION['success'])): ?>
        <!-- Success Message -->
        <div class="success-message">
          <div class="success-icon">
            <i class="bi bi-envelope-check-fill"></i>
          </div>
          <h3 class="success-title">Check Your Email!</h3>
          <p class="success-text">
            We've sent password reset instructions to your email address.<br>
            Please check your inbox and follow the link to reset your password.
          </p>
          <div class="email-preview mt-4">
            <h6><i class="bi bi-info-circle me-2"></i>What to Expect:</h6>
            <p class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i>Email from: <strong>noreply@sibonga.gov.ph</strong></p>
            <p class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i>Subject: <strong>Barangay Sibonga - Password Reset Request</strong></p>
            <p class="mb-0"><i class="bi bi-clock me-2 text-success"></i>Link expires in: <strong>1 hour</strong></p>
          </div>
        </div>

        <div class="reset-footer">
          <p class="mb-3">
            Didn't receive the email?
            <a href="index.php?action=forgot-password">
              <i class="bi bi-arrow-clockwise me-1"></i>Try Again
            </a>
          </p>
          <p class="mb-0">
            <a href="index.php?action=login">
              <i class="bi bi-box-arrow-in-right me-1"></i>Back to Login
            </a>
          </p>
        </div>
      <?php else: ?>
        <!-- Reset Form -->
        <div class="form-header">
          <h2 class="form-title">
            <i class="bi bi-key-fill form-icon"></i>
            Forgot Password
          </h2>
          <p class="form-subtitle">
            Enter your email address and we'll send you instructions to reset your password.
          </p>
        </div>

        <!-- Progress Indicator -->
        <div class="progress-indicator">
          <div class="progress-step active"></div>
          <div class="progress-step"></div>
          <div class="progress-step"></div>
        </div>

        <!-- Error Messages -->
        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div><?php echo htmlspecialchars($_SESSION['error']);
                  unset($_SESSION['error']); ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <!-- Info Message -->
        <?php if (isset($_SESSION['info'])): ?>
          <div class="alert alert-info alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            <div><?php echo htmlspecialchars($_SESSION['info']);
                  unset($_SESSION['info']); ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <!-- Reset Form -->
        <form action="index.php?action=forgot-password" method="POST" id="resetForm">
          <div class="mb-4">
            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-icon">
                <i class="bi bi-envelope-fill"></i>
              </span>
              <input type="email" class="form-control" id="email" name="email"
                value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"
                autocomplete="email" required
                placeholder="Enter your account email">
            </div>
            <div class="form-text">Make sure this is the email you used to register your account</div>
          </div>

          <button type="submit" class="btn btn-reset pulse">
            <i class="bi bi-send-fill"></i>
            Send Reset Instructions
          </button>

          <button type="button" class="btn btn-back" onclick="window.location.href='index.php?action=login'">
            <i class="bi bi-arrow-left"></i>
            Back to Login
          </button>
        </form>

        <div class="reset-footer">
          <p class="mb-3">
            Don't remember which email you used?
            <a href="index.php?action=contact-support">
              <i class="bi bi-headset me-1"></i>Contact Support
            </a>
          </p>
          <p class="mb-0">
            <a href="index.php?action=register">
              <i class="bi bi-person-plus-fill me-1"></i>Create New Account
            </a>
          </p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Auto-focus on email field
      const emailField = document.querySelector('#email');
      if (emailField) {
        emailField.focus();
      }

      // Form submission animation
      const resetForm = document.getElementById('resetForm');
      if (resetForm) {
        resetForm.addEventListener('submit', function(e) {
          const submitBtn = this.querySelector('.btn-reset');
          if (submitBtn) {
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Sending Email...';
            submitBtn.disabled = true;
            submitBtn.classList.remove('pulse');
          }
        });
      }

      // Email validation on blur
      if (emailField) {
        emailField.addEventListener('blur', function() {
          const email = this.value.trim();
          if (email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
              this.classList.add('is-invalid');
            } else {
              this.classList.remove('is-invalid');
            }
          }
        });
      }

      // Add input focus effects
      const inputs = document.querySelectorAll('.form-control');
      inputs.forEach(input => {
        input.addEventListener('focus', function() {
          this.parentElement.classList.add('focus');
        });

        input.addEventListener('blur', function() {
          this.parentElement.classList.remove('focus');
        });
      });

      // Step items hover effect
      const stepItems = document.querySelectorAll('.step-item');
      stepItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
          const number = this.querySelector('.step-number');
          number.style.animationPlayState = 'paused';
        });

        item.addEventListener('mouseleave', function() {
          const number = this.querySelector('.step-number');
          number.style.animationPlayState = 'running';
        });
      });

      // Simulate progress animation for demo
      const progressSteps = document.querySelectorAll('.progress-step');
      let currentStep = 0;

      function updateProgress() {
        progressSteps.forEach((step, index) => {
          step.classList.remove('active', 'completed');
          if (index < currentStep) {
            step.classList.add('completed');
          } else if (index === currentStep) {
            step.classList.add('active');
          }
        });
      }

      // Animate progress on form interaction
      if (emailField && progressSteps.length > 0) {
        emailField.addEventListener('input', function() {
          if (this.value.trim().length > 0) {
            currentStep = 1;
            updateProgress();
          } else {
            currentStep = 0;
            updateProgress();
          }
        });
      }

      // Initialize progress
      updateProgress();

      // Add tooltip for info icon
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    });
  </script>
</body>

</html>
