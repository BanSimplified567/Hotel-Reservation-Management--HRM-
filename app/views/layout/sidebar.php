<?php
// app/views/layout/sidebar.php
// This sidebar is for admin and staff only
$role = $_SESSION['role'] ?? 'guest';
$isStaff = in_array($role, ['admin', 'staff']);
?>

<?php if ($isStaff): ?>
  <!-- Sidebar - Only show for staff/admin, not for customers -->
  <div class="d-none d-md-block position-fixed start-0 top-0 bottom-0 bg-primary text-white" style="width: 256px; z-index: 1040;">
    <div class="p-4 h-100 overflow-y-auto">
      <!-- Hotel Logo -->
      <div class="d-flex align-items-center mb-4">
        <div class="rounded-circle bg-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
          <i class="fas fa-hotel text-primary fs-4"></i>
        </div>
        <div>
          <h2 class="h5 fw-bold mb-0">Hotel Bannie</h2>
          <p class="text-white-50 small mb-0">Management</p>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="nav flex-column">
        <a href="index.php?action=admin/dashboard" class="nav-link text-white d-flex align-items-center py-3 px-3 mb-1 rounded">
          <i class="fas fa-tachometer-alt me-3" style="width: 20px;"></i>
          <span>Dashboard</span>
        </a>

        <div class="mt-3">
          <p class="text-white-50 small text-uppercase mb-2 ps-3">Reservations</p>
          <a href="index.php?action=admin/reservations" class="nav-link text-white d-flex align-items-center py-2 px-3 mb-1 rounded">
            <i class="fas fa-calendar-alt me-3" style="width: 20px;"></i>
            <span>All Reservations</span>
          </a>
        </div>

        <div class="mt-3">
          <p class="text-white-50 small text-uppercase mb-2 ps-3">Rooms</p>
          <a href="index.php?action=admin/rooms" class="nav-link text-white d-flex align-items-center py-2 px-3 mb-1 rounded">
            <i class="fas fa-bed me-3" style="width: 20px;"></i>
            <span>Room Management</span>
          </a>
        </div>

        <div class="mt-3">
          <p class="text-white-50 small text-uppercase mb-2 ps-3">Services</p>
          <a href="index.php?action=admin/services" class="nav-link text-white d-flex align-items-center py-2 px-3 mb-1 rounded">
            <i class="fas fa-concierge-bell me-3" style="width: 20px;"></i>
            <span>Services</span>
          </a>
        </div>

        <?php if ($role === 'admin'): ?>
          <div class="mt-3">
            <p class="text-white-50 small text-uppercase mb-2 ps-3">Administration</p>
            <a href="index.php?action=admin/users" class="nav-link text-white d-flex align-items-center py-2 px-3 mb-1 rounded">
              <i class="fas fa-user-tie me-3" style="width: 20px;"></i>
              <span>User Management</span>
            </a>
            <a href="index.php?action=admin/reports" class="nav-link text-white d-flex align-items-center py-2 px-3 mb-1 rounded">
              <i class="fas fa-chart-bar me-3" style="width: 20px;"></i>
              <span>Reports & Analytics</span>
            </a>
          </div>
        <?php endif; ?>

        <div class="mt-3">
          <a href="index.php?action=admin/profile" class="nav-link text-white d-flex align-items-center py-2 px-3 mb-1 rounded">
            <i class="fas fa-user-circle me-3" style="width: 20px;"></i>
            <span>My Profile</span>
          </a>
        </div>

        <!-- Logout moved to be last in the navigation list -->
        <div class="mt-3">
          <a href="index.php?action=logout" class="nav-link text-white d-flex align-items-center py-2 px-3 rounded">
            <i class="fas fa-sign-out-alt me-3" style="width: 20px;"></i>
            <span>Logout</span>
          </a>
        </div>
      </nav>
    </div>
  </div>

  <!-- Main Content Area with margin for sidebar -->
  <div class="ps-md-0" style="margin-left: 256px;">
    <!-- Mobile Menu Button (only for staff) -->
    <div class="d-md-none bg-white shadow-sm">
      <div class="container-fluid">
        <button id="mobileMenuBtn" class="btn text-dark border-0 py-3">
          <i class="fas fa-bars fs-5"></i>
        </button>
      </div>
    </div>

    <!-- Mobile Sidebar -->
    <div id="mobileSidebar" class="offcanvas offcanvas-start d-md-none" tabindex="-1" style="max-width: 256px;">
      <div class="offcanvas-header bg-primary text-white">
        <div class="d-flex align-items-center">
          <div class="rounded-circle bg-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
            <i class="fas fa-hotel text-primary"></i>
          </div>
          <h5 class="offcanvas-title mb-0 fw-bold">Hotel Bannie</h5>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body p-0">
        <nav class="nav flex-column">
          <a href="index.php?action=admin/dashboard" class="nav-link text-dark py-3 px-3 border-bottom">
            <i class="fas fa-tachometer-alt me-3"></i> Dashboard
          </a>
          <a href="index.php?action=admin/reservations" class="nav-link text-dark py-3 px-3 border-bottom">
            <i class="fas fa-calendar-alt me-3"></i> Reservations
          </a>
          <a href="index.php?action=admin/reservation-guests" class="nav-link text-dark py-3 px-3 border-bottom">
            <i class="fas fa-users me-3"></i> Reservation Guests
          </a>
          <a href="index.php?action=admin/rooms" class="nav-link text-dark py-3 px-3 border-bottom">
            <i class="fas fa-bed me-3"></i> Rooms
          </a>
          <a href="index.php?action=admin/services" class="nav-link text-dark py-3 px-3 border-bottom">
            <i class="fas fa-concierge-bell me-3"></i> Services
          </a>
          <?php if ($role === 'admin'): ?>
            <a href="index.php?action=admin/users" class="nav-link text-dark py-3 px-3 border-bottom">
              <i class="fas fa-user-tie me-3"></i> Users
            </a>
            <a href="index.php?action=admin/reports" class="nav-link text-dark py-3 px-3 border-bottom">
              <i class="fas fa-chart-bar me-3"></i> Reports
            </a>
          <?php endif; ?>
          <a href="index.php?action=admin/profile" class="nav-link text-dark py-3 px-3 border-bottom">
            <i class="fas fa-user-circle me-3"></i> Profile
          </a>
          <a href="index.php?action=logout" class="nav-link text-dark py-3 px-3">
            <i class="fas fa-sign-out-alt me-3"></i> Logout
          </a>
        </nav>
      </div>
    </div>

    <script>
      // Mobile menu toggle using Bootstrap Offcanvas
      document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileSidebar = new bootstrap.Offcanvas(document.getElementById('mobileSidebar'));

        if (mobileMenuBtn) {
          mobileMenuBtn.addEventListener('click', function() {
            mobileSidebar.show();
          });
        }
      });
    </script>
  <?php else: ?>
    <!-- No sidebar for customers/guests - main content area -->
    <div>
    <?php endif; ?>
