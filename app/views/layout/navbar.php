<nav class="bg-white shadow-lg sticky top-0 z-50">
  <div class="w-full px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">
      <!-- Logo/Brand -->
      <div class="flex items-center">
        <a href="index.php" class="flex items-center text-xl font-bold text-gray-800">
          <i class="fas fa-hotel text-primary mr-2"></i>
          Hotel Management
        </a>
      </div>

      <!-- Desktop Navigation Links (Center) -->
      <div class="hidden md:flex md:items-center md:space-x-1 lg:space-x-2 mx-4 flex-1 justify-center">
        <?php if (isset($_SESSION['user_id'])): ?>
          <?php if ($_SESSION['role'] === 'customer'): ?>
            <a href="index.php?action=dashboard" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium flex items-center">
              <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
            </a>
            <a href="index.php?action=my-reservations" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium flex items-center">
              <i class="fas fa-calendar-check mr-1"></i> My Reservations
            </a>
            <a href="index.php?action=book-room" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium flex items-center">
              <i class="fas fa-bed mr-1"></i> Book Room
            </a>
          <?php elseif (in_array($_SESSION['role'], ['admin', 'staff'])): ?>
            <a href="index.php?action=admin/dashboard" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium flex items-center">
              <i class="fas fa-tachometer-alt mr-1"></i> Admin Dashboard
            </a>
            <?php if ($_SESSION['role'] === 'admin'): ?>
              <a href="index.php?action=admin/users" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium flex items-center">
                <i class="fas fa-users mr-1"></i> Users
              </a>
            <?php endif; ?>
            <a href="index.php?action=admin/reservations" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium flex items-center">
              <i class="fas fa-calendar-alt mr-1"></i> Reservations
            </a>
            <a href="index.php?action=admin/rooms" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium flex items-center">
              <i class="fas fa-bed mr-1"></i> Manage Rooms
            </a>
            <a href="index.php?action=admin/services" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium flex items-center">
              <i class="fas fa-concierge-bell mr-1"></i> Services
            </a>
            <?php if ($_SESSION['role'] === 'admin'): ?>
              <a href="index.php?action=admin/reports" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium flex items-center">
                <i class="fas fa-chart-bar mr-1"></i> Reports
              </a>
            <?php endif; ?>
          <?php endif; ?>
        <?php endif; ?>

        <!-- Public Links - Only show when NOT logged in as admin/staff -->
        <?php if (!isset($_SESSION['user_id']) || $_SESSION['role'] === 'customer'): ?>
          <a href="index.php?action=rooms" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium flex items-center">
            <i class="fas fa-door-open mr-1"></i> Rooms
          </a>
          <a href="index.php?action=room-search" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium flex items-center">
            <i class="fas fa-search mr-1"></i> Search Rooms
          </a>
        <?php endif; ?>

        <!-- Common Public Links -->
        <a href="index.php?action=about" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium flex items-center">
          <i class="fas fa-info-circle mr-1"></i> About
        </a>
        <a href="index.php?action=contact" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium flex items-center">
          <i class="fas fa-envelope mr-1"></i> Contact
        </a>
      </div>

      <!-- Right side items (Login/User Dropdown) -->
      <div class="hidden md:flex md:items-center md:space-x-4">
        <?php if (isset($_SESSION['user_id'])): ?>
          <!-- User info and dropdown -->
          <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-700">
              <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
              <?php
              echo $_SESSION['role'] === 'admin' ? 'bg-red-100 text-red-800' : ($_SESSION['role'] === 'staff' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800');
              ?>">
              <?php echo ucfirst($_SESSION['role']); ?>
            </span>

            <div class="relative group">
              <button class="flex items-center text-gray-700 hover:text-primary focus:outline-none">
                <i class="fas fa-user-circle text-xl"></i>
                <i class="fas fa-chevron-down ml-1 text-xs"></i>
              </button>

              <!-- Dropdown menu -->
              <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
                <a href="index.php?action=profile" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                  <i class="fas fa-user mr-2"></i> My Profile
                </a>
                <div class="border-t my-1"></div>
                <a href="index.php?action=logout" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                  <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
              </div>
            </div>
          </div>
        <?php else: ?>
          <!-- Login/Register buttons -->
          <a href="index.php?action=login" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium flex items-center">
            <i class="fas fa-sign-in-alt mr-1"></i> Login
          </a>
          <a href="index.php?action=register" class="ml-2 bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center transition duration-300">
            <i class="fas fa-user-plus mr-1"></i> Register
          </a>
        <?php endif; ?>
      </div>

      <!-- Mobile menu button -->
      <div class="md:hidden flex items-center">
        <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-primary focus:outline-none">
          <i class="fas fa-bars text-xl"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile menu -->
  <div class="mobile-menu hidden md:hidden bg-white border-t">
    <div class="px-2 pt-2 pb-3 space-y-1">
      <?php if (isset($_SESSION['user_id'])): ?>
        <?php if ($_SESSION['role'] === 'customer'): ?>
          <a href="index.php?action=dashboard" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium flex items-center">
            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
          </a>
          <a href="index.php?action=my-reservations" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium flex items-center">
            <i class="fas fa-calendar-check mr-3"></i> My Reservations
          </a>
          <a href="index.php?action=book-room" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium flex items-center">
            <i class="fas fa-bed mr-3"></i> Book Room
          </a>
        <?php elseif (in_array($_SESSION['role'], ['admin', 'staff'])): ?>
          <a href="index.php?action=admin/dashboard" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium flex items-center">
            <i class="fas fa-tachometer-alt mr-3"></i> Admin Dashboard
          </a>
          <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="index.php?action=admin/users" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium flex items-center">
              <i class="fas fa-users mr-3"></i> Users
            </a>
          <?php endif; ?>
          <a href="index.php?action=admin/reservations" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium flex items-center">
            <i class="fas fa-calendar-alt mr-3"></i> Reservations
          </a>
          <a href="index.php?action=admin/rooms" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium flex items-center">
            <i class="fas fa-bed mr-3"></i> Manage Rooms
          </a>
          <a href="index.php?action=admin/services" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium flex items-center">
            <i class="fas fa-concierge-bell mr-3"></i> Services
          </a>
          <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="index.php?action=admin/reports" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium flex items-center">
              <i class="fas fa-chart-bar mr-3"></i> Reports
            </a>
          <?php endif; ?>
        <?php endif; ?>
      <?php endif; ?>

      <!-- Public Links - Only show when NOT logged in as admin/staff -->
      <?php if (!isset($_SESSION['user_id']) || $_SESSION['role'] === 'customer'): ?>
        <a href="index.php?action=rooms" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium flex items-center">
          <i class="fas fa-door-open mr-3"></i> Rooms
        </a>
        <a href="index.php?action=room-search" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium flex items-center">
          <i class="fas fa-search mr-3"></i> Search Rooms
        </a>
      <?php endif; ?>

      <!-- Common Public Links -->
      <a href="index.php?action=about" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium flex items-center">
        <i class="fas fa-info-circle mr-3"></i> About
      </a>
      <a href="index.php?action=contact" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium flex items-center">
        <i class="fas fa-envelope mr-3"></i> Contact
      </a>

      <!-- Mobile Auth Links -->
      <?php if (isset($_SESSION['user_id'])): ?>
        <div class="border-t my-2 pt-2">
          <div class="px-3 py-2">
            <span class="text-gray-700 font-medium flex items-center">
              <i class="fas fa-user-circle mr-2"></i>
              <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1
              <?php
              echo $_SESSION['role'] === 'admin' ? 'bg-red-100 text-red-800' : ($_SESSION['role'] === 'staff' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800');
              ?>">
              <?php echo ucfirst($_SESSION['role']); ?>
            </span>
          </div>
          <a href="index.php?action=profile" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium flex items-center">
            <i class="fas fa-user mr-3"></i> My Profile
          </a>
          <a href="index.php?action=logout" class="text-red-600 hover:text-red-800 block px-3 py-2 rounded-md text-base font-medium flex items-center">
            <i class="fas fa-sign-out-alt mr-3"></i> Logout
          </a>
        </div>
      <?php else: ?>
        <div class="border-t my-2 pt-2">
          <a href="index.php?action=login" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium flex items-center">
            <i class="fas fa-sign-in-alt mr-3"></i> Login
          </a>
          <a href="index.php?action=register" class="bg-primary hover:bg-primary/90 text-white block px-3 py-2 rounded-md text-base font-medium flex items-center mt-2">
            <i class="fas fa-user-plus mr-3"></i> Register
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</nav>

<script>
  // Mobile menu toggle
  document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.querySelector('.mobile-menu');

    if (mobileMenuButton) {
      mobileMenuButton.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
      });
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
      if (!event.target.closest('.mobile-menu') && !event.target.closest('.mobile-menu-button')) {
        mobileMenu.classList.add('hidden');
      }
    });
  });
</script>
