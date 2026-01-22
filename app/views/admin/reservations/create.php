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

<div class="container-fluid px-3">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h1 class="h5 mb-1 text-dark fw-bold">
        <i class="fas fa-plus-circle text-primary me-2"></i>New Reservation
      </h1>
      <small class="text-muted">Create a new booking</small>
    </div>
    <div>
      <a href="index.php?action=admin/reservations" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
      </a>
    </div>
  </div>

  <!-- Alerts -->
  <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show p-2 mb-3" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i>
      <small class="flex-grow-1"><?php echo $error; ?></small>
      <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- Create Form -->
  <div class="row">
    <div class="col-lg-8">
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-white py-2 border-bottom">
          <h6 class="mb-0 text-dark">
            <i class="fas fa-calendar-plus text-primary me-1"></i>Reservation Details
          </h6>
        </div>
        <div class="card-body p-3">
          <form method="POST" action="index.php?action=admin/reservations&sub_action=create">
            <div class="row g-2 mb-3">
              <div class="col-md-6">
                <label for="user_id" class="form-label small fw-medium">
                  <i class="fas fa-user me-1"></i>Customer *
                </label>
                <select class="form-control form-control-sm" id="user_id" name="user_id" required>
                  <option value="">Select Customer</option>
                  <?php if (!empty($customers)): ?>
                    <?php foreach ($customers as $customer): ?>
                      <option value="<?php echo $customer['id']; ?>"
                        <?php echo (isset($old['user_id']) && $old['user_id'] == $customer['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?>
                      </option>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <option value="">No customers found</option>
                  <?php endif; ?>
                </select>
                <small class="text-muted">Select booking customer</small>
              </div>

              <div class="col-md-6">
                <label for="room_id" class="form-label small fw-medium">
                  <i class="fas fa-bed me-1"></i>Room *
                </label>
                <select class="form-control form-control-sm" id="room_id" name="room_id" required>
                  <option value="">Select Room</option>
                  <?php if (!empty($rooms)): ?>
                    <?php foreach ($rooms as $room): ?>
                      <option value="<?php echo $room['id']; ?>"
                        data-price="<?php echo $room['base_price']; ?>"
                        data-capacity="<?php echo $room['capacity']; ?>"
                        <?php echo (isset($old['room_id']) && $old['room_id'] == $room['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($room['room_number'] . ' - ' . $room['room_type']); ?>
                      </option>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <option value="">No available rooms</option>
                  <?php endif; ?>
                </select>
                <small class="text-muted">Available rooms</small>
              </div>

              <div class="col-md-6">
                <label for="check_in" class="form-label small fw-medium">
                  <i class="fas fa-sign-in-alt me-1"></i>Check-in *
                </label>
                <input type="date" class="form-control form-control-sm" id="check_in" name="check_in"
                  value="<?php echo htmlspecialchars($old['check_in'] ?? $defaultCheckIn); ?>" required>
              </div>

              <div class="col-md-6">
                <label for="check_out" class="form-label small fw-medium">
                  <i class="fas fa-sign-out-alt me-1"></i>Check-out *
                </label>
                <input type="date" class="form-control form-control-sm" id="check_out" name="check_out"
                  value="<?php echo htmlspecialchars($old['check_out'] ?? $defaultCheckOut); ?>" required>
              </div>

              <div class="col-md-3">
                <label for="adults" class="form-label small fw-medium">Adults *</label>
                <input type="number" class="form-control form-control-sm" id="adults" name="adults" min="1" max="10"
                  value="<?php echo htmlspecialchars($old['adults'] ?? 1); ?>" required>
              </div>

              <div class="col-md-3">
                <label for="children" class="form-label small fw-medium">Children</label>
                <input type="number" class="form-control form-control-sm" id="children" name="children" min="0" max="10"
                  value="<?php echo htmlspecialchars($old['children'] ?? 0); ?>">
              </div>

              <div class="col-md-6">
                <label for="status" class="form-label small fw-medium">
                  <i class="fas fa-tag me-1"></i>Status *
                </label>
                <select class="form-control form-control-sm" id="status" name="status" required>
                  <option value="pending" <?php echo (isset($old['status']) && $old['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                  <option value="confirmed" <?php echo (isset($old['status']) && $old['status'] == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                  <option value="checked_in" <?php echo (isset($old['status']) && $old['status'] == 'checked_in') ? 'selected' : ''; ?>>Checked-in</option>
                </select>
              </div>

              <div class="col-12">
                <label for="special_requests" class="form-label small fw-medium">
                  <i class="fas fa-comment-alt me-1"></i>Special Requests
                </label>
                <textarea class="form-control form-control-sm" id="special_requests" name="special_requests" rows="2"
                  placeholder="Any special requests..."><?php echo htmlspecialchars($old['special_requests'] ?? ''); ?></textarea>
              </div>

              <div class="col-12 mt-2">
                <div class="alert alert-info p-2 mb-0">
                  <small>
                    <i class="fas fa-info-circle me-1"></i>
                    <strong>Capacity:</strong> <span id="roomCapacity">-</span> guests •
                    <strong>Guests:</strong> <span id="guestsCount">1</span>
                  </small>
                </div>
              </div>

              <div class="col-12 mt-3 pt-2 border-top">
                <div class="d-flex justify-content-between">
                  <div>
                    <button type="submit" class="btn btn-primary btn-sm">
                      <i class="fas fa-save me-1"></i> Create Booking
                    </button>
                    <a href="index.php?action=admin/reservations" class="btn btn-outline-secondary btn-sm">Cancel</a>
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
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-white py-2 border-bottom">
          <h6 class="mb-0 text-dark">
            <i class="fas fa-receipt text-primary me-1"></i>Price Summary
          </h6>
        </div>
        <div class="card-body p-3">
          <div class="table-responsive">
            <table class="table table-sm mb-0">
              <tbody>
                <tr>
                  <td><small class="text-muted">Price/Night:</small></td>
                  <td class="text-end"><small>₱<span id="pricePerNight">0.00</span></small></td>
                </tr>
                <tr>
                  <td><small class="text-muted">Nights:</small></td>
                  <td class="text-end"><small><span id="numberOfNights">0</span> nights</small></td>
                </tr>
                <tr class="table-active">
                  <td><small class="fw-medium">Total:</small></td>
                  <td class="text-end"><small class="fw-bold">₱<span id="roomTotal">0.00</span></small></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Quick Tips Card -->
      <div class="card shadow-sm">
        <div class="card-header bg-white py-2 border-bottom">
          <h6 class="mb-0 text-dark">
            <i class="fas fa-lightbulb text-primary me-1"></i>Tips
          </h6>
        </div>
        <div class="card-body p-0">
          <div class="list-group list-group-flush small">
            <div class="list-group-item border-0 py-2 px-3">
              <i class="fas fa-check-circle text-success me-2"></i>
              <span>Ensure room matches guest count</span>
            </div>
            <div class="list-group-item border-0 py-2 px-3">
              <i class="fas fa-check-circle text-success me-2"></i>
              <span>Check dates are available</span>
            </div>
            <div class="list-group-item border-0 py-2 px-3">
              <i class="fas fa-check-circle text-success me-2"></i>
              <span>Set status based on arrival</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Compact Styles */
  .container-fluid {
    max-width: 1200px;
    margin: 0 auto;
  }

  .form-control-sm {
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
  }

  .form-label {
    font-size: 0.875rem;
  }

  .btn-close-sm {
    padding: 0.25rem;
    font-size: 0.75rem;
  }

  /* Smaller buttons */
  .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
  }

  /* Compact card */
  .card-body.p-3 {
    padding: 1rem !important;
  }

  .card-header {
    padding: 0.5rem 1rem;
  }

  /* Small table */
  .table-sm {
    font-size: 0.875rem;
  }

  .table-sm td,
  .table-sm th {
    padding: 0.25rem;
  }

  /* List group items */
  .list-group-item {
    padding: 0.5rem;
  }

  .alert {
    padding: 0.5rem;
    font-size: 0.875rem;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .col-md-6, .col-md-3 {
      margin-bottom: 0.5rem;
    }
  }
</style>

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
        pricePerNightSpan.textContent = parseFloat(price).toFixed(0);

        // Update guests max
        adultsInput.max = capacity;
        childrenInput.max = capacity;

        calculateTotal();
        updateGuestsCount();
      } else {
        roomCapacitySpan.textContent = '-';
        pricePerNightSpan.textContent = '0';
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
      const capacityAlert = roomCapacitySpan.closest('.alert');
      if (capacity > 0 && total > capacity) {
        capacityAlert.classList.remove('alert-info');
        capacityAlert.classList.add('alert-danger');
      } else {
        capacityAlert.classList.remove('alert-danger');
        capacityAlert.classList.add('alert-info');
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
          roomTotalSpan.textContent = total.toFixed(0);
        } else {
          numberOfNightsSpan.textContent = '0';
          roomTotalSpan.textContent = '0';
        }
      } else {
        numberOfNightsSpan.textContent = '0';
        roomTotalSpan.textContent = '0';
      }
    }

    // Initialize calculations
    calculateTotal();
    updateGuestsCount();

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
      const alerts = document.querySelectorAll('.alert:not(.alert-info):not(.alert-danger)');
      alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);
  });
</script>
