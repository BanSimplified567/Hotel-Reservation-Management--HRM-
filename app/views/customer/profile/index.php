<?php
// app/views/customer/profile/index.php
// Note: $user, $stats, $page_title are passed from controller
?>

<div class="container mx-auto px-4 py-8">
  <!-- Profile Header -->
  <div class="bg-gradient-to-r from-primary to-secondary rounded-xl shadow-lg p-8 mb-8 text-white">
    <div class="flex flex-col md:flex-row items-center md:items-start justify-between">
      <div class="flex items-center mb-4 md:mb-0">
        <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center text-4xl font-bold mr-6">
          <?php echo strtoupper(substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? 'S', 0, 1)); ?>
        </div>
        <div>
          <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')); ?></h1>
          <p class="text-white/80 mb-2">
            <i class="fas fa-envelope mr-2"></i> <?php echo htmlspecialchars($user['email'] ?? ''); ?>
          </p>
          <div class="flex gap-2">
            <span class="px-3 py-1 bg-white/20 rounded-full text-sm">
              <i class="fas fa-user mr-1"></i> Customer
            </span>
            <span class="px-3 py-1 bg-white/20 rounded-full text-sm">
              Member since <?php echo date('M Y', strtotime($user['created_at'] ?? 'now')); ?>
            </span>
          </div>
        </div>
      </div>
      <div class="flex gap-3">
        <a href="index.php?action=profile&sub_action=edit" class="bg-white text-primary hover:bg-gray-100 px-6 py-3 rounded-lg font-semibold transition duration-300">
          <i class="fas fa-edit mr-2"></i> Edit Profile
        </a>
        <a href="index.php?action=profile&sub_action=change-password" class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-semibold transition duration-300 border border-white/30">
          <i class="fas fa-key mr-2"></i> Change Password
        </a>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Profile Information -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Personal Information -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-user-circle text-primary mr-2"></i> Personal Information
          </h2>
          <a href="index.php?action=profile&sub_action=edit" class="text-primary hover:text-primary/80 text-sm font-semibold">
            <i class="fas fa-edit mr-1"></i> Edit
          </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <p class="text-gray-600 text-sm mb-1">First Name</p>
            <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($user['first_name'] ?? ''); ?></p>
          </div>
          <div>
            <p class="text-gray-600 text-sm mb-1">Last Name</p>
            <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($user['last_name'] ?? ''); ?></p>
          </div>
          <div>
            <p class="text-gray-600 text-sm mb-1">Email Address</p>
            <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
          </div>
          <div>
            <p class="text-gray-600 text-sm mb-1">Phone Number</p>
            <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($user['phone'] ?? 'Not provided'); ?></p>
          </div>
          <div>
            <p class="text-gray-600 text-sm mb-1">Username</p>
            <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($user['username'] ?? ''); ?></p>
          </div>
          <div>
            <p class="text-gray-600 text-sm mb-1">Date of Birth</p>
            <p class="font-semibold text-gray-800">
              <?php echo !empty($user['date_of_birth']) ? date('F j, Y', strtotime($user['date_of_birth'])) : 'Not provided'; ?>
            </p>
          </div>
        </div>
      </div>

      <!-- Contact Information -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
          <i class="fas fa-map-marker-alt text-primary mr-2"></i> Contact Information
        </h2>
        <div class="space-y-4">
          <div>
            <p class="text-gray-600 text-sm mb-1">Address</p>
            <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($user['address'] ?? 'Not provided'); ?></p>
          </div>
        </div>
      </div>

      <!-- Statistics -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
          <i class="fas fa-chart-bar text-primary mr-2"></i> Your Statistics
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="text-center p-4 bg-blue-50 rounded-lg">
            <p class="text-2xl font-bold text-primary"><?php echo $stats['total_reservations'] ?? 0; ?></p>
            <p class="text-sm text-gray-600">Total Reservations</p>
          </div>
          <div class="text-center p-4 bg-green-50 rounded-lg">
            <p class="text-2xl font-bold text-green-600"><?php echo $stats['completed_reservations'] ?? 0; ?></p>
            <p class="text-sm text-gray-600">Completed</p>
          </div>
          <div class="text-center p-4 bg-purple-50 rounded-lg">
            <p class="text-2xl font-bold text-purple-600"><?php echo $stats['upcoming_reservations'] ?? 0; ?></p>
            <p class="text-sm text-gray-600">Upcoming</p>
          </div>
          <div class="text-center p-4 bg-orange-50 rounded-lg">
            <p class="text-2xl font-bold text-orange-600">$<?php echo number_format($stats['total_spent'] ?? 0, 0); ?></p>
            <p class="text-sm text-gray-600">Total Spent</p>
          </div>
        </div>
        <?php if (!empty($stats['favorite_room_type']) && $stats['favorite_room_type'] !== 'None'): ?>
          <div class="mt-6 pt-6 border-t">
            <p class="text-gray-600 text-sm mb-1">Favorite Room Type</p>
            <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($stats['favorite_room_type']); ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Right Column: Quick Actions -->
    <div class="space-y-6">
      <!-- Quick Actions -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-bolt text-primary mr-2"></i> Quick Actions
        </h2>
        <div class="space-y-3">
          <a href="index.php?action=profile&sub_action=edit" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg font-semibold transition duration-300 text-center">
            <i class="fas fa-edit mr-2"></i> Edit Profile
          </a>
          <a href="index.php?action=profile&sub_action=change-password" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg font-semibold transition duration-300 text-center">
            <i class="fas fa-key mr-2"></i> Change Password
          </a>
          <a href="index.php?action=book-room" class="block w-full bg-primary hover:bg-primary/90 text-white px-4 py-3 rounded-lg font-semibold transition duration-300 text-center">
            <i class="fas fa-calendar-plus mr-2"></i> New Booking
          </a>
          <a href="index.php?action=my-reservations" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg font-semibold transition duration-300 text-center">
            <i class="fas fa-receipt mr-2"></i> My Reservations
          </a>
        </div>
      </div>

      <!-- Account Information -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-shield-alt text-primary mr-2"></i> Account Information
        </h2>
        <div class="space-y-3">
          <div>
            <p class="text-gray-600 text-sm mb-1">Account Status</p>
            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Active</span>
          </div>
          <div>
            <p class="text-gray-600 text-sm mb-1">Member Since</p>
            <p class="font-semibold text-gray-800"><?php echo date('F j, Y', strtotime($user['created_at'] ?? 'now')); ?></p>
          </div>
          <div>
            <p class="text-gray-600 text-sm mb-1">Last Updated</p>
            <p class="font-semibold text-gray-800">
              <?php echo !empty($user['updated_at']) ? date('F j, Y', strtotime($user['updated_at'])) : 'Never'; ?>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
