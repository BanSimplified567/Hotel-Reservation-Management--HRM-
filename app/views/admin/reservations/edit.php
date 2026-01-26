<?php
// app/views/admin/reservations/edit.php

$error = $_SESSION['error'] ?? '';
$old = $_SESSION['old'] ?? [];
unset($_SESSION['error']);
unset($_SESSION['old']);

// Use old data if available, otherwise use reservation data
$data = !empty($old) ? $old : $reservation;

// Calculate nights and totals
$check_in = new DateTime($data['check_in'] ?? $reservation['check_in'] ?? '');
$check_out = new DateTime($data['check_out'] ?? $reservation['check_out'] ?? '');
$nights = $check_in->diff($check_out)->days;
$room_total = ($data['price_per_night'] ?? $reservation['price_per_night'] ?? 0) * $nights;
$total_guests = ($data['adults'] ?? $reservation['adults'] ?? 1) + ($data['children'] ?? $reservation['children'] ?? 0);

// Safely get customer name
$customer_name = '';
if (isset($reservation['first_name']) && isset($reservation['last_name'])) {
    $customer_name = htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']);
} elseif (isset($data['first_name']) && isset($data['last_name'])) {
    $customer_name = htmlspecialchars($data['first_name'] . ' ' . $data['last_name']);
} else {
    $customer_name = 'Customer Not Found';
}

// Safely get reservation code
$reservation_code = $reservation['reservation_code'] ?? $data['reservation_code'] ?? 'N/A';

// Safely get room info
$room_number = $reservation['room_number'] ?? $data['room_number'] ?? 'N/A';
$room_type = $reservation['room_type'] ?? $data['room_type'] ?? 'N/A';

// Safely get dates for current details
$original_check_in = isset($reservation['check_in']) ? date('M d, Y', strtotime($reservation['check_in'])) : 'N/A';
$original_check_out = isset($reservation['check_out']) ? date('M d, Y', strtotime($reservation['check_out'])) : 'N/A';
$original_guests = $reservation['guests'] ?? ($reservation['adults'] ?? 1) + ($reservation['children'] ?? 0);

// Safely get status
$current_status = $reservation['status'] ?? $data['status'] ?? 'pending';
$updated_at = $reservation['updated_at'] ?? 'N/A';
?>

<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="h3 mb-1 text-gray-800">Edit Reservation #<?php echo $reservation['id'] ?? 'N/A'; ?></h1>
            <p class="text-muted mb-0">
                Code: <strong><?php echo $reservation_code; ?></strong>
                • Customer: <?php echo $customer_name; ?>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="index.php?action=admin/reservations/view/<?php echo $reservation['id'] ?? ''; ?>"
                class="btn btn-outline-info d-inline-flex align-items-center">
                <i class="fas fa-eye me-2"></i> View
            </a>
            <a href="index.php?action=admin/reservations" class="btn btn-outline-secondary d-inline-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i> Back
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

    <!-- Edit Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary d-flex align-items-center">
                        <i class="fas fa-edit me-2"></i>Edit Reservation Details
                    </h6>
                </div>
                <div class="card-body">
           <form method="POST" action="index.php?admin/reservations&sub_action=edit&id=<?php echo $reservation['id'] ?? ''; ?>">
                        <!-- Hidden fields -->
                        <input type="hidden" name="reservation_id" value="<?php echo $reservation['id'] ?? ''; ?>">

                        <div class="row g-3">
                            <!-- Reservation Type -->
                            <div class="col-12">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-user-tag me-1"></i>Reservation Type *
                                </label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reservation_type" id="type_customer" value="customer"
                                            <?php echo ($data['reservation_type'] ?? $reservation['reservation_type'] ?? 'customer') === 'customer' ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="type_customer">
                                            Customer (Registered User)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reservation_type" id="type_guest" value="guest"
                                            <?php echo ($data['reservation_type'] ?? $reservation['reservation_type'] ?? 'customer') === 'guest' ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="type_guest">
                                            Guest (Walk-in)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Selection -->
                            <div id="customer_section" class="col-12">
                                <label for="user_id" class="form-label fw-bold">
                                    <i class="fas fa-user me-1"></i>Customer *
                                </label>
                                <select class="form-select" id="user_id" name="user_id">
                                    <option value="">Select Customer</option>
                                    <?php if (isset($customers) && is_array($customers)): ?>
                                        <?php foreach ($customers as $customer): ?>
                                            <option value="<?php echo $customer['id']; ?>"
                                                <?php echo (($data['user_id'] ?? $reservation['user_id'] ?? '') == $customer['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="">No customers found</option>
                                    <?php endif; ?>
                                </select>
                                <div class="form-text">Select registered customer</div>
                            </div>

                            <!-- Guest Details -->
                            <div id="guest_section" class="row g-3" style="display: none;">
                                <div class="col-md-6">
                                    <label for="guest_first_name" class="form-label fw-bold">
                                        <i class="fas fa-user me-1"></i>First Name *
                                    </label>
                                    <input type="text" class="form-control" id="guest_first_name" name="guest_first_name"
                                        value="<?php echo htmlspecialchars($data['guest_first_name'] ?? $reservation['guest_first_name'] ?? ''); ?>" maxlength="50">
                                </div>
                                <div class="col-md-6">
                                    <label for="guest_last_name" class="form-label fw-bold">
                                        <i class="fas fa-user me-1"></i>Last Name *
                                    </label>
                                    <input type="text" class="form-control" id="guest_last_name" name="guest_last_name"
                                        value="<?php echo htmlspecialchars($data['guest_last_name'] ?? $reservation['guest_last_name'] ?? ''); ?>" maxlength="50">
                                </div>
                                <div class="col-md-6">
                                    <label for="guest_email" class="form-label fw-bold">
                                        <i class="fas fa-envelope me-1"></i>Email
                                    </label>
                                    <input type="email" class="form-control" id="guest_email" name="guest_email"
                                        value="<?php echo htmlspecialchars($data['guest_email'] ?? $reservation['guest_email'] ?? ''); ?>" maxlength="100">
                                </div>
                                <div class="col-md-6">
                                    <label for="guest_phone" class="form-label fw-bold">
                                        <i class="fas fa-phone me-1"></i>Phone
                                    </label>
                                    <input type="tel" class="form-control" id="guest_phone" name="guest_phone"
                                        value="<?php echo htmlspecialchars($data['guest_phone'] ?? $reservation['guest_phone'] ?? ''); ?>" maxlength="20">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="room_id" class="form-label fw-bold">
                                    <i class="fas fa-bed me-1"></i>Room *
                                </label>
                                <select class="form-select" id="room_id" name="room_id" required>
                                    <option value="">Select Room</option>
                                    <?php if (isset($rooms) && is_array($rooms)): ?>
                                        <?php foreach ($rooms as $room): ?>
                                            <option value="<?php echo $room['id']; ?>"
                                                data-price="<?php echo $room['base_price']; ?>"
                                                data-capacity="<?php echo $room['capacity']; ?>"
                                                <?php echo (($data['room_id'] ?? $reservation['room_id'] ?? '') == $room['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($room['room_number'] . ' - ' . $room['room_type'] . ' (₱' . number_format($room['base_price'], 2) . '/night)'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="">No rooms available</option>
                                    <?php endif; ?>
                                </select>
                                <div class="form-text">Change room if needed</div>
                            </div>

                            <!-- Reservation Type Section -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-users me-1"></i>Reservation Type *
                                </label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="reservation_type" id="customer_type" value="customer"
                                                <?php echo ($reservation['user_id'] ? 'checked' : ''); ?>>
                                            <label class="form-check-label fw-bold" for="customer_type">
                                                <i class="fas fa-user-check me-1"></i>Registered Customer
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="reservation_type" id="guest_type" value="guest"
                                                <?php echo (!$reservation['user_id'] ? 'checked' : ''); ?>>
                                            <label class="form-check-label fw-bold" for="guest_type">
                                                <i class="fas fa-user-plus me-1"></i>Walk-in Guest
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Selection (shown when customer type is selected) -->
                            <div class="col-12 mb-3" id="customer_section" style="<?php echo ($reservation['user_id'] ? '' : 'display: none;'); ?>">
                                <label for="user_id" class="form-label fw-bold">
                                    <i class="fas fa-user me-1"></i>Select Customer *
                                </label>
                                <select class="form-select" id="user_id" name="user_id">
                                    <option value="">Choose a customer...</option>
                                    <?php foreach ($customers as $customer): ?>
                                        <option value="<?php echo $customer['id']; ?>"
                                            <?php echo ($reservation['user_id'] == $customer['id'] ? 'selected' : ''); ?>>
                                            <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name'] . ' (' . $customer['email'] . ')'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Select the registered customer for this reservation</div>
                            </div>

                            <!-- Guest Details (shown when guest type is selected) -->
                            <div id="guest_section" style="<?php echo (!$reservation['user_id'] ? '' : 'display: none;'); ?>">
                                <div class="col-md-6 mb-3">
                                    <label for="guest_first_name" class="form-label fw-bold">
                                        <i class="fas fa-user me-1"></i>Guest First Name *
                                    </label>
                                    <input type="text" class="form-control" id="guest_first_name" name="guest_first_name"
                                        value="<?php echo htmlspecialchars($reservation['guest_first_name'] ?? ''); ?>" maxlength="50">
                                    <div class="form-text">Walk-in guest's first name</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="guest_last_name" class="form-label fw-bold">
                                        <i class="fas fa-user me-1"></i>Guest Last Name *
                                    </label>
                                    <input type="text" class="form-control" id="guest_last_name" name="guest_last_name"
                                        value="<?php echo htmlspecialchars($reservation['guest_last_name'] ?? ''); ?>" maxlength="50">
                                    <div class="form-text">Walk-in guest's last name</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="guest_email" class="form-label fw-bold">
                                        <i class="fas fa-envelope me-1"></i>Guest Email *
                                    </label>
                                    <input type="email" class="form-control" id="guest_email" name="guest_email"
                                        value="<?php echo htmlspecialchars($reservation['guest_email'] ?? ''); ?>" maxlength="100">
                                    <div class="form-text">Walk-in guest's email address</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="guest_phone" class="form-label fw-bold">
                                        <i class="fas fa-phone me-1"></i>Guest Phone *
                                    </label>
                                    <input type="tel" class="form-control" id="guest_phone" name="guest_phone"
                                        value="<?php echo htmlspecialchars($reservation['guest_phone'] ?? ''); ?>" maxlength="20">
                                    <div class="form-text">Walk-in guest's phone number</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="check_in" class="form-label fw-bold">
                                    <i class="fas fa-sign-in-alt me-1"></i>Check-in Date *
                                </label>
                                <input type="date" class="form-control" id="check_in" name="check_in"
                                    value="<?php echo htmlspecialchars($data['check_in'] ?? $reservation['check_in'] ?? ''); ?>" required>
                                <div class="form-text">Updated check-in date</div>
                            </div>

                            <div class="col-md-6">
                                <label for="check_out" class="form-label fw-bold">
                                    <i class="fas fa-sign-out-alt me-1"></i>Check-out Date *
                                </label>
                                <input type="date" class="form-control" id="check_out" name="check_out"
                                    value="<?php echo htmlspecialchars($data['check_out'] ?? $reservation['check_out'] ?? ''); ?>" required>
                                <div class="form-text">Updated check-out date</div>
                            </div>

                            <div class="col-md-3">
                                <label for="adults" class="form-label fw-bold">Adults *</label>
                                <input type="number" class="form-control" id="adults" name="adults" min="1" max="10"
                                    value="<?php echo htmlspecialchars($data['adults'] ?? $reservation['adults'] ?? 1); ?>" required>
                            </div>

                            <div class="col-md-3">
                                <label for="children" class="form-label fw-bold">Children</label>
                                <input type="number" class="form-control" id="children" name="children" min="0" max="10"
                                    value="<?php echo htmlspecialchars($data['children'] ?? $reservation['children'] ?? 0); ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label fw-bold">
                                    <i class="fas fa-tag me-1"></i>Status *
                                </label>
                          <!-- Replace the status select options -->
<select class="form-select" id="status" name="status" required>
    <option value="pending" <?php echo (($data['status'] ?? $reservation['status'] ?? '') == 'pending') ? 'selected' : ''; ?>>Pending</option>
    <option value="confirmed" <?php echo (($data['status'] ?? $reservation['status'] ?? '') == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
    <option value="checked_in" <?php echo (($data['status'] ?? $reservation['status'] ?? '') == 'checked_in') ? 'selected' : ''; ?>>Checked-in</option>
    <option value="checked_out" <?php echo (($data['status'] ?? $reservation['status'] ?? '') == 'checked_out') ? 'selected' : ''; ?>>Checked-out</option>
    <option value="cancelled" <?php echo (($data['status'] ?? $reservation['status'] ?? '') == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
    <option value="no_show" <?php echo (($data['status'] ?? $reservation['status'] ?? '') == 'no_show') ? 'selected' : ''; ?>>No Show</option>
</select>
                                <div class="form-text">Update reservation status</div>
                            </div>

                            <div class="col-md-6">
                                <label for="special_requests" class="form-label fw-bold">
                                    <i class="fas fa-comment-alt me-1"></i>Special Requests
                                </label>
                                <textarea class="form-control" id="special_requests" name="special_requests" rows="3"><?php echo htmlspecialchars($data['special_requests'] ?? $reservation['special_requests'] ?? ''); ?></textarea>
                                <div class="form-text">Customer special requests</div>
                            </div>

                            <div class="col-md-6">
                                <label for="admin_notes" class="form-label fw-bold">
                                    <i class="fas fa-sticky-note me-1"></i>Admin Notes
                                </label>
                                <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3"
                                    placeholder="Add internal notes..."><?php echo htmlspecialchars($data['admin_notes'] ?? $reservation['admin_notes'] ?? ''); ?></textarea>
                                <div class="form-text">Internal notes (visible to staff only)</div>
                            </div>

                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Room Capacity:</strong> <span id="roomCapacity"><?php echo $reservation['capacity'] ?? 4; ?></span> guests
                                    <span class="mx-2">•</span>
                                    <strong>Total Guests:</strong> <span id="guestsCount"><?php echo $total_guests; ?></span>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button type="submit" name="edit_reservation" class="btn btn-primary d-inline-flex align-items-center">
                                            <i class="fas fa-save me-2"></i> Save Changes
                                        </button>
                                        <a href="index.php?action=admin/reservations/view/<?php echo $reservation['id'] ?? ''; ?>"
                                            class="btn btn-outline-secondary">Cancel</a>
                                    </div>
                                    <div class="text-muted small">
                                        <?php if ($updated_at !== 'N/A'): ?>
                                            Last updated: <?php echo date('M d, Y H:i', strtotime($updated_at)); ?>
                                        <?php endif; ?>
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
                        <i class="fas fa-calculator me-2"></i>Price Preview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td><strong>Room Price per Night:</strong></td>
                                    <td class="text-end">₱<span id="pricePerNight"><?php echo number_format($reservation['price_per_night'] ?? 0, 2); ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Number of Nights:</strong></td>
                                    <td class="text-end"><span id="numberOfNights"><?php echo $nights; ?></span> nights</td>
                                </tr>
                                <tr class="table-active">
                                    <td><strong>Total Room Charges:</strong></td>
                                    <td class="text-end"><strong>₱<span id="roomTotal"><?php echo number_format($room_total, 2); ?></span></strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <small class="text-muted">Prices update automatically when dates change</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Current Details Card -->
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>Current Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td><strong>Original Room:</strong></td>
                                    <td class="text-end"><?php echo htmlspecialchars($room_number); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Room Type:</strong></td>
                                    <td class="text-end"><?php echo htmlspecialchars($room_type); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Original Dates:</strong></td>
                                    <td class="text-end"><?php echo $original_check_in; ?> - <?php echo $original_check_out; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Original Guests:</strong></td>
                                    <td class="text-end"><?php echo $original_guests; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Current Status:</strong></td>
                                    <td class="text-end">
                                        <span class="badge bg-<?php
                                            switch ($current_status) {
                                                case 'pending':
                                                    echo 'warning';
                                                    break;
                                                case 'confirmed':
                                                    echo 'info';
                                                    break;
                                                case 'checked_in':
                                                    echo 'success';
                                                    break;
                                                case 'completed':
                                                    echo 'primary';
                                                    break;
                                                case 'cancelled':
                                                    echo 'danger';
                                                    break;
                                                case 'no_show':
                                                    echo 'secondary';
                                                    break;
                                                default:
                                                    echo 'secondary';
                                            }
                                            ?>">
                                            <?php echo ucfirst($current_status); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Reservation ID:</strong></td>
                                    <td class="text-end">#<?php echo $reservation['id'] ?? 'N/A'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td class="text-end"><?php echo isset($reservation['created_at']) ? date('M d, Y', strtotime($reservation['created_at'])) : 'N/A'; ?></td>
                                </tr>
                            </tbody>
                        </table>
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
        let roomCapacitySpan = document.getElementById('roomCapacity');
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
                roomCapacitySpan.parentElement.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i><strong>Room Capacity:</strong> <span id="roomCapacity">' + capacity + '</span> guests <span class="badge bg-danger ms-2">Exceeded</span>';
            } else {
                roomCapacitySpan.parentElement.classList.remove('text-danger');
                roomCapacitySpan.parentElement.innerHTML = '<i class="fas fa-info-circle me-2"></i><strong>Room Capacity:</strong> <span id="roomCapacity">' + capacity + '</span> guests';
                // Re-select the element after updating HTML
                roomCapacitySpan = document.getElementById('roomCapacity');
            }
        }

        adultsInput.addEventListener('input', updateGuestsCount);
        childrenInput.addEventListener('input', updateGuestsCount);
        checkOutInput.addEventListener('change', calculateTotal);

        // Calculate total price
        function calculateTotal() {
            if (checkInInput.value && checkOutInput.value) {
                const checkIn = new Date(checkInInput.value);
                const checkOut = new Date(checkOutInput.value);

                // Ensure check-out is after check-in
                if (checkOut <= checkIn) {
                    numberOfNightsSpan.textContent = '0';
                    roomTotalSpan.textContent = '0.00';
                    return;
                }

                const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));

                if (nights > 0) {
                    const pricePerNight = parseFloat(pricePerNightSpan.textContent) || 0;
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

        // Toggle between customer and guest sections
        const customerTypeRadio = document.getElementById('customer_type');
        const guestTypeRadio = document.getElementById('guest_type');
        const customerSection = document.getElementById('customer_section');
        const guestSection = document.getElementById('guest_section');

        function toggleReservationType() {
            if (customerTypeRadio.checked) {
                customerSection.style.display = '';
                guestSection.style.display = 'none';
                // Clear guest fields when switching to customer
                document.getElementById('guest_first_name').value = '';
                document.getElementById('guest_last_name').value = '';
                document.getElementById('guest_email').value = '';
                document.getElementById('guest_phone').value = '';
            } else if (guestTypeRadio.checked) {
                customerSection.style.display = 'none';
                guestSection.style.display = '';
                // Clear customer selection when switching to guest
                document.getElementById('user_id').value = '';
            }
        }

        // Add event listeners for radio buttons
        customerTypeRadio.addEventListener('change', toggleReservationType);
        guestTypeRadio.addEventListener('change', toggleReservationType);

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
