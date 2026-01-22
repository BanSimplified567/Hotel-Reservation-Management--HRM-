<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">User Management</h1>
    <a href="index.php?action=admin/users&sub_action=create" class="btn btn-primary shadow-sm">
      <i class="fas fa-plus fa-sm text-white-50"></i> Add New User
    </a>
  </div>

<!-- Search and Filter Card - Add category filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="" class="row g-3">
            <input type="hidden" name="route" value="admin/services">

            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control" name="search"
                        placeholder="Search by name or description"
                        value="<?php echo htmlspecialchars($data['search'] ?? ''); ?>">
                </div>
            </div>

            <div class="col-md-2">
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="active" <?php echo ($data['status'] ?? '') == 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($data['status'] ?? '') == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <div class="col-md-2">
                <select class="form-select" name="category">
                    <option value="">All Categories</option>
                    <?php foreach ($data['categories'] as $cat): ?>
                        <option value="<?php echo $cat; ?>" <?php echo ($data['category'] ?? '') == $cat ? 'selected' : ''; ?>>
                            <?php echo ucfirst($cat); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i> Search
                </button>
            </div>

            <div class="col-md-3">
                <a href="?action=admin/services" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-clockwise me-1"></i> Reset Filters
                </a>
            </div>
        </form>
    </div>
</div>

  <!-- Users Table Card -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <h6 class="m-0 font-weight-bold text-primary">Users List</h6>
      <span class="badge badge-primary">Total: <?php echo $totalUsers; ?></span>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th>Registered</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($users)): ?>
              <?php foreach ($users as $user): ?>
                <tr>
                  <td>#<?php echo $user['id']; ?></td>
                  <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                  <td><?php echo htmlspecialchars($user['email']); ?></td>
                  <td>
                    <span class="badge badge-<?php
                                              echo $user['role'] == 'admin' ? 'danger' : ($user['role'] == 'staff' ? 'warning' : 'info');
                                              ?>">
                      <?php echo ucfirst($user['role']); ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge badge-<?php echo $user['is_active'] ? 'success' : 'secondary'; ?>">
                      <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                    </span>
                  </td>
                  <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                  <td>
                    <div class="btn-group" role="group">
                      <a href="index.php?action=admin/users&sub_action=view&id=<?php echo $user['id']; ?>"
                        class="btn btn-sm btn-info" title="View">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="index.php?action=admin/users&sub_action=edit&id=<?php echo $user['id']; ?>"
                        class="btn btn-sm btn-warning" title="Edit">
                        <i class="fas fa-edit"></i>
                      </a>
                      <button type="button" class="btn btn-sm btn-<?php echo $user['is_active'] ? 'warning' : 'success'; ?> toggle-status"
                        data-id="<?php echo $user['id']; ?>"
                        data-status="<?php echo $user['is_active'] ? 'active' : 'inactive'; ?>"
                        title="<?php echo $user['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                        <i class="fas fa-<?php echo $user['is_active'] ? 'ban' : 'check'; ?>"></i>
                      </button>
                      <?php if ($user['id'] != $_SESSION['user_id']): ?>
                        <button type="button" class="btn btn-sm btn-danger delete-user"
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
                <td colspan="7" class="text-center">No users found</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation">
          <ul class="pagination justify-content-center">
            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
              <a class="page-link" href="index.php?action=admin/users&page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($role) ? '&role=' . $role : ''; ?><?php echo !empty($status) ? '&status=' . $status : ''; ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="index.php?action=admin/users&page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($role) ? '&role=' . $role : ''; ?><?php echo !empty($status) ? '&status=' . $status : ''; ?>">
                  <?php echo $i; ?>
                </a>
              </li>
            <?php endfor; ?>

            <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
              <a class="page-link" href="index.php?action=admin/users&page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($role) ? '&role=' . $role : ''; ?><?php echo !empty($status) ? '&status=' . $status : ''; ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          </ul>
        </nav>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Status Toggle Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="statusModalLabel">Confirm Status Change</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to <span id="statusAction"></span> this user?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <a href="#" id="confirmStatusChange" class="btn btn-primary">Confirm</a>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete user: <strong id="deleteUserName"></strong>?
        <div class="alert alert-warning mt-2">
          <small><i class="fas fa-exclamation-triangle"></i> This action cannot be undone. All related data will be permanently deleted.</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Status toggle
    document.querySelectorAll('.toggle-status').forEach(button => {
      button.addEventListener('click', function() {
        const userId = this.getAttribute('data-id');
        const currentStatus = this.getAttribute('data-status');
        const action = currentStatus === 'active' ? 'deactivate' : 'activate';

        document.getElementById('statusAction').textContent = action;
        document.getElementById('confirmStatusChange').href =
          `index.php?action=admin/users&sub_action=toggle-status&id=${userId}`;

        $('#statusModal').modal('show');
      });
    });

    // Delete confirmation
    document.querySelectorAll('.delete-user').forEach(button => {
      button.addEventListener('click', function() {
        const userId = this.getAttribute('data-id');
        const userName = this.getAttribute('data-name');

        document.getElementById('deleteUserName').textContent = userName;
        document.getElementById('confirmDelete').href =
          `index.php?action=admin/users&sub_action=delete&id=${userId}`;

        $('#deleteModal').modal('show');
      });
    });
  });
</script>
