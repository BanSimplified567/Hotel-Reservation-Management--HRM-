<?php
// No need for full HTML structure - BaseController handles layout
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2"><?php echo $data['page_title']; ?></h1>
  <div class="btn-toolbar mb-2 mb-md-0">
    <a href="?route=admin/services&sub_action=create" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> Add New Service
    </a>
  </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['success'];
    unset($_SESSION['success']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['error'];
    unset($_SESSION['error']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<!-- Search and Filter Form -->
<div class="search-form card mb-4">
  <div class="card-body">
    <form method="GET" action="" class="row g-3">
      <input type="hidden" name="route" value="admin/services">

      <div class="col-md-4">
        <input type="text" class="form-control" name="search"
          placeholder="Search by name or description"
          value="<?php echo htmlspecialchars($data['search'] ?? ''); ?>">
      </div>

      <div class="col-md-3">
        <select class="form-select" name="status">
          <option value="">All Status</option>
          <option value="active" <?php echo ($data['status'] ?? '') == 'active' ? 'selected' : ''; ?>>Active</option>
          <option value="inactive" <?php echo ($data['status'] ?? '') == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
        </select>
      </div>

      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-search"></i> Search
        </button>
      </div>

      <div class="col-md-2">
        <a href="?route=admin/services" class="btn btn-outline-secondary w-100">
          <i class="bi bi-x-circle"></i> Clear
        </a>
      </div>
    </form>
  </div>
</div>

<!-- Services Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Services List</h5>
    <p class="text-muted mb-0">
      Showing <?php echo ($data['page'] - 1) * 15 + 1; ?> to
      <?php echo min($data['page'] * 15, $data['totalServices']); ?> of
      <?php echo $data['totalServices']; ?> services
    </p>
  </div>

  <div class="card-body">
    <?php if (empty($data['services'])): ?>
      <div class="alert alert-info text-center">
        <i class="bi bi-info-circle"></i> No services found.
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Description</th>
              <th>Price</th>
              <th>Status</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data['services'] as $service): ?>
              <?php
              // Debug: Uncomment to see what's in the service array
              // echo '<pre>'; print_r($service); echo '</pre>';

              // Get status with a default value
              $status = $service['status'] ?? 'active'; // Default to 'active' if not set
              $statusClass = $status == 'active' ? 'success' : 'danger';
              $statusText = ucfirst($status);
              ?>
              <tr>
                <td><?php echo $service['id'] ?? ''; ?></td>
                <td><?php echo htmlspecialchars($service['name'] ?? ''); ?></td>
                <td><?php
                    $description = $service['description'] ?? '';
                    echo htmlspecialchars(substr($description, 0, 50)) .
                      (strlen($description) > 50 ? '...' : '');
                    ?></td>
                <td>$<?php echo isset($service['price']) ? number_format($service['price'], 2) : '0.00'; ?></td>
                <td>
                  <span class="badge bg-<?php echo $statusClass; ?>">
                    <?php echo $statusText; ?>
                  </span>
                </td>
                <td><?php
                    if (isset($service['created_at'])) {
                      echo date('M d, Y', strtotime($service['created_at']));
                    } else {
                      echo 'N/A';
                    }
                    ?></td>
                <td class="text-nowrap">
                  <div class="btn-group btn-group-sm" role="group">
                    <!-- View Button -->
                    <a href="?route=admin/services&sub_action=view&id=<?php echo $service['id'] ?? ''; ?>"
                      class="btn btn-outline-info" title="View">
                      <i class="bi bi-eye"></i>
                    </a>

                    <!-- Edit Button -->
                    <a href="?route=admin/services&sub_action=edit&id=<?php echo $service['id'] ?? ''; ?>"
                      class="btn btn-outline-primary" title="Edit">
                      <i class="bi bi-pencil"></i>
                    </a>

                    <!-- Toggle Status Button -->
                    <a href="?route=admin/services&sub_action=toggle-status&id=<?php echo $service['id'] ?? ''; ?>"
                      class="btn btn-outline-warning" title="Toggle Status"
                      onclick="return confirm('Are you sure you want to <?php echo $status == 'active' ? 'deactivate' : 'activate'; ?> this service?')">
                      <i class="bi bi-power"></i>
                    </a>

                    <?php if ($_SESSION['role'] == 'admin'): ?>
                      <!-- Delete Button -->
                      <a href="?route=admin/services&sub_action=delete&id=<?php echo $service['id'] ?? ''; ?>"
                        class="btn btn-outline-danger" title="Delete"
                        onclick="return confirm('Are you sure you want to delete this service? This action cannot be undone.')">
                        <i class="bi bi-trash"></i>
                      </a>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if ($data['totalPages'] > 1): ?>
        <nav aria-label="Page navigation">
          <ul class="pagination justify-content-center mt-4">
            <li class="page-item <?php echo $data['page'] <= 1 ? 'disabled' : ''; ?>">
              <a class="page-link"
                href="?route=admin/services&page=<?php echo $data['page'] - 1; ?>&search=<?php echo urlencode($data['search']); ?>&status=<?php echo urlencode($data['status']); ?>">
                Previous
              </a>
            </li>

            <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
              <li class="page-item <?php echo $i == $data['page'] ? 'active' : ''; ?>">
                <a class="page-link"
                  href="?route=admin/services&page=<?php echo $i; ?>&search=<?php echo urlencode($data['search']); ?>&status=<?php echo urlencode($data['status']); ?>">
                  <?php echo $i; ?>
                </a>
              </li>
            <?php endfor; ?>

            <li class="page-item <?php echo $data['page'] >= $data['totalPages'] ? 'disabled' : ''; ?>">
              <a class="page-link"
                href="?route=admin/services&page=<?php echo $data['page'] + 1; ?>&search=<?php echo urlencode($data['search']); ?>&status=<?php echo urlencode($data['status']); ?>">
                Next
              </a>
            </li>
          </ul>
        </nav>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<script>
  // Auto-dismiss alerts after 5 seconds
  setTimeout(function() {
    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
      var bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    });
  }, 5000);
</script>
