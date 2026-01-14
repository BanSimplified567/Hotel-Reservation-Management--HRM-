<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-hotel me-2"></i>Hotel Management
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] === 'customer'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=dashboard">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=my-reservations">
                                <i class="fas fa-calendar-check me-1"></i>My Reservations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=book-room">
                                <i class="fas fa-bed me-1"></i>Book Room
                            </a>
                        </li>
                    <?php elseif (in_array($_SESSION['role'], ['admin', 'staff'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=admin/dashboard">
                                <i class="fas fa-tachometer-alt me-1"></i>Admin Dashboard
                            </a>
                        </li>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=admin/users">
                                    <i class="fas fa-users me-1"></i>Users
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=admin/reservations">
                                <i class="fas fa-calendar-alt me-1"></i>Reservations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=admin/rooms">
                                <i class="fas fa-bed me-1"></i>Rooms
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=admin/services">
                                <i class="fas fa-concierge-bell me-1"></i>Services
                                </a>
                        </li>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=admin/reports">
                                    <i class="fas fa-chart-bar me-1"></i>Reports
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Public Links -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=rooms">
                        <i class="fas fa-door-open me-1"></i>Rooms
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=room-search">
                        <i class="fas fa-search me-1"></i>Search Rooms
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=about">
                        <i class="fas fa-info-circle me-1"></i>About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=contact">
                        <i class="fas fa-envelope me-1"></i>Contact
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                            <span class="badge bg-<?php
                                echo $_SESSION['role'] === 'admin' ? 'danger' :
                                     ($_SESSION['role'] === 'staff' ? 'warning' : 'info');
                            ?> ms-1">
                                <?php echo ucfirst($_SESSION['role']); ?>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="index.php?action=profile">
                                <i class="fas fa-user me-2"></i>My Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="index.php?action=logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=login">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary ms-2" href="index.php?action=register">
                            <i class="fas fa-user-plus me-1"></i>Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom Scripts -->
<script>
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);

    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        // Enable Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Confirm before delete
        document.querySelectorAll('.confirm-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this item?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
