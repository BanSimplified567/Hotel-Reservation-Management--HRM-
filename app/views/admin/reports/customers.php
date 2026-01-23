<?php if (!empty($reportData)): ?>
  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>Customer Name</th>
          <th>Email</th>
          <th>Total Reservations</th>
          <th>Total Spent</th>
          <th>Last Visit</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reportData as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['total_reservations']) ?></td>
            <td>₱<?= number_format($row['total_spent'], 2) ?></td>
            <td><?= htmlspecialchars($row['last_visit'] ? date('M j, Y', strtotime($row['last_visit'])) : 'N/A') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php else: ?>
  <div class="alert alert-info">No customer data found for the selected period.</div>
<?php endif; ?>
