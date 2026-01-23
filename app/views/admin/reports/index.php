    <style>
      .report-card {
        transition: transform 0.3s;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }

      .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
      }

      .report-icon {
        font-size: 2rem;
      }

      .nav-tabs .nav-link.active {
        font-weight: bold;
        border-bottom: 3px solid #0d6efd;
      }

      .date-filter {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
      }

      .summary-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
      }
    </style>

    <div class="container-fluid py-4">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
          <i class="bi bi-bar-chart-fill text-primary"></i> <?= htmlspecialchars($reportTitle) ?>
        </h1>
        <div>
          <button class="btn btn-outline-primary" onclick="window.print()">
            <i class="bi bi-printer"></i> Print
          </button>
          <div class="btn-group">
            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
              <i class="bi bi-download"></i> Export
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="?action=admin/reports&type=<?= $report_type ?>&format=excel">
                  <i class="bi bi-file-excel"></i> Excel
                </a></li>
              <li><a class="dropdown-item" href="?action=admin/reports&type=<?= $report_type ?>&format=pdf">
                  <i class="bi bi-file-pdf"></i> PDF
                </a></li>
              <li><a class="dropdown-item" href="?action=admin/reports&sub_action=export&type=<?= $report_type ?>">
                  <i class="bi bi-file-text"></i> CSV
                </a></li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Report Type Navigation -->
      <div class="card mb-4">
        <div class="card-body">
          <ul class="nav nav-tabs">
            <li class="nav-item">
              <a class="nav-link <?= ($report_type == 'revenue') ? 'active' : '' ?>"
                href="?action=admin/reports&type=revenue">
                <i class="bi bi-cash-coin"></i> Revenue
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= ($report_type == 'occupancy') ? 'active' : '' ?>"
                href="?action=admin/reports&type=occupancy">
                <i class="bi bi-house-door"></i> Occupancy
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= ($report_type == 'reservations') ? 'active' : '' ?>"
                href="?action=admin/reports&type=reservations">
                <i class="bi bi-calendar-check"></i> Reservations
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= ($report_type == 'customers') ? 'active' : '' ?>"
                href="?action=admin/reports&type=customers">
                <i class="bi bi-people"></i> Customers
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= ($report_type == 'services') ? 'active' : '' ?>"
                href="?action=admin/reports&type=services">
                <i class="bi bi-cone-striped"></i> Services
              </a>
            </li>
          </ul>
        </div>
      </div>

      <!-- Date Filter -->
      <div class="card date-filter mb-4">
        <div class="card-body">
          <form method="GET" class="row g-3">
            <input type="hidden" name="action" value="admin/reports">
            <input type="hidden" name="type" value="<?= $report_type ?>">

            <div class="col-md-3">
              <label class="form-label">Start Date</label>
              <input type="date" name="start_date" class="form-control"
                value="<?= htmlspecialchars($start_date) ?>" required>
            </div>

            <div class="col-md-3">
              <label class="form-label">End Date</label>
              <input type="date" name="end_date" class="form-control"
                value="<?= htmlspecialchars($end_date) ?>" required>
            </div>

            <div class="col-md-3">
              <label class="form-label">Period</label>
              <select name="period" class="form-select">
                <option value="today" <?= ($period == 'today') ? 'selected' : '' ?>>Today</option>
                <option value="week" <?= ($period == 'week') ? 'selected' : '' ?>>This Week</option>
                <option value="month" <?= ($period == 'month') ? 'selected' : '' ?>>This Month</option>
                <option value="quarter" <?= ($period == 'quarter') ? 'selected' : '' ?>>This Quarter</option>
                <option value="year" <?= ($period == 'year') ? 'selected' : '' ?>>This Year</option>
                <option value="custom" <?= ($period == 'custom') ? 'selected' : '' ?>>Custom</option>
              </select>
            </div>

            <div class="col-md-3 d-flex align-items-end">
              <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-filter"></i> Generate Report
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Summary Statistics -->
      <?php if (!empty($summary)): ?>
        <div class="row mb-4">
          <?php foreach ($summary as $key => $value): ?>
            <div class="col-md-3 col-sm-6 mb-3">
              <div class="card report-card">
                <div class="card-body">
                  <h6 class="card-subtitle mb-2 text-muted text-uppercase small">
                    <?= str_replace('_', ' ', ucfirst($key)) ?>
                  </h6>
                  <h4 class="card-title mb-0">
                    <?php if (is_numeric($value)): ?>
                      <?php if (strpos($key, 'revenue') !== false || strpos($key, 'amount') !== false): ?>
                        ₱<?= number_format($value, 2) ?>
                      <?php elseif (strpos($key, 'rate') !== false): ?>
                        <?= number_format($value, 2) ?>%
                      <?php else: ?>
                        <?= number_format($value) ?>
                      <?php endif; ?>
                    <?php else: ?>
                      <?= htmlspecialchars($value) ?>
                    <?php endif; ?>
                  </h4>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Report Content -->
      <div class="card">
        <div class="card-body">
          <?php
          // Include the specific report view based on type
          $reportView = 'app/views/admin/reports/' . $report_type . '.php';
          if (file_exists($reportView)) {
            include $reportView;
          } else {
            echo '<div class="alert alert-warning">Report view not found for type: ' . htmlspecialchars($report_type) . '</div>';
          }
          ?>
        </div>
      </div>

      <!-- Export Links -->
      <div class="mt-4 text-end">
        <small class="text-muted">
          Report generated on <?= date('F j, Y g:i A') ?>
        </small>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      // Auto-update end date based on period selection
      document.querySelector('select[name="period"]').addEventListener('change', function() {
        const period = this.value;
        const startInput = document.querySelector('input[name="start_date"]');
        const endInput = document.querySelector('input[name="end_date"]');
        const today = new Date();

        switch (period) {
          case 'today':
            startInput.value = today.toISOString().split('T')[0];
            endInput.value = today.toISOString().split('T')[0];
            break;
          case 'week':
            const weekStart = new Date(today.setDate(today.getDate() - today.getDay()));
            const weekEnd = new Date(weekStart);
            weekEnd.setDate(weekStart.getDate() + 6);
            startInput.value = weekStart.toISOString().split('T')[0];
            endInput.value = weekEnd.toISOString().split('T')[0];
            break;
          case 'month':
            const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
            const monthEnd = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            startInput.value = monthStart.toISOString().split('T')[0];
            endInput.value = monthEnd.toISOString().split('T')[0];
            break;
          case 'year':
            const yearStart = new Date(today.getFullYear(), 0, 1);
            const yearEnd = new Date(today.getFullYear(), 11, 31);
            startInput.value = yearStart.toISOString().split('T')[0];
            endInput.value = yearEnd.toISOString().split('T')[0];
            break;
        }
      });
    </script>
