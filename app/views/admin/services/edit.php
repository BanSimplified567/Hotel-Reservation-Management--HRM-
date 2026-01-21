<?php
// Edit service form
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2"><?php echo $data['page_title']; ?></h1>
  <div class="btn-toolbar mb-2 mb-md-0">
    <a href="?route=admin/services" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left"></i> Back to Services
    </a>
  </div>
</div>

<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['error'];
    unset($_SESSION['error']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<!-- Service Info -->
<div class="row mb-4">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h6 class="card-subtitle mb-2 text-muted">Service ID</h6>
        <p class="card-text"><?php echo $data['service']['id']; ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h6 class="card-subtitle mb-2 text-muted">Created</h6>
        <p class="card-text"><?php echo date('M d, Y', strtotime($data['service']['created_at'])); ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h6 class="card-subtitle mb-2 text-muted">Last Updated</h6>
        <p class="card-text">
          <?php echo $data['service']['updated_at'] ? date('M d, Y', strtotime($data['service']['updated_at'])) : 'Never'; ?>
        </p>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Edit Service Details</h5>
  </div>
  <div class="card-body">
    <form method="POST" action="?route=admin/services&sub_action=edit&id=<?php echo $data['service']['id']; ?>">
      <div class="row">
        <div class="col-md-12 mb-3">
          <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="name" name="name"
            value="<?php
                    if (isset($_SESSION['old']['name'])) {
                      echo htmlspecialchars($_SESSION['old']['name']);
                      unset($_SESSION['old']['name']);
                    } else {
                      echo htmlspecialchars($data['service']['name']);
                    }
                    ?>"
            required maxlength="255">
          <div class="form-text">Enter a unique service name</div>
        </div>

        <div class="col-md-12 mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea class="form-control" id="description" name="description"
            rows="4"><?php
                      if (isset($_SESSION['old']['description'])) {
                        echo htmlspecialchars($_SESSION['old']['description']);
                        unset($_SESSION['old']['description']);
                      } else {
                        echo htmlspecialchars($data['service']['description']);
                      }
                      ?></textarea>
          <div class="form-text">Describe what this service includes</div>
        </div>

        <div class="col-md-6 mb-3">
          <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text">$</span>
            <input type="number" class="form-control" id="price" name="price"
              value="<?php
                      if (isset($_SESSION['old']['price'])) {
                        echo $_SESSION['old']['price'];
                        unset($_SESSION['old']['price']);
                      } else {
                        echo $data['service']['price'];
                      }
                      ?>"
              step="0.01" min="0" required>
          </div>
          <div class="form-text">Enter the service price</div>
        </div>

        <div class="col-md-6 mb-3">
          <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
          <select class="form-select" id="status" name="status" required>
            <option value="active" <?php
                                    $status = isset($_SESSION['old']['status']) ? $_SESSION['old']['status'] : $data['service']['status'];
                                    unset($_SESSION['old']['status']);
                                    echo $status == 'active' ? 'selected' : '';
                                    ?>>Active</option>
            <option value="inactive" <?php echo $status == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
          </select>
          <div class="form-text">Active services will be available for reservations</div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-md-6">
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-check-circle"></i> Update Service
          </button>
        </div>
        <div class="col-md-6">
          <a href="?route=admin/services" class="btn btn-outline-secondary w-100">
            <i class="bi bi-x-circle"></i> Cancel
          </a>
        </div>
      </div>
    </form>
  </div>
</div>
