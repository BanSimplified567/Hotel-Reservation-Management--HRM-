w
<?php
require_once '../../layout/admin-header.php';
require_once '../../layout/admin-sidebar.php';
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reservation Management</h1>
        <a href="index.php?action=admin/reservations&sub_action=create" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> New Reservation
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Reservations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalReservations; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Confirmed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $confirmedCount ?? 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $pendingCount ?? 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Checked-in Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $checkinToday ?? 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-door-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search & Filter</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="index.php" class="row">
                <input type="hidden" name="action" value="admin/reservations">

                <div class="col-md-3 mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Search customer or room"
                           value="<?php echo htmlspecialchars($search ?? ''); ?>">
                </div>

                <div class="col-md-2 mb-3">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="pending" <?php echo ($status ?? '') == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="confirmed" <?php echo ($status ?? '') == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                        <option value="checked_in" <?php echo ($status ?? '') == 'checked_in' ? 'selected' : ''; ?>>Checked-in</option>
                        <option value="completed" <?php echo ($status ?? '') == 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="cancelled" <?php echo ($status ?? '') == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <input type="date" name="date_from" class="form-control"
                           value="<?php echo htmlspecialchars($date_from ?? ''); ?>"
                           placeholder="From Date">
                </div>

                <div class="col-md-2 mb-3">
                    <input type="date" name="date_to" class="form-control"
                           value="<?php echo htmlspecialchars($date_to ?? ''); ?>"
                           placeholder="To Date">
                </div>

                <div class="col-md-3 mb-3">
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="index.php?action=admin/reservations" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reservations Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Reservations List</h6>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                        data-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-download"></i> Export
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#"><i class="fas fa-file-excel"></i> Excel</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-file-csv"></i> CSV</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Room</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Nights</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($reservations)): ?>
                            <?php foreach ($reservations as $reservation):
                                $check_in = new DateTime($reservation['check_in']);
                                $check_out = new DateTime($reservation['check_out']);
                                $nights = $check_in->diff($check_out)->days;
                            ?>
                                <tr>
                                    <td>#<?php echo $reservation['id']; ?></td>
                                    <td>
                                        <div><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($reservation['email']); ?></small>
                                    </td>
                                    <td>
                                        <div><?php echo htmlspecialchars($reservation['room_number']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($reservation['room_type']); ?></small>
                                    </td>
                                    <td>
                                        <div><?php echo date('M d, Y', strtotime($reservation['check_in'])); ?></div>
                                        <small class="text-muted"><?php echo date('H:i', strtotime($reservation['check_in'])); ?></small>
                                    </td>
                                    <td>
                                        <div><?php echo date('M d, Y', strtotime($reservation['check_out'])); ?></div>
                                        <small class="text-muted"><?php echo date('H:i', strtotime($reservation['check_out'])); ?></small>
                                    </td>
                                    <td><?php echo $nights; ?></td>
                                    <td>$<?php echo number_format($reservation['total_amount'], 2); ?></td>
                                    <td>
                                        <span class="badge badge-<?php
                                            echo $reservation['status'] == 'confirmed' ? 'success' :
                                                 ($reservation['status'] == 'pending' ? 'warning' :
                                                 ($reservation['status'] == 'checked_in' ? 'info' :
                                                 ($reservation['status'] == 'completed' ? 'primary' :
                                                 ($reservation['status'] == 'cancelled' ? 'secondary' : 'dark'))));
                                        ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $reservation['status'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($reservation['created_at'])); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="index.php?action=admin/reservations&sub_action=view&id=<?php echo $reservation['id']; ?>"
                                               class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="index.php?action=admin/reservations&sub_action=edit&id=<?php echo $reservation['id']; ?>"
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-primary update-status"
                                                    data-id="<?php echo $reservation['id']; ?>"
                                                    data-status="<?php echo $reservation['status']; ?>"
                                                    title="Update Status">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-reservation"
                                                    data-id="<?php echo $reservation['id']; ?>"
                                                    data-customer="<?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?>"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">No reservations found</td>
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
                            <a class="page-link" href="index.php?action=admin/reservations&page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($status) ? '&status=' . $status : ''; ?><?php echo !empty($date_from) ? '&date_from=' . $date_from : ''; ?><?php echo !empty($date_to) ? '&date_to=' . $date_to : ''; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="index.php?action=admin/reservations&page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($status) ? '&status=' . $status : ''; ?><?php echo !empty($date_from) ? '&date_from=' . $date_from : ''; ?><?php echo !empty($date_to) ? '&date_to=' . $date_to : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="index.php?action=admin/reservations&page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($status) ? '&status=' . $status : ''; ?><?php echo !empty($date_from) ? '&date_from=' . $date_from : ''; ?><?php echo !empty($date_to) ? '&date_to=' . $date_to : ''; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Update Reservation Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="" id="statusForm">
                <div class="modal-body">
                    <input type="hidden" name="reservation_id" id="reservationId">

                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="checked_in">Checked-in</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"
                                  placeholder="Add any notes about this status change..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
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
                Are you sure you want to delete reservation for: <strong id="deleteCustomerName"></strong>?
                <div class="alert alert-warning mt-2">
                    <small><i class="fas fa-exclamation-triangle"></i> This action cannot be undone. All reservation data will be permanently deleted.</small>
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
    // Update status
    document.querySelectorAll('.update-status').forEach(button => {
        button.addEventListener('click', function() {
            const reservationId = this.getAttribute('data-id');
            const currentStatus = this.getAttribute('data-status');

            document.getElementById('reservationId').value = reservationId;
            document.getElementById('statusForm').action =
                `index.php?action=admin/reservations&sub_action=update-status&id=${reservationId}`;
            document.getElementById('status').value = currentStatus;

            $('#statusModal').modal('show');
        });
    });

    // Delete confirmation
    document.querySelectorAll('.delete-reservation').forEach(button => {
        button.addEventListener('click', function() {
            const reservationId = this.getAttribute('data-id');
            const customerName = this.getAttribute('data-customer');

            document.getElementById('deleteCustomerName').textContent = customerName;
            document.getElementById('confirmDelete').href =
                `index.php?action=admin/reservations&sub_action=delete&id=${reservationId}`;

            $('#deleteModal').modal('show');
        });
    });
});
</script>

<?php
require_once '../../layout/admin-footer.php';
?>
