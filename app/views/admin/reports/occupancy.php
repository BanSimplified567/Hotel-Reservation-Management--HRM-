<?php if (!empty($reportData)): ?>
  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>Date</th>
          <th>Occupied Rooms</th>
          <th>Total Rooms</th>
          <th>Occupancy Rate</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reportData as $row): ?>
          <tr>
            <td><?= htmlspecialchars(date('M j, Y', strtotime($row['date']))) ?></td>
            <td><?= htmlspecialchars($row['occupied_rooms']) ?></td>
            <td><?= htmlspecialchars($row['total_rooms']) ?></td>
            <td><?= number_format($row['occupancy_rate'], 2) ?>%</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php else: ?>
  <div class="alert alert-info">No occupancy data found for the selected period.</div>
<?php endif; ?>
