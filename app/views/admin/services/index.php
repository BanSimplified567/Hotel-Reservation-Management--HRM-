<div class="container-fluid px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3">
        <div>
            <h1 class="h3 fw-bold mb-1">
                <i class="bi bi-cone-striped me-2"></i>
                <?php echo $data['page_title']; ?>
            </h1>
            <p class="text-muted mb-0">Manage your hotel services and amenities</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="?route=admin/services&sub_action=create" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add New Service
            </a>
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

    <!-- Search and Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <input type="hidden" name="route" value="admin/services">

                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control" name="search"
                            placeholder="Search by name or description"
                            value="<?php echo htmlspecialchars($data['search'] ?? ''); ?>">
                    </div>
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
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                </div>

                <div class="col-md-3">
                    <a href="?route=admin/services" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise me-1"></i> Reset Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Summary -->
    <div class="alert alert-light border shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-info-circle me-2 text-primary"></i>
                Showing <strong><?php echo ($data['page'] - 1) * 15 + 1; ?></strong> to
                <strong><?php echo min($data['page'] * 15, $data['totalServices']); ?></strong> of
                <strong><?php echo $data['totalServices']; ?></strong> services
            </div>
            <?php if ($data['totalServices'] > 0): ?>
                <span class="badge bg-primary">
                    <i class="bi bi-list-check me-1"></i>
                    <?php echo $data['totalServices']; ?> Total
                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Services Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($data['services'])): ?>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-cone-striped text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-muted mb-3">No services found</h5>
                    <p class="text-muted mb-4">Try adjusting your search or add a new service.</p>
                    <a href="?route=admin/services&sub_action=create" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Add First Service
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">ID</th>
                                <th class="border-0">Service Name</th>
                                <th class="border-0">Description</th>
                                <th class="border-0">Price</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Created</th>
                                <th class="border-0 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['services'] as $service): ?>
                                <?php
                                $status = $service['status'] ?? 'active';
                                $statusClass = $status == 'active' ? 'success' : 'danger';
                                $statusIcon = $status == 'active' ? 'bi-check-circle' : 'bi-x-circle';
                                ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            #<?php echo $service['id'] ?? ''; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?php echo htmlspecialchars($service['name'] ?? ''); ?></div>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php
                                            $description = $service['description'] ?? '';
                                            echo htmlspecialchars(substr($description, 0, 60)) .
                                                (strlen($description) > 60 ? '...' : '');
                                            ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10">
                                            <i class="bi bi-currency-dollar me-1"></i>
                                            <?php echo isset($service['price']) ? number_format($service['price'], 2) : '0.00'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $statusClass; ?> bg-opacity-10 text-<?php echo $statusClass; ?> border border-<?php echo $statusClass; ?> border-opacity-10">
                                            <i class="bi <?php echo $statusIcon; ?> me-1"></i>
                                            <?php echo ucfirst($status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php
                                            if (isset($service['created_at'])) {
                                                echo date('M d, Y', strtotime($service['created_at']));
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </small>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="?route=admin/services&sub_action=view&id=<?php echo $service['id'] ?? ''; ?>"
                                                class="btn btn-outline-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="?route=admin/services&sub_action=edit&id=<?php echo $service['id'] ?? ''; ?>"
                                                class="btn btn-outline-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="?route=admin/services&sub_action=toggle-status&id=<?php echo $service['id'] ?? ''; ?>"
                                                class="btn btn-outline-warning" title="Toggle Status"
                                                onclick="return confirm('Are you sure you want to <?php echo $status == 'active' ? 'deactivate' : 'activate'; ?> this service?')">
                                                <i class="bi bi-power"></i>
                                            </a>
                                            <?php if ($_SESSION['role'] == 'admin'): ?>
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
                    <nav class="p-4 border-top">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item <?php echo $data['page'] <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link"
                                    href="?route=admin/services&page=<?php echo $data['page'] - 1; ?>&search=<?php echo urlencode($data['search']); ?>&status=<?php echo urlencode($data['status']); ?>">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>

                            <?php
                            $start = max(1, $data['page'] - 2);
                            $end = min($data['totalPages'], $data['page'] + 2);

                            if ($start > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?route=admin/services&page=1&search=<?php echo urlencode($data['search']); ?>&status=<?php echo urlencode($data['status']); ?>">1</a>
                                </li>
                                <?php if ($start > 2): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <li class="page-item <?php echo $i == $data['page'] ? 'active' : ''; ?>">
                                    <a class="page-link"
                                        href="?route=admin/services&page=<?php echo $i; ?>&search=<?php echo urlencode($data['search']); ?>&status=<?php echo urlencode($data['status']); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($end < $data['totalPages']): ?>
                                <?php if ($end < $data['totalPages'] - 1): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link" href="?route=admin/services&page=<?php echo $data['totalPages']; ?>&search=<?php echo urlencode($data['search']); ?>&status=<?php echo urlencode($data['status']); ?>">
                                        <?php echo $data['totalPages']; ?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <li class="page-item <?php echo $data['page'] >= $data['totalPages'] ? 'disabled' : ''; ?>">
                                <a class="page-link"
                                    href="?route=admin/services&page=<?php echo $data['page'] + 1; ?>&search=<?php echo urlencode($data['search']); ?>&status=<?php echo urlencode($data['status']); ?>">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Auto-dismiss alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>
