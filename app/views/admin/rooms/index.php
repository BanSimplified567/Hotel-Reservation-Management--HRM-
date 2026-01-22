<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Room Management</h1>
    <a href="index.php?action=admin/rooms&sub_action=create" class="btn btn-primary shadow-sm">
      <i class="fas fa-plus fa-sm text-white-50"></i> Add New Room
    </a>
  </div>

  <!-- Statistics Cards -->
  <div class="row mb-4">
    <div class="col-xl-2 col-md-4 col-6 mb-4">
      <div class="card border-left-primary shadow h-100">
        <div class="card-body p-3">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Total Rooms</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalRooms; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-door-closed fa-lg text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6 mb-4">
      <div class="card border-left-success shadow h-100">
        <div class="card-body p-3">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Available</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $availableCount ?? 0; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-door-open fa-lg text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6 mb-4">
      <div class="card border-left-warning shadow h-100">
        <div class="card-body p-3">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Occupied</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $occupiedCount ?? 0; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-bed fa-lg text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6 mb-4">
      <div class="card border-left-danger shadow h-100">
        <div class="card-body p-3">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                Maintenance</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $maintenanceCount ?? 0; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-tools fa-lg text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6 mb-4">
      <div class="card border-left-info shadow h-100">
        <div class="card-body p-3">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                Cleaning</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $cleaningCount ?? 0; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-broom fa-lg text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6 mb-4">
      <div class="card border-left-secondary shadow h-100">
        <div class="card-body p-3">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                Reserved</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $reservedCount ?? 0; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-calendar-check fa-lg text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Search and Filter Card -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <h6 class="m-0 font-weight-bold text-primary">Search & Filter</h6>
      <button class="btn btn-sm btn-outline-secondary" type="button" data-toggle="collapse"
        data-target="#filterCollapse" aria-expanded="true" aria-controls="filterCollapse">
        <i class="fas fa-filter"></i>
      </button>
    </div>
    <div class="collapse show" id="filterCollapse">
      <div class="card-body">
        <form method="GET" action="index.php" class="row g-3">
          <input type="hidden" name="action" value="admin/rooms">

          <div class="col-lg-3 col-md-6">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
              <input type="text" name="search" class="form-control"
                placeholder="Search room or description"
                value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>
          </div>

          <div class="col-lg-2 col-md-6">
            <select name="type" class="form-control">
              <option value="">All Types</option>
              <?php foreach ($roomTypes as $roomType): ?>
                <option value="<?php echo htmlspecialchars($roomType['name']); ?>"
                  <?php echo ($type ?? '') == $roomType['name'] ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($roomType['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-lg-2 col-md-6">
            <select name="status" class="form-control">
              <option value="">All Status</option>
              <option value="available" <?php echo $status == 'available' ? 'selected' : ''; ?>>Available</option>
              <option value="occupied" <?php echo $status == 'occupied' ? 'selected' : ''; ?>>Occupied</option>
              <option value="maintenance" <?php echo $status == 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
              <option value="cleaning" <?php echo $status == 'cleaning' ? 'selected' : ''; ?>>Cleaning</option>
              <option value="reserved" <?php echo $status == 'reserved' ? 'selected' : ''; ?>>Reserved</option>
            </select>
          </div>

          <div class="col-lg-2 col-md-6">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
              </div>
              <input type="number" name="floor" class="form-control" placeholder="Floor"
                value="<?php echo htmlspecialchars($floor ?? ''); ?>" min="1" max="20">
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="btn-group w-100" role="group">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-search mr-1"></i> Search
              </button>
              <a href="index.php?action=admin/rooms" class="btn btn-outline-secondary">
                <i class="fas fa-redo"></i>
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Rooms Table Card -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
      <h6 class="m-0 font-weight-bold text-primary mb-2 mb-md-0">Rooms List</h6>
      <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
          id="bulkActionsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-cog mr-1"></i> Bulk Actions
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="bulkActionsDropdown">
          <a class="dropdown-item" href="#"><i class="fas fa-toggle-on text-success mr-2"></i>Set Available</a>
          <a class="dropdown-item" href="#"><i class="fas fa-toggle-off text-danger mr-2"></i>Set Maintenance</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#"><i class="fas fa-file-export text-primary mr-2"></i>Export to CSV</a>
        </div>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="thead-light">
            <tr>
              <th>Room #</th>
              <th>Type</th>
              <th class="d-none d-md-table-cell">Floor</th>
              <th class="d-none d-lg-table-cell">View</th>
              <th class="d-none d-lg-table-cell">Description</th>
              <th class="d-none d-xl-table-cell">Features</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($rooms)): ?>
              <?php foreach ($rooms as $room): ?>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="mr-2">
                        <i class="fas
                          <?php echo $room['status'] == 'available' ? 'fa-door-open text-success' : ($room['status'] == 'occupied' ? 'fa-bed text-warning' : ($room['status'] == 'maintenance' ? 'fa-tools text-danger' : ($room['status'] == 'cleaning' ? 'fa-broom text-info' :
                            'fa-calendar-check text-secondary'))); ?>"></i>
                      </div>
                      <div>
                        <strong><?php echo htmlspecialchars($room['room_number']); ?></strong>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="badge text-black badge-info"><?php echo htmlspecialchars($room['room_type'] ?? 'Unknown'); ?></span>
                    <small class="d-block text-muted">₱<?php echo number_format($room['room_type_price'] ?? 0, 0); ?>/night</small>
                  </td>
                  <td class="d-none d-md-table-cell">
                    <span class="badge text-black badge-light border">Floor <?php echo $room['floor']; ?></span>
                  </td>
                  <td class="d-none d-lg-table-cell">
                    <span class="badge text-black badge-light border"><?php echo ucfirst($room['view_type'] ?? 'city'); ?> View</span>
                  </td>
                  <td class="d-none d-lg-table-cell">
                    <?php
                    $description = htmlspecialchars($room['description'] ?? '');
                    if (!empty($description)) {
                      echo strlen($description) > 30 ? substr($description, 0, 30) . '...' : $description;
                    } else {
                      echo '<span class="text-muted">No description</span>';
                    }
                    ?>
                  </td>
                  <td class="d-none d-xl-table-cell">
                    <?php
                    $features = json_decode($room['features'] ?? '[]', true);
                    if (!empty($features)) {
                      $featureBadges = [];
                      if (isset($features['bed'])) {
                        $featureBadges[] = '<span class="badge text-black badge-light border mr-1 mb-1">' . ucfirst($features['bed']) . ' Bed</span>';
                      }
                      if (isset($features['balcony']) && $features['balcony']) {
                        $featureBadges[] = '<span class="badge text-black badge-light border mr-1 mb-1">Balcony</span>';
                      }
                      if (isset($features['private_pool']) && $features['private_pool']) {
                        $featureBadges[] = '<span class="badge text-black badge-light border mr-1 mb-1">Pool</span>';
                      }
                      echo implode(' ', $featureBadges);
                    } else {
                      echo '<span class="text-muted">-</span>';
                    }
                    ?>
                  </td>
                  <td>
                    <span class="badge text-black badge-pill badge-<?php
                                                                    echo $room['status'] == 'available' ? 'success' : ($room['status'] == 'occupied' ? 'warning' : ($room['status'] == 'maintenance' ? 'danger' : ($room['status'] == 'cleaning' ? 'info' : 'secondary')));
                                                                    ?>">
                      <?php echo ucfirst($room['status']); ?>
                    </span>
                  </td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group">
                      <a href="index.php?action=admin/rooms&sub_action=view&id=<?php echo $room['id']; ?>"
                        class="btn btn-outline-info" data-toggle="tooltip" title="View Details">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="index.php?action=admin/rooms&sub_action=edit&id=<?php echo $room['id']; ?>"
                        class="btn btn-outline-warning" data-toggle="tooltip" title="Edit Room">
                        <i class="fas fa-edit"></i>
                      </a>
                      <button type="button" class="btn btn-outline-primary view-availability"
                        data-id="<?php echo $room['id']; ?>"
                        data-room="<?php echo htmlspecialchars($room['room_number']); ?>"
                        data-toggle="tooltip" title="View Availability">
                        <i class="fas fa-calendar-alt"></i>
                      </button>
                      <button type="button" class="btn btn-outline-danger delete-room"
                        data-id="<?php echo $room['id']; ?>"
                        data-room="<?php echo htmlspecialchars($room['room_number']); ?>"
                        data-toggle="tooltip" title="Delete Room">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center py-5">
                  <div class="text-muted">
                    <i class="fas fa-door-closed fa-3x mb-3"></i>
                    <h5>No rooms found</h5>
                    <p class="mb-0">Try adjusting your search filters</p>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
        <div class="card-footer bg-white border-top">
          <nav aria-label="Page navigation">
            <div class="d-flex justify-content-between align-items-center flex-column flex-md-row">
              <div class="mb-3 mb-md-0">
                <small class="text-muted">
                  Showing <?php echo (($page - 1) * $perPage) + 1; ?> to
                  <?php echo min($page * $perPage, $totalRooms); ?> of <?php echo $totalRooms; ?> rooms
                </small>
              </div>

              <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                  <a class="page-link" href="index.php?action=admin/rooms&page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($type) ? '&type=' . urlencode($type) : ''; ?><?php echo !empty($status) ? '&status=' . $status : ''; ?><?php echo !empty($floor) ? '&floor=' . $floor : ''; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>

                <?php
                $startPage = max(1, $page - 2);
                $endPage = min($totalPages, $startPage + 4);
                $startPage = max(1, $endPage - 4);
                ?>

                <?php if ($startPage > 1): ?>
                  <li class="page-item">
                    <a class="page-link" href="index.php?action=admin/rooms&page=1<?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($type) ? '&type=' . urlencode($type) : ''; ?><?php echo !empty($status) ? '&status=' . $status : ''; ?><?php echo !empty($floor) ? '&floor=' . $floor : ''; ?>">1</a>
                  </li>
                  <?php if ($startPage > 2): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                  <?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                  <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="index.php?action=admin/rooms&page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($type) ? '&type=' . urlencode($type) : ''; ?><?php echo !empty($status) ? '&status=' . $status : ''; ?><?php echo !empty($floor) ? '&floor=' . $floor : ''; ?>">
                      <?php echo $i; ?>
                    </a>
                  </li>
                <?php endfor; ?>

                <?php if ($endPage < $totalPages): ?>
                  <?php if ($endPage < $totalPages - 1): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                  <?php endif; ?>
                  <li class="page-item">
                    <a class="page-link" href="index.php?action=admin/rooms&page=<?php echo $totalPages; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($type) ? '&type=' . urlencode($type) : ''; ?><?php echo !empty($status) ? '&status=' . $status : ''; ?><?php echo !empty($floor) ? '&floor=' . $floor : ''; ?>"><?php echo $totalPages; ?></a>
                  </li>
                <?php endif; ?>

                <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                  <a class="page-link" href="index.php?action=admin/rooms&page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($type) ? '&type=' . urlencode($type) : ''; ?><?php echo !empty($status) ? '&status=' . $status : ''; ?><?php echo !empty($floor) ? '&floor=' . $floor : ''; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
            </div>
          </nav>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Availability Calendar Modal -->
<div class="modal fade" id="availabilityModal" tabindex="-1" role="dialog" aria-labelledby="availabilityModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="availabilityModalLabel">
          <i class="fas fa-calendar-alt mr-2"></i>Room Availability
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="d-flex align-items-center mb-4">
          <div class="room-icon mr-3">
            <i class="fas fa-door-open fa-2x text-primary"></i>
          </div>
          <div>
            <h5 id="roomTitle" class="mb-0"></h5>
            <small class="text-muted">Availability for the next 30 days</small>
          </div>
        </div>

        <div id="availabilityCalendar" class="mb-4"></div>

        <div class="availability-legend">
          <div class="row text-center">
            <div class="col-4">
              <div class="d-flex align-items-center justify-content-center mb-2">
                <div class="availability-dot available mr-2"></div>
                <span>Available</span>
              </div>
            </div>
            <div class="col-4">
              <div class="d-flex align-items-center justify-content-center mb-2">
                <div class="availability-dot booked mr-2"></div>
                <span>Booked</span>
              </div>
            </div>
            <div class="col-4">
              <div class="d-flex align-items-center justify-content-center mb-2">
                <div class="availability-dot maintenance mr-2"></div>
                <span>Maintenance</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">
          <i class="fas fa-exclamation-triangle mr-2"></i>Confirm Delete
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <div class="py-3">
          <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
          <h5>Delete Room?</h5>
          <p class="mb-0">Are you sure you want to delete room:</p>
          <h4 class="text-danger my-2" id="deleteRoomNumber"></h4>
        </div>
        <div class="alert alert-warning">
          <small>
            <i class="fas fa-exclamation-circle mr-1"></i>
            This action cannot be undone. All reservations for this room will also be deleted.
          </small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i> Cancel
        </button>
        <a href="#" id="confirmDelete" class="btn btn-danger">
          <i class="fas fa-trash mr-1"></i> Delete Room
        </a>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    $(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });

    // View availability
    document.querySelectorAll('.view-availability').forEach(button => {
      button.addEventListener('click', function() {
        const roomId = this.getAttribute('data-id');
        const roomNumber = this.getAttribute('data-room');

        document.getElementById('roomTitle').textContent = `Room ${roomNumber}`;
        loadAvailabilityCalendar(roomId, roomNumber);
        $('#availabilityModal').modal('show');
      });
    });

    // Delete confirmation
    document.querySelectorAll('.delete-room').forEach(button => {
      button.addEventListener('click', function() {
        const roomId = this.getAttribute('data-id');
        const roomNumber = this.getAttribute('data-room');

        document.getElementById('deleteRoomNumber').textContent = roomNumber;
        document.getElementById('confirmDelete').href =
          `index.php?action=admin/rooms&sub_action=delete&id=${roomId}`;

        $('#deleteModal').modal('show');
      });
    });

    // Load availability calendar
    function loadAvailabilityCalendar(roomId, roomNumber) {
      const calendarEl = document.getElementById('availabilityCalendar');

      // Show loading state
      calendarEl.innerHTML = `
        <div class="text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
          </div>
          <p class="mt-2 text-muted">Loading availability data...</p>
        </div>
      `;

      // Simulate API call
      setTimeout(() => {
        // Generate a simple calendar for demo
        const today = new Date();
        const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        const months = ['January', 'February', 'March', 'April', 'May', 'June',
          'July', 'August', 'September', 'October', 'November', 'December'
        ];

        let calendarHTML = '';

        // Generate next 30 days
        for (let i = 0; i < 5; i++) { // 5 weeks
          calendarHTML += '<div class="week-row d-flex mb-2">';
          for (let j = 0; j < 7; j++) {
            const date = new Date(today);
            date.setDate(today.getDate() + (i * 7) + j);

            if (date < today) continue;
            if (i * 7 + j >= 30) break;

            const dayNum = date.getDate();
            const dayName = days[date.getDay()];
            const monthName = months[date.getMonth()];
            const isWeekend = date.getDay() === 0 || date.getDay() === 6;

            // Random status for demo
            const rand = Math.random();
            let statusClass = 'available';
            let statusText = 'Available';

            if (rand < 0.3) {
              statusClass = 'booked';
              statusText = 'Booked';
            } else if (rand < 0.4) {
              statusClass = 'maintenance';
              statusText = 'Maintenance';
            }

            calendarHTML += `
              <div class="day-cell text-center flex-fill mx-1">
                <div class="day-header small text-muted mb-1">${dayName}</div>
                <div class="day-number ${statusClass} rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1"
                     data-toggle="tooltip" title="${statusText} - ${monthName} ${dayNum}">
                  ${dayNum}
                </div>
                <div class="day-status small">${statusText}</div>
              </div>
            `;
          }
          calendarHTML += '</div>';
        }

        calendarEl.innerHTML = calendarHTML;

        // Re-initialize tooltips for new elements
        $(function() {
          $('[data-toggle="tooltip"]').tooltip();
        });
      }, 500);
    }
  });
</script>

<style>
  .availability-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
  }

  .availability-dot.available {
    background-color: #28a745;
  }

  .availability-dot.booked {
    background-color: #dc3545;
  }

  .availability-dot.maintenance {
    background-color: #ffc107;
  }

  .day-cell {
    min-width: 60px;
  }

  .day-number {
    width: 36px;
    height: 36px;
    font-weight: bold;
    cursor: default;
  }

  .day-number.available {
    background-color: #d4edda;
    color: #155724;
    border: 2px solid #c3e6cb;
  }

  .day-number.booked {
    background-color: #f8d7da;
    color: #721c24;
    border: 2px solid #f5c6cb;
  }

  .day-number.maintenance {
    background-color: #fff3cd;
    color: #856404;
    border: 2px solid #ffeaa7;
  }

  .day-status {
    font-size: 0.75rem;
    height: 18px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  /* Responsive table adjustments */
  @media (max-width: 768px) {
    .btn-group-sm .btn {
      padding: 0.25rem 0.4rem;
      font-size: 0.75rem;
    }
  }

  @media (max-width: 576px) {
    .stat-card .h5 {
      font-size: 1.1rem;
    }

    .btn-group-sm .btn {
      padding: 0.2rem 0.3rem;
      font-size: 0.7rem;
    }
  }
</style>
