<?php
// app/views/customer/reservations/cancel.php
// Note: $reservation, $page_title are passed from controller
?>

<div class="container mx-auto px-4 py-8">
  <!-- Breadcrumb -->
  <nav class="mb-6">
    <ol class="flex items-center space-x-2 text-sm">
      <li><a href="index.php?action=dashboard" class="text-primary hover:text-primary/80">Dashboard</a></li>
      <li><i class="fas fa-chevron-right text-gray-400"></i></li>
      <li><a href="index.php?action=my-reservations" class="text-primary hover:text-primary/80">My Reservations</a></li>
      <li><i class="fas fa-chevron-right text-gray-400"></i></li>
      <li class="text-gray-600">Cancel Reservation</li>
    </ol>
  </nav>

  <div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
      <i class="fas fa-exclamation-triangle text-5xl text-yellow-500 mb-4"></i>
      <h1 class="text-3xl font-bold text-gray-800 mb-2">Cancel Reservation</h1>
      <p class="text-gray-600">Reservation #<?php echo str_pad($reservation['id'], 6, '0', STR_PAD_LEFT); ?> • Code: <?php echo htmlspecialchars($reservation['reservation_code']); ?></p>
    </div>

    <!-- Warning Box -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-6">
      <div class="flex items-start">
        <i class="fas fa-exclamation-circle text-yellow-600 text-xl mr-3 mt-1"></i>
        <div>
          <h3 class="font-bold text-yellow-800 mb-2">Important Cancellation Policy</h3>
          <ul class="text-yellow-700 space-y-1 text-sm">
            <li>• Cancellations within 24 hours of check-in may incur a 50% fee</li>
            <li>• No-show reservations will be charged the full amount</li>
            <li>• Refunds may take 5-7 business days to process</li>
            <li>• Cancellation requests are processed during business hours (9 AM - 6 PM)</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Reservation Details -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
      <h3 class="text-lg font-semibold text-gray-800 mb-4">Reservation Details</h3>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <p class="text-gray-600 text-sm">Check-in Date</p>
          <p class="font-semibold"><?php echo date('F j, Y', strtotime($reservation['check_in'])); ?></p>
        </div>
        <div>
          <p class="text-gray-600 text-sm">Total Amount</p>
          <p class="font-semibold text-lg text-primary">$<?php echo number_format($reservation['total_amount'], 2); ?></p>
        </div>
      </div>
    </div>

    <!-- Cancellation Form -->
    <div class="bg-white rounded-xl shadow-md p-6">
      <h3 class="text-lg font-semibold text-gray-800 mb-4">Cancellation Details</h3>
      <form method="POST" action="index.php?action=my-reservations&sub_action=cancel&id=<?php echo $reservation['id']; ?>">
        <div class="mb-6">
          <label class="block text-gray-700 font-semibold mb-2">Reason for cancellation <span class="text-gray-500">(optional)</span></label>
          <textarea name="reason" rows="4"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
            placeholder="Please let us know why you're cancelling this reservation..."></textarea>
          <p class="text-gray-500 text-sm mt-1">Your feedback helps us improve our service.</p>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
          <div class="flex items-center">
            <i class="fas fa-info-circle text-red-600 mr-3"></i>
            <div>
              <h4 class="font-semibold text-red-800 mb-1">Please Confirm</h4>
              <p class="text-red-700 text-sm">This action cannot be undone. Once cancelled, this reservation will be permanently removed from your bookings.</p>
            </div>
          </div>
        </div>

        <div class="flex gap-4">
          <a href="index.php?action=my-reservations"
            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold transition duration-300 text-center">
            <i class="fas fa-arrow-left mr-2"></i> Go Back
          </a>
          <button type="submit"
            class="flex-1 bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-300 flex items-center justify-center"
            onclick="return confirm('Are you sure you want to cancel this reservation?')">
            <i class="fas fa-times-circle mr-2"></i> Confirm Cancellation
          </button>
        </div>
      </form>
    </div>

    <!-- Contact Support -->
    <div class="text-center mt-8">
      <p class="text-gray-600 mb-2">Need assistance?</p>
      <a href="index.php?action=contact" class="text-primary hover:text-primary/80 font-semibold">
        <i class="fas fa-headset mr-2"></i> Contact Support
      </a>
    </div>
  </div>
</div>
