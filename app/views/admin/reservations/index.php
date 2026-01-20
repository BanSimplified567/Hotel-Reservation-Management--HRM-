<?php
// app/views/admin/reservations/index.php
?>
<style>
  table {
    border-collapse: collapse;
    width: 100%;
  }

  th,
  td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }

  th {
    background-color: #f2f2f2;
  }

  .pagination {
    margin-top: 20px;
  }

  .pagination a {
    padding: 8px 16px;
    text-decoration: none;
    border: 1px solid #ddd;
    margin: 0 4px;
  }

  .pagination a.active {
    background-color: #4CAF50;
    color: white;
  }
</style>
<div class="reservation">
  <h1>Reservation Management</h1>

  <!-- Create Button -->
  <div style="margin-bottom: 20px;">
    <a href="index.php?action=admin/reservations&sub_action=create" class="btn btn-primary">
      <i class="fas fa-plus"></i> Create New Reservation
    </a>
  </div>

  <!-- Search and Filter Form -->
  <form method="GET" action="index.php">
    <input type="hidden" name="action" value="admin/reservations">

    <div>
      <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
      <select name="status">
        <option value="">All Status</option>
        <option value="pending" <?php echo ($status ?? '') == 'pending' ? 'selected' : ''; ?>>Pending</option>
        <option value="confirmed" <?php echo ($status ?? '') == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
        <option value="checked_in" <?php echo ($status ?? '') == 'checked_in' ? 'selected' : ''; ?>>Checked In</option>
        <option value="completed" <?php echo ($status ?? '') == 'completed' ? 'selected' : ''; ?>>Completed</option>
        <option value="cancelled" <?php echo ($status ?? '') == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
      </select>
      <input type="date" name="date_from" value="<?php echo htmlspecialchars($date_from ?? ''); ?>">
      <input type="date" name="date_to" value="<?php echo htmlspecialchars($date_to ?? ''); ?>">
      <button type="submit">Filter</button>
      <a href="index.php?action=admin/reservations">Clear</a>
    </div>
  </form>

  <!-- Reservations Table -->
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Guest</th>
        <th>Room</th>
        <th>Check-in</th>
        <th>Check-out</th>
        <th>Status</th>
        <th>Amount</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($reservations)): ?>
        <tr>
          <td colspan="8">No reservations found.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($reservations as $reservation): ?>
          <tr>
            <td><?php echo $reservation['id']; ?></td>
            <td><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?></td>
            <td><?php echo htmlspecialchars($reservation['room_number'] . ' (' . $reservation['room_type'] . ')'); ?></td>
            <td><?php echo date('Y-m-d', strtotime($reservation['check_in'])); ?></td>
            <td><?php echo date('Y-m-d', strtotime($reservation['check_out'])); ?></td>
            <td>
              <span style="
                            padding: 4px 8px;
                            border-radius: 4px;
                            background-color: <?php
                                              switch ($reservation['status']) {
                                                case 'pending':
                                                  echo '#ffc107';
                                                  break;
                                                case 'confirmed':
                                                  echo '#17a2b8';
                                                  break;
                                                case 'checked_in':
                                                  echo '#28a745';
                                                  break;
                                                case 'completed':
                                                  echo '#6c757d';
                                                  break;
                                                case 'cancelled':
                                                  echo '#dc3545';
                                                  break;
                                                default:
                                                  echo '#6c757d';
                                                  break;
                                              }
                                              ?>;
                            color: white;
                        ">
                <?php echo ucfirst($reservation['status']); ?>
              </span>
            </td>
            <td>₱<?php echo number_format($reservation['total_amount'], 2); ?></td>
            <td>
              <a href="index.php?action=admin/reservations&sub_action=view&id=<?php echo $reservation['id']; ?>"
                class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i> View
              </a>
              <a href="index.php?action=admin/reservations&sub_action=edit&id=<?php echo $reservation['id']; ?>">Edit</a>
              <a href="index.php?action=admin/reservations&sub_action=delete&id=<?php echo $reservation['id']; ?>"
                onclick="return confirm('Are you sure you want to delete this reservation?')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>

  <!-- Pagination -->
  <?php if ($totalPages > 1): ?>
    <div class="pagination">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="index.php?action=admin/reservations&page=<?php echo $i; ?>&search=<?php echo urlencode($search ?? ''); ?>&status=<?php echo urlencode($status ?? ''); ?>"
          class="<?php echo $i == $currentPage ? 'active' : ''; ?>">
          <?php echo $i; ?>
        </a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</div>
