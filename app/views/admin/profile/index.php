<div class="profile-container">
    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success'] ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= $_SESSION['error'] ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="profile-header">
        <div style="display: flex; align-items: center; gap: 30px;">
            <img src="<?= !empty($user['profile_image']) ? 'uploads/profiles/' . htmlspecialchars($user['profile_image']) : 'assets/default-avatar.png' ?>"
                 alt="Profile" class="profile-image">
            <div>
                <h1><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h1>
                <p><?= htmlspecialchars(ucfirst($user['role'])) ?> • <?= htmlspecialchars($user['email']) ?></p>
                <p>Member since: <?= date('F j, Y', strtotime($user['created_at'])) ?></p>
                <?php if ($user['last_login']): ?>
                    <p>Last login: <?= date('M j, Y g:i A', strtotime($user['last_login'])) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value"><?= $stats['total_users'] ?? 0 ?></div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['active_reservations'] ?? 0 ?></div>
            <div class="stat-label">Active Reservations</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">₱<?= number_format($stats['revenue_today'] ?? 0, 2) ?></div>
            <div class="stat-label">Revenue Today</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['available_rooms'] ?? 0 ?></div>
            <div class="stat-label">Available Rooms</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['pending_reservations'] ?? 0 ?></div>
            <div class="stat-label">Pending Reservations</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['recent_signups'] ?? 0 ?></div>
            <div class="stat-label">Recent Signups (7 days)</div>
        </div>
    </div>

    <div class="profile-content">
        <!-- Sidebar -->
        <div class="profile-sidebar">
            <h3>Quick Actions</h3>
            <ul style="list-style: none; padding: 0;">
                <li><a href="?route=admin/profile&sub_action=edit">Edit Profile</a></li>
                <li><a href="?route=admin/profile&sub_action=change-password">Change Password</a></li>
                <li><a href="?route=admin/users">Manage Users</a></li>
                <li><a href="?route=admin/reservations">View Reservations</a></li>
                <li><a href="?route=admin/rooms">Manage Rooms</a></li>
                <li><a href="?route=admin/dashboard">Dashboard</a></li>
            </ul>

            <h3 style="margin-top: 30px;">Account Info</h3>
            <p><strong>Username:</strong> <?= htmlspecialchars($user['username'] ?? '') ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? '') ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Not set') ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($user['address'] ?? 'Not set') ?></p>
        </div>

        <!-- Main Content -->
        <div class="profile-main">
            <h2>Recent Activities</h2>

            <h3>System Logs</h3>
            <?php if (!empty($activities['logs'])): ?>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th style="padding: 10px; text-align: left;">User</th>
                        <th style="padding: 10px; text-align: left;">Action</th>
                        <th style="padding: 10px; text-align: left;">Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities['logs'] as $log): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;"><?= htmlspecialchars($log['first_name'] . ' ' . $log['last_name']) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($log['action']) ?></td>
                        <td style="padding: 10px;"><?= date('M j, g:i A', strtotime($log['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>No recent logs found.</p>
            <?php endif; ?>

            <h3 style="margin-top: 30px;">Recent Reservations</h3>
            <?php if (!empty($activities['reservations'])): ?>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th style="padding: 10px; text-align: left;">Guest</th>
                        <th style="padding: 10px; text-align: left;">Code</th>
                        <th style="padding: 10px; text-align: left;">Check-in</th>
                        <th style="padding: 10px; text-align: left;">Check-out</th>
                        <th style="padding: 10px; text-align: left;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities['reservations'] as $reservation): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;"><?= htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($reservation['reservation_code']) ?></td>
                        <td style="padding: 10px;"><?= date('M j, Y', strtotime($reservation['check_in'])) ?></td>
                        <td style="padding: 10px;"><?= date('M j, Y', strtotime($reservation['check_out'])) ?></td>
                        <td style="padding: 10px;">
                            <span style="padding: 3px 8px; border-radius: 3px; background: <?=
                                $reservation['status'] === 'confirmed' ? '#d4edda' :
                                ($reservation['status'] === 'checked_in' ? '#cce5ff' :
                                ($reservation['status'] === 'pending' ? '#fff3cd' : '#f8d7da'))
                            ?>;">
                                <?= ucfirst($reservation['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>No recent reservations found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    .profile-content {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 30px;
    }
    .profile-sidebar {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .profile-main {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        text-align: center;
    }
    .stat-value {
        font-size: 2em;
        font-weight: bold;
        color: #667eea;
    }
    .stat-label {
        color: #666;
        font-size: 0.9em;
    }
    .profile-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        margin-bottom: 20px;
    }
    .alert {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>
