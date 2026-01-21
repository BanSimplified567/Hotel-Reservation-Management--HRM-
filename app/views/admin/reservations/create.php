<?php
// app/views/admin/reservations/create.php

$error = $_SESSION['error'] ?? '';
$old = $_SESSION['old'] ?? [];
unset($_SESSION['error']);
unset($_SESSION['old']);

// Check if rooms and customers data exists
if (!isset($rooms) || !isset($customers)) {
  die("ERROR: Rooms or customers data not loaded. Check your controller.");
}

// Set default date to tomorrow
$defaultCheckIn = date('Y-m-d', strtotime('+1 day'));
$defaultCheckOut = date('Y-m-d', strtotime('+2 days'));
?>

<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Create New Reservation</h1>
    <div>
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

  <!-- Create Form -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Reservation Details</h6>
    </div>
    <div class="card-body">
      <form method="POST" action="index.php?action=admin/reservations&sub_action=create">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="user_id">Customer *</label>
              <select class="form-control" id="user_id" name="user_id" required>
                <option value="">Select Customer</option>
                <?php if (!empty($customers)): ?>
                  <?php foreach ($customers as $customer): ?>
                    <option value="<?php echo $customer['id']; ?>"
                      <?php echo (isset($old['user_id']) && $old['user_id'] == $customer['id']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name'] . ' (' . $customer['email'] . ')'); ?>
                    </option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="">No customers found</option>
                <?php endif; ?>
              </select>
            </div>

            <div class="form-group">
              <label for="room_id">Room *</label>
              <select class="form-control" id="room_id" name="room_id" required>
                <option value="">Select Room</option>
                <?php if (!empty($rooms)): ?>
                  <?php foreach ($rooms as $room): ?>
                    <option value="<?php echo $room['id']; ?>"
                      data-price="<?php echo $room['base_price']; ?>"
                      data-capacity="<?php echo $room['capacity']; ?>"
                      <?php echo (isset($old['room_id']) && $old['room_id'] == $room['id']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($room['room_number'] . ' - ' . $room['room_type'] . ' (₱' . number_format($room['base_price'], 2) . '/night, Capacity: ' . $room['capacity'] . ')'); ?>
                    </option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="">No available rooms found</option>
                <?php endif; ?>
              </select>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="adults">Adults *</label>
                  <input type="number" class="form-control" id="adults" name="adults" min="1" max="10"
                    value="<?php echo htmlspecialchars($old['adults'] ?? 1); ?>" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="children">Children</label>
                  <input type="number" class="form-control" id="children" name="children" min="0" max="10"
                    value="<?php echo htmlspecialchars($old['children'] ?? 0); ?>">
                </div>
              </div>
            </div>
            <small class="form-text text-muted">Maximum capacity: <span id="roomCapacity">-</span> guests</small>
            <small class="form-text text-muted" id="totalGuests">Total guests: <span id="guestsCount">1</span></small>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="check_in">Check-in Date *</label>
              <input type="date" class="form-control" id="check_in" name="check_in"
                value="<?php echo htmlspecialchars($old['check_in'] ?? $defaultCheckIn); ?>" required>
            </div>

            <div class="form-group">
              <label for="check_out">Check-out Date *</label>
              <input type="date" class="form-control" id="check_out" name="check_out"
                value="<?php echo htmlspecialchars($old['check_out'] ?? $defaultCheckOut); ?>" required>
            </div>

            <div class="form-group">
              <label for="status">Status *</label>
              <select class="form-control" id="status" name="status" required>
                <option value="pending" <?php echo (isset($old['status']) && $old['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="confirmed" <?php echo (isset($old['status']) && $old['status'] == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                <option value="checked_in" <?php echo (isset($old['status']) && $old['status'] == 'checked_in') ? 'selected' : ''; ?>>Checked-in</option>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="special_requests">Special Requests</label>
              <textarea class="form-control" id="special_requests" name="special_requests" rows="3"><?php echo htmlspecialchars($old['special_requests'] ?? ''); ?></textarea>
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
                    <td class="text-right">₱<span id="pricePerNight">0.00</span></td>
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
            <i class="fas fa-save"></i> Create Reservation
          </button>
          <a href="index.php?action=admin/reservations" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const roomSelect = document.getElementById('room_id');
    const adultsInput = document.getElementById('adults');
    const childrenInput = document.getElementById('children');
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const pricePerNightSpan = document.getElementById('pricePerNight');
    const numberOfNightsSpan = document.getElementById('numberOfNights');
    const roomTotalSpan = document.getElementById('roomTotal');
    const roomCapacitySpan = document.getElementById('roomCapacity');
    const guestsCountSpan = document.getElementById('guestsCount');

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    checkInInput.min = today;

    // Set default check-out to check-in + 1 day
    checkInInput.addEventListener('change', function() {
      if (checkInInput.value) {
        const nextDay = new Date(checkInInput.value);
        nextDay.setDate(nextDay.getDate() + 1);
        checkOutInput.min = nextDay.toISOString().split('T')[0];

        // If check-out is before new min date, update it
        if (checkOutInput.value && checkOutInput.value < checkOutInput.min) {
          checkOutInput.value = checkOutInput.min;
        }
      }
      calculateTotal();
    });

    // Update room capacity when room is selected
    roomSelect.addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      if (selectedOption.value) {
        const capacity = selectedOption.getAttribute('data-capacity');
        const price = selectedOption.getAttribute('data-price');

        roomCapacitySpan.textContent = capacity;
        pricePerNightSpan.textContent = parseFloat(price).toFixed(2);

        // Update guests max
        adultsInput.max = capacity;
        childrenInput.max = capacity;

        // Recalculate total
        calculateTotal();
      } else {
        roomCapacitySpan.textContent = '-';
        pricePerNightSpan.textContent = '0.00';
        adultsInput.max = '';
        childrenInput.max = '';
      }
    });

    // Update total guests count
    function updateGuestsCount() {
      const adults = parseInt(adultsInput.value) || 0;
      const children = parseInt(childrenInput.value) || 0;
      const total = adults + children;
      guestsCountSpan.textContent = total;

      // Check capacity
      const capacity = parseInt(roomCapacitySpan.textContent) || 0;
      if (capacity > 0 && total > capacity) {
        roomCapacitySpan.style.color = 'red';
      } else {
        roomCapacitySpan.style.color = '';
      }
    }

    adultsInput.addEventListener('input', updateGuestsCount);
    childrenInput.addEventListener('input', updateGuestsCount);

    checkOutInput.addEventListener('change', calculateTotal);

    function calculateTotal() {
      if (checkInInput.value && checkOutInput.value && roomSelect.value) {
        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);
        const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));

        if (nights > 0) {
          const pricePerNight = parseFloat(roomSelect.options[roomSelect.selectedIndex].getAttribute('data-price'));
          const total = pricePerNight * nights;

          numberOfNightsSpan.textContent = nights;
          roomTotalSpan.textContent = total.toFixed(2);
        } else {
          numberOfNightsSpan.textContent = '0';
          roomTotalSpan.textContent = '0.00';
        }
      } else {
        numberOfNightsSpan.textContent = '0';
        roomTotalSpan.textContent = '0.00';
      }
    }

    // Initialize calculation
    calculateTotal();
    updateGuestsCount();

    // Debug: Check if rooms are loaded
    console.log('Rooms select options:', roomSelect.options.length);
  });
</script>
