<?php
// app/views/layout/header.php
$role = $_SESSION['role'] ?? 'guest';
$isCustomer = $role === 'customer';
$isStaff = in_array($role, ['admin', 'staff']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Bannie State of Cebu Reservation System</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Custom Tailwind Config -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#1e40af', // Blue
            secondary: '#0f766e', // Teal
            accent: '#dc2626', // Red
            luxury: '#d97706', // Amber
            success: '#059669', // Emerald
          },
          fontFamily: {
            'display': ['Playfair Display', 'Georgia', 'serif'],
            'sans': ['Inter', 'system-ui', 'sans-serif'],
          }
        }
      }
    }
  </script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    .sidebar-link {
      transition: all 0.3s ease;
    }

    .sidebar-link:hover {
      background-color: rgba(255, 255, 255, 0.1);
      transform: translateX(5px);
    }

    .sidebar-link.active {
      background-color: rgba(255, 255, 255, 0.15);
      border-left: 4px solid white;
    }
  </style>
</head>

<body class="bg-gray-50 font-sans">
  <div>
    <!-- Sidebar - Only show for staff/admin, not for customers -->
    <?php if ($isStaff): ?>
      <div class="hidden md:block bg-gradient-to-b from-primary to-secondary text-white w-64">
        <div class="p-6">
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
            <a href="index.php?action=dashboard" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
              <i class="fas fa-tachometer-alt w-6 text-center"></i>
              <span>Dashboard</span>
            </a>

            <div class="pt-4">
              <p class="text-white/60 text-xs uppercase tracking-wider mb-2">Reservations</p>
              <a href="index.php?action=reservations" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                <i class="fas fa-calendar-alt w-6 text-center"></i>
                <span>All Reservations</span>
              </a>
              <a href="index.php?action=new-reservation" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                <i class="fas fa-plus-circle w-6 text-center"></i>
                <span>New Reservation</span>
              </a>
              <a href="index.php?action=checkin" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                <i class="fas fa-sign-in-alt w-6 text-center"></i>
                <span>Check-in</span>
              </a>
              <a href="index.php?action=checkout" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                <i class="fas fa-sign-out-alt w-6 text-center"></i>
                <span>Check-out</span>
              </a>
            </div>

            <div class="pt-4">
              <p class="text-white/60 text-xs uppercase tracking-wider mb-2">Rooms</p>
              <a href="index.php?action=rooms" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                <i class="fas fa-bed w-6 text-center"></i>
                <span>Room Management</span>
              </a>
              <a href="index.php?action=room-types" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                <i class="fas fa-tags w-6 text-center"></i>
                <span>Room Types</span>
              </a>
              <a href="index.php?action=availability" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                <i class="fas fa-calendar-check w-6 text-center"></i>
                <span>Availability</span>
              </a>
            </div>

            <div class="pt-4">
              <p class="text-white/60 text-xs uppercase tracking-wider mb-2">Guests</p>
              <a href="index.php?action=guests" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                <i class="fas fa-users w-6 text-center"></i>
                <span>Guest Directory</span>
              </a>
              <a href="index.php?action=reviews" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                <i class="fas fa-star w-6 text-center"></i>
                <span>Guest Reviews</span>
              </a>
            </div>

            <?php if ($role === 'admin'): ?>
              <div class="pt-4">
                <p class="text-white/60 text-xs uppercase tracking-wider mb-2">Administration</p>
                <a href="index.php?action=staff" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                  <i class="fas fa-user-tie w-6 text-center"></i>
                  <span>Staff Management</span>
                </a>
                <a href="index.php?action=reports" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                  <i class="fas fa-chart-bar w-6 text-center"></i>
                  <span>Reports & Analytics</span>
                </a>
                <a href="index.php?action=settings" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                  <i class="fas fa-cog w-6 text-center"></i>
                  <span>System Settings</span>
                </a>
              </div>
            <?php endif; ?>

            <div class="pt-4">
              <a href="index.php?action=profile" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                <i class="fas fa-user-circle w-6 text-center"></i>
                <span>My Profile</span>
              </a>
            </div>
          </nav>

          <!-- Logout -->
          <div class="absolute bottom-0 left-0 right-0 p-6">
            <a href="index.php?action=logout" class="flex items-center space-x-3 p-3 rounded-lg bg-white/10 hover:bg-white/20 transition duration-300">
              <i class="fas fa-sign-out-alt"></i>
              <span>Logout</span>
            </a>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Main Content Area -->
    <div class="flex-1 <?php echo $isStaff ? 'md:ml-64' : ''; ?>">
      <!-- Mobile Menu Button (only for staff) -->
      <?php if ($isStaff): ?>
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

              <!-- Same navigation as desktop but simplified -->
              <nav class="space-y-2">
                <a href="index.php?action=dashboard" class="block p-3 rounded-lg hover:bg-white/10">Dashboard</a>
                <a href="index.php?action=reservations" class="block p-3 rounded-lg hover:bg-white/10">Reservations</a>
                <a href="index.php?action=rooms" class="block p-3 rounded-lg hover:bg-white/10">Rooms</a>
                <a href="index.php?action=guests" class="block p-3 rounded-lg hover:bg-white/10">Guests</a>
                <?php if ($role === 'admin'): ?>
                  <a href="index.php?action=reports" class="block p-3 rounded-lg hover:bg-white/10">Reports</a>
                  <a href="index.php?action=settings" class="block p-3 rounded-lg hover:bg-white/10">Settings</a>
                <?php endif; ?>
              </nav>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <!-- Page Content -->
      <main>
