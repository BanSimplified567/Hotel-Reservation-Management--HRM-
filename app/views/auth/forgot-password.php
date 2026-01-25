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
                    rgba(206, 17, 38, 0.05) 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }

        .reset-container {
            max-width: 900px;
            width: 100%;
        }

        .reset-card {
            border: 1px solid rgba(0, 56, 147, 0.08);
            box-shadow: 0 8px 20px rgba(0, 56, 147, 0.1);
            animation: fadeInUp 0.6s ease-out;
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

        .help-section {
            background: linear-gradient(135deg, var(--ph-blue) 0%, var(--ph-dark-blue) 100%);
            position: relative;
            overflow: hidden;
        }

        .help-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 18px 18px;
            opacity: 0.08;
            pointer-events: none;
        }

        .logo-container {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.95);
            border: 4px solid var(--ph-yellow);
        }

        .step-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(4px);
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .step-card:hover {
            transform: translateX(5px);
            background: rgba(255, 255, 255, 0.12);
        }

        .step-number {
            animation: float 3s ease-in-out infinite;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.15);
            color: var(--ph-yellow);
        }

        .step-card:nth-child(2) .step-number {
            animation-delay: 0.5s;
        }

        .step-card:nth-child(3) .step-number {
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        .input-group-custom {
            border: 1.5px solid #e0e6f0;
            transition: all 0.25s ease;
        }

        .input-group-custom:focus-within {
            border-color: var(--ph-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 56, 147, 0.1);
        }

        .input-icon {
            background-color: #f8fafd;
            border-right: 1px solid #e0e6f0;
            color: var(--ph-blue);
        }

        .btn-reset {
            background: linear-gradient(135deg, var(--ph-blue) 0%, var(--ph-dark-blue) 100%);
            border: none;
            transition: all 0.25s ease;
            box-shadow: 0 4px 10px rgba(0, 56, 147, 0.15);
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 56, 147, 0.25);
        }

        .btn-reset:active {
            transform: translateY(0);
        }

        .btn-outline-ph-blue {
            border-color: var(--ph-blue);
            color: var(--ph-blue);
        }

        .btn-outline-ph-blue:hover {
            background-color: var(--ph-light-blue);
            color: var(--ph-blue);
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 4px 10px rgba(0, 56, 147, 0.15), 0 0 0 0 rgba(0, 56, 147, 0.15);
            }
            70% {
                box-shadow: 0 4px 10px rgba(0, 56, 147, 0.15), 0 0 0 8px rgba(0, 56, 147, 0);
            }
            100% {
                box-shadow: 0 4px 10px rgba(0, 56, 147, 0.15), 0 0 0 0 rgba(0, 56, 147, 0);
            }
        }

        .progress-step {
            width: 12px;
            height: 12px;
            background-color: #e0e6f0;
            transition: all 0.25s ease;
        }

        .progress-step.active {
            background-color: var(--ph-blue);
            transform: scale(1.1);
        }

        .progress-step.completed {
            background-color: #28a745;
        }

        .email-preview-card {
            background-color: #f8f9fa;
            border-left: 3px solid var(--ph-blue);
        }

        .success-icon {
            font-size: 3rem;
        }

        .text-ph-blue {
            color: var(--ph-blue) !important;
        }

        .text-ph-red {
            color: var(--ph-red) !important;
        }

        @media (max-width: 992px) {
            .reset-card {
                max-width: 500px;
                margin: 0 auto;
            }

            .logo-container {
                width: 100px;
                height: 100px;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 10px;
            }

            .reset-card {
                border-radius: 12px !important;
            }
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="row g-0 reset-card rounded-4">
            <!-- Left Column: Help/Steps Section -->
            <div class="col-lg-6 help-section text-white p-4 p-lg-5 d-flex flex-column justify-content-center">
            <div class="position-relative text-center">
  <div class="logo-container rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4 shadow bg-primary text-white"
       style="width: 90px; height: 90px;">
    <i class="bi bi-building fs-1"></i>
  </div>

  <h1 class="h2 fw-bold mb-2">Reset Your Password</h1>
  <p class="mb-5 opacity-75">Barangay Sibonga Management System</p>
</div>


                <div class="position-relative z-1 mt-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="step-card rounded-3 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="step-number rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold">
                                        1
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Enter Email Address</h6>
                                        <p class="small opacity-85 mb-0">Provide the email associated with your account</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="step-card rounded-3 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="step-number rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold">
                                        2
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Check Your Email</h6>
                                        <p class="small opacity-85 mb-0">We'll send you a password reset link</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="step-card rounded-3 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="step-number rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold">
                                        3
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Set New Password</h6>
                                        <p class="small opacity-85 mb-0">Create a strong new password for your account</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Reset Form -->
            <div class="col-lg-6 bg-white p-4 p-lg-5 d-flex flex-column justify-content-center">
                <?php if (isset($_SESSION['success'])): ?>
                    <!-- Success Message -->
                    <div class="text-center mb-4">
                        <div class="success-icon text-success mb-3">
                            <i class="bi bi-envelope-check-fill"></i>
                        </div>
                        <h2 class="h3 fw-bold text-success mb-3">Check Your Email!</h2>
                        <p class="text-muted mb-4">
                            We've sent password reset instructions to your email address.<br>
                            Please check your inbox and follow the link to reset your password.
                        </p>
                    </div>

                    <div class="card email-preview-card border-0 mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold text-ph-blue mb-3">
                                <i class="bi bi-info-circle me-2"></i>What to Expect:
                            </h6>
                            <div class="row g-2">
                                <div class="col-12">
                                    <p class="mb-2 small">
                                        <i class="bi bi-check-circle me-2 text-success"></i>
                                        Email from: <strong>noreply@sibonga.gov.ph</strong>
                                    </p>
                                </div>
                                <div class="col-12">
                                    <p class="mb-2 small">
                                        <i class="bi bi-check-circle me-2 text-success"></i>
                                        Subject: <strong>Barangay Sibonga - Password Reset Request</strong>
                                    </p>
                                </div>
                                <div class="col-12">
                                    <p class="mb-0 small">
                                        <i class="bi bi-clock me-2 text-success"></i>
                                        Link expires in: <strong>1 hour</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-3">
                        <button type="button" class="btn btn-outline-ph-blue" onclick="window.location.href='index.php?action=forgot-password'">
                            <i class="bi bi-arrow-clockwise me-2"></i>Try Again
                        </button>
                        <button type="button" class="btn btn-reset text-white" onclick="window.location.href='index.php?action=login'">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Back to Login
                        </button>
                    </div>
                <?php else: ?>
                    <!-- Reset Form -->
                    <div class="text-center mb-4">
                        <h2 class="h3 fw-bold text-ph-blue mb-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-key-fill me-3"></i>Forgot Password
                        </h2>
                        <p class="text-muted mb-4">
                            Enter your email address and we'll send you instructions to reset your password.
                        </p>
                    </div>

                    <!-- Progress Indicator -->
                    <div class="d-flex justify-content-center align-items-center gap-2 mb-4">
                        <div class="progress-step rounded-circle active"></div>
                        <div class="progress-step rounded-circle"></div>
                        <div class="progress-step rounded-circle"></div>
                    </div>

                    <!-- Error Messages -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div><?php echo htmlspecialchars($_SESSION['error']);
                                unset($_SESSION['error']); ?></div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Info Message -->
                    <?php if (isset($_SESSION['info'])): ?>
                        <div class="alert alert-info alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <div><?php echo htmlspecialchars($_SESSION['info']);
                                unset($_SESSION['info']); ?></div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Reset Form -->
                    <form action="index.php?action=forgot-password" method="POST" id="resetForm" class="mb-4">
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold text-ph-blue">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-custom rounded-3">
                                <span class="input-icon input-group-text border-0 rounded-start-3">
                                    <i class="bi bi-envelope-fill"></i>
                                </span>
                                <input type="email" class="form-control border-0 shadow-none" id="email" name="email"
                                    value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"
                                    autocomplete="email" required placeholder="Enter your account email">
                            </div>
                            <div class="form-text small mt-2">
                                Make sure this is the email you used to register your account
                            </div>
                        </div>

                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-reset text-white py-2 rounded-3 fw-semibold pulse">
                                <i class="bi bi-send-fill me-2"></i>Send Reset Instructions
                            </button>
                            <button type="button" class="btn btn-outline-ph-blue py-2 rounded-3 fw-semibold" onclick="window.location.href='index.php?action=login'">
                                <i class="bi bi-arrow-left me-2"></i>Back to Login
                            </button>
                        </div>
                    </form>

                    <div class="text-center pt-3 border-top">
                        <p class="text-muted mb-3 small">
                            Don't remember which email you used?
                            <a href="index.php?action=contact-support" class="text-ph-blue text-decoration-none fw-semibold">
                                <i class="bi bi-headset me-1"></i>Contact Support
                            </a>
                        </p>
                        <p class="mb-0 small">
                            <a href="index.php?action=register" class="text-ph-blue text-decoration-none fw-semibold">
                                <i class="bi bi-person-plus-fill me-1"></i>Create New Account
                            </a>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
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

            // Step cards hover effect
            const stepCards = document.querySelectorAll('.step-card');
            stepCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    const number = this.querySelector('.step-number');
                    if (number) number.style.animationPlayState = 'paused';
                });

                card.addEventListener('mouseleave', function() {
                    const number = this.querySelector('.step-number');
                    if (number) number.style.animationPlayState = 'running';
                });
            });

            // Progress indicator animation
            const progressSteps = document.querySelectorAll('.progress-step');

            function updateProgress() {
                const emailField = document.querySelector('#email');
                if (emailField && progressSteps.length > 0) {
                    if (emailField.value.trim().length > 0) {
                        progressSteps.forEach((step, index) => {
                            step.classList.remove('active', 'completed');
                            if (index < 1) {
                                step.classList.add('completed');
                            } else if (index === 1) {
                                step.classList.add('active');
                            }
                        });
                    } else {
                        progressSteps.forEach((step, index) => {
                            step.classList.remove('active', 'completed');
                            if (index === 0) {
                                step.classList.add('active');
                            }
                        });
                    }
                }
            }

            // Initialize progress
            updateProgress();

            // Update progress on email input
            if (emailField) {
                emailField.addEventListener('input', updateProgress);
            }

            // Add tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>
