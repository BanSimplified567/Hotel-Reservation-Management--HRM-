<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Hotel Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card.bg-primary {
            background: linear-gradient(135deg, #003893 0%, #002a6e 100%) !important;
        }

        .bg-purple {
            background-color: #6f42c1 !important;
        }

        .text-purple {
            color: #6f42c1 !important;
        }

        .btn-light {
            background-color: #f8f9fa;
            border-color: #f8f9fa;
        }

        .btn-light:hover {
            background-color: #e9ecef;
            border-color: #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Display Messages -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Profile Header -->
        <div class="card border-0 shadow-lg mb-4 bg-primary text-white">
            <div class="card-body p-5">
                <div class="row align-items-center">
                    <div class="col-lg-8 mb-4 mb-lg-0">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-4"
                                 style="width: 100px; height: 100px;">
                                <span class="display-6 fw-bold">
                                    <?php echo strtoupper(substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? 'S', 0, 1)); ?>
                                </span>
                            </div>
                            <div>
                                <h1 class="display-5 fw-bold mb-2"><?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')); ?></h1>
                                <p class="text-white text-opacity-80 mb-3">
                                    <i class="bi bi-envelope me-2"></i> <?php echo htmlspecialchars($user['email'] ?? ''); ?>
                                </p>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-white bg-opacity-20 text-white">
                                        <i class="bi bi-person me-1"></i> <?php echo ucfirst($user['role'] ?? 'Customer'); ?>
                                    </span>
                                    <span class="badge bg-white bg-opacity-20 text-white">
                                        Member since <?php echo date('M Y', strtotime($user['created_at'] ?? 'now')); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="d-flex flex-column flex-md-row gap-3 justify-content-lg-end">
                            <a href="index.php?action=profile&sub_action=edit" class="btn btn-light text-primary fw-semibold">
                                <i class="bi bi-pencil me-2"></i> Edit Profile
                            </a>
                            <a href="index.php?index.php?action=profile&sub_action=change-password"
                               class="btn btn-outline-light fw-semibold border-white border-opacity-30">
                                <i class="bi bi-key me-2"></i> Change Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column: Profile Information -->
            <div class="col-lg-8">
                <!-- Personal Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="h4 fw-bold text-dark d-flex align-items-center">
                                <i class="bi bi-person-circle text-primary me-2"></i> Personal Information
                            </h2>
                            <a href="index.php?action=profile&sub_action=edit" class="text-primary text-decoration-none small fw-semibold">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">First Name</p>
                                <p class="fw-semibold text-dark mb-0"><?php echo htmlspecialchars($user['first_name'] ?? 'Not provided'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Last Name</p>
                                <p class="fw-semibold text-dark mb-0"><?php echo htmlspecialchars($user['last_name'] ?? 'Not provided'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Email Address</p>
                                <p class="fw-semibold text-dark mb-0"><?php echo htmlspecialchars($user['email'] ?? 'Not provided'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Phone Number</p>
                                <p class="fw-semibold text-dark mb-0"><?php echo htmlspecialchars($user['phone'] ?? 'Not provided'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Username</p>
                                <p class="fw-semibold text-dark mb-0"><?php echo htmlspecialchars($user['username'] ?? 'Not provided'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Date of Birth</p>
                                <p class="fw-semibold text-dark mb-0">
                                    <?php echo !empty($user['date_of_birth']) ? date('F j, Y', strtotime($user['date_of_birth'])) : 'Not provided'; ?>
                                </p>
                            </div>
                            <div class="col-md-6">
        <p class="text-muted small mb-1">City</p>
        <p class="fw-semibold text-dark mb-0"><?php echo htmlspecialchars($user['city'] ?? 'Not provided'); ?></p>
    </div>
    <div class="col-md-6">
        <p class="text-muted small mb-1">State</p>
        <p class="fw-semibold text-dark mb-0"><?php echo htmlspecialchars($user['state'] ?? 'Not provided'); ?></p>
    </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h4 fw-bold text-dark mb-4 d-flex align-items-center">
                            <i class="bi bi-geo-alt text-primary me-2"></i> Contact Information
                        </h2>
                        <div class="row">
                            <div class="col-12">
                                <p class="text-muted small mb-1">Address</p>
                                <p class="fw-semibold text-dark mb-0"><?php echo htmlspecialchars($user['address'] ?? 'Not provided'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h4 fw-bold text-dark mb-4 d-flex align-items-center">
                            <i class="bi bi-bar-chart text-primary me-2"></i> Your Statistics
                        </h2>
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                    <p class="display-6 fw-bold text-primary mb-0"><?php echo $stats['total_reservations'] ?? 0; ?></p>
                                    <p class="small text-muted mb-0">Total Reservations</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                    <p class="display-6 fw-bold text-success mb-0"><?php echo $stats['completed_reservations'] ?? 0; ?></p>
                                    <p class="small text-muted mb-0">Completed</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center p-3 bg-purple bg-opacity-10 rounded">
                                    <p class="display-6 fw-bold text-white mb-0"><?php echo $stats['upcoming_reservations'] ?? 0; ?></p>
                                    <p class="small text-white mb-0">Upcoming</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                                    <p class="display-6 fw-bold text-warning mb-0">₱<?php echo number_format($stats['total_spent'] ?? 0, 0); ?></p>
                                    <p class="small text-muted mb-0">Total Spent</p>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($stats['favorite_room_type']) && $stats['favorite_room_type'] !== 'None'): ?>
                            <div class="mt-4 pt-4 border-top">
                                <p class="text-muted small mb-1">Favorite Room Type</p>
                                <p class="fw-semibold text-dark mb-0"><?php echo htmlspecialchars($stats['favorite_room_type']); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Column: Quick Actions -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h4 fw-bold text-dark mb-4 d-flex align-items-center">
                            <i class="bi bi-lightning text-primary me-2"></i> Quick Actions
                        </h2>
                        <div class="d-grid gap-3">
               <a href="index.php?action=profile&sub_action=edit" class="btn btn-light text-dark fw-semibold">

                                <i class="bi bi-pencil me-2"></i> Edit Profile
                            </a>
                            <a href="index.php?action=profile&sub_action=change-password" class="btn btn-light text-dark fw-semibold">
                                <i class="bi bi-key me-2"></i> Change Password
                            </a>
                            <a href="index.php?action=book-room" class="btn btn-primary fw-semibold">
                                <i class="bi bi-calendar-plus me-2"></i> New Booking
                            </a>
                            <a href="index.php?action=my-reservations" class="btn btn-light text-dark fw-semibold">
                                <i class="bi bi-receipt me-2"></i> My Reservations
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h4 fw-bold text-dark mb-4 d-flex align-items-center">
                            <i class="bi bi-shield-check text-primary me-2"></i> Account Information
                        </h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-muted small mb-1">Account Status</p>
                                <span class="badge bg-success">Active</span>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Member Since</p>
                                <p class="fw-semibold text-dark mb-0"><?php echo date('F j, Y', strtotime($user['created_at'] ?? 'now')); ?></p>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Last Updated</p>
                                <p class="fw-semibold text-dark mb-0">
                                    <?php echo !empty($user['updated_at']) ? date('F j, Y', strtotime($user['updated_at'])) : 'Never'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts after 5 seconds
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
