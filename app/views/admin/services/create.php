<?php
// Create service form
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
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Create New Service</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="?route=admin/services&sub_action=create">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="<?php echo htmlspecialchars($_SESSION['old']['name'] ?? ''); unset($_SESSION['old']['name']); ?>"
                           required maxlength="255">
                    <div class="form-text">Enter a unique service name</div>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description"
                              rows="4"><?php echo htmlspecialchars($_SESSION['old']['description'] ?? ''); unset($_SESSION['old']['description']); ?></textarea>
                    <div class="form-text">Describe what this service includes</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control" id="price" name="price"
                               value="<?php echo $_SESSION['old']['price'] ?? '0.00'; unset($_SESSION['old']['price']); ?>"
                               step="0.01" min="0" required>
                    </div>
                    <div class="form-text">Enter the service price</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="active" <?php echo (($_SESSION['old']['status'] ?? 'active') == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo (($_SESSION['old']['status'] ?? 'active') == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                    <div class="form-text">Active services will be available for reservations</div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle"></i> Create Service
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Clear old session data if it exists
    if (window.location.search.indexOf('sub_action=create') === -1) {
        // Clear session storage for old form data
        sessionStorage.removeItem('oldFormData');
    }
});
</script>
