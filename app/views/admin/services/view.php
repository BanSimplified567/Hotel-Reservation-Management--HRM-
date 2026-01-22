<!-- admin/services/view.php -->
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3">
        <h1 class="h3 fw-bold">
            <i class="bi bi-cone-striped me-2"></i>
            <?php echo $data['page_title']; ?>
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="?action=admin/services" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-1"></i> Back to Services
            </a>
            <div class="btn-group">
                <a href="?action=admin/services&sub_action=edit&id=<?php echo $data['service']['id']; ?>"
                    class="btn btn-outline-primary">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <a href="?action=admin/services&sub_action=toggle-status&id=<?php echo $data['service']['id']; ?>"
                    class="btn btn-outline-warning"
                    onclick="return confirm('Are you sure you want to <?php echo $data['service']['status'] == 'active' ? 'deactivate' : 'activate'; ?> this service?')">
                    <i class="bi bi-power me-1"></i>
                    <?php echo $data['service']['status'] == 'active' ? 'Deactivate' : 'Activate'; ?>
                </a>
                <?php if ($_SESSION['role'] == 'admin'): ?>
                    <a href="?action=admin/services&sub_action=delete&id=<?php echo $data['service']['id']; ?>"
                        class="btn btn-outline-danger"
                        onclick="return confirm('Are you sure you want to delete this service? This action cannot be undone.')">
                        <i class="bi bi-trash me-1"></i> Delete
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>Service Information
                    </h5>
                </div>
                <div class="card-body">
                    <h3 class="card-title text-primary mb-4">
                        <?php echo htmlspecialchars($data['service']['name']); ?>
                    </h3>

                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Description</h6>
                        <div class="bg-light p-4 rounded">
                            <?php echo nl2br(htmlspecialchars($data['service']['description'])); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Price</h6>
                                    <h2 class="text-success mb-0">
                                        $<?php echo number_format($data['service']['price'], 2); ?>
                                    </h2>
                                    <small class="text-muted">Per service</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Status</h6>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-<?php echo $data['service']['status'] == 'active' ? 'success' : 'danger'; ?> bg-opacity-10 text-<?php echo $data['service']['status'] == 'active' ? 'success' : 'danger'; ?> p-2 me-3">
                                            <i class="bi <?php echo $data['service']['status'] == 'active' ? 'bi-check-circle' : 'bi-x-circle'; ?> fs-5"></i>
                                        </span>
                                        <div>
                                            <h5 class="mb-0"><?php echo ucfirst($data['service']['status']); ?></h5>
                                            <small class="text-muted">
                                                <?php echo $data['service']['status'] == 'active' ? 'Available for reservations' : 'Not available for reservations'; ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-card-checklist me-2"></i>Service Details
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Service ID</span>
                                <span class="badge bg-light text-dark border">#<?php echo $data['service']['id']; ?></span>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Category</span>
                                <span class="badge bg-primary"><?php echo ucfirst($data['service']['category']); ?></span>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Created Date</span>
                                <span>
                                    <?php echo date('M d, Y', strtotime($data['service']['created_at'])); ?>
                                </span>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Last Updated</span>
                                <span>
                                    <?php echo $data['service']['updated_at'] ? date('M d, Y', strtotime($data['service']['updated_at'])) : 'Never'; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-grid gap-2">
                        <a href="?action=admin/services&sub_action=edit&id=<?php echo $data['service']['id']; ?>"
                            class="btn btn-primary">
                            <i class="bi bi-pencil me-1"></i> Edit Service
                        </a>
                        <a href="?action=admin/services&sub_action=toggle-status&id=<?php echo $data['service']['id']; ?>"
                            class="btn btn-outline-warning"
                            onclick="return confirm('Are you sure you want to <?php echo $data['service']['status'] == 'active' ? 'deactivate' : 'activate'; ?> this service?')">
                            <i class="bi bi-power me-1"></i>
                            <?php echo $data['service']['status'] == 'active' ? 'Deactivate Service' : 'Activate Service'; ?>
                        </a>
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <a href="?action=admin/services&sub_action=delete&id=<?php echo $data['service']['id']; ?>"
                                class="btn btn-outline-danger"
                                onclick="return confirm('Are you sure you want to delete this service? This action cannot be undone.')">
                                <i class="bi bi-trash me-1"></i> Delete Service
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
