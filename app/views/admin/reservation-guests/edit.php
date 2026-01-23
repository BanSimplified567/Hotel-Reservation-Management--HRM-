<style>
    .edit-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    input, select, textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
    }
    .btn {
        padding: 10px 20px;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }
    .btn:hover {
        background: #5a67d8;
    }
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .reservation-info {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
</style>

<div class="edit-container">
    <h1>Edit Reservation Guest</h1>

    <div class="reservation-info">
        <h3>Reservation Details</h3>
        <p><strong>Guest:</strong> <?= htmlspecialchars($guest['user_first_name'] . ' ' . $guest['user_last_name']) ?></p>
        <p><strong>Room:</strong> <?= htmlspecialchars($guest['room_number']) ?></p>
        <p><strong>Check-in:</strong> <?= date('M j, Y', strtotime($guest['check_in'])) ?> |
           <strong>Check-out:</strong> <?= date('M j, Y', strtotime($guest['check_out'])) ?></p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <?= $_SESSION['error'] ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="admin/reservation-guests/edit?id=<?= $guest['id'] ?>">
        <div class="form-group">
            <label for="reservation_id">Reservation *</label>
            <select id="reservation_id" name="reservation_id" required>
                <option value="">Select Reservation</option>
                <?php foreach ($reservations as $reservation): ?>
                    <option value="<?= $reservation['id'] ?>"
                            <?= $guest['reservation_id'] == $reservation['id'] ? 'selected' : '' ?>>
                        #<?= $reservation['id'] ?> - <?= htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']) ?>
                        (Room <?= $reservation['room_number'] ?>, Check-in: <?= date('M j, Y', strtotime($reservation['check_in'])) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label for="guest_name">Guest Name *</label>
                <input type="text" id="guest_name" name="guest_name"
                       value="<?= htmlspecialchars($guest['guest_name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="guest_email">Guest Email *</label>
                <input type="email" id="guest_email" name="guest_email"
                       value="<?= htmlspecialchars($guest['guest_email']) ?>" required>
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label for="guest_phone">Guest Phone</label>
                <input type="tel" id="guest_phone" name="guest_phone"
                       value="<?= htmlspecialchars($guest['guest_phone'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="id_type">ID Type</label>
                <select id="id_type" name="id_type">
                    <option value="">Select ID Type</option>
                    <option value="passport" <?= $guest['id_type'] == 'passport' ? 'selected' : '' ?>>Passport</option>
                    <option value="drivers_license" <?= $guest['id_type'] == 'drivers_license' ? 'selected' : '' ?>>Driver's License</option>
                    <option value="national_id" <?= $guest['id_type'] == 'national_id' ? 'selected' : '' ?>>National ID</option>
                    <option value="other" <?= $guest['id_type'] == 'other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="id_number">ID Number</label>
            <input type="text" id="id_number" name="id_number"
                   value="<?= htmlspecialchars($guest['id_number'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="guest_address">Guest Address</label>
            <textarea id="guest_address" name="guest_address" rows="3"><?= htmlspecialchars($guest['guest_address'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn">Update Guest</button>
        <a href="admin/reservation-guests" class="btn" style="background: #6c757d; margin-left: 10px;">Cancel</a>
    </form>
</div>
