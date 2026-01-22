<div class="container-fluid px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3">
        <h1 class="h3 fw-bold">
            <i class="bi bi-pencil-square me-2"></i>
            <?php echo $data['page_title']; ?>
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="?route=admin/services" class="btn btn-outline-secondary">
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

    <!-- Service Information Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                            <i class="bi bi-tag text-primary"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Service ID</small>
                            <h6 class="mb-0">#<?php echo $data['service']['id']; ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                            <i class="bi bi-calendar-plus text-success"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Created Date</small>
                            <h6 class="mb-0"><?php echo date('M d, Y', strtotime($data['service']['created_at'])); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                            <i class="bi bi-clock-history text-info"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Last Updated</small>
                            <h6 class="mb-0">
                                <?php echo $data['service']['updated_at'] ? date('M d, Y', strtotime($data['service']['updated_at'])) : 'Never'; ?>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Edit Service Details
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="?route=admin/services&sub_action=edit&id=<?php echo $data['service']['id']; ?>" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="<?php
                                                if (isset($_SESSION['old']['name'])) {
                                                    echo htmlspecialchars($_SESSION['old']['name']);
                                                    unset($_SESSION['old']['name']);
                                                } else {
                                                    echo htmlspecialchars($data['service']['name']);
                                                }
                                                ?>"
                                        required maxlength="255" placeholder="Enter service name">
                                    <label for="name">Service Name <span class="text-danger">*</span></label>
                                </div>
                                <div class="form-text text-muted small">Enter a unique service name for identification</div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" id="description" name="description"
                                        style="height: 120px"
                                        placeholder="Enter service description"><?php
                                        if (isset($_SESSION['old']['description'])) {
                                            echo htmlspecialchars($_SESSION['old']['description']);
                                            unset($_SESSION['old']['description']);
                                        } else {
                                            echo htmlspecialchars($data['service']['description']);
                                        }
                                        ?></textarea>
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
                                        value="<?php
                                                if (isset($_SESSION['old']['price'])) {
                                                    echo $_SESSION['old']['price'];
                                                    unset($_SESSION['old']['price']);
                                                } else {
                                                    echo $data['service']['price'];
                                                }
                                                ?>"
                                        step="0.01" min="0" required placeholder="0.00">
                                    <div class="invalid-feedback">
                                        Please enter a valid price.
                                    </div>
                                </div>
                                <div class="form-text text-muted small">Enter the service price in USD</div>
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active" <?php
                                        $status = isset($_SESSION['old']['status']) ? $_SESSION['old']['status'] : $data['service']['status'];
                                        unset($_SESSION['old']['status']);
                                        echo $status == 'active' ? 'selected' : '';
                                        ?>>Active</option>
                                    <option value="inactive" <?php echo $status == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                                <div class="form-text text-muted small">Active services will be available for reservations</div>
                            </div>

                            <div class="col-12 mt-4 pt-3 border-top">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="?route=admin/services" class="btn btn-outline-secondary me-md-2">
                                        <i class="bi bi-x-circle me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-check-circle me-1"></i> Update Service
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
</script>
