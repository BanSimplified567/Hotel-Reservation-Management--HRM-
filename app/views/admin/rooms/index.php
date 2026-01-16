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
    <div class="col-xl-2 col-md-4 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Total Rooms</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalRooms; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-door-closed fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Available</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $availableCount ?? 0; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-door-open fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Occupied</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $occupiedCount ?? 0; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-bed fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                Maintenance</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $maintenanceCount ?? 0; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-tools fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                Cleaning</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $cleaningCount ?? 0; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-broom fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
      <div class="card border-left-secondary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                Reserved</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $reservedCount ?? 0; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Search and Filter Card -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Search & Filter</h6>
    </div>
    <div class="card-body">
      <form method="GET" action="index.php" class="row">
        <input type="hidden" name="action" value="admin/rooms">

        <div class="col-md-3 mb-3">
          <input type="text" name="search" class="form-control" placeholder="Search room number or description"
            value="<?php echo htmlspecialchars($search ?? ''); ?>">
        </div>

        <div class="col-md-2 mb-3">
          <select name="type" class="form-control">
            <option value="">All Types</option>
            <?php foreach ($roomTypes as $roomType): ?>
              <option value="<?php echo htmlspecialchars($roomType); ?>"
                <?php echo $type == $roomType ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($roomType); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-2 mb-3">
          <select name="status" class="form-control">
            <option value="">All Status</option>
            <option value="available" <?php echo $status == 'available' ? 'selected' : ''; ?>>Available</option>
            <option value="occupied" <?php echo $status == 'occupied' ? 'selected' : ''; ?>>Occupied</option>
            <option value="maintenance" <?php echo $status == 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
            <option value="cleaning" <?php echo $status == 'cleaning' ? 'selected' : ''; ?>>Cleaning</option>
            <option value="reserved" <?php echo $status == 'reserved' ? 'selected' : ''; ?>>Reserved</option>
          </select>
        </div>

        <div class="col-md-3 mb-3">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">Floor</span>
            </div>
            <input type="number" name="floor" class="form-control" placeholder="Specific floor"
              value="<?php echo htmlspecialchars($_GET['floor'] ?? ''); ?>" min="1" max="20">
          </div>
        </div>

        <div class="col-md-2 mb-3">
          <div class="d-flex">
            <button type="submit" class="btn btn-primary mr-2">
              <i class="fas fa-search"></i> Search
            </button>
            <a href="index.php?action=admin/rooms" class="btn btn-secondary">
              <i class="fas fa-redo"></i>
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Rooms Table Card -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <h6 class="m-0 font-weight-bold text-primary">Rooms List</h6>
      <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
          data-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-cog"></i> Bulk Actions
        </button>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="#"><i class="fas fa-toggle-on"></i> Set All Available</a>
          <a class="dropdown-item" href="#"><i class="fas fa-toggle-off"></i> Set All Maintenance</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#"><i class="fas fa-file-export"></i> Export to CSV</a>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Room #</th>
              <th>Type</th>
              <th>Floor</th>
              <th>View</th>
              <th>Description</th>
              <th>Features</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($rooms)): ?>
              <?php foreach ($rooms as $room): ?>
                <tr>
                  <td>
                    <strong><?php echo htmlspecialchars($room['room_number']); ?></strong>
                  </td>
                  <td>
                    <span class="badge badge-info"><?php echo htmlspecialchars($room['room_type'] ?? 'Unknown'); ?></span>
                  </td>
                  <td><?php echo $room['floor']; ?></td>
                  <td>
                    <span class="badge badge-secondary"><?php echo ucfirst($room['view_type'] ?? 'city'); ?></span>
                  </td>
                  <td>
                    <?php
                    $description = htmlspecialchars($room['description'] ?? '');
                    echo strlen($description) > 50 ? substr($description, 0, 50) . '...' : $description;
                    ?>
                  </td>
                  <td>
                    <?php
                    $features = json_decode($room['features'] ?? '[]', true);
                    if (!empty($features)) {
                      if (isset($features['bed'])) {
                        echo '<span class="badge badge-light border mr-1">Bed: ' . ucfirst($features['bed']) . '</span>';
                      }
                      if (isset($features['balcony']) && $features['balcony']) {
                        echo '<span class="badge badge-light border mr-1">Balcony</span>';
                      }
                      if (isset($features['private_pool']) && $features['private_pool']) {
                        echo '<span class="badge badge-light border mr-1">Private Pool</span>';
                      }
                    } else {
                      echo '<span class="text-muted">None</span>';
                    }
                    ?>
                  </td>
                  <td>
                    <span class="badge badge-<?php
                                              echo $room['status'] == 'available' ? 'success' : ($room['status'] == 'occupied' ? 'warning' : ($room['status'] == 'maintenance' ? 'danger' : ($room['status'] == 'cleaning' ? 'info' : 'secondary')));
                                              ?>">
                      <?php echo ucfirst($room['status']); ?>
                    </span>
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <a href="index.php?action=admin/rooms&sub_action=view&id=<?php echo $room['id']; ?>"
                        class="btn btn-sm btn-info" title="View">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="index.php?action=admin/rooms&sub_action=edit&id=<?php echo $room['id']; ?>"
                        class="btn btn-sm btn-warning" title="Edit">
                        <i class="fas fa-edit"></i>
                      </a>
                      <button type="button" class="btn btn-sm btn-primary view-availability"
                        data-id="<?php echo $room['id']; ?>"
                        data-room="<?php echo htmlspecialchars($room['room_number']); ?>"
                        title="View Availability">
                        <i class="fas fa-calendar-alt"></i>
                      </button>
                      <button type="button" class="btn btn-sm btn-danger delete-room"
                        data-id="<?php echo $room['id']; ?>"
                        data-room="<?php echo htmlspecialchars($room['room_number']); ?>"
                        title="Delete">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center">No rooms found</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation">
          <ul class="pagination justify-content-center">
            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
              <a class="page-link" href="index.php?action=admin/rooms&page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($type) ? '&type=' . urlencode($type) : ''; ?><?php echo !empty($status) ? '&status=' . $status : ''; ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="index.php?action=admin/rooms&page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($type) ? '&type=' . urlencode($type) : ''; ?><?php echo !empty($status) ? '&status=' . $status : ''; ?>">
                  <?php echo $i; ?>
                </a>
              </li>
            <?php endfor; ?>

            <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
              <a class="page-link" href="index.php?action=admin/rooms&page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($type) ? '&type=' . urlencode($type) : ''; ?><?php echo !empty($status) ? '&status=' . $status : ''; ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          </ul>
        </nav>
      <?php endif; ?>
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // View availability
    document.querySelectorAll('.view-availability').forEach(button => {
      button.addEventListener('click', function() {
        const roomId = this.getAttribute('data-id');
        const roomNumber = this.getAttribute('data-room');

        document.getElementById('roomTitle').textContent = `Room ${roomNumber} - Availability`;
        loadAvailabilityCalendar(roomId);
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
    function loadAvailabilityCalendar(roomId) {
      // This would normally make an AJAX call to get availability data
      const calendarEl = document.getElementById('availabilityCalendar');

      // For demo, show a simple calendar
      const today = new Date();
      const month = today.getMonth();
      const year = today.getFullYear();

      // Generate simple calendar (in production, use FullCalendar or similar)
      calendarEl.innerHTML = `
            <div class="text-center">
                <h5>${getMonthName(month)} ${year}</h5>
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
                        <tbody id="calendarBody"></tbody>
                    </table>
                </div>
            </div>
        `;

      // Generate calendar days (simplified)
      const daysInMonth = new Date(year, month + 1, 0).getDate();
      const firstDay = new Date(year, month, 1).getDay();

      let calendarHTML = '';
      let day = 1;

      for (let i = 0; i < 6; i++) {
        calendarHTML += '<tr>';
        for (let j = 0; j < 7; j++) {
          if (i === 0 && j < firstDay) {
            calendarHTML += '<td></td>';
          } else if (day > daysInMonth) {
            calendarHTML += '<td></td>';
          } else {
            const status = Math.random() > 0.7 ? 'booked' : 'available';
            calendarHTML += `<td class="calendar-day ${status}">${day}</td>`;
            day++;
          }
        }
        calendarHTML += '</tr>';
        if (day > daysInMonth) break;
      }

      document.getElementById('calendarBody').innerHTML = calendarHTML;
    }

    function getMonthName(month) {
      const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
      ];
      return months[month];
    }
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
    .calendar-day.available {
        background-color: #d4edda;
    }
    .calendar-day.booked {
        background-color: #f8d7da;
        color: #721c24;
    }
    .calendar-day.maintenance {
        background-color: #fff3cd;
        color: #856404;
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
`;
  document.head.appendChild(style);
</script>
