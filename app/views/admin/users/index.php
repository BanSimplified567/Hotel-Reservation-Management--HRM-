<div class="container-fluid px-3">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-1 text-dark fw-bold">
                <i class="fas fa-users text-primary me-2"></i>User Management
            </h1>
            <p class="text-muted small mb-0">Manage hotel users and permissions</p>
        </div>
        <a href="index.php?action=admin/users&sub_action=create" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Add User
        </a>
    </div>

    <!-- Search and Filter Card -->
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-white py-2 border-bottom">
            <h6 class="mb-0 text-dark">
                <i class="fas fa-filter text-primary me-1"></i>Filters
            </h6>
        </div>
        <div class="card-body p-3">
            <form method="GET" action="" class="row g-2">
                <input type="hidden" name="action" value="admin/users">

                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control form-control-sm border-start-0" name="search"
                            placeholder="Search users..."
                            value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    </div>
                </div>

                <div class="col-md-2">
                    <select class="form-control form-control-sm" name="role">
                        <option value="">All Roles</option>
                        <option value="customer" <?php echo ($role ?? '') == 'customer' ? 'selected' : ''; ?>>Customer</option>
                        <option value="staff" <?php echo ($role ?? '') == 'staff' ? 'selected' : ''; ?>>Staff</option>
                        <option value="admin" <?php echo ($role ?? '') == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select class="form-control form-control-sm" name="status">
                        <option value="">All Status</option>
                        <option value="active" <?php echo ($status ?? '') == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($status ?? '') == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                </div>

                <div class="col-md-2">
                    <a href="?action=admin/users" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-2 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-dark">
                    <i class="fas fa-list text-primary me-1"></i>Users List
                </h6>
                <span class="badge bg-light text-dark border">
                    <small>Total: <?php echo $totalUsers; ?></small>
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show m-3 p-2" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <small><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></small>
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show m-3 p-2" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <small><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></small>
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 ps-3" width="60"><small class="text-muted">ID</small></th>
                            <th class="border-0"><small class="text-muted">USER</small></th>
                            <th class="border-0"><small class="text-muted">ROLE</small></th>
                            <th class="border-0"><small class="text-muted">STATUS</small></th>
                            <th class="border-0"><small class="text-muted">REGISTERED</small></th>
                            <th class="border-0 pe-3 text-end"><small class="text-muted">ACTIONS</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="border-bottom">
                                    <td class="ps-3">
                                        <small class="text-muted">#<?php echo $user['id']; ?></small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <div class="avatar-circle-sm bg-<?php
                                                    echo $user['role'] == 'admin' ? 'danger' :
                                                        ($user['role'] == 'staff' ? 'warning' : 'primary');
                                                ?>">
                                                    <span class="avatar-text-sm">
                                                        <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="small fw-medium"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
                                                <small class="text-muted d-block">@<?php echo htmlspecialchars($user['username']); ?></small>
                                                <small class="text-muted"><?php echo htmlspecialchars($user['email']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-role-<?php echo $user['role']; ?> py-1 px-2">
                                            <small>
                                                <i class="fas fa-<?php
                                                    echo $user['role'] == 'admin' ? 'crown' :
                                                        ($user['role'] == 'staff' ? 'concierge-bell' : 'user');
                                                ?> me-1"></i>
                                                <?php echo ucfirst($user['role']); ?>
                                            </small>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge status-<?php echo $user['is_active'] ? 'active' : 'inactive'; ?> py-1 px-2">
                                            <small>
                                                <i class="fas fa-<?php echo $user['is_active'] ? 'check-circle' : 'minus-circle'; ?> me-1"></i>
                                                <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </small>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                                        </small>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="index.php?action=admin/users&sub_action=view&id=<?php echo $user['id']; ?>"
                                                class="btn btn-outline-info border" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="index.php?action=admin/users&sub_action=edit&id=<?php echo $user['id']; ?>"
                                                class="btn btn-outline-warning border" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-<?php echo $user['is_active'] ? 'warning' : 'success'; ?> border toggle-status"
                                                data-id="<?php echo $user['id']; ?>"
                                                data-status="<?php echo $user['is_active'] ? 'active' : 'inactive'; ?>"
                                                title="<?php echo $user['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                                                <i class="fas fa-<?php echo $user['is_active'] ? 'ban' : 'check'; ?>"></i>
                                            </button>
                                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                <button type="button" class="btn btn-outline-danger border delete-user"
                                                    data-id="<?php echo $user['id']; ?>"
                                                    data-name="<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="py-3">
                                        <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                        <h6 class="text-muted mb-2">No users found</h6>
                                        <p class="text-muted small mb-3">Try adjusting your search criteria</p>
                                        <a href="index.php?action=admin/users&sub_action=create" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i>Add User
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="card-footer bg-white border-0 py-2">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm justify-content-center mb-0">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link border"
                                   href="index.php?action=admin/users&page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($role) ? '&role=' . $role : ''; ?><?php echo !empty($status) ? '&status=' . $status : ''; ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link border"
                                       href="index.php?action=admin/users&page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($role) ? '&role=' . $role : ''; ?><?php echo !empty($status) ? '&status=' . $status : ''; ?>">
                                        <small><?php echo $i; ?></small>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                <a class="page-link border"
                                   href="index.php?action=admin/users&page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($role) ? '&role=' . $role : ''; ?><?php echo !empty($status) ? '&status=' . $status : ''; ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Status Toggle Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header bg-warning text-white py-2">
                <h6 class="modal-title mb-0" id="statusModalLabel">
                    <i class="fas fa-exchange-alt me-1"></i>Confirm
                </h6>
                <button type="button" class="btn-close btn-close-white btn-close-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="text-center mb-2">
                    <i class="fas fa-exclamation-triangle fa-lg text-warning mb-2"></i>
                    <p class="small mb-1">Change user status to <span id="statusAction" class="fw-bold"></span>?</p>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                    Cancel
                </button>
                <a href="#" id="confirmStatusChange" class="btn btn-warning btn-sm">
                    Confirm
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title mb-0" id="deleteModalLabel">
                    <i class="fas fa-trash me-1"></i>Delete User
                </h6>
                <button type="button" class="btn-close btn-close-white btn-close-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="text-center mb-2">
                    <i class="fas fa-user-slash fa-lg text-danger mb-2"></i>
                    <p class="small mb-1">Delete <strong id="deleteUserName"></strong>?</p>
                    <small class="text-muted d-block">This action cannot be undone.</small>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                    Cancel
                </button>
                <a href="#" id="confirmDelete" class="btn btn-danger btn-sm">
                    Delete
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Compact Styles */
    .container-fluid {
        max-width: 1200px;
        margin: 0 auto;
    }

    .avatar-circle-sm {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 13px;
    }

    .avatar-text-sm {
        font-size: 12px;
    }

    .table-sm th,
    .table-sm td {
        padding: 0.5rem;
    }

    .btn-close-sm {
        padding: 0.25rem;
        font-size: 0.75rem;
    }

    /* Badge Styles */
    .badge-role-admin {
        background-color: rgba(220, 53, 69, 0.08);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
        font-size: 11px;
    }

    .badge-role-staff {
        background-color: rgba(255, 193, 7, 0.08);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.2);
        font-size: 11px;
    }

    .badge-role-customer {
        background-color: rgba(13, 110, 253, 0.08);
        color: #0d6efd;
        border: 1px solid rgba(13, 110, 253, 0.2);
        font-size: 11px;
    }

    .status-active {
        background-color: rgba(25, 135, 84, 0.08);
        color: #198754;
        border: 1px solid rgba(25, 135, 84, 0.2);
        font-size: 11px;
    }

    .status-inactive {
        background-color: rgba(108, 117, 125, 0.08);
        color: #6c757d;
        border: 1px solid rgba(108, 117, 125, 0.2);
        font-size: 11px;
    }

    /* Button group compact */
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    /* Compact form controls */
    .form-control-sm {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }

    .input-group-sm > .form-control {
        padding: 0.25rem 0.5rem;
    }

    /* Smaller table */
    .table {
        font-size: 0.875rem;
    }

    /* Compact modal */
    .modal-sm {
        max-width: 300px;
    }

    /* Reduced padding */
    .card-body.p-0 .table {
        margin-bottom: 0;
    }

    /* Smaller alerts */
    .alert {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        margin: 0.5rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Status toggle
        document.querySelectorAll('.toggle-status').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                const currentStatus = this.getAttribute('data-status');
                const action = currentStatus === 'active' ? 'deactivate' : 'activate';

                const modal = new bootstrap.Modal(document.getElementById('statusModal'));
                document.getElementById('statusAction').textContent = action;
                document.getElementById('confirmStatusChange').href =
                    `index.php?action=admin/users&sub_action=toggle-status&id=${userId}`;

                modal.show();
            });
        });

        // Delete confirmation
        document.querySelectorAll('.delete-user').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                const userName = this.getAttribute('data-name');

                const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                document.getElementById('deleteUserName').textContent = userName;
                document.getElementById('confirmDelete').href =
                    `index.php?action=admin/users&sub_action=delete&id=${userId}`;

                modal.show();
            });
        });
    });
</script>
