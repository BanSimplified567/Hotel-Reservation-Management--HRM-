<style>
    .guests-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    .filters {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .filters form {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr auto;
        gap: 15px;
        align-items: end;
    }
    .filters input, .filters select {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .btn {
        padding: 10px 20px;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    .btn:hover {
        background: #5a67d8;
    }
    .btn-danger {
        background: #e53e3e;
    }
    .btn-danger:hover {
        background: #c53030;
    }
    .table-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    th {
        background: #f8f9fa;
        font-weight: 600;
    }
    .actions {
        display: flex;
        gap: 5px;
    }
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
        gap: 5px;
    }
    .pagination a, .pagination span {
        padding: 8px 12px;
        border: 1px solid #ddd;
        text-decoration: none;
        color: #333;
        border-radius: 3px;
    }
    .pagination .active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }
</style>

<div class="guests-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Reservation Guests</h1>
        <a href="admin/reservation-guests/create" class="btn">Add New Guest</a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <?= $_SESSION['success'] ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <?= $_SESSION['error'] ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="filters">
        <form method="GET" action="admin/reservation-guests">
            <input type="text" name="search" placeholder="Search by name, email, phone..."
                   value="<?= htmlspecialchars($search) ?>">
            <input type="text" name="reservation_id" placeholder="Reservation ID"
                   value="<?= htmlspecialchars($reservation_id) ?>">
            <input type="date" name="date_from" placeholder="Check-in from">
            <input type="date" name="date_to" placeholder="Check-in to">
            <button type="submit" class="btn">Filter</button>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Guest Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Reservation</th>
                    <th>Room</th>
                    <th>Check-in</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($guests as $guest): ?>
                <tr>
                    <td><?= htmlspecialchars($guest['guest_name']) ?></td>
                    <td><?= htmlspecialchars($guest['guest_email']) ?></td>
                    <td><?= htmlspecialchars($guest['guest_phone'] ?? 'N/A') ?></td>
                    <td>
                        <?= htmlspecialchars($guest['user_first_name'] . ' ' . $guest['user_last_name']) ?><br>
                        <small>ID: <?= $guest['reservation_id'] ?></small>
                    </td>
                    <td><?= htmlspecialchars($guest['room_number']) ?></td>
                    <td><?= date('M j, Y', strtotime($guest['check_in'])) ?></td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 3px; font-size: 0.8em;
                              background: <?= $guest['reservation_status'] === 'confirmed' ? '#d4edda' :
                                           ($guest['reservation_status'] === 'checked_in' ? '#fff3cd' : '#f8d7da') ?>;
                              color: <?= $guest['reservation_status'] === 'confirmed' ? '#155724' :
                                      ($guest['reservation_status'] === 'checked_in' ? '#856404' : '#721c24') ?>;">
                            <?= ucfirst(str_replace('_', ' ', $guest['reservation_status'])) ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="admin/reservation-guests/view?id=<?= $guest['id'] ?>" class="btn" style="padding: 5px 10px; font-size: 0.8em;">View</a>
                        <a href="admin/reservation-guests/edit?id=<?= $guest['id'] ?>" class="btn" style="padding: 5px 10px; font-size: 0.8em; background: #ffa500;">Edit</a>
                        <a href="admin/reservation-guests/delete?id=<?= $guest['id'] ?>" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.8em;"
                           onclick="return confirm('Are you sure you want to delete this guest?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i == $page): ?>
                <span class="active"><?= $i ?></span>
            <?php else: ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&reservation_id=<?= urlencode($reservation_id) ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
    <?php endif; ?>

    <p style="text-align: center; margin-top: 20px; color: #666;">
        Total: <?= $total ?> reservation guests
    </p>
</div>
