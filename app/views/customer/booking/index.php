<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book a Room - Hotel Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <style>
    .search-form {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 10px;
      padding: 2rem;
      color: white;
      margin-bottom: 2rem;
    }

    .room-card {
      transition: all 0.3s;
      border: 1px solid #dee2e6;
    }

    .room-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .room-image {
      height: 200px;
      object-fit: cover;
      border-radius: 5px 5px 0 0;
    }

    .amenities-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .amenities-list li {
      display: inline-block;
      margin-right: 10px;
      margin-bottom: 5px;
    }

    .price-tag {
      position: absolute;
      top: 15px;
      right: 15px;
      background: rgba(0, 0, 0, 0.7);
      color: white;
      padding: 5px 10px;
      border-radius: 5px;
      font-weight: bold;
    }

    .rating-stars {
      color: #ffc107;
    }
  </style>
</head>

<body>
  <?php include '../layout/customer-header.php'; ?>

  <div class="container-fluid">
    <div class="row">
      <?php include '../layout/customer-sidebar.php'; ?>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        <div class="mb-4">
          <h1 class="h2 mb-2">Book a Room</h1>
          <p class="text-muted">Find and book the perfect room for your stay</p>
        </div>

        <!-- Search Form -->
        <div class="search-form">
          <form method="GET" action="index.php">
            <input type="hidden" name="action" value="customer/booking">

            <div class="row g-3">
              <div class="col-md-3">
                <label class="form-label text-white">Check-in Date</label>
                <input type="text"
                  name="check_in"
                  class="form-control datepicker"
                  placeholder="Select date"
                  value="<?php echo htmlspecialchars($checkIn); ?>"
                  required>
              </div>

              <div class="col-md-3">
                <label class="form-label text-white">Check-out Date</label>
                <input type="text"
                  name="check_out"
                  class="form-control datepicker"
                  placeholder="Select date"
                  value="<?php echo htmlspecialchars($checkOut); ?>"
                  required>
              </div>

              <div class="col-md-2">
                <label class="form-label text-white">Guests</label>
                <select name="guests" class="form-select" required>
                  <?php for ($i = 1; $i <= 6; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo $guests == $i ? 'selected' : ''; ?>>
                      <?php echo $i; ?> <?php echo $i == 1 ? 'Guest' : 'Guests'; ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>

              <div class="col-md-2">
                <label class="form-label text-white">Room Type</label>
                <select name="room_type" class="form-select">
                  <option value="">All Types</option>
                  <?php foreach ($roomTypes as $type): ?>
                    <option value="<?php echo htmlspecialchars($type); ?>"
                      <?php echo $roomType === $type ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($type); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-light w-100">
                  <i class="bi bi-search me-1"></i> Search Rooms
                </button>
              </div>
            </div>
          </form>
        </div>

        <!-- Results -->
        <?php if ($checkIn && $checkOut): ?>
          <?php if (empty($availableRooms)): ?>
            <div class="alert alert-warning">
              <i class="bi bi-exclamation-triangle me-2"></i>
              No rooms available for the selected dates and criteria. Please try different dates or room type.
            </div>
          <?php else: ?>
            <div class="row mb-4">
              <div class="col">
                <h4 class="mb-0"><?php echo count($availableRooms); ?> Rooms Available</h4>
                <p class="text-muted">
                  <?php echo date('M j', strtotime($checkIn)) . ' - ' . date('M j, Y', strtotime($checkOut)); ?>
                  • <?php echo $guests; ?> guest<?php echo $guests > 1 ? 's' : ''; ?>
                </p>
              </div>
              <div class="col-auto">
                <div class="btn-group">
                  <button class="btn btn-outline-secondary" onclick="sortBy('price')">
                    <i class="bi bi-sort-numeric-down"></i> Price
                  </button>
                  <button class="btn btn-outline-secondary" onclick="sortBy('rating')">
                    <i class="bi bi-star"></i> Rating
                  </button>
                </div>
              </div>
            </div>

            <div class="row" id="roomsContainer">
              <?php foreach ($availableRooms as $room): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                  <div class="card room-card h-100">
                    <div class="position-relative">
                      <!-- Room Image (placeholder) -->
                      <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 200px; border-radius: 5px 5px 0 0;"></div>
                      <div class="price-tag">
                        $<?php echo number_format($room['price_per_night'], 2); ?>/night
                      </div>
                    </div>

                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                          <h5 class="card-title mb-1"><?php echo htmlspecialchars($room['room_type']); ?></h5>
                          <p class="text-muted small mb-0">
                            Room #<?php echo $room['room_number']; ?>
                          </p>
                        </div>
                        <?php if ($room['average_rating'] > 0): ?>
                          <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <small><?php echo number_format($room['average_rating'], 1); ?></small>
                          </div>
                        <?php endif; ?>
                      </div>

                      <p class="card-text small text-muted mb-3">
                        <?php echo htmlspecialchars($room['description']); ?>
                      </p>

                      <div class="mb-3">
                        <ul class="amenities-list">
                          <li><i class="bi bi-wifi"></i> WiFi</li>
                          <li><i class="bi bi-tv"></i> TV</li>
                          <li><i class="bi bi-thermometer-snow"></i> AC</li>
                          <li><i class="bi bi-door-closed"></i> <?php echo $room['max_capacity']; ?> guests max</li>
                        </ul>
                      </div>

                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h6 class="mb-0 text-primary">
                            $<?php
                              $checkInDate = new DateTime($checkIn);
                              $checkOutDate = new DateTime($checkOut);
                              $nights = $checkOutDate->diff($checkInDate)->days;
                              echo number_format($room['price_per_night'] * $nights, 2);
                              ?>
                          </h6>
                          <small class="text-muted">Total for <?php echo $nights; ?> nights</small>
                        </div>
                        <button class="btn btn-primary"
                          onclick="bookRoom(<?php echo $room['id']; ?>)">
                          Book Now
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        <?php else: ?>
          <!-- Welcome/Instructional Content -->
          <div class="card">
            <div class="card-body text-center py-5">
              <i class="bi bi-calendar-date text-primary" style="font-size: 4rem;"></i>
              <h3 class="mt-3 mb-2">Start Your Booking</h3>
              <p class="text-muted mb-4">
                Select your check-in and check-out dates to see available rooms
              </p>
              <div class="row justify-content-center">
                <div class="col-md-8">
                  <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Need help choosing?</strong><br>
                    • Standard rooms: Perfect for solo travelers or couples<br>
                    • Deluxe rooms: Extra space with premium amenities<br>
                    • Suite: Spacious living area with separate bedroom<br>
                    • All rooms include free WiFi, breakfast, and parking
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </main>
    </div>
  </div>

  <!-- Booking Modal -->
  <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="bookingForm" method="POST" action="index.php?action=customer/booking/create">
          <div class="modal-header">
            <h5 class="modal-title">Complete Your Booking</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <!-- Room Summary -->
            <div class="card mb-4">
              <div class="card-body">
                <h6 class="card-title">Booking Summary</h6>
                <div class="row">
                  <div class="col-md-6">
                    <p class="mb-1"><strong id="modalRoomType"></strong></p>
                    <p class="text-muted small mb-2" id="modalRoomNumber"></p>
                    <p class="mb-0">
                      <i class="bi bi-calendar-check"></i>
                      <span id="modalCheckIn"></span> to <span id="modalCheckOut"></span>
                    </p>
                    <p class="mb-0">
                      <i class="bi bi-people"></i>
                      <span id="modalGuests"></span> guests •
                      <span id="modalNights"></span> nights
                    </p>
                  </div>
                  <div class="col-md-6 text-end">
                    <h4 class="text-primary mb-1" id="modalTotalPrice"></h4>
                    <p class="text-muted small mb-0" id="modalNightlyRate"></p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Hidden Inputs -->
            <input type="hidden" name="room_id" id="roomId">
            <input type="hidden" name="check_in" value="<?php echo htmlspecialchars($checkIn); ?>">
            <input type="hidden" name="check_out" value="<?php echo htmlspecialchars($checkOut); ?>">
            <input type="hidden" name="guests" value="<?php echo htmlspecialchars($guests); ?>">

            <!-- Special Requests -->
            <div class="mb-4">
              <label class="form-label">Special Requests (Optional)</label>
              <textarea class="form-control" name="special_requests" rows="3"
                placeholder="E.g., early check-in, dietary restrictions, room preferences..."></textarea>
            </div>

            <!-- Additional Services -->
            <div class="mb-4">
              <label class="form-label">Add Services (Optional)</label>
              <div class="list-group">
                <div class="list-group-item">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="service_id" value="1" id="service1">
                    <label class="form-check-label d-flex justify-content-between w-100" for="service1">
                      <div>
                        <strong>Airport Transfer</strong>
                        <p class="mb-0 small text-muted">Private transfer from airport to hotel</p>
                      </div>
                      <span class="text-primary">$50.00</span>
                    </label>
                  </div>
                </div>
                <div class="list-group-item">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="service_id" value="2" id="service2">
                    <label class="form-check-label d-flex justify-content-between w-100" for="service2">
                      <div>
                        <strong>Spa Package</strong>
                        <p class="mb-0 small text-muted">60-minute massage and spa access</p>
                      </div>
                      <span class="text-primary">$120.00</span>
                    </label>
                  </div>
                </div>
                <div class="list-group-item">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="service_id" value="3" id="service3">
                    <label class="form-check-label d-flex justify-content-between w-100" for="service3">
                      <div>
                        <strong>Breakfast Buffet</strong>
                        <p class="mb-0 small text-muted">Daily breakfast for all guests</p>
                      </div>
                      <span class="text-primary">$25.00/person/day</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Terms & Conditions -->
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" id="termsCheck" required>
              <label class="form-check-label" for="termsCheck">
                I agree to the
                <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a>
                and understand that a 20% deposit is required to confirm this booking.
              </label>
            </div>

            <!-- Cancellation Policy -->
            <div class="alert alert-warning small">
              <i class="bi bi-exclamation-triangle"></i>
              <strong>Cancellation Policy:</strong> Free cancellation up to 24 hours before check-in.
              Cancellations within 24 hours incur a 50% fee. No-shows are charged the full amount.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Proceed to Payment</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Terms Modal -->
  <div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Terms & Conditions</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <h6>Booking Policy</h6>
          <p>All bookings require a 20% deposit to confirm reservation. Balance is due upon check-in.</p>

          <h6>Cancellation Policy</h6>
          <p>Cancellations made more than 24 hours before check-in receive full refund.
            Cancellations within 24 hours incur a 50% cancellation fee. No-shows are charged the full amount.</p>

          <h6>Check-in/Check-out</h6>
          <p>Check-in time is 3:00 PM. Check-out time is 11:00 AM. Early check-in and late check-out
            are subject to availability and may incur additional charges.</p>

          <h6>Guest Responsibility</h6>
          <p>Guests are responsible for any damage to hotel property. Valid ID and credit card
            are required at check-in for incidentals.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <?php include '../layout/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
    // Date picker
    flatpickr('.datepicker', {
      minDate: 'today',
      dateFormat: 'Y-m-d',
      disableMobile: true
    });

    // Book room function
    function bookRoom(roomId) {
      // In a real app, you would fetch room details via AJAX
      // For now, we'll use the data from the card
      const roomCard = event.target.closest('.room-card');
      const roomType = roomCard.querySelector('.card-title').textContent;
      const roomNumber = roomCard.querySelector('.text-muted.small').textContent.replace('Room #', '');
      const pricePerNight = roomCard.querySelector('.price-tag').textContent.match(/\$([\d,.]+)/)[1];

      // Calculate total
      const checkIn = document.querySelector('input[name="check_in"]').value;
      const checkOut = document.querySelector('input[name="check_out"]').value;
      const guests = document.querySelector('select[name="guests"]').value;

      const nights = Math.ceil((new Date(checkOut) - new Date(checkIn)) / (1000 * 60 * 60 * 24));
      const totalPrice = (parseFloat(pricePerNight) * nights).toFixed(2);

      // Populate modal
      document.getElementById('roomId').value = roomId;
      document.getElementById('modalRoomType').textContent = roomType;
      document.getElementById('modalRoomNumber').textContent = 'Room #' + roomNumber;
      document.getElementById('modalCheckIn').textContent = formatDate(checkIn);
      document.getElementById('modalCheckOut').textContent = formatDate(checkOut);
      document.getElementById('modalGuests').textContent = guests;
      document.getElementById('modalNights').textContent = nights;
      document.getElementById('modalTotalPrice').textContent = '$' + totalPrice;
      document.getElementById('modalNightlyRate').textContent = '$' + pricePerNight + ' per night';

      // Show modal
      const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
      modal.show();
    }

    function formatDate(dateStr) {
      const date = new Date(dateStr);
      return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric'
      });
    }

    // Sort rooms
    function sortBy(criteria) {
      const container = document.getElementById('roomsContainer');
      const rooms = Array.from(container.children);

      rooms.sort((a, b) => {
        if (criteria === 'price') {
          const priceA = parseFloat(a.querySelector('.price-tag').textContent.match(/\$([\d,.]+)/)[1]);
          const priceB = parseFloat(b.querySelector('.price-tag').textContent.match(/\$([\d,.]+)/)[1]);
          return priceA - priceB;
        } else if (criteria === 'rating') {
          const ratingA = parseFloat(a.querySelector('.text-warning small')?.textContent || 0);
          const ratingB = parseFloat(b.querySelector('.text-warning small')?.textContent || 0);
          return ratingB - ratingA;
        }
        return 0;
      });

      // Re-append sorted rooms
      rooms.forEach(room => container.appendChild(room));
    }

    // Auto-dismiss alerts
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);
  </script>
</body>

</html>
