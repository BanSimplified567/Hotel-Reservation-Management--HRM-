<?php
$old = $_SESSION['old'] ?? [];
$error = $_SESSION['error'] ?? '';
unset($_SESSION['old']);
unset($_SESSION['error']);
?>

<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Add New Room</h1>
    <a href="index.php?action=admin/rooms" class="btn btn-secondary shadow-sm">
      <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Rooms
    </a>
  </div>

  <!-- Create Room Form -->
  <div class="row">
    <div class="col-lg-8">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Room Information</h6>
        </div>
        <div class="card-body">
          <?php if ($error): ?>
            <div class="alert alert-danger">
              <?php echo $error; ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="index.php?action=admin/rooms&sub_action=create" id="roomForm">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="room_number" class="form-label">Room Number *</label>
                <input type="text" class="form-control" id="room_number" name="room_number"
                  value="<?php echo htmlspecialchars($old['room_number'] ?? ''); ?>" required>
                <small class="text-muted">Must be unique</small>
              </div>
              <div class="col-md-6 mb-3">
                <label for="room_type_id" class="form-label">Room Type *</label>
                <select class="form-control" id="room_type_id" name="room_type_id" required>
                  <option value="">Select Type</option>
                  <?php foreach ($roomTypes as $type): ?>
                    <option value="<?php echo $type['id']; ?>"
                      <?php echo ($old['room_type_id'] ?? '') == $type['id'] ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($type['name']); ?>
                      (₱<?php echo number_format($type['base_price'], 2); ?>/night,
                      Capacity: <?php echo $type['capacity']; ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="floor" class="form-label">Floor *</label>
                <input type="number" class="form-control" id="floor" name="floor"
                  value="<?php echo htmlspecialchars($old['floor'] ?? 1); ?>"
                  min="1" max="20" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="view_type" class="form-label">View Type *</label>
                <select class="form-control" id="view_type" name="view_type" required>
                  <?php foreach ($viewTypes as $view): ?>
                    <option value="<?php echo $view; ?>"
                      <?php echo ($old['view_type'] ?? 'city') == $view ? 'selected' : ''; ?>>
                      <?php echo ucfirst($view); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea class="form-control" id="description" name="description"
                rows="3"><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
            </div>

            <!-- Features Section -->
            <div class="mb-4">
              <label class="form-label">Features</label>
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="features_bed" class="form-label">Bed Type</label>
                  <select class="form-control" id="features_bed" name="features_bed">
                    <option value="">Select Bed Type</option>
                    <?php foreach ($bedTypes as $bed): ?>
                      <option value="<?php echo $bed; ?>"
                        <?php echo (isset($old['features_bed']) && $old['features_bed'] == $bed) ? 'selected' : ''; ?>>
                        <?php echo ucfirst($bed); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-4 mb-3">
                  <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox"
                      id="features_balcony" name="features_balcony" value="1"
                      <?php echo isset($old['features_balcony']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="features_balcony">
                      Has Balcony
                    </label>
                  </div>
                </div>
                <div class="col-md-4 mb-3">
                  <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox"
                      id="features_private_pool" name="features_private_pool" value="1"
                      <?php echo isset($old['features_private_pool']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="features_private_pool">
                      Private Pool
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <label for="status" class="form-label">Status *</label>
              <select class="form-control" id="status" name="status" required>
                <option value="available" <?php echo ($old['status'] ?? 'available') == 'available' ? 'selected' : ''; ?>>Available</option>
                <option value="occupied" <?php echo ($old['status'] ?? '') == 'occupied' ? 'selected' : ''; ?>>Occupied</option>
                <option value="maintenance" <?php echo ($old['status'] ?? '') == 'maintenance' ? 'selected' : ''; ?>>Under Maintenance</option>
                <option value="cleaning" <?php echo ($old['status'] ?? '') == 'cleaning' ? 'selected' : ''; ?>>Cleaning</option>
                <option value="reserved" <?php echo ($old['status'] ?? '') == 'reserved' ? 'selected' : ''; ?>>Reserved</option>
              </select>
            </div>

            <div class="d-flex justify-content-between">
              <button type="reset" class="btn btn-secondary">Reset</button>
              <button type="submit" class="btn btn-primary">Create Room</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <!-- Help Card -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Guidelines</h6>
        </div>
        <div class="card-body">
          <ul class="list-unstyled">
            <li class="mb-2">
              <i class="fas fa-info-circle text-primary mr-2"></i>
              All fields marked with * are required
            </li>
            <li class="mb-2">
              <i class="fas fa-hashtag text-warning mr-2"></i>
              Room number must be unique
            </li>
            <li class="mb-2">
              <i class="fas fa-layer-group text-info mr-2"></i>
              Choose appropriate floor (1-20)
            </li>
            <li class="mb-2">
              <i class="fas fa-mountain text-success mr-2"></i>
              Select view type based on room location
            </li>
            <li class="mb-2">
              <i class="fas fa-bed text-warning mr-2"></i>
              Bed type affects room classification
            </li>
            <li>
              <i class="fas fa-tools text-danger mr-2"></i>
              Set to maintenance if room needs repairs
            </li>
          </ul>
        </div>
      </div>

      <!-- Preview Card -->
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Room Preview</h6>
        </div>
        <div class="card-body">
          <div id="roomPreview" class="text-center">
            <div class="preview-icon mb-3">
              <i class="fas fa-door-closed fa-3x text-gray-300"></i>
            </div>
            <h5 id="previewRoomNumber">Room #</h5>
            <p id="previewType" class="text-muted mb-2">Type: <span class="text-info">-</span></p>
            <p id="previewFloor" class="text-muted mb-2">Floor: <span class="text-primary">-</span></p>
            <p id="previewView" class="text-muted mb-2">View: <span class="text-success">-</span></p>
            <div id="previewFeatures" class="text-left mt-3">
              <small class="text-muted">Features will appear here</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Update preview in real-time
    const roomNumberInput = document.getElementById('room_number');
    const roomTypeSelect = document.getElementById('room_type_id');
    const floorInput = document.getElementById('floor');
    const viewTypeSelect = document.getElementById('view_type');
    const featuresBedSelect = document.getElementById('features_bed');
    const featuresBalconyCheck = document.getElementById('features_balcony');
    const featuresPoolCheck = document.getElementById('features_private_pool');
    const statusSelect = document.getElementById('status');

    const previewRoomNumber = document.getElementById('previewRoomNumber');
    const previewType = document.querySelector('#previewType span');
    const previewFloor = document.querySelector('#previewFloor span');
    const previewView = document.querySelector('#previewView span');
    const previewFeatures = document.getElementById('previewFeatures');
    const previewIcon = document.querySelector('.preview-icon i');

    function updatePreview() {
      // Room number
      previewRoomNumber.textContent = roomNumberInput.value ? `Room ${roomNumberInput.value}` : 'Room #';

      // Room type
      if (roomTypeSelect.value) {
        const selectedOption = roomTypeSelect.options[roomTypeSelect.selectedIndex];
        const typeText = selectedOption.text.split(' (')[0]; // Remove price/capacity info
        previewType.textContent = typeText;
        previewType.className = 'text-info';
      } else {
        previewType.textContent = '-';
        previewType.className = 'text-muted';
      }

      // Floor
      if (floorInput.value) {
        previewFloor.textContent = `Floor ${floorInput.value}`;
        previewFloor.className = 'text-primary';
      } else {
        previewFloor.textContent = '-';
        previewFloor.className = 'text-muted';
      }

      // View type
      if (viewTypeSelect.value) {
        previewView.textContent = ucFirst(viewTypeSelect.value);
        previewView.className = 'text-success';
      } else {
        previewView.textContent = '-';
        previewView.className = 'text-muted';
      }

      // Features
      const features = [];
      if (featuresBedSelect.value) {
        features.push(`Bed: ${ucFirst(featuresBedSelect.value)}`);
      }
      if (featuresBalconyCheck.checked) {
        features.push('Balcony');
      }
      if (featuresPoolCheck.checked) {
        features.push('Private Pool');
      }

      if (features.length > 0) {
        previewFeatures.innerHTML = `
                <small class="text-primary">Features:</small><br>
                <div class="mt-1">
                    ${features.map(feature =>
                        `<span class="badge badge-light border mr-1 mb-1">${feature}</span>`
                    ).join('')}
                </div>
            `;
      } else {
        previewFeatures.innerHTML = '<small class="text-muted">No features selected</small>';
      }

      // Status icon
      switch (statusSelect.value) {
        case 'available':
          previewIcon.className = 'fas fa-door-open fa-3x text-success';
          break;
        case 'occupied':
          previewIcon.className = 'fas fa-bed fa-3x text-warning';
          break;
        case 'maintenance':
          previewIcon.className = 'fas fa-tools fa-3x text-danger';
          break;
        case 'cleaning':
          previewIcon.className = 'fas fa-broom fa-3x text-info';
          break;
        case 'reserved':
          previewIcon.className = 'fas fa-calendar-check fa-3x text-secondary';
          break;
        default:
          previewIcon.className = 'fas fa-door-closed fa-3x text-gray-300';
      }
    }

    // Helper function to capitalize first letter
    function ucFirst(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // Add event listeners
    [roomNumberInput, roomTypeSelect, floorInput, viewTypeSelect, featuresBedSelect, statusSelect].forEach(element => {
      element.addEventListener('input', updatePreview);
      element.addEventListener('change', updatePreview);
    });

    [featuresBalconyCheck, featuresPoolCheck].forEach(checkbox => {
      checkbox.addEventListener('change', updatePreview);
    });

    // Initial update
    updatePreview();

    // Form validation
    const form = document.getElementById('roomForm');
    form.addEventListener('submit', function(e) {
      let valid = true;

      // Check room number format
      if (!/^[A-Z0-9\-]+$/i.test(roomNumberInput.value.trim())) {
        alert('Room number can only contain letters, numbers, and hyphens');
        valid = false;
      }

      // Check floor
      if (parseInt(floorInput.value) < 1 || parseInt(floorInput.value) > 20) {
        alert('Floor must be between 1 and 20');
        valid = false;
      }

      if (!valid) {
        e.preventDefault();
      }
    });
  });
</script>

<style>
  .preview-icon {
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
</style>
