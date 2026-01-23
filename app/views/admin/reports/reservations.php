<?php
function getStatusColor($status) {
  switch ($status) {
    case 'confirmed': return 'success';
    case 'pending': return 'warning';
    case 'cancelled': return 'danger';
    case 'completed': return 'info';
    case 'checked_in': return 'primary';
    default: return 'secondary';
  }
}
?>
<?php if (!empty($reportData)): ?>
  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Customer</th>
          <th>Room</th>
          <th>Check-in</th>
          <th>Check-out</th>
          <th>Status</th>
          <th>Total Amount</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reportData as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
            <td><?= htmlspecialchars($row['room_number']) ?></td>
            <td><?= htmlspecialchars(date('M j, Y', strtotime($row['check_in_date']))) ?></td>
            <td><?= htmlspecialchars(date('M j, Y', strtotime($row['check_out_date']))) ?></td>
            <td>
              <span class="badge bg-<?= getStatusColor($row['status']) ?>">
                <?= htmlspecialchars(ucfirst($row['status'])) ?>
              </span>
            </td>
            <td>₱<?= number_format($row['total_amount'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php else: ?>
  <div class="alert alert-info">No reservation data found for the selected period.</div>
<?php endif; ?>
