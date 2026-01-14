<?php
require_once '../../layout/admin-header.php';
require_once '../../layout/admin-sidebar.php';

$old = $_SESSION['old'] ?? [];
$error = $_SESSION['error'] ?? '';
unset($_SESSION['old']);
unset($_SESSION['error']);

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
                <label for="type" class="form-label">Room Type *</label>
                <select class="form-control" id="type" name="type" required>
                  <option value="">Select Type</option>
                  <?php foreach ($roomTypes as $type): ?>
                    <option value="<?php echo htmlspecialchars($type); ?>"
                      <?php echo ($old['type'] ?? '') == $type ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($type); ?>
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

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="price_per_night" class="form-label">Price per Night *</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                  </div>
                  <input type="number" class="form-control" id="price_per_night" name="price_per_night"
                    value="<?php echo htmlspecialchars($old['price_per_night'] ?? ''); ?>"
                    step="0.01" min="0" required>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="capacity" class="form-label">Capacity *</label>
                <select class="form-control" id="capacity" name="capacity" required>
                  <option value="">Select Capacity</option>
                  <?php for ($i = 1; $i <= 6; $i++): ?>
                    <option value="<?php echo $i; ?>"
                      <?php echo ($old['capacity'] ?? '') == $i ? 'selected' : ''; ?>>
                      <?php echo $i; ?> person<?php echo $i > 1 ? 's' : ''; ?>
                    </option>
                  <?php endfor; ?>
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
                        <?php echo isset($old['amenities']) && in_array($amenity, $old['amenities']) ? 'checked' : ''; ?>>
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
                <option value="available" <?php echo ($old['status'] ?? 'available') == 'available' ? 'selected' : ''; ?>>Available</option>
                <option value="occupied" <?php echo ($old['status'] ?? '') == 'occupied' ? 'selected' : ''; ?>>Occupied</option>
                <option value="maintenance" <?php echo ($old['status'] ?? '') == 'maintenance' ? 'selected' : ''; ?>>Under Maintenance</option>
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
              <i class="fas fa-dollar-sign text-success mr-2"></i>
              Price should be competitive with market rates
            </li>
            <li class="mb-2">
              <i class="fas fa-users text-info mr-2"></i>
              Capacity should match room size
            </li>
            <li class="mb-2">
              <i class="fas fa-star text-warning mr-2"></i>
              Select relevant amenities for the room type
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
            <p id="previewPrice" class="text-muted mb-2">Price: <span class="text-success">$0.00/night</span></p>
            <p id="previewCapacity" class="text-muted mb-3">Capacity: <span class="text-primary">- persons</span></p>
            <div id="previewAmenities" class="text-left">
              <small class="text-muted">Amenities will appear here</small>
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
    const typeSelect = document.getElementById('type');
    const priceInput = document.getElementById('price_per_night');
    const capacitySelect = document.getElementById('capacity');
    const amenitiesCheckboxes = document.querySelectorAll('input[name="amenities[]"]');
    const statusSelect = document.getElementById('status');

    const previewRoomNumber = document.getElementById('previewRoomNumber');
    const previewType = document.querySelector('#previewType span');
    const previewPrice = document.querySelector('#previewPrice span');
    const previewCapacity = document.querySelector('#previewCapacity span');
    const previewAmenities = document.getElementById('previewAmenities');
    const previewIcon = document.querySelector('.preview-icon i');

    function updatePreview() {
      // Room number
      previewRoomNumber.textContent = roomNumberInput.value ? `Room ${roomNumberInput.value}` : 'Room #';

      // Type
      previewType.textContent = typeSelect.value ? typeSelect.options[typeSelect.selectedIndex].text : '-';

      // Price
      if (priceInput.value) {
        previewPrice.textContent = `$${parseFloat(priceInput.value).toFixed(2)}/night`;
        previewPrice.className = 'text-success';
      } else {
        previewPrice.textContent = '$0.00/night';
        previewPrice.className = 'text-muted';
      }

      // Capacity
      if (capacitySelect.value) {
        const capacity = capacitySelect.options[capacitySelect.selectedIndex].text;
        previewCapacity.textContent = capacity;
        previewCapacity.className = 'text-primary';
      } else {
        previewCapacity.textContent = '- persons';
        previewCapacity.className = 'text-muted';
      }

      // Amenities
      const selectedAmenities = Array.from(amenitiesCheckboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.value);

      if (selectedAmenities.length > 0) {
        previewAmenities.innerHTML = `
                <small class="text-primary">Selected Amenities:</small><br>
                <div class="mt-1">
                    ${selectedAmenities.map(amenity =>
                        `<span class="badge badge-light border mr-1 mb-1">${amenity}</span>`
                    ).join('')}
                </div>
            `;
      } else {
        previewAmenities.innerHTML = '<small class="text-muted">No amenities selected</small>';
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
        default:
          previewIcon.className = 'fas fa-door-closed fa-3x text-gray-300';
      }
    }

    // Add event listeners
    [roomNumberInput, typeSelect, priceInput, capacitySelect, statusSelect].forEach(element => {
      element.addEventListener('input', updatePreview);
      element.addEventListener('change', updatePreview);
    });

    amenitiesCheckboxes.forEach(checkbox => {
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

      // Check price
      if (parseFloat(priceInput.value) <= 0) {
        alert('Price must be greater than 0');
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

<?php
require_once '../../layout/admin-footer.php';
?>
