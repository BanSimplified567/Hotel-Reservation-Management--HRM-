<!-- admin/services/create.php -->
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3">
        <h1 class="h3 fw-bold">
            <i class="bi bi-pencil-square me-2"></i>
            <?php echo $data['page_title']; ?>
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="?action=admin/services" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Services
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Create Service Details
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="?action=admin/services&sub_action=create" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="<?php echo htmlspecialchars($_SESSION['old']['name'] ?? ''); ?>"
                                        required maxlength="255" placeholder="Enter service name">
                                    <label for="name">Service Name <span class="text-danger">*</span></label>
                                </div>
                                <div class="form-text text-muted small">Enter a unique service name for identification</div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" id="description" name="description"
                                        style="height: 120px"
                                        placeholder="Enter service description"><?php echo htmlspecialchars($_SESSION['old']['description'] ?? ''); ?></textarea>
                                    <label for="description">Description</label>
                                </div>
                                <div class="form-text text-muted small">Describe what this service includes and any important details</div>
                            </div>

                            <div class="col-md-6">
                                <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-currency-dollar text-primary"></i>
                                    </span>
                                    <input type="number" class="form-control" id="price" name="price"
                                        value="<?php echo $_SESSION['old']['price'] ?? 0; ?>"
                                        step="0.01" min="0" required placeholder="0.00">
                                    <div class="invalid-feedback">
                                        Please enter a valid price.
                                    </div>
                                </div>
                                <div class="form-text text-muted small">Enter the service price in USD</div>
                            </div>

                            <div class="col-md-6">
                                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" id="category" name="category" required>
                                    <?php foreach ($data['categories'] as $cat): ?>
                                        <option value="<?php echo $cat; ?>" <?php echo ($_SESSION['old']['category'] ?? 'other') == $cat ? 'selected' : ''; ?>><?php echo ucfirst($cat); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text text-muted small">Select the service category</div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_available" name="is_available" <?php echo isset($_SESSION['old']['is_available']) ? 'checked' : ''; ?> checked>
                                    <label class="form-check-label" for="is_available">Available</label>
                                </div>
                                <div class="form-text text-muted small">Toggle to make the service available for reservations</div>
                            </div>

                            <div class="col-12 mt-4 pt-3 border-top">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="?action=admin/services" class="btn btn-outline-secondary me-md-2">
                                        <i class="bi bi-x-circle me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-check-circle me-1"></i> Create Service
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
});
<?php unset($_SESSION['old']); ?>
</script>
