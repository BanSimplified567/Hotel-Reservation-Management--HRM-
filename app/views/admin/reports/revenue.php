<?php if (!empty($reportData)): ?>
  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>Date</th>
          <th>Daily Revenue</th>
          <th>Reservations Count</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reportData as $row): ?>
          <tr>
            <td><?= htmlspecialchars(date('M j, Y', strtotime($row['date']))) ?></td>
            <td>₱<?= number_format($row['daily_revenue'], 2) ?></td>
            <td><?= htmlspecialchars($row['reservations_count']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php else: ?>
  <div class="alert alert-info">No revenue data found for the selected period.</div>
<?php endif; ?>
