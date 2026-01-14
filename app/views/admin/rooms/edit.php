<?php
require_once '../../layout/admin-header.php';
require_once '../../layout/admin-sidebar.php';

$old = $_SESSION['old'] ?? [];
$error = $_SESSION['error'] ?? '';
$room = $old ?: $room;
unset($_SESSION['old']);
unset($_SESSION['error']);

// Decode amenities
$room['amenities'] = json_decode($room['amenities'] ?? '[]', true);

// Predefined room types and amenities
$roomTypes = ['Standard', 'Deluxe', 'Suite', 'Executive', 'Family', 'Penthouse'];
$allAmenities = [
  'WiFi',
  'TV',
  'Air Conditioning',
  'Mini Bar',
  'Safe',
  'Hair Dryer',
  'Coffee Maker',
  'Iron',
  'Room Service',
  'Balcony',
  'Ocean View',
  'Bathtub',
  'Kitchenette',
  'Jacuzzi',
  'Fireplace',
  'Pool View',
  'Garden View',
  'City View',
  'Mountain View',
  'Private Pool'
];
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
                                    echo $room['status'] == 'available' ? 'success' : ($room['status'] == 'occupied' ? 'warning' : 'danger');
                                    ?>">
            <?php echo ucfirst($room['status']); ?>
          </span>
        </div>
        <div class="card-body">
          <?php if ($error): ?>
            <div class="alert alert-danger">
              <?php echo $error; ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="index.php?action=admin/rooms&sub_action=edit&id=<?php echo $room['id']; ?>" id="roomForm">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="room_number" class="form-label">Room Number *</label>
                <input type="text" class="form-control" id="room_number" name="room_number"
                  value="<?php echo htmlspecialchars($room['room_number']); ?>" required>
                <small class="text-muted">Must be unique</small>
              </div>
              <div class="col-md-6 mb-3">
                <label for="type" class="form-label">Room Type *</label>
                <select class="form-control" id="type" name="type" required>
                  <option value="">Select Type</option>
                  <?php foreach ($roomTypes as $type): ?>
                    <option value="<?php echo htmlspecialchars($type); ?>"
                      <?php echo ($room['type'] ?? '') == $type ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($type); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea class="form-control" id="description" name="description"
                rows="3"><?php echo htmlspecialchars($room['description'] ?? ''); ?></textarea>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="price_per_night" class="form-label">Price per Night *</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                  </div>
                  <input type="number" class="form-control" id="price_per_night" name="price_per_night"
                    value="<?php echo htmlspecialchars($room['price_per_night']); ?>"
                    step="0.01" min="0" required>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="capacity" class="form-label">Capacity *</label>
                <select class="form-control" id="capacity" name="capacity" required>
                  <option value="">Select Capacity</option>
                  <?php for ($i = 1; $i <= 6; $i++): ?>
                    <option value="<?php echo $i; ?>"
                      <?php echo ($room['capacity'] ?? '') == $i ? 'selected' : ''; ?>>
                      <?php echo $i; ?> person<?php echo $i > 1 ? 's' : ''; ?>
                    </option>
                  <?php endfor; ?> <!-- Changed from endforeach to endfor -->
                </select>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label">Amenities</label>
              <div class="row">
                <?php foreach ($allAmenities as $index => $amenity): ?>
                  <div class="col-md-4 mb-2">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox"
                        id="amenity_<?php echo $index; ?>"
                        name="amenities[]" value="<?php echo htmlspecialchars($amenity); ?>"
                        <?php echo in_array($amenity, $room['amenities']) ? 'checked' : ''; ?>>
                      <label class="form-check-label" for="amenity_<?php echo $index; ?>">
                        <?php echo htmlspecialchars($amenity); ?>
                      </label>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="mb-4">
              <label for="status" class="form-label">Status *</label>
              <select class="form-control" id="status" name="status" required>
                <option value="available" <?php echo $room['status'] == 'available' ? 'selected' : ''; ?>>Available</option>
                <option value="occupied" <?php echo $room['status'] == 'occupied' ? 'selected' : ''; ?>>Occupied</option>
                <option value="maintenance" <?php echo $room['status'] == 'maintenance' ? 'selected' : ''; ?>>Under Maintenance</option>
              </select>
              <?php if ($room['status'] == 'occupied' && $activeReservations > 0): ?>
                <small class="text-danger">
                  <i class="fas fa-exclamation-triangle"></i>
                  Cannot set as available. Room has active reservations.
                </small>
              <?php endif; ?>
            </div>

            <div class="d-flex justify-content-between">
              <a href="index.php?action=admin/rooms&sub_action=view&id=<?php echo $room['id']; ?>"
                class="btn btn-secondary">Cancel</a>
              <div>
                <button type="reset" class="btn btn-outline-secondary mr-2">Reset</button>
                <button type="submit" class="btn btn-primary"
                  <?php echo ($room['status'] == 'occupied' && $room['status'] != 'available') ? '' : ''; ?>>
                  Update Room
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
                                echo $room['status'] == 'available' ? 'door-open text-success' : ($room['status'] == 'occupied' ? 'bed text-warning' : 'tools text-danger');
                                ?> fa-3x"></i>
            </div>
            <h4><?php echo htmlspecialchars($room['room_number']); ?></h4>
            <p class="text-muted mb-1"><?php echo htmlspecialchars($room['type']); ?></p>
            <p class="text-success font-weight-bold">$<?php echo number_format($room['price_per_night'], 2); ?>/night</p>
          </div>

          <div class="list-group list-group-flush">
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              Total Reservations
              <span class="badge badge-primary badge-pill"><?php echo $total_reservations ?? 0; ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              Active Reservations
              <span class="badge badge-warning badge-pill"><?php echo $active_reservations ?? 0; ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              Total Revenue
              <span class="text-success">$<?php echo number_format($total_revenue ?? 0, 2); ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              Created
              <span><?php echo date('M d, Y', strtotime($room['created_at'])); ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
              Last Updated
              <span><?php echo !empty($room['updated_at']) ? date('M d, Y', strtotime($room['updated_at'])) : 'Never'; ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Current Amenities -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Current Amenities</h6>
        </div>
        <div class="card-body">
          <?php if (!empty($room['amenities'])): ?>
            <div class="d-flex flex-wrap">
              <?php foreach ($room['amenities'] as $amenity): ?>
                <span class="badge badge-light border mr-1 mb-1"><?php echo htmlspecialchars($amenity); ?></span>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="text-center text-muted">No amenities set</p>
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
              data-room="<?php echo htmlspecialchars($room['room_number']); ?>">
              <i class="fas fa-calendar-alt"></i> View Availability
            </button>

            <a href="index.php?action=admin/reservations&search=<?php echo urlencode($room['room_number']); ?>"
              class="btn btn-outline-info">
              <i class="fas fa-history"></i> View Reservations
            </a>

            <button type="button" class="btn btn-outline-danger delete-room"
              data-id="<?php echo $room['id']; ?>"
              data-room="<?php echo htmlspecialchars($room['room_number']); ?>">
              <i class="fas fa-trash"></i> Delete Room
            </button>
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
        Are you sure you want to delete room: <strong id="deleteRoomNumber"></strong>?
        <div class="alert alert-danger mt-2">
          <small>
            <i class="fas fa-exclamation-triangle"></i>
            This action cannot be undone. All reservations for this room will also be deleted.
          </small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <a href="index.php?action=admin/rooms&sub_action=delete&id=<?php echo $room['id']; ?>" class="btn btn-danger">Delete</a>
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
      // This would normally make an AJAX call to get availability data
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
                    <h5>December 2023</h5>
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
                                    <td></td><td></td><td></td><td></td><td></td><td>1</td><td class="available">2</td>
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
                </div>
            `;
      }, 1000);
    }

    // Form validation
    const form = document.getElementById('roomForm');
    const statusSelect = document.getElementById('status');

    form.addEventListener('submit', function(e) {
      // Check if trying to set occupied room to available
      if (statusSelect.value === 'available' && <?php echo $active_reservations > 0 ? 'true' : 'false'; ?>) {
        e.preventDefault();
        alert('Cannot set room as available. It has active reservations.');
        return false;
      }

      // Check room number format
      const roomNumberInput = document.getElementById('room_number');
      if (!/^[A-Z0-9\-]+$/i.test(roomNumberInput.value.trim())) {
        alert('Room number can only contain letters, numbers, and hyphens');
        e.preventDefault();
        return false;
      }

      // Check price
      const priceInput = document.getElementById('price_per_night');
      if (parseFloat(priceInput.value) <= 0) {
        alert('Price must be greater than 0');
        e.preventDefault();
        return false;
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
`;
  document.head.appendChild(style);
</script>

<?php
require_once '../../layout/admin-footer.php';
?>
