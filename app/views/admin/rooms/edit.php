<?php
// app/views/admin/rooms/edit.php
$old = $_SESSION['old'] ?? [];
$error = $_SESSION['error'] ?? '';
// Use old data if available (from form submission errors), otherwise use room data from controller
$room = !empty($old) ? $old : $room;
unset($_SESSION['old']);
unset($_SESSION['error']);

// Decode features - handle both array and JSON string
if (isset($room['features'])) {
  if (is_string($room['features'])) {
    $features = json_decode($room['features'], true);
    $room['features'] = $features ?: [];
  }
} else {
  $room['features'] = [];
}
?>

<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Room</h1>
    <div>
      <a href="index.php?action=admin/rooms&sub_action=view&id=<?php echo $room['id']; ?>"
        class="btn btn-info shadow-sm mr-2">
        <i class="fas fa-eye fa-sm text-white-50"></i> View
      </a>
      <a href="index.php?action=admin/rooms" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Rooms
      </a>
    </div>
  </div>

  <!-- Edit Room Form -->
  <div class="row">
    <div class="col-lg-8">
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Edit Room Information</h6>
          <span class="badge badge-<?php
                                    echo $room['status'] == 'available' ? 'success' : ($room['status'] == 'occupied' ? 'warning' : ($room['status'] == 'maintenance' ? 'danger' : ($room['status'] == 'cleaning' ? 'info' : 'secondary')));
                                    ?>">
            <i class="fas fa-<?php
                              echo $room['status'] == 'available' ? 'door-open' : ($room['status'] == 'occupied' ? 'bed' : ($room['status'] == 'maintenance' ? 'tools' : ($room['status'] == 'cleaning' ? 'broom' : 'calendar-check')));
                              ?> mr-1"></i>
            <?php echo ucfirst($room['status']); ?>
          </span>
        </div>
        <div class="card-body">
          <?php if ($error): ?>
            <div class="alert alert-danger">
              <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="index.php?action=admin/rooms&sub_action=edit&id=<?php echo $room['id']; ?>" id="roomForm">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="room_number" class="form-label">Room Number *</label>
                <input type="text" class="form-control" id="room_number" name="room_number"
                  value="<?php echo htmlspecialchars($room['room_number'] ?? ''); ?>" required
                  pattern="[A-Za-z0-9\-]+" title="Only letters, numbers, and hyphens are allowed">
                <small class="text-muted">Must be unique</small>
              </div>
              <div class="col-md-6 mb-3">
                <label for="room_type_id" class="form-label">Room Type *</label>
                <select class="form-control" id="room_type_id" name="room_type_id" required>
                  <option value="">Select Room Type</option>
                  <?php foreach ($roomTypes as $type): ?>
                    <option value="<?php echo $type['id']; ?>"
                      <?php echo ($room['room_type_id'] ?? '') == $type['id'] ? 'selected' : ''; ?>
                      data-price="<?php echo $type['base_price']; ?>"
                      data-capacity="<?php echo $type['capacity']; ?>"
                      data-size="<?php echo htmlspecialchars($type['size'] ?? ''); ?>"
                      data-description="<?php echo htmlspecialchars($type['description'] ?? ''); ?>">
                      <?php echo htmlspecialchars($type['name']); ?>
                      - ₱<?php echo number_format($type['base_price'], 2); ?>/night
                    </option>
                  <?php endforeach; ?>
                </select>
                <div id="roomTypeDetails" class="mt-2 p-2 bg-light border rounded">
                  <small>
                    <strong>Current Type Details:</strong><br>
                    <span id="typeCapacity">Capacity: <?php echo $room['capacity'] ?? 'Not specified'; ?></span><br>
                    <span id="typeSize">Size: <?php echo htmlspecialchars($room['size'] ?? 'Not specified'); ?></span><br>
                    <span id="typeDescription">Description: <?php echo htmlspecialchars($room['room_type_description'] ?? 'No description available'); ?></span>
                  </small>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="floor" class="form-label">Floor *</label>
                <input type="number" class="form-control" id="floor" name="floor"
                  value="<?php echo htmlspecialchars($room['floor'] ?? 1); ?>"
                  min="1" max="20" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="view_type" class="form-label">View Type *</label>
                <select class="form-control" id="view_type" name="view_type" required>
                  <option value="">Select View Type</option>
                  <?php foreach ($viewTypes as $view): ?>
                    <option value="<?php echo $view; ?>"
                      <?php echo ($room['view_type'] ?? '') == $view ? 'selected' : ''; ?>>
                      <?php echo ucfirst($view); ?> View
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">Room Description</label>
              <textarea class="form-control" id="description" name="description"
                rows="3" placeholder="Enter additional room details, special features, or notes..."><?php echo htmlspecialchars($room['description'] ?? ''); ?></textarea>
            </div>

            <!-- Features Section -->
            <div class="mb-4">
              <label class="form-label">Room Features</label>
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="features_bed" class="form-label">Bed Type</label>
                  <select class="form-control" id="features_bed" name="features_bed">
                    <option value="">Select Bed Type</option>
                    <?php foreach ($bedTypes as $bed): ?>
                      <option value="<?php echo $bed; ?>"
                        <?php echo (isset($room['features']['bed']) && $room['features']['bed'] == $bed) ? 'selected' : ''; ?>>
                        <?php echo ucfirst($bed); ?> Bed
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-4 mb-3">
                  <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox"
                      id="features_balcony" name="features_balcony" value="1"
                      <?php echo (isset($room['features']['balcony']) && $room['features']['balcony']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="features_balcony">
                      <i class="fas fa-door-open"></i> Has Balcony
                    </label>
                  </div>
                </div>
                <div class="col-md-4 mb-3">
                  <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox"
                      id="features_private_pool" name="features_private_pool" value="1"
                      <?php echo (isset($room['features']['private_pool']) && $room['features']['private_pool']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="features_private_pool">
                      <i class="fas fa-swimming-pool"></i> Private Pool
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <label for="status" class="form-label">Status *</label>
              <select class="form-control" id="status" name="status" required <?php echo ($room['status'] == 'occupied' && $active_reservations > 0) ? 'disabled' : ''; ?>>
                <option value="">Select Status</option>
                <option value="available" <?php echo ($room['status'] ?? '') == 'available' ? 'selected' : ''; ?>>Available</option>
                <option value="occupied" <?php echo ($room['status'] ?? '') == 'occupied' ? 'selected' : ''; ?>>Occupied</option>
                <option value="maintenance" <?php echo ($room['status'] ?? '') == 'maintenance' ? 'selected' : ''; ?>>Under Maintenance</option>
                <option value="cleaning" <?php echo ($room['status'] ?? '') == 'cleaning' ? 'selected' : ''; ?>>Cleaning</option>
                <option value="reserved" <?php echo ($room['status'] ?? '') == 'reserved' ? 'selected' : ''; ?>>Reserved</option>
              </select>
              <?php if ($room['status'] == 'occupied' && $active_reservations > 0): ?>
                <input type="hidden" name="status" value="occupied">
                <small class="text-danger">
                  <i class="fas fa-exclamation-triangle"></i>
                  Cannot change status. Room has <?php echo $active_reservations; ?> active reservation(s).
                </small>
              <?php endif; ?>
            </div>

            <div class="d-flex justify-content-between">
              <a href="index.php?action=admin/rooms&sub_action=view&id=<?php echo $room['id']; ?>"
                class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
              </a>
              <div>
                <button type="reset" class="btn btn-outline-secondary mr-2">
                  <i class="fas fa-redo"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save"></i> Update Room
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <!-- Room Statistics -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Room Statistics</h6>
        </div>
        <div class="card-body">
          <div class="text-center mb-4">
            <div class="room-icon mb-3">
              <i class="fas fa-<?php
                                echo isset($room['status']) ? ($room['status'] == 'available' ? 'door-open text-success' : ($room['status'] == 'occupied' ? 'bed text-warning' : ($room['status'] == 'maintenance' ? 'tools text-danger' : ($room['status'] == 'cleaning' ? 'broom text-info' :
                                  'calendar-check text-secondary')))) : 'door-closed text-gray-300'; ?> fa-3x"></i>
            </div>
            <h4 class="font-weight-bold"><?php echo htmlspecialchars($room['room_number'] ?? ''); ?></h4>
            <p class="text-muted mb-1">
              <i class="fas fa-home text-info mr-1"></i>
              <?php echo htmlspecialchars($room['room_type_name'] ?? 'Unknown Type'); ?>
            </p>
            <p class="text-success font-weight-bold">
              <i class="fas fa-money-bill-wave mr-1"></i>
              ₱<?php echo number_format($room['room_type_price'] ?? 0, 2); ?>/night
            </p>
            <p class="text-primary">
              <i class="fas fa-users mr-1"></i>
              Capacity: <?php echo $room['capacity'] ?? 0; ?> persons
            </p>
            <?php if (!empty($room['size'])): ?>
              <p class="text-secondary">
                <i class="fas fa-expand-arrows-alt mr-1"></i>
                Size: <?php echo htmlspecialchars($room['size']); ?>
              </p>
            <?php endif; ?>
          </div>

          <div class="list-group list-group-flush">
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              <span><i class="fas fa-history text-primary mr-2"></i>Total Reservations</span>
              <span class="badge badge-primary badge-pill"><?php echo $total_reservations ?? 0; ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              <span><i class="fas fa-clock text-warning mr-2"></i>Active Reservations</span>
              <span class="badge badge-warning badge-pill"><?php echo $active_reservations ?? 0; ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              <span><i class="fas fa-calendar-plus text-info mr-2"></i>Created</span>
              <span><?php echo isset($room['created_at']) ? date('M d, Y', strtotime($room['created_at'])) : 'Unknown'; ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              <span><i class="fas fa-calendar-check text-success mr-2"></i>Last Updated</span>
              <span><?php echo !empty($room['updated_at']) ? date('M d, Y', strtotime($room['updated_at'])) : 'Never'; ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Current Features -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Current Features</h6>
        </div>
        <div class="card-body">
          <?php if (!empty($room['features'])): ?>
            <div class="d-flex flex-wrap">
              <?php if (isset($room['features']['bed'])): ?>
                <span class="badge badge-light border mr-1 mb-1">
                  <i class="fas fa-bed text-primary mr-1"></i>
                  Bed: <?php echo ucfirst($room['features']['bed']); ?>
                </span>
              <?php endif; ?>
              <?php if (isset($room['features']['balcony']) && $room['features']['balcony']): ?>
                <span class="badge badge-light border mr-1 mb-1">
                  <i class="fas fa-door-open text-success mr-1"></i>
                  Balcony
                </span>
              <?php endif; ?>
              <?php if (isset($room['features']['private_pool']) && $room['features']['private_pool']): ?>
                <span class="badge badge-light border mr-1 mb-1">
                  <i class="fas fa-swimming-pool text-info mr-1"></i>
                  Private Pool
                </span>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <p class="text-center text-muted">
              <i class="fas fa-info-circle mr-1"></i>
              No features set
            </p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
        </div>
        <div class="card-body">
          <div class="d-grid gap-2">
            <button type="button" class="btn btn-outline-primary view-availability"
              data-id="<?php echo $room['id']; ?>"
              data-room="<?php echo htmlspecialchars($room['room_number'] ?? ''); ?>">
              <i class="fas fa-calendar-alt"></i> View Availability
            </button>

            <a href="index.php?action=admin/reservations&search=<?php echo urlencode($room['room_number'] ?? ''); ?>"
              class="btn btn-outline-info">
              <i class="fas fa-history"></i> View Reservations
            </a>

            <?php if ($total_reservations == 0): ?>
              <button type="button" class="btn btn-outline-danger delete-room"
                data-id="<?php echo $room['id']; ?>"
                data-room="<?php echo htmlspecialchars($room['room_number'] ?? ''); ?>">
                <i class="fas fa-trash"></i> Delete Room
              </button>
            <?php else: ?>
              <button type="button" class="btn btn-outline-danger" disabled
                title="Cannot delete room with reservations">
                <i class="fas fa-trash"></i> Delete Room
              </button>
            <?php endif; ?>
          </div>
        </div>
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
        <p>Are you sure you want to delete room: <strong id="deleteRoomNumber"></strong>?</p>
        <div class="alert alert-danger mt-2">
          <small>
            <i class="fas fa-exclamation-triangle"></i>
            This action cannot be undone. The room will be permanently removed from the system.
          </small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <a href="index.php?action=admin/rooms&sub_action=delete&id=<?php echo $room['id']; ?>" class="btn btn-danger">
          <i class="fas fa-trash"></i> Delete Room
        </a>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Update room type details when selection changes
    const roomTypeSelect = document.getElementById('room_type_id');
    const roomTypeDetails = document.getElementById('roomTypeDetails');
    const typeCapacity = document.getElementById('typeCapacity');
    const typeSize = document.getElementById('typeSize');
    const typeDescription = document.getElementById('typeDescription');

    function updateRoomTypeDetails() {
      if (roomTypeSelect.value) {
        const selectedOption = roomTypeSelect.options[roomTypeSelect.selectedIndex];
        const capacity = selectedOption.getAttribute('data-capacity');
        const size = selectedOption.getAttribute('data-size');
        const description = selectedOption.getAttribute('data-description');

        typeCapacity.textContent = `Capacity: ${capacity} person${capacity > 1 ? 's' : ''}`;
        typeSize.textContent = `Size: ${size || 'Not specified'}`;
        typeDescription.textContent = `Description: ${description || 'No description available'}`;

        roomTypeDetails.style.display = 'block';
      } else {
        roomTypeDetails.style.display = 'none';
      }
    }

    roomTypeSelect.addEventListener('change', updateRoomTypeDetails);

    // View availability
    document.querySelector('.view-availability')?.addEventListener('click', function() {
      const roomId = this.getAttribute('data-id');
      const roomNumber = this.getAttribute('data-room');

      document.getElementById('roomTitle').textContent = `Room ${roomNumber} - Availability Calendar`;
      loadAvailabilityCalendar(roomId);
      $('#availabilityModal').modal('show');
    });

    // Delete confirmation
    document.querySelector('.delete-room')?.addEventListener('click', function() {
      const roomNumber = this.getAttribute('data-room');
      document.getElementById('deleteRoomNumber').textContent = roomNumber;
      $('#deleteModal').modal('show');
    });

    // Load availability calendar
    function loadAvailabilityCalendar(roomId) {
      const calendarEl = document.getElementById('availabilityCalendar');

      // For demo, show a loading message
      calendarEl.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Loading availability data...</p>
            </div>
        `;

      // Simulate API call
      setTimeout(() => {
        calendarEl.innerHTML = `
                <div class="text-center">
                    <h5>Current Month</h5>
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
                                <tr>
                                    <td></td><td></td><td></td><td></td><td></td><td class="available">1</td><td class="available">2</td>
                                </tr>
                                <tr>
                                    <td class="available">3</td><td class="booked">4</td><td class="booked">5</td>
                                    <td class="available">6</td><td class="available">7</td><td class="available">8</td>
                                    <td class="available">9</td>
                                </tr>
                                <tr>
                                    <td class="available">10</td><td class="available">11</td><td class="booked">12</td>
                                    <td class="booked">13</td><td class="booked">14</td><td class="available">15</td>
                                    <td class="available">16</td>
                                </tr>
                                <tr>
                                    <td class="available">17</td><td class="available">18</td><td class="available">19</td>
                                    <td class="available">20</td><td class="available">21</td><td class="available">22</td>
                                    <td class="available">23</td>
                                </tr>
                                <tr>
                                    <td class="available">24</td><td class="booked">25</td><td class="booked">26</td>
                                    <td class="booked">27</td><td class="booked">28</td><td class="available">29</td>
                                    <td class="available">30</td>
                                </tr>
                                <tr>
                                    <td class="available">31</td><td></td><td></td><td></td><td></td><td></td><td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <span class="badge badge-success mr-2">Available</span>
                        <span class="badge badge-danger mr-2">Booked</span>
                        <span class="badge badge-warning mr-2">Partially Booked</span>
                    </div>
                </div>
            `;
      }, 1000);
    }

    // Form validation
    const form = document.getElementById('roomForm');
    form.addEventListener('submit', function(e) {
      let valid = true;
      const errors = [];

      // Check room number format
      if (!/^[A-Z0-9\-]+$/i.test(document.getElementById('room_number').value.trim())) {
        errors.push('Room number can only contain letters, numbers, and hyphens');
        valid = false;
      }

      // Check floor
      const floorInput = document.getElementById('floor');
      if (parseInt(floorInput.value) < 1 || parseInt(floorInput.value) > 20) {
        errors.push('Floor must be between 1 and 20');
        valid = false;
      }

      // Check room type
      if (!roomTypeSelect.value) {
        errors.push('Please select a room type');
        valid = false;
      }

      // Check view type
      const viewTypeSelect = document.getElementById('view_type');
      if (!viewTypeSelect.value) {
        errors.push('Please select a view type');
        valid = false;
      }

      // Check status
      const statusSelect = document.getElementById('status');
      if (!statusSelect.value && !statusSelect.disabled) {
        errors.push('Please select a status');
        valid = false;
      }

      if (!valid) {
        e.preventDefault();
        alert('Please fix the following errors:\n\n' + errors.join('\n'));
      }
    });
  });

  // Styles for calendar
  const style = document.createElement('style');
  style.textContent = `
    .calendar-day {
        height: 40px;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
    }
    .available {
        background-color: #d4edda;
        color: #155724;
    }
    .booked {
        background-color: #f8d7da;
        color: #721c24;
    }
    .room-icon {
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .badge-light {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
`;
  document.head.appendChild(style);
</script>
