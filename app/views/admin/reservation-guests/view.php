<style>
    .view-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    .guest-details {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 30px;
    }
    .detail-row {
        display: flex;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .detail-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .detail-label {
        font-weight: bold;
        width: 200px;
        flex-shrink: 0;
    }
    .detail-value {
        flex: 1;
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
        margin-right: 10px;
    }
    .btn:hover {
        background: #5a67d8;
    }
    .btn-secondary {
        background: #6c757d;
    }
    .btn-secondary:hover {
        background: #5a6268;
    }
    .reservation-info {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 30px;
    }
</style>

<div class="view-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Reservation Guest Details</h1>
        <div>
            <a href="admin/reservation-guests/edit?id=<?= $guest['id'] ?>" class="btn">Edit Guest</a>
            <a href="admin/reservation-guests" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <div class="reservation-info">
        <h3>Reservation Information</h3>
        <p><strong>Reservation ID:</strong> #<?= $guest['reservation_id'] ?></p>
        <p><strong>Customer:</strong> <?= htmlspecialchars($guest['user_first_name'] . ' ' . $guest['user_last_name']) ?></p>
        <p><strong>Room:</strong> <?= htmlspecialchars($guest['room_number'])?> (<?= htmlspecialchars($guest['room_type']) ?>)</p>
        <p><strong>Check-in:</strong> <?= date('M j, Y', strtotime($guest['check_in'])) ?> |
           <strong>Check-out:</strong> <?= date('M j, Y', strtotime($guest['check_out'])) ?></p>
        <p><strong>Status:</strong>
            <span style="padding: 4px 8px; border-radius: 3px; font-size: 0.9em;
                  background: <?= $guest['reservation_status'] === 'confirmed' ? '#d4edda' :
                               ($guest['reservation_status'] === 'checked_in' ? '#fff3cd' : '#f8d7da') ?>;
                  color: <?= $guest['reservation_status'] === 'confirmed' ? '#155724' :
                          ($guest['reservation_status'] === 'checked_in' ? '#856404' : '#721c24') ?>;">
                <?= ucfirst(str_replace('_', ' ', $guest['reservation_status'])) ?>
            </span>
        </p>
    </div>

    <div class="guest-details">
        <h2>Guest Information</h2>

        <div class="detail-row">
            <div class="detail-label">Guest Name:</div>
            <div class="detail-value"><?= htmlspecialchars($guest['guest_name']) ?></div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Email:</div>
            <div class="detail-value"><?= htmlspecialchars($guest['guest_email']) ?></div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Phone:</div>
            <div class="detail-value"><?= htmlspecialchars($guest['guest_phone'] ?? 'Not provided') ?></div>
        </div>

        <div class="detail-row">
            <div class="detail-label">ID Type:</div>
            <div class="detail-value">
                <?= !empty($guest['id_type']) ? ucfirst(str_replace('_', ' ', $guest['id_type'])) : 'Not provided' ?>
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">ID Number:</div>
            <div class="detail-value"><?= htmlspecialchars($guest['id_number'] ?? 'Not provided') ?></div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Address:</div>
            <div class="detail-value"><?= htmlspecialchars($guest['guest_address'] ?? 'Not provided') ?></div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Created:</div>
            <div class="detail-value"><?= date('M j, Y g:i A', strtotime($guest['created_at'])) ?></div>
        </div>

        <?php if (!empty($guest['updated_at'])): ?>
        <div class="detail-row">
            <div class="detail-label">Last Updated:</div>
            <div class="detail-value"><?= date('M j, Y g:i A', strtotime($guest['updated_at'])) ?></div>
        </div>
        <?php endif; ?>
    </div>
</div>
