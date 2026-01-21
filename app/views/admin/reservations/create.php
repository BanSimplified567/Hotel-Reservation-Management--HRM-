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

<div class="container-fluid px-4">
  <!-- Page Header -->
  <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4">
    <div class="mb-3 mb-md-0">
      <h1 class="h3 mb-1 text-gray-800">Create New Reservation</h1>
      <p class="text-muted mb-0">Create a new booking for a customer</p>
    </div>
    <div class="d-flex gap-2">
      <a href="index.php?action=admin/reservations" class="btn btn-outline-secondary d-inline-flex align-items-center">
        <i class="fas fa-arrow-left me-2"></i> Back to Reservations
      </a>
    </div>
  </div>

  <!-- Alerts -->
  <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i>
      <div class="flex-grow-1">
        <?php echo $error; ?>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- Create Form -->
  <div class="row">
    <div class="col-lg-8">
      <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
          <h6 class="m-0 fw-bold text-primary d-flex align-items-center">
            <i class="fas fa-calendar-plus me-2"></i>Reservation Details
          </h6>
        </div>
        <div class="card-body">
          <form method="POST" action="index.php?action=admin/reservations&sub_action=create">
            <div class="row g-3">
              <div class="col-md-6">
                <label for="user_id" class="form-label fw-bold">
                  <i class="fas fa-user me-1"></i>Customer *
                </label>
                <select class="form-select" id="user_id" name="user_id" required>
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
                <div class="form-text">Select the customer making the reservation</div>
              </div>

              <div class="col-md-6">
                <label for="room_id" class="form-label fw-bold">
                  <i class="fas fa-bed me-1"></i>Room *
                </label>
                <select class="form-select" id="room_id" name="room_id" required>
                  <option value="">Select Room</option>
                  <?php if (!empty($rooms)): ?>
                    <?php foreach ($rooms as $room): ?>
                      <option value="<?php echo $room['id']; ?>"
                        data-price="<?php echo $room['base_price']; ?>"
                        data-capacity="<?php echo $room['capacity']; ?>"
                        <?php echo (isset($old['room_id']) && $old['room_id'] == $room['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($room['room_number'] . ' - ' . $room['room_type'] . ' (₱' . number_format($room['base_price'], 2) . '/night)'); ?>
                      </option>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <option value="">No available rooms found</option>
                  <?php endif; ?>
                </select>
                <div class="form-text">Available rooms for booking</div>
              </div>

              <div class="col-md-6">
                <label for="check_in" class="form-label fw-bold">
                  <i class="fas fa-sign-in-alt me-1"></i>Check-in Date *
                </label>
                <input type="date" class="form-control" id="check_in" name="check_in"
                  value="<?php echo htmlspecialchars($old['check_in'] ?? $defaultCheckIn); ?>" required>
                <div class="form-text">Date when guest will check in</div>
              </div>

              <div class="col-md-6">
                <label for="check_out" class="form-label fw-bold">
                  <i class="fas fa-sign-out-alt me-1"></i>Check-out Date *
                </label>
                <input type="date" class="form-control" id="check_out" name="check_out"
                  value="<?php echo htmlspecialchars($old['check_out'] ?? $defaultCheckOut); ?>" required>
                <div class="form-text">Date when guest will check out</div>
              </div>

              <div class="col-md-3">
                <label for="adults" class="form-label fw-bold">Adults *</label>
                <input type="number" class="form-control" id="adults" name="adults" min="1" max="10"
                  value="<?php echo htmlspecialchars($old['adults'] ?? 1); ?>" required>
              </div>

              <div class="col-md-3">
                <label for="children" class="form-label fw-bold">Children</label>
                <input type="number" class="form-control" id="children" name="children" min="0" max="10"
                  value="<?php echo htmlspecialchars($old['children'] ?? 0); ?>">
              </div>

              <div class="col-md-6">
                <label for="status" class="form-label fw-bold">
                  <i class="fas fa-tag me-1"></i>Status *
                </label>
                <select class="form-select" id="status" name="status" required>
                  <option value="pending" <?php echo (isset($old['status']) && $old['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                  <option value="confirmed" <?php echo (isset($old['status']) && $old['status'] == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                  <option value="checked_in" <?php echo (isset($old['status']) && $old['status'] == 'checked_in') ? 'selected' : ''; ?>>Checked-in</option>
                </select>
                <div class="form-text">Initial reservation status</div>
              </div>

              <div class="col-md-12">
                <label for="special_requests" class="form-label fw-bold">
                  <i class="fas fa-comment-alt me-1"></i>Special Requests
                </label>
                <textarea class="form-control" id="special_requests" name="special_requests" rows="3"
                  placeholder="Any special requests from the customer..."><?php echo htmlspecialchars($old['special_requests'] ?? ''); ?></textarea>
                <div class="form-text">Optional special requests or notes</div>
              </div>

              <div class="col-12">
                <div class="alert alert-info">
                  <i class="fas fa-info-circle me-2"></i>
                  <strong>Room Capacity:</strong> <span id="roomCapacity">-</span> guests
                  <span class="mx-2">•</span>
                  <strong>Total Guests:</strong> <span id="guestsCount">1</span>
                </div>
              </div>

              <div class="col-12 mt-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                      <i class="fas fa-save me-2"></i> Create Reservation
                    </button>
                    <a href="index.php?action=admin/reservations" class="btn btn-outline-secondary">Cancel</a>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <!-- Price Summary Card -->
      <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
          <h6 class="m-0 fw-bold text-primary d-flex align-items-center">
            <i class="fas fa-receipt me-2"></i>Price Summary
          </h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-sm">
              <tbody>
                <tr>
                  <td><strong>Room Price per Night:</strong></td>
                  <td class="text-end">₱<span id="pricePerNight">0.00</span></td>
                </tr>
                <tr>
                  <td><strong>Number of Nights:</strong></td>
                  <td class="text-end"><span id="numberOfNights">0</span> nights</td>
                </tr>
                <tr class="table-active">
                  <td><strong>Total Room Charges:</strong></td>
                  <td class="text-end"><strong>₱<span id="roomTotal">0.00</span></strong></td>
                </tr>
                <tr>
                  <td colspan="2">
                    <small class="text-muted">* Additional services can be added after reservation creation</small>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Quick Tips Card -->
      <div class="card shadow">
        <div class="card-header bg-white py-3">
          <h6 class="m-0 fw-bold text-primary d-flex align-items-center">
            <i class="fas fa-lightbulb me-2"></i>Quick Tips
          </h6>
        </div>
        <div class="card-body">
          <div class="list-group list-group-flush">
            <div class="list-group-item border-0 px-0">
              <i class="fas fa-check-circle text-success me-2"></i>
              <small>Set status to "Checked-in" if guest is arriving today</small>
            </div>
            <div class="list-group-item border-0 px-0">
              <i class="fas fa-check-circle text-success me-2"></i>
              <small>Ensure room capacity matches number of guests</small>
            </div>
            <div class="list-group-item border-0 px-0">
              <i class="fas fa-check-circle text-success me-2"></i>
              <small>Minimum stay is 1 night (check-out must be after check-in)</small>
            </div>
            <div class="list-group-item border-0 px-0">
              <i class="fas fa-check-circle text-success me-2"></i>
              <small>Confirm room availability for selected dates</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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

    // Update check-out min date when check-in changes
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
        adultsInput.max = capacity;
        childrenInput.max = capacity;

        calculateTotal();
        updateGuestsCount();
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
        roomCapacitySpan.parentElement.classList.add('text-danger');
      } else {
        roomCapacitySpan.parentElement.classList.remove('text-danger');
      }
    }

    adultsInput.addEventListener('input', updateGuestsCount);
    childrenInput.addEventListener('input', updateGuestsCount);
    checkOutInput.addEventListener('change', calculateTotal);

    // Calculate total price
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

    // Initialize calculations
    calculateTotal();
    updateGuestsCount();

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);
  });
</script>

<style>
  .form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
  }

  .form-select,
  .form-control {
    border-radius: 0.375rem;
  }

  .card {
    border: none;
    border-radius: 0.5rem;
  }

  .card-header {
    border-bottom: 1px solid rgba(0, 0, 0, .125);
  }

  .table-active {
    background-color: rgba(0, 0, 0, .05);
  }

  .list-group-item {
    padding: 0.5rem 0;
  }

  .table-sm td,
  .table-sm th {
    padding: 0.5rem;
  }
</style>
