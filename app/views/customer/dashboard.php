<?php
// app/views/customer/dashboard.php
// Note: $user, $upcomingReservations, $pastReservations, $loyaltyInfo, $availableRooms, $stats, $page_title are passed from controller
?>

<div class="container mx-auto px-4 py-8">
  <!-- Welcome Section -->
  <div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-2">Welcome back, <?php echo htmlspecialchars($user['first_name'] ?? 'Guest'); ?>!</h1>
    <p class="text-gray-600">Manage your reservations and explore our available rooms</p>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-blue-100 text-sm mb-1">Total Reservations</p>
          <h3 class="text-3xl font-bold"><?php echo count($upcomingReservations ?? []) + count($pastReservations ?? []); ?></h3>
        </div>
        <div class="bg-white/20 p-4 rounded-lg">
          <i class="fas fa-calendar-check text-2xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-green-100 text-sm mb-1">Upcoming Stays</p>
          <h3 class="text-3xl font-bold"><?php echo count($upcomingReservations ?? []); ?></h3>
        </div>
        <div class="bg-white/20 p-4 rounded-lg">
          <i class="fas fa-bed text-2xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-purple-100 text-sm mb-1">Loyalty Points</p>
          <h3 class="text-3xl font-bold"><?php echo number_format($loyaltyInfo['loyalty_points'] ?? 0); ?></h3>
        </div>
        <div class="bg-white/20 p-4 rounded-lg">
          <i class="fas fa-star text-2xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-orange-100 text-sm mb-1">Total Spent</p>
          <h3 class="text-3xl font-bold">$<?php echo number_format($loyaltyInfo['total_spent'] ?? 0, 2); ?></h3>
        </div>
        <div class="bg-white/20 p-4 rounded-lg">
          <i class="fas fa-dollar-sign text-2xl"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Upcoming Reservations -->
    <div class="lg:col-span-2">
      <!-- Upcoming Reservations -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-800">Upcoming Reservations</h2>
          <a href="index.php?action=my-reservations" class="text-primary hover:text-primary/80 font-semibold text-sm">
            View All <i class="fas fa-arrow-right ml-1"></i>
          </a>
        </div>

        <?php if (empty($upcomingReservations)): ?>
          <div class="text-center py-12">
            <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-600 mb-4">No upcoming reservations</p>
            <a href="index.php?action=book-room" class="bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-lg font-semibold transition duration-300 inline-block">
              <i class="fas fa-plus-circle mr-2"></i> Book a Room
            </a>
          </div>
        <?php else: ?>
          <div class="space-y-4">
            <?php foreach (array_slice($upcomingReservations, 0, 3) as $reservation): ?>
              <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-300">
                <div class="flex justify-between items-start">
                  <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                      <h3 class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($reservation['room_type'] ?? 'Room'); ?></h3>
                      <span class="px-2 py-1 rounded-full text-xs font-semibold <?php
                        echo $reservation['status'] === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                      ?>">
                        <?php echo ucfirst($reservation['status'] ?? 'pending'); ?>
                      </span>
                    </div>
                    <p class="text-gray-600 text-sm mb-2">Room #<?php echo htmlspecialchars($reservation['room_number'] ?? 'N/A'); ?></p>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                      <span><i class="fas fa-calendar-check text-primary mr-1"></i> <?php echo date('M d, Y', strtotime($reservation['check_in'] ?? 'now')); ?></span>
                      <span><i class="fas fa-calendar-times text-primary mr-1"></i> <?php echo date('M d, Y', strtotime($reservation['check_out'] ?? 'now')); ?></span>
                    </div>
                  </div>
                  <div class="text-right">
                    <p class="text-lg font-bold text-gray-800">$<?php echo number_format($reservation['total_amount'] ?? 0, 2); ?></p>
                    <a href="index.php?action=my-reservations&sub_action=view&id=<?php echo $reservation['id']; ?>" class="text-primary hover:text-primary/80 text-sm font-semibold mt-2 inline-block">
                      View Details <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Past Reservations -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-800">Past Reservations</h2>
          <a href="index.php?action=my-reservations" class="text-primary hover:text-primary/80 font-semibold text-sm">
            View All <i class="fas fa-arrow-right ml-1"></i>
          </a>
        </div>

        <?php if (empty($pastReservations)): ?>
          <div class="text-center py-8">
            <i class="fas fa-history text-gray-300 text-4xl mb-3"></i>
            <p class="text-gray-600">No past reservations</p>
          </div>
        <?php else: ?>
          <div class="space-y-4">
            <?php foreach (array_slice($pastReservations, 0, 3) as $reservation): ?>
              <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-300 opacity-75">
                <div class="flex justify-between items-start">
                  <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($reservation['room_type'] ?? 'Room'); ?></h3>
                    <p class="text-gray-600 text-sm mb-2">Room #<?php echo htmlspecialchars($reservation['room_number'] ?? 'N/A'); ?></p>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                      <span><i class="fas fa-calendar-check text-primary mr-1"></i> <?php echo date('M d, Y', strtotime($reservation['check_in'] ?? 'now')); ?></span>
                      <span><i class="fas fa-calendar-times text-primary mr-1"></i> <?php echo date('M d, Y', strtotime($reservation['check_out'] ?? 'now')); ?></span>
                    </div>
                  </div>
                  <div class="text-right">
                    <p class="text-lg font-bold text-gray-800">$<?php echo number_format($reservation['total_amount'] ?? 0, 2); ?></p>
                    <a href="index.php?action=my-reservations&sub_action=view&id=<?php echo $reservation['id']; ?>" class="text-primary hover:text-primary/80 text-sm font-semibold mt-2 inline-block">
                      View Details <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Right Column: Quick Actions & Available Rooms -->
    <div class="space-y-6">
      <!-- Quick Actions -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h2>
        <div class="space-y-3">
          <a href="index.php?action=book-room" class="block w-full bg-primary hover:bg-primary/90 text-white px-4 py-3 rounded-lg font-semibold transition duration-300 text-center">
            <i class="fas fa-bed mr-2"></i> Book a Room
          </a>
          <a href="index.php?action=my-reservations" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg font-semibold transition duration-300 text-center">
            <i class="fas fa-calendar-check mr-2"></i> My Reservations
          </a>
          <a href="index.php?action=profile" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg font-semibold transition duration-300 text-center">
            <i class="fas fa-user mr-2"></i> My Profile
          </a>
        </div>
      </div>

      <!-- Available Rooms Preview -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold text-gray-800">Available Rooms</h2>
          <a href="index.php?action=rooms" class="text-primary hover:text-primary/80 text-sm font-semibold">
            View All
          </a>
        </div>
        <?php if (!empty($availableRooms)): ?>
          <div class="space-y-4">
            <?php foreach (array_slice($availableRooms, 0, 3) as $room): ?>
              <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-300">
                <div class="flex items-center gap-3 mb-2">
                  <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-lg flex items-center justify-center text-white font-bold">
                    <?php echo strtoupper(substr($room['type'] ?? 'R', 0, 1)); ?>
                  </div>
                  <div class="flex-1">
                    <h3 class="font-semibold text-gray-800"><?php echo htmlspecialchars($room['type'] ?? 'Room'); ?></h3>
                    <p class="text-sm text-gray-600"><?php echo $room['available_count'] ?? 0; ?> available</p>
                  </div>
                </div>
                <div class="flex justify-between items-center mt-3">
                  <span class="text-lg font-bold text-primary">$<?php echo number_format($room['price_per_night'] ?? 0, 2); ?>/night</span>
                  <a href="index.php?action=book-room" class="text-primary hover:text-primary/80 text-sm font-semibold">
                    Book Now <i class="fas fa-arrow-right ml-1"></i>
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-gray-600 text-center py-4">No rooms available</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
