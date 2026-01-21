<?php
// View service details (if you want a view page)
// Note: Your controller doesn't have a view method, so you might need to add it
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2"><?php echo $data['page_title']; ?></h1>
  <div class="btn-toolbar mb-2 mb-md-0">
    <a href="?route=admin/services" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left"></i> Back to Services
    </a>
    <div class="btn-group ms-2">
      <a href="?route=admin/services&sub_action=edit&id=<?php echo $data['service']['id']; ?>"
        class="btn btn-outline-primary">
        <i class="bi bi-pencil"></i> Edit
      </a>
      <a href="?route=admin/services&sub_action=toggle-status&id=<?php echo $data['service']['id']; ?>"
        class="btn btn-outline-warning"
        onclick="return confirm('Are you sure you want to <?php echo $data['service']['status'] == 'active' ? 'deactivate' : 'activate'; ?> this service?')">
        <i class="bi bi-power"></i>
        <?php echo $data['service']['status'] == 'active' ? 'Deactivate' : 'Activate'; ?>
      </a>
      <?php if ($_SESSION['role'] == 'admin'): ?>
        <a href="?route=admin/services&sub_action=delete&id=<?php echo $data['service']['id']; ?>"
          class="btn btn-outline-danger"
          onclick="return confirm('Are you sure you want to delete this service? This action cannot be undone.')">
          <i class="bi bi-trash"></i> Delete
        </a>
      <?php endif; ?>
    </div>
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

<div class="row">
  <div class="col-md-8">
    <div class="card mb-4">
      <div class="card-header">
        <h5 class="card-title mb-0">Service Information</h5>
      </div>
      <div class="card-body">
        <h3 class="card-title text-primary"><?php echo htmlspecialchars($data['service']['name']); ?></h3>
        <p class="card-text lead"><?php echo nl2br(htmlspecialchars($data['service']['description'])); ?></p>

        <div class="row mt-4">
          <div class="col-md-6">
            <div class="mb-3">
              <h6 class="text-muted">Price</h6>
              <h4 class="text-success">$<?php echo number_format($data['service']['price'], 2); ?></h4>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <h6 class="text-muted">Status</h6>
              <span class="badge bg-<?php echo $data['service']['status'] == 'active' ? 'success' : 'danger'; ?> fs-6">
                <?php echo ucfirst($data['service']['status']); ?>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Service Details</h5>
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Service ID
            <span class="badge bg-secondary"><?php echo $data['service']['id']; ?></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Created
            <span><?php echo date('M d, Y', strtotime($data['service']['created_at'])); ?></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Last Updated
            <span>
              <?php echo $data['service']['updated_at'] ? date('M d, Y', strtotime($data['service']['updated_at'])) : 'Never'; ?>
            </span>
          </li>
          <li class="list-group-item">
            <div class="d-grid gap-2">
              <a href="?route=admin/services&sub_action=edit&id=<?php echo $data['service']['id']; ?>"
                class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit Service
              </a>
              <a href="?route=admin/services&sub_action=toggle-status&id=<?php echo $data['service']['id']; ?>"
                class="btn btn-warning"
                onclick="return confirm('Are you sure you want to <?php echo $data['service']['status'] == 'active' ? 'deactivate' : 'activate'; ?> this service?')">
                <i class="bi bi-power"></i>
                <?php echo $data['service']['status'] == 'active' ? 'Deactivate' : 'Activate'; ?> Service
              </a>
              <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="?route=admin/services&sub_action=delete&id=<?php echo $data['service']['id']; ?>"
                  class="btn btn-danger"
                  onclick="return confirm('Are you sure you want to delete this service? This action cannot be undone.')">
                  <i class="bi bi-trash"></i> Delete Service
                </a>
              <?php endif; ?>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
