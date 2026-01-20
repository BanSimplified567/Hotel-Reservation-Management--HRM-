<?php
// app/views/admin/reservations/edit.php

$error = $_SESSION['error'] ?? '';
$old = $_SESSION['old'] ?? [];
unset($_SESSION['error']);
unset($_SESSION['old']);

// Use old data if available, otherwise use reservation data
$data = !empty($old) ? $old : $reservation;
?>

<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Reservation #<?php echo $reservation['id']; ?></h1>
    <div>
      <a href="index.php?action=admin/reservations&sub_action=view&id=<?php echo $reservation['id']; ?>"
        class="btn btn-secondary shadow-sm mr-2">
        <i class="fas fa-eye fa-sm text-white-50"></i> View
      </a>
      <a href="index.php?action=admin/reservations" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Reservations
      </a>
    </div>
  </div>

  <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $error; ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>

  <!-- Edit Form -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Edit Reservation Details</h6>
    </div>
    <div class="card-body">
      <form method="POST" action="index.php?action=admin/reservations&sub_action=edit&id=<?php echo $reservation['id']; ?>">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="room_id">Room *</label>
              <select class="form-control" id="room_id" name="room_id" required>
                <option value="">Select Room</option>
                <?php foreach ($rooms as $room): ?>
                  <option value="<?php echo $room['id']; ?>"
                    data-price="<?php echo $room['base_price']; ?>"
                    data-capacity="<?php echo $room['capacity']; ?>"
                    <?php echo ($data['room_id'] ?? '') == $room['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($room['room_number'] . ' - ' . $room['room_type'] . ' (₱' . number_format($room['base_price'], 2) . '/night, Capacity: ' . $room['capacity'] . ')'); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label for="check_in">Check-in Date *</label>
              <input type="date" class="form-control" id="check_in" name="check_in"
                value="<?php echo htmlspecialchars($data['check_in'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
              <label for="check_out">Check-out Date *</label>
              <input type="date" class="form-control" id="check_out" name="check_out"
                value="<?php echo htmlspecialchars($data['check_out'] ?? ''); ?>" required>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="guests">Number of Guests *</label>
              <input type="number" class="form-control" id="guests" name="guests" min="1"
                value="<?php echo htmlspecialchars($data['guests'] ?? 1); ?>" required>
              <small class="form-text text-muted">Maximum capacity: <span id="roomCapacity"><?php echo $reservation['capacity'] ?? 4; ?></span> guests</small>
            </div>

            <div class="form-group">
              <label for="status">Status *</label>
              <select class="form-control" id="status" name="status" required>
                <option value="pending" <?php echo ($data['status'] ?? '') == 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="confirmed" <?php echo ($data['status'] ?? '') == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                <option value="checked_in" <?php echo ($data['status'] ?? '') == 'checked_in' ? 'selected' : ''; ?>>Checked-in</option>
                <option value="completed" <?php echo ($data['status'] ?? '') == 'completed' ? 'selected' : ''; ?>>Completed</option>
                <option value="cancelled" <?php echo ($data['status'] ?? '') == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
              </select>
            </div>

            <div class="form-group">
              <label>Customer</label>
              <input type="text" class="form-control"
                value="<?php echo htmlspecialchars(($reservation['first_name'] ?? '') . ' ' . ($reservation['last_name'] ?? '') . ' (' . ($reservation['email'] ?? '') . ')'); ?>"
                readonly>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="special_requests">Special Requests</label>
              <textarea class="form-control" id="special_requests" name="special_requests" rows="3"><?php echo htmlspecialchars($data['special_requests'] ?? ''); ?></textarea>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="admin_notes">Admin Notes</label>
              <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3"><?php echo htmlspecialchars($data['admin_notes'] ?? ''); ?></textarea>
            </div>
          </div>
        </div>

        <!-- Price Preview -->
        <div class="card mt-4">
          <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Price Preview</h6>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <table class="table table-sm">
                  <tr>
                    <td>Room Price per Night:</td>
                    <td class="text-right">₱<span id="pricePerNight"><?php echo number_format($reservation['price_per_night'], 2); ?></span></td>
                  </tr>
                  <tr>
                    <td>Number of Nights:</td>
                    <td class="text-right"><span id="numberOfNights">0</span> nights</td>
                  </tr>
                  <tr class="table-active">
                    <td><strong>Total Room Charges:</strong></td>
                    <td class="text-right"><strong>₱<span id="roomTotal">0.00</span></strong></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Update Reservation
          </button>
          <a href="index.php?action=admin/reservations&sub_action=view&id=<?php echo $reservation['id']; ?>" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const roomSelect = document.getElementById('room_id');
    const guestsInput = document.getElementById('guests');
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const pricePerNightSpan = document.getElementById('pricePerNight');
    const numberOfNightsSpan = document.getElementById('numberOfNights');
    const roomTotalSpan = document.getElementById('roomTotal');
    const roomCapacitySpan = document.getElementById('roomCapacity');

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    checkInInput.min = today;

    // Date validation
    checkInInput.addEventListener('change', function() {
      if (checkInInput.value) {
        const nextDay = new Date(checkInInput.value);
        nextDay.setDate(nextDay.getDate() + 1);
        checkOutInput.min = nextDay.toISOString().split('T')[0];

        if (checkOutInput.value && checkOutInput.value < checkOutInput.min) {
          checkOutInput.value = checkOutInput.min;
        }
      }
      calculateTotal();
    });

    // Update room info when room is selected
    roomSelect.addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      if (selectedOption.value) {
        const capacity = selectedOption.getAttribute('data-capacity');
        const price = selectedOption.getAttribute('data-price');

        roomCapacitySpan.textContent = capacity;
        pricePerNightSpan.textContent = parseFloat(price).toFixed(2);

        // Update guests max
        guestsInput.max = capacity;

        calculateTotal();
      }
    });

    checkOutInput.addEventListener('change', calculateTotal);

    function calculateTotal() {
      if (checkInInput.value && checkOutInput.value) {
        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);
        const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));

        if (nights > 0) {
          const pricePerNight = parseFloat(pricePerNightSpan.textContent);
          const total = pricePerNight * nights;

          numberOfNightsSpan.textContent = nights;
          roomTotalSpan.textContent = total.toFixed(2);
        } else {
          numberOfNightsSpan.textContent = '0';
          roomTotalSpan.textContent = '0.00';
        }
      }
    }

    // Initialize calculation
    calculateTotal();
  });
</script>
