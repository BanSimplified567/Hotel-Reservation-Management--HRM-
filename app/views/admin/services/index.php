<!-- admin/services/index.php -->
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row align-items-center mb-4">
        <div class="col">
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="?action=admin/dashboard" class="text-decoration-none"><i class="bi bi-house-door"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Services</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                    <i class="bi bi-cone-striped text-primary fs-4"></i>
                </div>
                <div>
                    <h1 class="h3 fw-bold mb-1"><?php echo $data['page_title']; ?></h1>
                    <p class="text-muted mb-0">Manage your hotel services and amenities</p>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <a href="?action=admin/services&sub_action=create" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i> Add New Service
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill fs-5 me-3"></i>
            <div class="flex-grow-1">
                <strong>Success!</strong> <?php echo $_SESSION['success']; ?>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 me-3"></i>
            <div class="flex-grow-1">
                <strong>Error!</strong> <?php echo $_SESSION['error']; ?>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Search and Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-funnel text-primary me-2"></i>
                <h5 class="mb-0">Filter Services</h5>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <input type="hidden" name="route" value="admin/services">

                <div class="col-lg-5">
                    <label class="form-label text-muted small mb-1"><i class="bi bi-search me-1"></i> Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" name="search"
                            placeholder="Search by name or description"
                            value="<?php echo htmlspecialchars($data['search'] ?? ''); ?>">
                    </div>
                </div>

                <div class="col-lg-3">
                    <label class="form-label text-muted small mb-1"><i class="bi bi-toggle-on me-1"></i> Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="active" <?php echo ($data['status'] ?? '') == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($data['status'] ?? '') == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>

                <div class="col-lg-3">
                    <label class="form-label text-muted small mb-1"><i class="bi bi-tags me-1"></i> Category</label>
                    <select class="form-select" name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($data['categories'] as $cat): ?>
                            <option value="<?php echo $cat; ?>" <?php echo ($data['category'] ?? '') == $cat ? 'selected' : ''; ?>>
                                <i class="bi bi-tag me-1"></i><?php echo ucfirst($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-lg-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Go
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Summary -->
    <div class="row mb-4">
        <div class="col">
            <div class="card border-0 bg-light shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="bg-white p-2 rounded me-3">
                                <i class="bi bi-cone-striped text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Showing
                                    <span class="fw-bold text-primary"><?php echo ($data['page'] - 1) * 15 + 1; ?></span> to
                                    <span class="fw-bold text-primary"><?php echo min($data['page'] * 15, $data['totalServices']); ?></span> of
                                    <span class="fw-bold text-primary"><?php echo $data['totalServices']; ?></span> services
                                </h6>
                                <p class="text-muted small mb-0">
                                    Page <?php echo $data['page']; ?> of <?php echo $data['totalPages']; ?>
                                </p>
                            </div>
                        </div>
                        <?php if ($data['totalServices'] > 0): ?>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>
                                        <?php echo $data['activeCount'] ?? 0; ?> Active
                                    </span>
                                </div>
                                <div>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2">
                                        <i class="bi bi-x-circle me-1"></i>
                                        <?php echo $data['inactiveCount'] ?? 0; ?> Inactive
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list-check text-primary me-2"></i> Services List</h5>
                <?php if (!empty($data['services'])): ?>
                    <div class="text-muted small">
                        <i class="bi bi-arrow-clockwise me-1"></i> Updated just now
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-body p-0">
            <?php if (empty($data['services'])): ?>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-cone-striped text-muted opacity-25" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-muted mb-3">No services found</h4>
                    <p class="text-muted mb-4">Try adjusting your search criteria or add a new service.</p>
                    <a href="?action=admin/services&sub_action=create" class="btn btn-primary px-4">
                        <i class="bi bi-plus-circle me-2"></i> Create First Service
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 ps-4">ID</th>
                                <th class="border-0">Service</th>
                                <th class="border-0">Category</th>
                                <th class="border-0 text-end">Price</th>
                                <th class="border-0 text-center">Status</th>
                                <th class="border-0">Created</th>
                                <th class="border-0 text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['services'] as $service): ?>
                                <?php
                                $status = $service['status'] ?? 'active';
                                $statusClass = $status == 'active' ? 'success' : 'danger';
                                $statusIcon = $status == 'active' ? 'check-circle' : 'x-circle';
                                $categoryIcon = 'bi-tag';
                                ?>
                                <tr class="<?php echo $status == 'inactive' ? 'opacity-75' : ''; ?>">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-2">
                                                <i class="bi bi-cone-striped text-muted" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <span class="fw-semibold">#<?php echo $service['id'] ?? ''; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?php echo htmlspecialchars($service['name'] ?? ''); ?></div>
                                        <small class="text-muted">
                                            <?php
                                            $description = $service['description'] ?? '';
                                            echo htmlspecialchars(substr($description, 0, 50)) .
                                                (strlen($description) > 50 ? '...' : '');
                                            ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi <?php echo $categoryIcon; ?> me-1"></i>
                                            <?php echo ucfirst($service['category'] ?? 'general'); ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-success">
                                            <i class="bi bi-currency-dollar me-1"></i>
                                            <?php echo isset($service['price']) ? number_format($service['price'], 2) : '0.00'; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-<?php echo $statusClass; ?> bg-opacity-10 text-<?php echo $statusClass; ?> border border-<?php echo $statusClass; ?> border-opacity-25 px-3 py-2">
                                            <i class="bi bi-<?php echo $statusIcon; ?> me-1"></i>
                                            <?php echo ucfirst($status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                <?php echo isset($service['created_at']) ? date('M d, Y', strtotime($service['created_at'])) : 'N/A'; ?>
                                            </small>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                <?php echo isset($service['created_at']) ? date('h:i A', strtotime($service['created_at'])) : ''; ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group" role="group">
                                            <a href="?action=admin/services&sub_action=view&id=<?php echo $service['id'] ?? ''; ?>"
                                                class="btn btn-sm btn-outline-info border-end-0 rounded-start"
                                                data-bs-toggle="tooltip" data-bs-title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="?action=admin/services&sub_action=edit&id=<?php echo $service['id'] ?? ''; ?>"
                                                class="btn btn-sm btn-outline-primary border-start-0 border-end-0"
                                                data-bs-toggle="tooltip" data-bs-title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="?action=admin/services&sub_action=toggle-status&id=<?php echo $service['id'] ?? ''; ?>"
                                                class="btn btn-sm btn-outline-<?php echo $status == 'active' ? 'warning' : 'success'; ?> border-start-0 border-end-0"
                                                data-bs-toggle="tooltip" data-bs-title="<?php echo $status == 'active' ? 'Deactivate' : 'Activate'; ?>"
                                                onclick="return confirm('Are you sure you want to <?php echo $status == 'active' ? 'deactivate' : 'activate'; ?> this service?')">
                                                <i class="bi bi-power"></i>
                                            </a>
                                            <?php if ($_SESSION['role'] == 'admin'): ?>
                                                <a href="?action=admin/services&sub_action=delete&id=<?php echo $service['id'] ?? ''; ?>"
                                                    class="btn btn-sm btn-outline-danger border-start-0 rounded-end"
                                                    data-bs-toggle="tooltip" data-bs-title="Delete"
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
                    <div class="border-top px-4 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing page <?php echo $data['page']; ?> of <?php echo $data['totalPages']; ?>
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center mb-0">
                                    <li class="page-item <?php echo $data['page'] <= 1 ? 'disabled' : ''; ?>">
                                        <a class="page-link"
                                            href="?action=admin/services&page=<?php echo $data['page'] - 1; ?>&search=<?php echo urlencode($data['search']); ?>&status=<?php echo urlencode($data['status']); ?>&category=<?php echo urlencode($data['category']); ?>">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    </li>

                                    <?php
                                    $start = max(1, $data['page'] - 2);
                                    $end = min($data['totalPages'], $data['page'] + 2);

                                    if ($start > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?action=admin/services&page=1&search=<?php echo urlencode($data['search']); ?>&status=<?php echo urlencode($data['status']); ?>&category=<?php echo urlencode($data['category']); ?>">1</a>
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
                                                href="?action=admin/services&page=<?php echo $i; ?>&search=<?php echo urlencode($data['search']); ?>&status=<?php echo urlencode($data['status']); ?>&category=<?php echo urlencode($data['category']); ?>">
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
                                            <a class="page-link" href="?action=admin/services&page=<?php echo $data['totalPages']; ?>&search=<?php echo urlencode($data['search']); ?>&status=<?php echo urlencode($data['status']); ?>&category=<?php echo urlencode($data['category']); ?>">
                                                <?php echo $data['totalPages']; ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <li class="page-item <?php echo $data['page'] >= $data['totalPages'] ? 'disabled' : ''; ?>">
                                        <a class="page-link"
                                            href="?action=admin/services&page=<?php echo $data['page'] + 1; ?>&search=<?php echo urlencode($data['search']); ?>&status=<?php echo urlencode($data['status']); ?>&category=<?php echo urlencode($data['category']); ?>">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
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

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
