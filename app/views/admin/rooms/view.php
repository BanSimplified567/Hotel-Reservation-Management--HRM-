<?php
// app/views/admin/rooms/view.php

// Check if room data exists
if (!isset($room)) {
  echo '<div class="alert alert-danger">Room not found.</div>';
  return;
}

// Decode features JSON if it exists
$room['features'] = json_decode($room['features'] ?? '[]', true);
?>

<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Room Details</h1>
    <div>
      <a href="index.php?action=admin/rooms&sub_action=edit&id=<?php echo $room['id']; ?>"
        class="btn btn-warning shadow-sm mr-2">
        <i class="fas fa-edit fa-sm text-white-50"></i> Edit Room
      </a>
      <a href="index.php?action=admin/rooms" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Rooms
      </a>
    </div>
  </div>

  <!-- Room Details -->
  <div class="row">
    <!-- Left Column - Room Information -->
    <div class="col-lg-8">
      <!-- Room Overview Card -->
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Room Overview</h6>
          <span class="badge badge-<?php
                                    echo $room['status'] == 'available' ? 'success' : ($room['status'] == 'occupied' ? 'warning' : ($room['status'] == 'maintenance' ? 'danger' : ($room['status'] == 'cleaning' ? 'info' : 'secondary')));
                                    ?>">
            <?php echo ucfirst($room['status']); ?>
          </span>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4 text-center mb-4">
              <div class="room-icon mb-3">
                <i class="fas fa-<?php
                                  echo $room['status'] == 'available' ? 'door-open text-success fa-4x' : ($room['status'] == 'occupied' ? 'bed text-warning fa-4x' : ($room['status'] == 'maintenance' ? 'tools text-danger fa-4x' : ($room['status'] == 'cleaning' ? 'broom text-info fa-4x' : 'calendar-check text-secondary fa-4x')));
                                  ?>"></i>
              </div>
              <h3 class="font-weight-bold"><?php echo htmlspecialchars($room['room_number']); ?></h3>
              <p class="text-muted"><?php echo htmlspecialchars($room['room_type_name'] ?? 'Unknown Type'); ?></p>
            </div>
            <div class="col-md-8">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <h6 class="text-primary mb-1">Basic Information</h6>
                  <table class="table table-sm">
                    <tr>
                      <td><strong>Room Number:</strong></td>
                      <td><?php echo htmlspecialchars($room['room_number']); ?></td>
                    </tr>
                    <tr>
                      <td><strong>Room Type:</strong></td>
                      <td>
                        <span class="badge badge-info">
                          <?php echo htmlspecialchars($room['room_type_name'] ?? 'Unknown'); ?>
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td><strong>Floor:</strong></td>
                      <td>Floor <?php echo $room['floor']; ?></td>
                    </tr>
                    <tr>
                      <td><strong>View Type:</strong></td>
                      <td>
                        <span class="badge badge-secondary">
                          <?php echo ucfirst($room['view_type'] ?? 'city'); ?>
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td><strong>Price per Night:</strong></td>
                      <td class="text-success font-weight-bold">
                        ₱<?php echo number_format($room['room_type_price'] ?? 0, 2); ?>
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="col-md-6 mb-3">
                  <h6 class="text-primary mb-1">Room Status & History</h6>
                  <table class="table table-sm">
                    <tr>
                      <td><strong>Status:</strong></td>
                      <td>
                        <span class="badge badge-<?php
                                                  echo $room['status'] == 'available' ? 'success' : ($room['status'] == 'occupied' ? 'warning' : ($room['status'] == 'maintenance' ? 'danger' : ($room['status'] == 'cleaning' ? 'info' : 'secondary')));
                                                  ?>">
                          <?php echo ucfirst($room['status']); ?>
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td><strong>Created:</strong></td>
                      <td><?php echo date('F d, Y', strtotime($room['created_at'])); ?></td>
                    </tr>
                    <tr>
                      <td><strong>Last Updated:</strong></td>
                      <td>
                        <?php echo !empty($room['updated_at']) ?
                          date('F d, Y', strtotime($room['updated_at'])) : 'Never'; ?>
                      </td>
                    </tr>
                    <tr>
                      <td><strong>Total Reservations:</strong></td>
                      <td>
                        <span class="badge badge-primary">
                          <?php echo $room['total_reservations'] ?? 0; ?>
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td><strong>Active Reservations:</strong></td>
                      <td>
                        <span class="badge badge-warning">
                          <?php echo $room['active_reservations'] ?? 0; ?>
                        </span>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>

              <!-- Description -->
              <?php if (!empty($room['description'])): ?>
                <div class="mt-3">
                  <h6 class="text-primary mb-2">Description</h6>
                  <div class="border rounded p-3 bg-light">
                    <?php echo nl2br(htmlspecialchars($room['description'])); ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Features Card -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Room Features</h6>
        </div>
        <div class="card-body">
          <?php if (!empty($room['features'])): ?>
            <div class="row">
              <?php if (isset($room['features']['bed'])): ?>
                <div class="col-md-4 mb-3">
                  <div class="feature-item text-center p-3 border rounded">
                    <i class="fas fa-bed fa-2x text-primary mb-2"></i>
                    <h6>Bed Type</h6>
                    <p class="mb-0"><?php echo ucfirst($room['features']['bed']); ?></p>
                  </div>
                </div>
              <?php endif; ?>

              <?php if (isset($room['features']['balcony']) && $room['features']['balcony']): ?>
                <div class="col-md-4 mb-3">
                  <div class="feature-item text-center p-3 border rounded">
                    <i class="fas fa-umbrella-beach fa-2x text-success mb-2"></i>
                    <h6>Balcony</h6>
                    <p class="mb-0">Private balcony included</p>
                  </div>
                </div>
              <?php endif; ?>

              <?php if (isset($room['features']['private_pool']) && $room['features']['private_pool']): ?>
                <div class="col-md-4 mb-3">
                  <div class="feature-item text-center p-3 border rounded">
                    <i class="fas fa-swimming-pool fa-2x text-info mb-2"></i>
                    <h6>Private Pool</h6>
                    <p class="mb-0">Exclusive pool access</p>
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <!-- Additional features display -->
            <div class="mt-3">
              <h6 class="text-primary mb-2">All Features</h6>
              <div class="d-flex flex-wrap">
                <?php foreach ($room['features'] as $key => $value): ?>
                  <?php if ($value === true || $value === 'true'): ?>
                    <span class="badge badge-success badge-pill mr-2 mb-2 p-2">
                      <i class="fas fa-check mr-1"></i><?php echo ucfirst(str_replace('_', ' ', $key)); ?>
                    </span>
                  <?php elseif (is_string($value) && $value !== 'true' && $value !== 'false'): ?>
                    <span class="badge badge-info badge-pill mr-2 mb-2 p-2">
                      <?php echo ucfirst($key) . ': ' . ucfirst($value); ?>
                    </span>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>
          <?php else: ?>
            <div class="text-center py-4">
              <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
              <h5 class="text-muted">No Features Added</h5>
              <p class="text-muted">This room doesn't have any special features added yet.</p>
              <a href="index.php?action=admin/rooms&sub_action=edit&id=<?php echo $room['id']; ?>"
                class="btn btn-outline-primary">
                <i class="fas fa-plus"></i> Add Features
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Upcoming Reservations Card -->
      <?php if (!empty($upcomingReservations)): ?>
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Upcoming Reservations</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Reservation Code</th>
                    <th>Guest</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Nights</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($upcomingReservations as $reservation): ?>
                    <tr>
                      <td>
                        <strong><?php echo htmlspecialchars($reservation['reservation_code']); ?></strong>
                      </td>
                      <td>
                        <?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?>
                        <br>
                        <small class="text-muted"><?php echo htmlspecialchars($reservation['email']); ?></small>
                      </td>
                      <td><?php echo date('M d, Y', strtotime($reservation['check_in'])); ?></td>
                      <td><?php echo date('M d, Y', strtotime($reservation['check_out'])); ?></td>
                      <td><?php echo $reservation['total_nights']; ?></td>
                      <td>
                        <span class="badge badge-<?php
                                                  echo $reservation['status'] == 'confirmed' ? 'success' : 'warning';
                                                  ?>">
                          <?php echo ucfirst($reservation['status']); ?>
                        </span>
                      </td>
                      <td>
                        <a href="index.php?action=admin/reservations&sub_action=view&id=<?php echo $reservation['id']; ?>"
                          class="btn btn-sm btn-info" title="View Reservation">
                          <i class="fas fa-eye"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <!-- Right Column - Actions & Statistics -->
    <div class="col-lg-4">
      <!-- Quick Actions Card -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
        </div>
        <div class="card-body">
          <div class="d-grid gap-2">
            <!-- Change Status Button -->
            <button type="button" class="btn btn-outline-primary mb-2" data-toggle="modal" data-target="#changeStatusModal">
              <i class="fas fa-exchange-alt"></i> Change Status
            </button>

            <!-- View Availability Button -->
            <button type="button" class="btn btn-outline-info mb-2 view-availability"
              data-id="<?php echo $room['id']; ?>"
              data-room="<?php echo htmlspecialchars($room['room_number']); ?>">
              <i class="fas fa-calendar-alt"></i> View Availability Calendar
            </button>

            <!-- Create Reservation Button -->
            <a href="index.php?action=admin/reservations&sub_action=create&room_id=<?php echo $room['id']; ?>"
              class="btn btn-outline-success mb-2">
              <i class="fas fa-plus-circle"></i> Create Reservation
            </a>

            <!-- View All Reservations Button -->
            <a href="index.php?action=admin/reservations&search=<?php echo urlencode($room['room_number']); ?>"
              class="btn btn-outline-warning mb-2">
              <i class="fas fa-history"></i> View All Reservations
            </a>

            <!-- Maintenance Button -->
            <?php if ($room['status'] != 'maintenance'): ?>
              <button type="button" class="btn btn-outline-danger mb-2"
                onclick="setRoomStatus(<?php echo $room['id']; ?>, 'maintenance')">
                <i class="fas fa-tools"></i> Set to Maintenance
              </button>
            <?php endif; ?>

            <!-- Cleaning Button -->
            <?php if ($room['status'] != 'cleaning'): ?>
              <button type="button" class="btn btn-outline-info mb-2"
                onclick="setRoomStatus(<?php echo $room['id']; ?>, 'cleaning')">
                <i class="fas fa-broom"></i> Set to Cleaning
              </button>
            <?php endif; ?>

            <!-- Delete Button -->
            <button type="button" class="btn btn-outline-danger delete-room"
              data-id="<?php echo $room['id']; ?>"
              data-room="<?php echo htmlspecialchars($room['room_number']); ?>">
              <i class="fas fa-trash"></i> Delete Room
            </button>
          </div>
        </div>
      </div>

      <!-- Reservation Statistics Card -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Reservation Statistics</h6>
        </div>
        <div class="card-body">
          <div class="text-center mb-4">
            <div class="position-relative d-inline-block">
              <div class="chart-circle"
                data-percent="<?php echo min(100, ($room['total_reservations'] ?? 0) * 10); ?>"
                data-color="primary">
                <span class="chart-circle-value"><?php echo $room['total_reservations'] ?? 0; ?></span>
              </div>
            </div>
            <h5 class="mt-3">Total Reservations</h5>
          </div>

          <div class="list-group list-group-flush">
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              <div>
                <i class="fas fa-calendar-check text-success mr-2"></i>
                <span>Confirmed</span>
              </div>
              <span class="badge badge-success badge-pill"><?php echo $reservationStats['confirmed'] ?? 0; ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              <div>
                <i class="fas fa-user-clock text-warning mr-2"></i>
                <span>Pending</span>
              </div>
              <span class="badge badge-warning badge-pill"><?php echo $reservationStats['pending'] ?? 0; ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              <div>
                <i class="fas fa-check-circle text-primary mr-2"></i>
                <span>Checked-in</span>
              </div>
              <span class="badge badge-primary badge-pill"><?php echo $reservationStats['checked_in'] ?? 0; ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              <div>
                <i class="fas fa-door-closed text-secondary mr-2"></i>
                <span>Checked-out</span>
              </div>
              <span class="badge badge-secondary badge-pill"><?php echo $reservationStats['checked_out'] ?? 0; ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              <div>
                <i class="fas fa-ban text-danger mr-2"></i>
                <span>Cancelled</span>
              </div>
              <span class="badge badge-danger badge-pill"><?php echo $reservationStats['cancelled'] ?? 0; ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Room Type Details Card -->
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Room Type Information</h6>
        </div>
        <div class="card-body">
          <h5 class="text-primary"><?php echo htmlspecialchars($room['room_type_name'] ?? 'Unknown'); ?></h5>

          <div class="mb-3">
            <strong>Base Price:</strong>
            <span class="float-right text-success font-weight-bold">
              ₱<?php echo number_format($room['room_type_price'] ?? 0, 2); ?>/night
            </span>
          </div>

          <div class="mb-2">
            <strong>Capacity:</strong>
            <span class="float-right"><?php echo $room['capacity'] ?? 0; ?> persons</span>
          </div>

          <?php if (!empty($room['size'])): ?>
            <div class="mb-2">
              <strong>Size:</strong>
              <span class="float-right"><?php echo $room['size']; ?></span>
            </div>
          <?php endif; ?>

          <?php if (!empty($room['room_type_description'])): ?>
            <div class="mb-3">
              <strong>Description:</strong>
              <p class="mt-1"><?php echo nl2br(htmlspecialchars($room['room_type_description'])); ?></p>
            </div>
          <?php endif; ?>

          <?php if (!empty($room['amenities'])): ?>
            <div class="mt-3">
              <strong>Amenities:</strong>
              <div class="d-flex flex-wrap mt-2">
                <?php foreach ($room['amenities'] as $amenity => $available):
                  if ($available === true || $available === 'true'): ?>
                    <span class="badge badge-light border mr-1 mb-1">
                      <?php echo ucfirst(str_replace('_', ' ', $amenity)); ?>
                    </span>
                <?php
                  endif;
                endforeach; ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Change Status Modal -->
<div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changeStatusModalLabel">Change Room Status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="statusForm">
          <div class="form-group">
            <label for="newStatus">Select New Status</label>
            <select class="form-control" id="newStatus" name="newStatus">
              <option value="available" <?php echo $room['status'] == 'available' ? 'selected' : ''; ?>>Available</option>
              <option value="occupied" <?php echo $room['status'] == 'occupied' ? 'selected' : ''; ?>>Occupied</option>
              <option value="maintenance" <?php echo $room['status'] == 'maintenance' ? 'selected' : ''; ?>>Under Maintenance</option>
              <option value="cleaning" <?php echo $room['status'] == 'cleaning' ? 'selected' : ''; ?>>Cleaning</option>
              <option value="reserved" <?php echo $room['status'] == 'reserved' ? 'selected' : ''; ?>>Reserved</option>
            </select>
          </div>
          <div class="form-group">
            <label for="statusReason">Reason (Optional)</label>
            <textarea class="form-control" id="statusReason" name="statusReason" rows="3"
              placeholder="Enter reason for status change..."></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="changeRoomStatus(<?php echo $room['id']; ?>)">
          Update Status
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Availability Calendar Modal -->
<div class="modal fade" id="availabilityModal" tabindex="-1" role="dialog" aria-labelledby="availabilityModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="availabilityModalLabel">Room Availability Calendar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6 id="roomTitle" class="mb-3"></h6>
        <div id="availabilityCalendar"></div>
        <div class="mt-3">
          <div class="d-flex align-items-center">
            <div class="availability-legend available mr-3"></div>
            <small>Available</small>
            <div class="availability-legend booked mx-3"></div>
            <small>Booked</small>
            <div class="availability-legend maintenance mx-3"></div>
            <small>Maintenance</small>
            <div class="availability-legend cleaning mx-3"></div>
            <small>Cleaning</small>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete room: <strong id="deleteRoomNumber"></strong>?
        <div class="alert alert-danger mt-2">
          <small>
            <i class="fas fa-exclamation-triangle"></i>
            This action cannot be undone. All reservations for this room will also be deleted.
          </small>
        </div>
        <?php if (($room['total_reservations'] ?? 0) > 0): ?>
          <div class="alert alert-warning">
            <small>
              <i class="fas fa-exclamation-circle"></i>
              This room has <?php echo $room['total_reservations']; ?> reservation(s).
              Deleting it will remove all associated reservations.
            </small>
          </div>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <a href="index.php?action=admin/rooms&sub_action=delete&id=<?php echo $room['id']; ?>"
          class="btn btn-danger" id="confirmDelete">Delete</a>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // View availability
    document.querySelector('.view-availability').addEventListener('click', function() {
      const roomId = this.getAttribute('data-id');
      const roomNumber = this.getAttribute('data-room');

      document.getElementById('roomTitle').textContent = `Room ${roomNumber} - Availability`;
      loadAvailabilityCalendar(roomId);
      $('#availabilityModal').modal('show');
    });

    // Delete confirmation
    document.querySelector('.delete-room').addEventListener('click', function() {
      const roomNumber = this.getAttribute('data-room');
      document.getElementById('deleteRoomNumber').textContent = roomNumber;
      $('#deleteModal').modal('show');
    });

    // Load availability calendar
    function loadAvailabilityCalendar(roomId) {
      const calendarEl = document.getElementById('availabilityCalendar');

      // Show loading
      calendarEl.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Loading availability data...</p>
            </div>
        `;

      // Simulate API call - in production, this would be an AJAX call
      setTimeout(() => {
        const today = new Date();
        const month = today.getMonth();
        const year = today.getFullYear();

        calendarEl.innerHTML = generateCalendarHTML(month, year);
      }, 1000);
    }

    function generateCalendarHTML(month, year) {
      const monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
      ];

      const daysInMonth = new Date(year, month + 1, 0).getDate();
      const firstDay = new Date(year, month, 1).getDay();

      let calendarHTML = `
            <div class="text-center">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button class="btn btn-sm btn-outline-primary prev-month">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h5 class="mb-0">${monthNames[month]} ${year}</h5>
                    <button class="btn btn-sm btn-outline-primary next-month">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sun</th>
                                <th>Mon</th>
                                <th>Tue</th>
                                <th>Wed</th>
                                <th>Thu</th>
                                <th>Fri</th>
                                <th>Sat</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

      let day = 1;
      for (let i = 0; i < 6; i++) {
        calendarHTML += '<tr>';
        for (let j = 0; j < 7; j++) {
          if (i === 0 && j < firstDay) {
            calendarHTML += '<td></td>';
          } else if (day > daysInMonth) {
            calendarHTML += '<td></td>';
          } else {
            // Simulate random status for demo
            const statuses = ['available', 'booked', 'maintenance', 'cleaning'];
            const status = statuses[Math.floor(Math.random() * statuses.length)];
            const today = new Date();
            const isToday = (day === today.getDate() && month === today.getMonth() && year === today.getFullYear());

            calendarHTML += `
                        <td class="calendar-day ${status} ${isToday ? 'today' : ''}">
                            ${day}
                            ${isToday ? '<div class="today-indicator"></div>' : ''}
                        </td>
                    `;
            day++;
          }
        }
        calendarHTML += '</tr>';
        if (day > daysInMonth) break;
      }

      calendarHTML += `
                        </tbody>
                    </table>
                </div>
            </div>
        `;

      return calendarHTML;
    }

    // Change room status function
    window.changeRoomStatus = function(roomId) {
      const newStatus = document.getElementById('newStatus').value;
      const reason = document.getElementById('statusReason').value;

      // Show loading
      const btn = document.querySelector('#changeStatusModal .btn-primary');
      const originalText = btn.innerHTML;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2"></span> Updating...';
      btn.disabled = true;

      // Simulate API call - in production, this would be an AJAX call
      setTimeout(() => {
        alert(`Room status changed to: ${newStatus}`);
        $('#changeStatusModal').modal('hide');
        location.reload(); // Reload to show updated status

        btn.innerHTML = originalText;
        btn.disabled = false;
      }, 1000);
    };

    // Set room status to specific value
    window.setRoomStatus = function(roomId, status) {
      if (confirm(`Are you sure you want to set this room to "${status}"?`)) {
        // Show loading
        const event = new Event('click');
        document.querySelector('.view-availability').dispatchEvent(event);

        // Simulate API call
        setTimeout(() => {
          alert(`Room set to ${status} successfully`);
          location.reload();
        }, 1000);
      }
    };
  });

  // Add styles for calendar
  const style = document.createElement('style');
  style.textContent = `
    .calendar-day {
        height: 60px;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        position: relative;
        transition: all 0.3s ease;
    }
    .calendar-day:hover {
        transform: scale(1.05);
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .calendar-day.available {
        background-color: #d4edda;
        color: #155724;
    }
    .calendar-day.booked {
        background-color: #f8d7da;
        color: #721c24;
    }
    .calendar-day.maintenance {
        background-color: #fff3cd;
        color: #856404;
    }
    .calendar-day.cleaning {
        background-color: #d1ecf1;
        color: #0c5460;
    }
    .calendar-day.today {
        border: 2px solid #007bff;
    }
    .today-indicator {
        position: absolute;
        top: 2px;
        right: 2px;
        width: 6px;
        height: 6px;
        background-color: #007bff;
        border-radius: 50%;
    }
    .availability-legend {
        width: 20px;
        height: 20px;
        border-radius: 3px;
    }
    .availability-legend.available {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
    }
    .availability-legend.booked {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
    }
    .availability-legend.maintenance {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
    }
    .availability-legend.cleaning {
        background-color: #d1ecf1;
        border: 1px solid #bee5eb;
    }
    .feature-item {
        transition: all 0.3s ease;
    }
    .feature-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .chart-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: conic-gradient(#4e73df 0% 75%, #e3e6f0 75% 100%);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .chart-circle-value {
        font-size: 24px;
        font-weight: bold;
        color: #4e73df;
    }
`;
  document.head.appendChild(style);
</script>
