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
      <div class="flex items-center space-x-3 mb-8">
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
          <i class="fas fa-hotel text-primary text-2xl"></i>
        </div>
        <div>
          <h2 class="font-bold text-lg font-display">Hotel Bannie</h2>
          <p class="text-white/80 text-sm">Management</p>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="space-y-2">
        <a href="index.php?action=admin/dashboard" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
          <i class="fas fa-tachometer-alt w-6 text-center"></i>
          <span>Dashboard</span>
        </a>

        <div class="pt-4">
          <p class="text-white/60 text-xs uppercase tracking-wider mb-2">Reservations</p>
          <a href="index.php?action=admin/reservations" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
            <i class="fas fa-calendar-alt w-6 text-center"></i>
            <span>All Reservations</span>
          </a>
        </div>

        <div class="pt-4">
          <p class="text-white/60 text-xs uppercase tracking-wider mb-2">Rooms</p>
          <a href="index.php?action=admin/rooms" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
            <i class="fas fa-bed w-6 text-center"></i>
            <span>Room Management</span>
          </a>
        </div>

        <div class="pt-4">
          <p class="text-white/60 text-xs uppercase tracking-wider mb-2">Services</p>
          <a href="index.php?action=admin/services" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
            <i class="fas fa-concierge-bell w-6 text-center"></i>
            <span>Services</span>
          </a>
        </div>

        <?php if ($role === 'admin'): ?>
          <div class="pt-4">
            <p class="text-white/60 text-xs uppercase tracking-wider mb-2">Administration</p>
            <a href="index.php?action=admin/users" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
              <i class="fas fa-user-tie w-6 text-center"></i>
              <span>User Management</span>
            </a>
            <a href="index.php?action=admin/reports" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
              <i class="fas fa-chart-bar w-6 text-center"></i>
              <span>Reports & Analytics</span>
            </a>
          </div>
        <?php endif; ?>

        <div class="pt-4">
          <a href="index.php?action=profile" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
            <i class="fas fa-user-circle w-6 text-center"></i>
            <span>My Profile</span>
          </a>
        </div>

        <!-- Logout moved to be last in the navigation list -->
        <div class="pt-4">
          <a href="index.php?action=logout" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
            <i class="fas fa-sign-out-alt w-6 text-center"></i>
            <span>Logout</span>
          </a>
        </div>
      </nav>
    </div>
  </div>

  <!-- Main Content Area with margin for sidebar -->
  <div class="md:ml-64">
    <!-- Mobile Menu Button (only for staff) -->
    <div class="md:hidden bg-white shadow">
      <button id="mobileMenuBtn" class="p-4 text-gray-600">
        <i class="fas fa-bars text-xl"></i>
      </button>
    </div>

    <!-- Mobile Sidebar -->
    <div id="mobileSidebar" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-50 hidden">
      <div class="absolute left-0 top-0 bottom-0 w-64 bg-gradient-to-b from-primary to-secondary text-white">
        <div class="p-6">
          <div class="flex justify-between items-center mb-8">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                <i class="fas fa-hotel text-primary"></i>
              </div>
              <h2 class="font-bold">Hotel Bannie</h2>
            </div>
            <button id="closeMobileMenu" class="text-white">
              <i class="fas fa-times"></i>
            </button>
          </div>

          <!-- Same navigation as desktop with logout last -->
          <nav class="space-y-2">
            <a href="index.php?action=admin/dashboard" class="block p-3 rounded-lg hover:bg-white/10">Dashboard</a>
            <a href="index.php?action=admin/reservations" class="block p-3 rounded-lg hover:bg-white/10">Reservations</a>
            <a href="index.php?action=admin/rooms" class="block p-3 rounded-lg hover:bg-white/10">Rooms</a>
            <a href="index.php?action=admin/services" class="block p-3 rounded-lg hover:bg-white/10">Services</a>
            <?php if ($role === 'admin'): ?>
              <a href="index.php?action=admin/users" class="block p-3 rounded-lg hover:bg-white/10">Users</a>
              <a href="index.php?action=admin/reports" class="block p-3 rounded-lg hover:bg-white/10">Reports</a>
            <?php endif; ?>
            <a href="index.php?action=profile" class="block p-3 rounded-lg hover:bg-white/10">Profile</a>
            <!-- Logout is now last in the mobile navigation -->
            <a href="index.php?action=logout" class="block p-3 rounded-lg hover:bg-white/10">Logout</a>
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
            mobileSidebar.classList.remove('hidden');
          });
        }

        if (closeMobileMenu) {
          closeMobileMenu.addEventListener('click', function() {
            mobileSidebar.classList.add('hidden');
          });
        }

        // Close mobile menu when clicking outside
        if (mobileSidebar) {
          mobileSidebar.addEventListener('click', function(e) {
            if (e.target === mobileSidebar) {
              mobileSidebar.classList.add('hidden');
            }
          });
        }
      });
    </script>
  <?php else: ?>
    <!-- No sidebar for customers/guests - main content area -->
    <div>
    <?php endif; ?>
