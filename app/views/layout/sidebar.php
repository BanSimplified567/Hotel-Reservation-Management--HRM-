<?php
// app/views/layout/sidebar.php
// This sidebar is for admin and staff only
$role = $_SESSION['role'] ?? 'guest';
$isStaff = in_array($role, ['admin', 'staff']);
?>

<?php if ($isStaff): ?>
  <!-- Sidebar - Only show for staff/admin, not for customers -->
  <div class="hidden md:block fixed left-0 top-0 bottom-0 bg-gradient-to-b from-primary to-secondary text-white w-64 z-40">
    <div class="p-6 h-full overflow-y-auto relative">
      <!-- Hotel Logo -->
      <div class="d-flex align-items-center mb-5">
        <div class="rounded-circle bg-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
          <i class="fas fa-hotel text-primary fs-4"></i>
        </div>
        <div class="ms-3">
          <h2 class="fw-bold mb-0">Hotel Bannie</h2>
          <p class="text-white-50 small mb-0">Management</p>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="d-flex flex-column">
        <a href="index.php?action=admin/dashboard" class="sidebar-link text-white text-decoration-none d-flex align-items-center p-3 rounded mb-1">
          <i class="fas fa-tachometer-alt me-3" style="width: 24px;"></i>
          <span>Dashboard</span>
        </a>

        <div class="mt-3">
          <p class="text-white-50 small text-uppercase mb-2">Reservations</p>
          <a href="index.php?action=admin/reservations" class="sidebar-link text-white text-decoration-none d-flex align-items-center p-3 rounded mb-1">
            <i class="fas fa-calendar-alt me-3" style="width: 24px;"></i>
            <span>All Reservations</span>
          </a>
        </div>

        <div class="mt-3">
          <p class="text-white-50 small text-uppercase mb-2">Rooms</p>
          <a href="index.php?action=admin/rooms" class="sidebar-link text-white text-decoration-none d-flex align-items-center p-3 rounded mb-1">
            <i class="fas fa-bed me-3" style="width: 24px;"></i>
            <span>Room Management</span>
          </a>
        </div>

        <div class="mt-3">
          <p class="text-white-50 small text-uppercase mb-2">Services</p>
          <a href="index.php?action=admin/services" class="sidebar-link text-white text-decoration-none d-flex align-items-center p-3 rounded mb-1">
            <i class="fas fa-concierge-bell me-3" style="width: 24px;"></i>
            <span>Services</span>
          </a>
        </div>

        <?php if ($role === 'admin'): ?>
          <div class="mt-3">
            <p class="text-white-50 small text-uppercase mb-2">Administration</p>
            <a href="index.php?action=admin/users" class="sidebar-link text-white text-decoration-none d-flex align-items-center p-3 rounded mb-1">
              <i class="fas fa-user-tie me-3" style="width: 24px;"></i>
              <span>User Management</span>
            </a>
            <a href="index.php?action=admin/reports" class="sidebar-link text-white text-decoration-none d-flex align-items-center p-3 rounded mb-1">
              <i class="fas fa-chart-bar me-3" style="width: 24px;"></i>
              <span>Reports & Analytics</span>
            </a>
          </div>
        <?php endif; ?>

        <div class="mt-3">
          <a href="index.php?action=profile" class="sidebar-link text-white text-decoration-none d-flex align-items-center p-3 rounded mb-1">
            <i class="fas fa-user-circle me-3" style="width: 24px;"></i>
            <span>My Profile</span>
          </a>
        </div>

        <!-- Logout moved to be last in the navigation list -->
        <div class="mt-3">
          <a href="index.php?action=logout" class="sidebar-link text-white text-decoration-none d-flex align-items-center p-3 rounded">
            <i class="fas fa-sign-out-alt me-3" style="width: 24px;"></i>
            <span>Logout</span>
          </a>
        </div>
      </nav>
    </div>
  </div>

  <!-- Main Content Area with margin for sidebar -->
  <div class="md:ml-64">
    <!-- Mobile Menu Button (only for staff) -->
    <div class="d-md-none bg-white shadow">
      <button id="mobileMenuBtn" class="btn p-3 text-dark border-0">
        <i class="fas fa-bars fs-4"></i>
      </button>
    </div>

    <!-- Mobile Sidebar -->
    <div id="mobileSidebar" class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 z-50 d-none">
      <div class="position-absolute top-0 start-0 bottom-0 bg-gradient bg-primary text-white" style="width: 256px;">
        <div class="p-4">
          <div class="d-flex justify-content-between align-items-center mb-5">
            <div class="d-flex align-items-center">
              <div class="rounded-circle bg-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                <i class="fas fa-hotel text-primary"></i>
              </div>
              <h2 class="fw-bold mb-0">Hotel Bannie</h2>
            </div>
            <button id="closeMobileMenu" class="btn text-white p-0">
              <i class="fas fa-times fs-4"></i>
            </button>
          </div>

          <!-- Same navigation as desktop with logout last -->
          <nav class="d-flex flex-column">
            <a href="index.php?action=admin/dashboard" class="text-white text-decoration-none p-3 rounded mb-1">Dashboard</a>
            <a href="index.php?action=admin/reservations" class="text-white text-decoration-none p-3 rounded mb-1">Reservations</a>
            <a href="index.php?action=admin/rooms" class="text-white text-decoration-none p-3 rounded mb-1">Rooms</a>
            <a href="index.php?action=admin/services" class="text-white text-decoration-none p-3 rounded mb-1">Services</a>
            <?php if ($role === 'admin'): ?>
              <a href="index.php?action=admin/users" class="text-white text-decoration-none p-3 rounded mb-1">Users</a>
              <a href="index.php?action=admin/reports" class="text-white text-decoration-none p-3 rounded mb-1">Reports</a>
            <?php endif; ?>
            <a href="index.php?action=profile" class="text-white text-decoration-none p-3 rounded mb-1">Profile</a>
            <!-- Logout is now last in the mobile navigation -->
            <a href="index.php?action=logout" class="text-white text-decoration-none p-3 rounded">Logout</a>
          </nav>
        </div>
      </div>
    </div>

    <script>
      // Mobile menu toggle
      document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const closeMobileMenu = document.getElementById('closeMobileMenu');

        if (mobileMenuBtn) {
          mobileMenuBtn.addEventListener('click', function() {
            mobileSidebar.classList.remove('d-none');
          });
        }

        if (closeMobileMenu) {
          closeMobileMenu.addEventListener('click', function() {
            mobileSidebar.classList.add('d-none');
          });
        }

        // Close mobile menu when clicking outside
        if (mobileSidebar) {
          mobileSidebar.addEventListener('click', function(e) {
            if (e.target === mobileSidebar) {
              mobileSidebar.classList.add('d-none');
            }
          });
        }
      });
    </script>
  <?php else: ?>
    <!-- No sidebar for customers/guests - main content area -->
    <div>
    <?php endif; ?>
