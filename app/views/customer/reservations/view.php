<?php
// app/views/customer/reservations/view.php
// Note: $reservation, $services, $page_title are passed from controller
?>

<div class="container mx-auto px-4 py-8">
  <!-- Breadcrumb -->
  <nav class="mb-6">
    <ol class="flex items-center space-x-2 text-sm">
      <li><a href="index.php?action=dashboard" class="text-primary hover:text-primary/80">Dashboard</a></li>
      <li><i class="fas fa-chevron-right text-gray-400"></i></li>
      <li><a href="index.php?action=my-reservations" class="text-primary hover:text-primary/80">My Reservations</a></li>
      <li><i class="fas fa-chevron-right text-gray-400"></i></li>
      <li class="text-gray-600">Reservation #<?php echo str_pad($reservation['id'], 6, '0', STR_PAD_LEFT); ?></li>
    </ol>
  </nav>

  <!-- Header -->
  <div class="flex justify-between items-center mb-6">
    <div>
      <h1 class="text-3xl font-bold text-gray-800 mb-2">Reservation Details</h1>
      <p class="text-gray-600">Reservation Code: <span class="font-semibold"><?php echo htmlspecialchars($reservation['reservation_code'] ?? 'N/A'); ?></span></p>
    </div>
    <div class="flex gap-3">
      <a href="index.php?action=my-reservations&sub_action=print-invoice&id=<?php echo $reservation['id']; ?>"
        class="bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
        <i class="fas fa-file-invoice mr-2"></i> Invoice
      </a>
      <?php if ($reservation['status'] === 'pending'): ?>
        <a href="index.php?action=book-room&sub_action=confirmation&id=<?php echo $reservation['id']; ?>"
          class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
          <i class="fas fa-credit-card mr-2"></i> Complete Payment
        </a>
      <?php endif; ?>
    </div>
  </div>

  <!-- Status Alert -->
  <div class="mb-6 p-4 rounded-lg <?php
                                  echo $reservation['status'] === 'confirmed' ? 'bg-green-50 border border-green-200' : ($reservation['status'] === 'pending' ? 'bg-yellow-50 border border-yellow-200' : ($reservation['status'] === 'cancelled' ? 'bg-red-50 border border-red-200' : 'bg-gray-50 border border-gray-200'));
                                  ?>">
    <div class="flex items-center">
      <i class="fas <?php
                    echo $reservation['status'] === 'confirmed' ? 'fa-check-circle text-green-600' : ($reservation['status'] === 'pending' ? 'fa-clock text-yellow-600' : ($reservation['status'] === 'cancelled' ? 'fa-times-circle text-red-600' : 'fa-info-circle text-gray-600'));
                    ?> text-2xl mr-3"></i>
      <div>
        <h3 class="font-semibold text-gray-800 mb-1">Reservation <?php echo ucfirst($reservation['status']); ?></h3>
        <p class="text-gray-600 text-sm">
          <?php
          if ($reservation['status'] === 'pending') {
            echo 'Awaiting payment confirmation';
          } elseif ($reservation['status'] === 'confirmed') {
            echo 'Your booking is confirmed and ready';
          } elseif ($reservation['status'] === 'cancelled') {
            echo 'This reservation has been cancelled';
          } else {
            echo 'This reservation has been completed';
          }
          ?>
        </p>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Main Details -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Room Details -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-door-open text-primary mr-2"></i> Room Details
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($reservation['room_type']); ?></h3>
            <p class="text-gray-600 mb-4">Room #<?php echo htmlspecialchars($reservation['room_number']); ?></p>
            <p class="text-gray-700"><?php echo htmlspecialchars($reservation['room_description'] ?? ''); ?></p>
          </div>
          <div class="bg-gray-50 rounded-lg p-4 text-center">
            <p class="text-gray-600 text-sm mb-1">Price per night</p>
            <p class="text-2xl font-bold text-primary">$<?php echo number_format($reservation['price_per_night'] ?? 0, 2); ?></p>
          </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t">
          <div>
            <p class="text-gray-600 text-sm mb-1">Check-in</p>
            <p class="font-semibold text-gray-800"><?php echo date('M j, Y', strtotime($reservation['check_in'])); ?></p>
            <p class="text-gray-500 text-xs">From 3:00 PM</p>
          </div>
          <div>
            <p class="text-gray-600 text-sm mb-1">Check-out</p>
            <p class="font-semibold text-gray-800"><?php echo date('M j, Y', strtotime($reservation['check_out'])); ?></p>
            <p class="text-gray-500 text-xs">Until 11:00 AM</p>
          </div>
          <div>
            <p class="text-gray-600 text-sm mb-1">Duration</p>
            <p class="font-semibold text-gray-800"><?php echo $reservation['nights'] ?? 1; ?> nights</p>
          </div>
          <div>
            <p class="text-gray-600 text-sm mb-1">Guests</p>
            <p class="font-semibold text-gray-800"><?php echo ($reservation['adults'] ?? 1) + ($reservation['children'] ?? 0); ?> guests</p>
          </div>
        </div>
      </div>

      <!-- Services -->
      <?php if (!empty($services)): ?>
        <div class="bg-white rounded-xl shadow-md p-6">
          <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-concierge-bell text-primary mr-2"></i> Additional Services
          </h2>
          <div class="space-y-3">
            <?php foreach ($services as $service): ?>
              <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <div>
                  <h4 class="font-semibold text-gray-800"><?php echo htmlspecialchars($service['name']); ?></h4>
                  <?php if (!empty($service['description'])): ?>
                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($service['description']); ?></p>
                  <?php endif; ?>
                </div>
                <div class="text-right">
                  <p class="font-semibold text-gray-800">$<?php echo number_format($service['service_price'] ?? 0, 2); ?></p>
                  <?php if (isset($service['quantity']) && $service['quantity'] > 1): ?>
                    <p class="text-xs text-gray-500">Qty: <?php echo $service['quantity']; ?></p>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <!-- Special Requests -->
      <?php if (!empty($reservation['special_requests'])): ?>
        <div class="bg-white rounded-xl shadow-md p-6">
          <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-comment-alt text-primary mr-2"></i> Special Requests
          </h2>
          <p class="text-gray-700 whitespace-pre-wrap"><?php echo htmlspecialchars($reservation['special_requests']); ?></p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Right Column: Price & Actions -->
    <div class="space-y-6">
      <!-- Price Breakdown -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-calculator text-primary mr-2"></i> Price Breakdown
        </h2>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-gray-600">Room (<?php echo $reservation['nights'] ?? 1; ?> nights)</span>
            <span class="font-semibold">$<?php echo number_format($reservation['room_total'] ?? 0, 2); ?></span>
          </div>
          <?php if (!empty($services)): ?>
            <div class="flex justify-between">
              <span class="text-gray-600">Services</span>
              <span class="font-semibold">$<?php echo number_format(array_sum(array_column($services, 'service_price')), 2); ?></span>
            </div>
          <?php endif; ?>
          <div class="flex justify-between">
            <span class="text-gray-600">Tax (10%)</span>
            <span class="font-semibold">$<?php echo number_format(($reservation['room_total'] ?? 0) * 0.10, 2); ?></span>
          </div>
          <div class="border-t pt-3 mt-3">
            <div class="flex justify-between items-center">
              <span class="text-lg font-bold text-gray-800">Total Amount</span>
              <span class="text-lg font-bold text-primary">$<?php echo number_format($reservation['total_amount'] ?? 0, 2); ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Payment Information -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-credit-card text-primary mr-2"></i> Payment Information
        </h2>
        <div class="space-y-3">
          <div>
            <p class="text-gray-600 text-sm mb-1">Payment Status</p>
            <span class="px-3 py-1 rounded-full text-xs font-semibold <?php
                                                                      echo $reservation['payment_status'] === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                                                                      ?>">
              <?php echo ucfirst($reservation['payment_status'] ?? 'pending'); ?>
            </span>
          </div>
          <?php if (!empty($reservation['payment_method'])): ?>
            <div>
              <p class="text-gray-600 text-sm mb-1">Payment Method</p>
              <p class="font-semibold text-gray-800"><?php echo ucfirst(str_replace('_', ' ', $reservation['payment_method'])); ?></p>
            </div>
          <?php endif; ?>
          <?php if (!empty($reservation['transaction_id'])): ?>
            <div>
              <p class="text-gray-600 text-sm mb-1">Transaction ID</p>
              <p class="font-mono text-sm text-gray-800"><?php echo htmlspecialchars($reservation['transaction_id']); ?></p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-bolt text-primary mr-2"></i> Quick Actions
        </h2>
        <div class="space-y-3">
          <a href="index.php?action=my-reservations&sub_action=print-invoice&id=<?php echo $reservation['id']; ?>"
            class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg font-semibold transition duration-300 text-center">
            <i class="fas fa-download mr-2"></i> Download Invoice
          </a>
          <?php if (in_array($reservation['status'], ['pending', 'confirmed'])): ?>
            <button onclick="document.getElementById('cancelModal').classList.remove('hidden')"
              class="block w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg font-semibold transition duration-300 text-center">
              <i class="fas fa-times-circle mr-2"></i> Cancel Reservation
            </button>
          <?php endif; ?>
          <a href="index.php?action=book-room"
            class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg font-semibold transition duration-300 text-center">
            <i class="fas fa-plus-circle mr-2"></i> Make Another Booking
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Cancel Modal -->
<?php if (in_array($reservation['status'], ['pending', 'confirmed'])): ?>
  <div id="cancelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-gray-800">Cancel Reservation</h3>
        <button onclick="document.getElementById('cancelModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <form method="POST" action="index.php?action=my-reservations&sub_action=cancel&id=<?php echo $reservation['id']; ?>">
        <p class="text-gray-600 mb-4">Are you sure you want to cancel reservation #<?php echo str_pad($reservation['id'], 6, '0', STR_PAD_LEFT); ?>?</p>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-semibold mb-2">Reason for cancellation (optional):</label>
          <textarea name="reason" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
            placeholder="Please provide a reason for cancellation"></textarea>
        </div>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
          <p class="text-sm text-yellow-800">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            <strong>Cancellation Policy:</strong><br>
            • Cancellations within 24 hours of check-in may incur a 50% fee<br>
            • No-show reservations will be charged the full amount
          </p>
        </div>
        <div class="flex gap-3">
          <button type="button" onclick="document.getElementById('cancelModal').classList.add('hidden')"
            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold transition duration-300">
            Close
          </button>
          <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold transition duration-300">
            Confirm Cancellation
          </button>
        </div>
      </form>
    </div>
  </div>
<?php endif; ?>
