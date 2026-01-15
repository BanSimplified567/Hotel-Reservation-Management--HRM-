<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile - Hotel Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <style>
    .profile-avatar {
      width: 150px;
      height: 150px;
      border: 5px solid white;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .form-section {
      background: #f8f9fa;
      border-radius: 10px;
      padding: 1.5rem;
      margin-bottom: 2rem;
    }

    .form-section h5 {
      color: #495057;
      border-bottom: 2px solid #dee2e6;
      padding-bottom: 0.5rem;
      margin-bottom: 1.5rem;
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
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="index.php?action=customer/profile">Profile</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
              </ol>
            </nav>
            <h1 class="h2 mb-0">Edit Profile</h1>
          </div>
          <a href="index.php?action=customer/profile" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Profile
          </a>
        </div>

        <form method="POST" action="index.php?action=customer/profile/edit">
          <div class="row">
            <!-- Left Column: Personal Information -->
            <div class="col-lg-8">
              <!-- Basic Information -->
              <div class="form-section">
                <h5><i class="bi bi-person me-2"></i>Basic Information</h5>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">First Name *</label>
                    <input type="text"
                      class="form-control"
                      name="first_name"
                      value="<?php echo htmlspecialchars($user['first_name']); ?>"
                      required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Last Name *</label>
                    <input type="text"
                      class="form-control"
                      name="last_name"
                      value="<?php echo htmlspecialchars($user['last_name']); ?>"
                      required>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Email Address *</label>
                    <input type="email"
                      class="form-control"
                      name="email"
                      value="<?php echo htmlspecialchars($user['email']); ?>"
                      required>
                    <div class="form-text">
                      Your login email address
                    </div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="tel"
                      class="form-control"
                      name="phone"
                      value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                      placeholder="+1 (123) 456-7890">
                    <div class="form-text">
                      We'll only contact you for important updates
                    </div>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label">Date of Birth</label>
                  <input type="date"
                    class="form-control"
                    name="date_of_birth"
                    value="<?php echo $user['date_of_birth'] ?? ''; ?>"
                    max="<?php echo date('Y-m-d'); ?>">
                </div>
              </div>

              <!-- Contact Information -->
              <div class="form-section">
                <h5><i class="bi bi-geo-alt me-2"></i>Contact Information</h5>
                <div class="mb-3">
                  <label class="form-label">Address</label>
                  <textarea class="form-control"
                    name="address"
                    rows="3"
                    placeholder="Street address, city, state, zip code"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label">Emergency Contact</label>
                  <input type="text"
                    class="form-control"
                    name="emergency_contact"
                    value="<?php echo htmlspecialchars($user['emergency_contact'] ?? ''); ?>"
                    placeholder="Name and phone number">
                  <div class="form-text">
                    In case of emergency during your stay
                  </div>
                </div>
              </div>

              <!-- Preferences -->
              <div class="form-section">
                <h5><i class="bi bi-sliders me-2"></i>Preferences</h5>
                <div class="mb-3">
                  <label class="form-label">Communication Preferences</label>
                  <div class="form-check">
                    <input class="form-check-input"
                      type="checkbox"
                      name="pref_emails"
                      id="prefEmails"
                      <?php echo ($user['pref_emails'] ?? 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="prefEmails">
                      Receive promotional emails and offers
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input"
                      type="checkbox"
                      name="pref_sms"
                      id="prefSMS"
                      <?php echo ($user['pref_sms'] ?? 0) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="prefSMS">
                      Receive SMS notifications about reservations
                    </label>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label">Room Preferences</label>
                  <div class="form-check">
                    <input class="form-check-input"
                      type="checkbox"
                      name="pref_smoking"
                      id="prefSmoking"
                      <?php echo ($user['pref_smoking'] ?? 0) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="prefSmoking">
                      Smoking room preferred
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input"
                      type="checkbox"
                      name="pref_high_floor"
                      id="prefHighFloor"
                      <?php echo ($user['pref_high_floor'] ?? 0) ? 'checked' : ''; ?>>
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
              <div class="card mb-4">
                <div class="card-body text-center">
                  <div class="profile-avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3">
                    <span class="display-4">
                      <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
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
              <div class="card mb-4">
                <div class="card-body">
                  <h5 class="card-title mb-3">Save Changes</h5>
                  <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                      <i class="bi bi-check-circle me-1"></i> Update Profile
                    </button>
                    <a href="index.php?action=customer/profile" class="btn btn-outline-secondary">
                      <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                  </div>
                </div>
              </div>

              <!-- Help Card -->
              <div class="card">
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
  <div class="modal fade" id="uploadPhotoModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Upload Profile Picture</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-4">
            <div class="profile-avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3">
              <span class="display-4">
                <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
              </span>
            </div>
            <p class="text-muted">Current profile picture</p>
          </div>

          <div class="mb-3">
            <label class="form-label">Choose a photo</label>
            <input type="file" class="form-control" accept="image/*">
            <div class="form-text">
              Recommended: Square image, at least 400x400 pixels, max 5MB
            </div>
          </div>

          <div class="alert alert-info small">
            <i class="bi bi-info-circle"></i>
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
  </script>
</body>

</html>
