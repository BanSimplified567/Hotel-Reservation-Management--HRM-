<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Hotel Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .profile-avatar {
            width: 150px;
            height: 150px;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            font-size: 3.5rem;
        }

        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--bs-primary);
        }

        .form-section h5 {
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .profile-avatar {
                width: 120px;
                height: 120px;
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <?php
    $old = $_SESSION['old'] ?? [];
    unset($_SESSION['old']);
    ?>

    <div class="container-fluid">
        <div class="row">
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="index.php?action=customer/profile" class="text-decoration-none">
                                        <i class="bi bi-house-door me-1"></i>Profile
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <i class="bi bi-pencil me-1"></i>Edit Profile
                                </li>
                            </ol>
                        </nav>
                        <h1 class="h2 mb-0">Edit Profile</h1>
                        <p class="text-muted mb-0">Update your personal information and preferences</p>
                    </div>
                    <a href="index.php?action=customer/profile" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to Profile
                    </a>
                </div>

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

                <form method="POST" action="index.php?action=profile&sub_action=edit" class="needs-validation" novalidate>
                    <div class="row">
                        <!-- Left Column: Personal Information -->
                        <div class="col-lg-8">
                            <!-- Basic Information -->
                            <div class="form-section">
                                <h5><i class="bi bi-person me-2"></i>Basic Information</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control"
                                            name="first_name"
                                            value="<?php echo htmlspecialchars($old['first_name'] ?? $user['first_name'] ?? ''); ?>"
                                            required>
                                        <div class="invalid-feedback">
                                            Please enter your first name.
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control"
                                            name="last_name"
                                            value="<?php echo htmlspecialchars($old['last_name'] ?? $user['last_name'] ?? ''); ?>"
                                            required>
                                        <div class="invalid-feedback">
                                            Please enter your last name.
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                        <input type="email"
                                            class="form-control"
                                            name="email"
                                            value="<?php echo htmlspecialchars($old['email'] ?? $user['email'] ?? ''); ?>"
                                            required>
                                        <div class="form-text">
                                            Your login email address
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter a valid email address.
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Phone Number</label>
                                        <input type="tel"
                                            class="form-control"
                                            name="phone"
                                            value="<?php echo htmlspecialchars($old['phone'] ?? $user['phone'] ?? ''); ?>"
                                            placeholder="+63 912 345 6789">
                                        <div class="form-text">
                                            We'll only contact you for important updates
                                        </div>
                                    </div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label fw-semibold">City</label>
        <input type="text" class="form-control" name="city"
               value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label fw-semibold">State</label>
        <input type="text" class="form-control" name="state"
               value="<?php echo htmlspecialchars($user['state'] ?? ''); ?>">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label fw-semibold">Postal Code</label>
        <input type="text" class="form-control" name="postal_code"
               value="<?php echo htmlspecialchars($user['postal_code'] ?? ''); ?>">
    </div>
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Country</label>
    <input type="text" class="form-control" name="country"
           value="<?php echo htmlspecialchars($user['country'] ?? ''); ?>">
</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Date of Birth</label>
                                    <input type="date"
                                        class="form-control"
                                        name="date_of_birth"
                                        value="<?php echo $old['date_of_birth'] ?? $user['date_of_birth'] ?? ''; ?>"
                                        max="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="form-section">
                                <h5><i class="bi bi-geo-alt me-2"></i>Contact Information</h5>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Address</label>
                                    <textarea class="form-control"
                                        name="address"
                                        rows="3"
                                        placeholder="Street address, city, state, zip code"><?php echo htmlspecialchars($old['address'] ?? $user['address'] ?? ''); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Emergency Contact</label>
                                    <input type="text"
                                        class="form-control"
                                        name="emergency_contact"
                                        value="<?php echo htmlspecialchars($old['emergency_contact'] ?? $user['emergency_contact'] ?? ''); ?>"
                                        placeholder="Name and phone number">
                                    <div class="form-text">
                                        In case of emergency during your stay
                                    </div>
                                </div>
                            </div>

                            <!-- Preferences -->
                            <div class="form-section">
                                <h5><i class="bi bi-sliders me-2"></i>Preferences</h5>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Communication Preferences</label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input"
                                            type="checkbox"
                                            name="pref_emails"
                                            id="prefEmails"
                                            value="1"
                                            <?php echo (($old['pref_emails'] ?? $user['pref_emails'] ?? 1) == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="prefEmails">
                                            Receive promotional emails and offers
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="checkbox"
                                            name="pref_sms"
                                            id="prefSMS"
                                            value="1"
                                            <?php echo (($old['pref_sms'] ?? $user['pref_sms'] ?? 0) == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="prefSMS">
                                            Receive SMS notifications about reservations
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Room Preferences</label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input"
                                            type="checkbox"
                                            name="pref_smoking"
                                            id="prefSmoking"
                                            value="1"
                                            <?php echo (($old['pref_smoking'] ?? $user['pref_smoking'] ?? 0) == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="prefSmoking">
                                            Smoking room preferred
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="checkbox"
                                            name="pref_high_floor"
                                            id="prefHighFloor"
                                            value="1"
                                            <?php echo (($old['pref_high_floor'] ?? $user['pref_high_floor'] ?? 0) == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="prefHighFloor">
                                            Higher floor preferred
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Avatar & Actions -->
                        <div class="col-lg-4">
                            <!-- Profile Picture -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="profile-avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3">
                                        <span>
                                            <?php echo strtoupper(substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? 'S', 0, 1)); ?>
                                        </span>
                                    </div>
                                    <h5 class="card-title mb-2">Profile Picture</h5>
                                    <p class="text-muted small mb-3">
                                        Currently using initials. You can upload a photo if you'd like.
                                    </p>
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal">
                                            <i class="bi bi-camera me-1"></i> Upload Photo
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Save Changes</h5>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="bi bi-check-circle me-1"></i> Update Profile
                                        </button>
                                        <a href="index.php?action=customer/profile" class="btn btn-outline-secondary">
                                            <i class="bi bi-x-circle me-1"></i> Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Help Card -->
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Need Help?</h5>
                                </div>
                                <div class="card-body">
                                    <p class="small text-muted mb-3">
                                        Keeping your profile updated helps us serve you better.
                                    </p>
                                    <ul class="list-unstyled small">
                                        <li class="mb-2">
                                            <i class="bi bi-check text-success me-2"></i>
                                            Keep your contact information current
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check text-success me-2"></i>
                                            Set preferences for better service
                                        </li>
                                        <li>
                                            <i class="bi bi-check text-success me-2"></i>
                                            Emergency contact helps in urgent situations
                                        </li>
                                    </ul>
                                    <div class="text-center mt-3">
                                        <a href="mailto:support@hotelmanagement.com" class="btn btn-outline-info btn-sm">
                                            <i class="bi bi-envelope me-1"></i> Contact Support
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <!-- Upload Photo Modal -->
    <div class="modal fade" id="uploadPhotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="profile-avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3">
                            <span>
                                <?php echo strtoupper(substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? 'S', 0, 1)); ?>
                            </span>
                        </div>
                        <p class="text-muted">Current profile picture</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Choose a photo</label>
                        <input type="file" class="form-control" accept="image/*">
                        <div class="form-text">
                            Recommended: Square image, at least 400x400 pixels, max 5MB
                        </div>
                    </div>

                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle me-2"></i>
                        Profile pictures help our staff recognize you and provide better service.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Upload Photo</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Bootstrap form validation
        (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>

