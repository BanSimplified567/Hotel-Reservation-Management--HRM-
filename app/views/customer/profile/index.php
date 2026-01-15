<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Hotel Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,0.2);
            border: 4px solid white;
        }
        .info-card {
            transition: transform 0.3s;
        }
        .info-card:hover {
            transform: translateY(-5px);
        }
        .stat-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body>
    <?php include '../layout/customer-header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include '../layout/customer-sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="profile-avatar rounded-circle d-flex align-items-center justify-content-center me-4">
                                    <span class="display-4">
                                        <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                                    </span>
                                </div>
                                <div>
                                    <h1 class="h2 mb-1"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h1>
                                    <p class="mb-2 opacity-75">
                                        <i class="bi bi-envelope me-1"></i> <?php echo htmlspecialchars($user['email']); ?>
                                    </p>
                                    <div class="d-flex gap-2">
                                        <span class="badge stat-badge bg-light text-dark">
                                            <i class="bi bi-person me-1"></i> Customer
                                        </span>
                                        <span class="badge stat-badge bg-light text-dark">
                                            Member since <?php echo date('M Y', strtotime($user['created_at'])); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="index.php?action=customer/profile/edit" class="btn btn-light me-2">
                                <i class="bi bi-pencil me-1"></i> Edit Profile
                            </a>
                            <a href="index.php?action=customer/profile/change-password" class="btn btn-outline-light">
                                <i class="bi bi-key me-1"></i> Change Password
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Personal Information -->
                    <div class="col-lg-8">
                        <!-- Basic Information -->
                        <div class="card info-card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Personal Information</h5>
                                <a href="index.php?action=customer/profile/edit" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <small class="text-muted d-block">First Name</small>
                                            <p class="mb-0"><?php echo htmlspecialchars($user['first_name']); ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Last Name</small>
                                            <p class="mb-0"><?php echo htmlspecialchars($user['last_name']); ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Email Address</small>
                                            <p class="mb-0"><?php echo htmlspecialchars($user['email']); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Phone Number</small>
                                            <p class="mb-0"><?php echo htmlspecialchars($user['phone'] ?? 'Not provided'); ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Date of Birth</small>
                                            <p class="mb-0">
                                                <?php echo $user['date_of_birth'] ? date('F j, Y', strtotime($user['date_of_birth'])) : 'Not provided'; ?>
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Username</small>
                                            <p class="mb-0"><?php echo htmlspecialchars($user['username']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="card info-card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Contact Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <small class="text-muted d-block">Address</small>
                                    <p class="mb-0"><?php echo htmlspecialchars($user['address'] ?? 'Not provided'); ?></p>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Emergency Contact</small>
                                    <p class="mb-0"><?php echo htmlspecialchars($user['emergency_contact'] ?? 'Not provided'); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="card info-card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-shield-check me-2"></i>Account Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Account Status</small>
                                            <p class="mb-0">
                                                <span class="badge bg-success">Active</span>
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Member Since</small>
                                            <p class="mb-0"><?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Last Updated</small>
                                            <p class="mb-0">
                                                <?php echo $user['updated_at'] ? date('F j, Y', strtotime($user['updated_at'])) : 'Never'; ?>
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Last Login</small>
                                            <p class="mb-0">
                                                <?php
                                                // You would need to track last login separately
                                                echo 'Recently';
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions & Stats -->
                    <div class="col-lg-4">
                        <!-- Quick Actions -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="index.php?action=customer/profile/edit" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil me-2"></i> Edit Profile
                                    </a>
                                    <a href="index.php?action=customer/profile/change-password" class="btn btn-outline-primary">
                                        <i class="bi bi-key me-2"></i> Change Password
                                    </a>
                                    <a href="index.php?action=customer/booking" class="btn btn-outline-success">
                                        <i class="bi bi-calendar-plus me-2"></i> New Booking
                                    </a>
                                    <a href="index.php?action=customer/reservations" class="btn btn-outline-info">
                                        <i class="bi bi-receipt me-2"></i> My Reservations
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Security Tips -->
                        <div class="card mb-4">
                            <div class="card-header bg-warning">
                                <h5 class="mb-0 text-white"><i class="bi bi-shield-exclamation me-2"></i>Security Tips</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        Use a strong, unique password
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        Update your contact information regularly
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        Never share your login credentials
                                    </li>
                                    <li>
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        Log out after each session
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Contact Support -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-headset me-2"></i>Need Help?</h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="bi bi-question-circle fs-1 text-primary"></i>
                                </div>
                                <p class="small text-muted mb-3">
                                    Have questions about your account or need assistance?
                                </p>
                                <div class="d-grid gap-2">
                                    <a href="mailto:support@hotelmanagement.com" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-envelope me-1"></i> Email Support
                                    </a>
                                    <a href="tel:+1234567890" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-telephone me-1"></i> Call Support
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="card border-danger mt-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Danger Zone</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="mb-1">Delete Account</h6>
                                <p class="text-muted mb-0 small">
                                    Once you delete your account, there is no going back. Please be certain.
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                    <i class="bi bi-trash me-1"></i> Delete Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Delete Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Warning:</strong> This action cannot be undone.
                    </div>
                    <p>Are you sure you want to delete your account? This will:</p>
                    <ul class="mb-3">
                        <li>Permanently delete your profile</li>
                        <li>Cancel all upcoming reservations</li>
                        <li>Remove your booking history</li>
                        <li>Delete all personal information</li>
                    </ul>
                    <div class="mb-3">
                        <label class="form-label">Type "DELETE" to confirm:</label>
                        <input type="text" class="form-control" id="deleteConfirm" placeholder="DELETE">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete" disabled>
                        Delete My Account
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php include '../layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Delete account confirmation
        const deleteConfirm = document.getElementById('deleteConfirm');
        const confirmDeleteBtn = document.getElementById('confirmDelete');

        deleteConfirm.addEventListener('input', function() {
            confirmDeleteBtn.disabled = this.value !== 'DELETE';
        });

        confirmDeleteBtn.addEventListener('click', function() {
            // In a real application, this would trigger an account deletion process
            alert('Account deletion functionality would be implemented here.');
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteAccountModal'));
            modal.hide();
        });
    </script>
</body>
</html>
