<?php
// app/views/customer/reservations/index.php
// Note: $reservations, $status, $page, $totalPages, $totalReservations, $page_title are passed from controller
?>

<div class="container mx-auto px-4 py-8">
  <div class="flex justify-between items-center mb-6">
    <div>
      <h1 class="text-3xl font-bold mb-2 text-gray-800">My Reservations</h1>
      <p class="text-gray-600">Manage and view all your bookings</p>
    </div>
    <a href="index.php?action=book-room" class="bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
      <i class="fas fa-plus-circle mr-2"></i> New Booking
    </a>
  </div>

  <!-- Filters -->
  <div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h6 class="font-semibold text-gray-800 mb-3">Filter by Status:</h6>
        <div class="flex flex-wrap gap-2">
          <a href="index.php?action=my-reservations"
            class="px-4 py-2 rounded-lg font-semibold text-sm transition duration-300 <?php echo !$status ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
            All (<?php echo $totalReservations ?? 0; ?>)
          </a>
          <a href="index.php?action=my-reservations&status=confirmed"
            class="px-4 py-2 rounded-lg font-semibold text-sm transition duration-300 <?php echo $status === 'confirmed' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
            Confirmed
          </a>
          <a href="index.php?action=my-reservations&status=pending"
            class="px-4 py-2 rounded-lg font-semibold text-sm transition duration-300 <?php echo $status === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
            Pending
          </a>
          <a href="index.php?action=my-reservations&status=cancelled"
            class="px-4 py-2 rounded-lg font-semibold text-sm transition duration-300 <?php echo $status === 'cancelled' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
            Cancelled
          </a>
          <a href="index.php?action=my-reservations&status=completed"
            class="px-4 py-2 rounded-lg font-semibold text-sm transition duration-300 <?php echo $status === 'completed' ? 'bg-gray-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
            Completed
          </a>
        </div>
      </div>
      <div>
        <form method="GET" class="flex gap-2">
          <input type="hidden" name="action" value="my-reservations">
          <?php if ($status): ?>
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
          <?php endif; ?>
          <input type="text" name="search" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
            placeholder="Search reservations..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
          <button type="submit" class="bg-primary hover:bg-primary/90 text-white px-6 py-2 rounded-lg font-semibold transition duration-300">
            <i class="fas fa-search"></i>
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Reservations List -->
  <?php if (empty($reservations)): ?>
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
      <i class="fas fa-calendar-times text-gray-400 text-6xl mb-4"></i>
      <h4 class="text-2xl font-semibold text-gray-800 mb-2">No reservations found</h4>
      <p class="text-gray-600 mb-6">You haven't made any reservations yet.</p>
      <a href="index.php?action=book-room" class="bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-lg font-semibold transition duration-300 inline-block">
        <i class="fas fa-plus-circle mr-2"></i> Make Your First Booking
      </a>
    </div>
  <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($reservations as $reservation): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300 border-l-4 <?php
                                                                                                                      echo $reservation['status'] === 'confirmed' ? 'border-green-500' : ($reservation['status'] === 'pending' ? 'border-yellow-500' : ($reservation['status'] === 'cancelled' ? 'border-red-500' : 'border-gray-500'));
                                                                                                                      ?>">
          <div class="p-6">
            <div class="flex justify-between items-start mb-4">
              <div>
                <h5 class="text-xl font-semibold mb-1 text-gray-800">
                  <?php echo htmlspecialchars($reservation['room_type'] ?? 'Room'); ?>
                </h5>
                <p class="text-gray-600 text-sm">
                  Room #<?php echo htmlspecialchars($reservation['room_number'] ?? 'N/A'); ?>
                </p>
              </div>
              <span class="px-3 py-1 rounded-full text-xs font-semibold <?php
                                                                        echo $reservation['status'] === 'confirmed' ? 'bg-green-100 text-green-800' : ($reservation['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($reservation['status'] === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'));
                                                                        ?>">
                <?php echo ucfirst($reservation['status'] ?? 'Unknown'); ?>
              </span>
            </div>

            <div class="space-y-2 mb-4">
              <div class="flex items-center text-gray-600">
                <i class="fas fa-calendar-check text-primary mr-2"></i>
                <span class="text-sm"><?php echo date('M d, Y', strtotime($reservation['check_in'] ?? 'now')); ?></span>
              </div>
              <div class="flex items-center text-gray-600">
                <i class="fas fa-calendar-times text-primary mr-2"></i>
                <span class="text-sm"><?php echo date('M d, Y', strtotime($reservation['check_out'] ?? 'now')); ?></span>
              </div>
              <div class="flex items-center text-gray-600">
                <i class="fas fa-users text-primary mr-2"></i>
                <span class="text-sm"><?php echo $reservation['guests'] ?? 1; ?> guests</span>
              </div>
            </div>

            <div class="border-t pt-4 mb-4">
              <div class="flex justify-between items-center">
                <div>
                  <h6 class="text-lg font-bold text-gray-800">$<?php echo number_format($reservation['total_amount'] ?? 0, 2); ?></h6>
                  <small class="text-gray-600">Total amount</small>
                </div>
              </div>
            </div>

            <div class="flex gap-2">
              <a href="index.php?action=my-reservations&sub_action=view&id=<?php echo $reservation['id']; ?>"
                class="flex-1 bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg font-semibold transition duration-300 text-center text-sm">
                <i class="fas fa-eye mr-1"></i> View
              </a>
              <?php if (in_array($reservation['status'] ?? '', ['pending', 'confirmed'])): ?>
                <a href="index.php?action=my-reservations&sub_action=cancel&id=<?php echo $reservation['id']; ?>"
                  class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold transition duration-300 text-sm">
                  <i class="fas fa-times mr-1"></i> Cancel
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if (($totalPages ?? 1) > 1): ?>
      <div class="mt-8 flex justify-center">
        <div class="flex gap-2">
          <a href="index.php?action=my-reservations&page=<?php echo max(1, ($page ?? 1) - 1); ?><?php echo $status ? '&status=' . urlencode($status) : ''; ?>"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg font-semibold <?php echo ($page ?? 1) == 1 ? 'opacity-50 cursor-not-allowed' : ''; ?>">
            Previous
          </a>
          <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
            <a href="index.php?action=my-reservations&page=<?php echo $i; ?><?php echo $status ? '&status=' . urlencode($status) : ''; ?>"
              class="px-4 py-2 rounded-lg font-semibold transition duration-300 <?php echo ($page ?? 1) == $i ? 'bg-primary text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-700'; ?>">
              <?php echo $i; ?>
            </a>
          <?php endfor; ?>
          <a href="index.php?action=my-reservations&page=<?php echo min(($totalPages ?? 1), ($page ?? 1) + 1); ?><?php echo $status ? '&status=' . urlencode($status) : ''; ?>"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg font-semibold <?php echo ($page ?? 1) == ($totalPages ?? 1) ? 'opacity-50 cursor-not-allowed' : ''; ?>">
            Next
          </a>
        </div>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>
