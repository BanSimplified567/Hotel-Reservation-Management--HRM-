<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> | Hotel Reservation System</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">

    <!-- Datepicker CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #17a2b8;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
        }

        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 10px 10px;
        }

        .stat-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            border: none;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }

        .stat-change {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .change-positive {
            color: var(--success-color);
        }

        .change-negative {
            color: var(--danger-color);
        }

        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
            height: 100%;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
            height: 100%;
        }

        .progress-bar-container {
            margin-bottom: 1rem;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }

        .progress {
            height: 20px;
            border-radius: 10px;
        }

        .progress-bar-stacked {
            display: flex;
            height: 100%;
        }

        .room-type-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .filter-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }

        .date-filter {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .date-input-group {
            flex: 1;
            min-width: 200px;
        }

        .avatar-circle-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e9ecef;
        }

        .avatar-text-sm {
            font-size: 12px;
            font-weight: bold;
            color: #6c757d;
        }

        .badge-status-pending {
            background-color: rgba(255, 193, 7, 0.08);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.2);
            font-size: 11px;
        }

        .badge-status-confirmed {
            background-color: rgba(25, 135, 84, 0.08);
            color: #198754;
            border: 1px solid rgba(25, 135, 84, 0.2);
            font-size: 11px;
        }

        .badge-status-checked_in {
            background-color: rgba(13, 110, 253, 0.08);
            color: #0d6efd;
            border: 1px solid rgba(13, 110, 253, 0.2);
            font-size: 11px;
        }

        .badge-status-completed {
            background-color: rgba(111, 66, 193, 0.08);
            color: #6f42c1;
            border: 1px solid rgba(111, 66, 193, 0.2);
            font-size: 11px;
        }

        .badge-status-cancelled {
            background-color: rgba(220, 53, 69, 0.08);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.2);
            font-size: 11px;
        }

        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }

        @media (max-width: 768px) {
            .stat-value {
                font-size: 1.5rem;
            }

            .date-filter {
                flex-direction: column;
                gap: 0.5rem;
            }

            .date-input-group {
                width: 100%;
            }

            .container-fluid {
                padding-left: 10px;
                padding-right: 10px;
            }
        }

        /* Hover effects */
        tr:hover {
            background-color: rgba(0, 123, 255, 0.02) !important;
        }

        .list-group-item:hover {
            background-color: rgba(0, 123, 255, 0.02);
        }

        /* Compact styles */
        .card-header {
            padding: 0.75rem 1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="dashboard-header">
        <div class="container-fluid px-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </h1>
                    <p class="mb-0 opacity-75">
                        Welcome, <?php echo htmlspecialchars($_SESSION['first_name'] ?? 'Admin'); ?> •
                        <i class="fas fa-calendar-alt me-1"></i><?php echo date('M d, Y'); ?>
                    </p>
                </div>
                <div class="text-end">
                    <div class="btn-group">
                        <a href="index.php?action=admin/reports" class="btn btn-light btn-sm me-1">
                            <i class="fas fa-download me-1"></i>Reports
                        </a>
                        <a href="index.php?action=admin/reservations&sub_action=create" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i>New Booking
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-3">
        <!-- Alerts -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show p-2 mb-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <small class="flex-grow-1"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></small>
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Date Filter Card -->
        <div class="filter-card">
            <h5 class="mb-3"><i class="fas fa-filter me-2"></i>Date Range Filter</h5>
            <form method="GET" action="" class="date-filter">
                <div class="date-input-group">
                    <label for="start_date" class="form-label small">Start Date</label>
                    <input type="text" class="form-control form-control-sm datepicker" id="start_date" name="start_date"
                           value="<?php echo htmlspecialchars($start_date); ?>" required>
                </div>
                <div class="date-input-group">
                    <label for="end_date" class="form-label small">End Date</label>
                    <input type="text" class="form-control form-control-sm datepicker" id="end_date" name="end_date"
                           value="<?php echo htmlspecialchars($end_date); ?>" required>
                </div>
                <div class="date-input-group align-self-end">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-sync-alt me-2"></i>Apply Filter
                    </button>
                </div>
                <div class="date-input-group align-self-end">
                    <a href="?" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="fas fa-times me-2"></i>Clear Filter
                    </a>
                </div>
            </form>
        </div>

        <!-- Stats Cards Row (4 cards per row on md+) -->
        <div class="row g-3 mb-4">
            <!-- Total Revenue -->
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card border-top-0 border-top-5" style="border-top-color: var(--primary-color);">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted mb-2 d-block">Total Revenue</small>
                                <h3 class="stat-value mb-1">₱<?php echo number_format($stats['total_revenue'], 0); ?></h3>
                                <span class="stat-change <?php echo $stats['revenue_change'] >= 0 ? 'change-positive' : 'change-negative'; ?>">
                                    <i class="fas fa-arrow-<?php echo $stats['revenue_change'] >= 0 ? 'up' : 'down'; ?> me-1"></i>
                                    <?php echo abs($stats['revenue_change']); ?>% vs last period
                                </span>
                            </div>
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Reservations -->
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card border-top-0 border-top-5" style="border-top-color: var(--success-color);">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted mb-2 d-block">Active Reservations</small>
                                <h3 class="stat-value mb-1"><?php echo $stats['active_reservations']; ?></h3>
                                <span class="stat-change <?php echo $stats['active_change'] >= 0 ? 'change-positive' : 'change-negative'; ?>">
                                    <i class="fas fa-arrow-<?php echo $stats['active_change'] >= 0 ? 'up' : 'down'; ?> me-1"></i>
                                    <?php echo abs($stats['active_change']); ?>% vs last period
                                </span>
                                <small class="text-muted d-block mt-1"><?php echo $stats['occupied_rooms']; ?> rooms occupied</small>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Occupancy Rate -->
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card border-top-0 border-top-5" style="border-top-color: var(--info-color);">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted mb-2 d-block">Occupancy Rate</small>
                                <h3 class="stat-value mb-1"><?php echo $stats['occupancy_rate']; ?>%</h3>
                                <span class="text-muted">
                                    <?php echo $stats['occupied_rooms']; ?> of <?php
                                    $stmt = $this->pdo->query("SELECT COUNT(*) FROM rooms WHERE status IN ('available', 'occupied', 'reserved')");
                                    $totalRooms = $stmt->fetchColumn();
                                    echo $totalRooms; ?> rooms
                                </span>
                            </div>
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-bed"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Daily Rate -->
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card border-top-0 border-top-5" style="border-top-color: var(--warning-color);">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted mb-2 d-block">Avg Daily Rate</small>
                                <h3 class="stat-value mb-1">₱<?php echo number_format($stats['average_daily_rate'], 0); ?></h3>
                                <span class="text-muted">
                                    Per room per night
                                </span>
                            </div>
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Stats Row -->
        <div class="row g-3 mb-4">
            <!-- Total Reservations -->
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted mb-2 d-block">Total Reservations</small>
                                <h3 class="stat-value mb-1"><?php echo $stats['total_reservations']; ?></h3>
                                <small class="text-muted">Current period</small>
                            </div>
                            <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                                <i class="fas fa-receipt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Reservations -->
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted mb-2 d-block">Pending Reservations</small>
                                <h3 class="stat-value mb-1"><?php echo $stats['pending_reservations']; ?></h3>
                                <span class="stat-change <?php echo $stats['pending_change'] >= 0 ? 'change-positive' : 'change-negative'; ?>">
                                    <i class="fas fa-arrow-<?php echo $stats['pending_change'] >= 0 ? 'up' : 'down'; ?> me-1"></i>
                                    <?php echo abs($stats['pending_change']); ?>%
                                </span>
                            </div>
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Customers -->
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted mb-2 d-block">New Customers</small>
                                <h3 class="stat-value mb-1"><?php echo $stats['new_customers']; ?></h3>
                                <span class="stat-change <?php echo $stats['customers_change'] >= 0 ? 'change-positive' : 'change-negative'; ?>">
                                    <i class="fas fa-arrow-<?php echo $stats['customers_change'] >= 0 ? 'up' : 'down'; ?> me-1"></i>
                                    <?php echo abs($stats['customers_change']); ?>%
                                </span>
                            </div>
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Check-ins -->
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted mb-2 d-block">Today's Check-ins</small>
                                <h3 class="stat-value mb-1"><?php echo $stats['today_checkins']; ?></h3>
                                <span class="text-muted">
                                    Expected arrivals
                                </span>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-sign-in-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Row (8:4 ratio) -->
        <div class="row">
            <!-- Left Column - Charts & Tables -->
            <div class="col-lg-8">
                <!-- Revenue Chart -->
                <div class="p-2">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Revenue Trend</h5>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary btn-sm dropdown-toggle py-1 px-2" type="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-calendar"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item small" href="?period=7">7 days</a></li>
                                <li><a class="dropdown-item small" href="?period=30">30 days</a></li>
                                <li><a class="dropdown-item small" href="?period=90">90 days</a></li>
                            </ul>
                        </div>
                    </div>
                    <div style="position: relative; height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Recent Reservations Table with DataTables -->
                <div class="p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Recent Reservations</h5>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary btn-sm dropdown-toggle py-1 px-2" type="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-filter"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item small" href="index.php?action=admin/reservations&status=pending">Pending</a></li>
                                <li><a class="dropdown-item small" href="index.php?action=admin/reservations&status=confirmed">Confirmed</a></li>
                                <li><a class="dropdown-item small" href="index.php?action=admin/reservations&status=checked_in">Checked In</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item small" href="index.php?action=admin/reservations">View All</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="recentReservationsTable" class="table table-hover w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Room</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentReservations as $reservation): ?>
                                <?php
                                $status_badge = '';
                                $status_icon = '';
                                switch ($reservation['status']) {
                                    case 'pending':
                                        $status_badge = 'warning';
                                        $status_icon = 'clock';
                                        break;
                                    case 'confirmed':
                                        $status_badge = 'success';
                                        $status_icon = 'check-circle';
                                        break;
                                    case 'checked_in':
                                        $status_badge = 'info';
                                        $status_icon = 'key';
                                        break;
                                    case 'completed':
                                        $status_badge = 'primary';
                                        $status_icon = 'flag-checkered';
                                        break;
                                    case 'cancelled':
                                        $status_badge = 'danger';
                                        $status_icon = 'times-circle';
                                        break;
                                    default:
                                        $status_badge = 'secondary';
                                        $status_icon = 'question-circle';
                                        break;
                                }
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reservation['reservation_code']); ?></td>
                                    <td>
                                        <div class="fw-medium"><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($reservation['email'] ?? ''); ?></small>
                                    </td>
                                    <td>
                                        <div class="fw-medium"><?php echo htmlspecialchars($reservation['room_number']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($reservation['room_type']); ?></small>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($reservation['check_in'])); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($reservation['check_out'])); ?></td>
                                    <td>
                                        <span class="badge badge-status-<?php echo $reservation['status']; ?> py-1 px-2">
                                            <i class="fas fa-<?php echo $status_icon; ?> me-1"></i>
                                            <?php echo ucfirst($reservation['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="fw-bold text-success">₱<?php echo number_format($reservation['total_amount'], 0); ?></div>
                                        <small class="text-muted"><?php echo $reservation['payment_status'] == 'paid' ? 'Paid' : 'Pending'; ?></small>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column - Side Stats -->
            <div class="col-lg-4">
                <!-- Today's Activity -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6 col-lg-12">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white py-2 border-bottom">
                                <h6 class="mb-0">
                                    <i class="fas fa-sign-in-alt me-2"></i>Today's Check-ins
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="text-center">
                                    <div class="display-6 fw-bold text-primary mb-1"><?php echo $stats['today_checkins']; ?></div>
                                    <small class="text-muted d-block mb-2">guests arriving today</small>
                                    <?php if ($stats['today_checkins'] > 0 && !empty($todayCheckins)): ?>
                                    <?php $nextCheckin = reset($todayCheckins); ?>
                                    <small class="text-muted d-block">
                                        <i class="fas fa-clock me-1"></i>
                                        Next: <?php echo date('h:i A', strtotime($nextCheckin['check_in_time'] ?? '14:00')); ?>
                                    </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if ($stats['today_checkins'] > 0): ?>
                            <div class="card-footer bg-white py-2">
                                <a href="index.php?action=admin/reservations&status=checked_in&date_from=<?php echo date('Y-m-d'); ?>"
                                class="small text-decoration-none d-flex align-items-center">
                                    <i class="fas fa-eye me-1"></i> View arrivals
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-12">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white py-2 border-bottom">
                                <h6 class="mb-0">
                                    <i class="fas fa-sign-out-alt me-2"></i>Today's Check-outs
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="text-center">
                                    <div class="display-6 fw-bold text-primary mb-1"><?php echo $stats['today_checkouts']; ?></div>
                                    <small class="text-muted d-block mb-2">guests departing today</small>
                                    <?php if ($stats['today_checkouts'] > 0 && !empty($todayCheckouts)): ?>
                                    <?php $nextCheckout = reset($todayCheckouts); ?>
                                    <small class="text-muted d-block">
                                        <i class="fas fa-clock me-1"></i>
                                        Next: <?php echo date('h:i A', strtotime($nextCheckout['check_out_time'] ?? '12:00')); ?>
                                    </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if ($stats['today_checkouts'] > 0): ?>
                            <div class="card-footer bg-white py-2">
                                <a href="index.php?action=admin/reservations&status=checked_in&date_to=<?php echo date('Y-m-d'); ?>"
                                class="small text-decoration-none d-flex align-items-center">
                                    <i class="fas fa-eye me-1"></i> View departures
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Room Occupancy with Progress Bars -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-white py-2 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-bed me-2"></i>Room Occupancy
                        </h6>
                        <span class="badge bg-primary">
                            <?php
                            $totalRooms = 0;
                            $occupiedRooms = 0;
                            foreach ($roomOccupancy as $room) {
                                $totalRooms += $room['total_rooms'];
                                $occupiedRooms += $room['occupied'];
                            }
                            $availableRooms = $totalRooms - $occupiedRooms;
                            echo $availableRooms . '/' . $totalRooms . ' avail';
                            ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($roomOccupancy)): ?>
                            <!-- Overall Occupancy -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Overall</small>
                                    <small class="fw-bold"><?php echo round(($occupiedRooms / $totalRooms) * 100, 1); ?>%</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar <?php
                                        $rate = ($occupiedRooms / $totalRooms) * 100;
                                        echo $rate > 80 ? 'bg-danger' : ($rate > 60 ? 'bg-warning' : 'bg-success');
                                        ?>" role="progressbar"
                                        style="width: <?php echo min(100, $rate); ?>%">
                                    </div>
                                </div>
                            </div>

                            <!-- Room Type Breakdown -->
                            <?php foreach ($roomOccupancy as $room): ?>
                                <?php
                                $percentage = ($room['occupied'] / $room['total_rooms']) * 100;
                                $available = $room['available'];
                                $unavailable = $room['unavailable'];
                                ?>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="fw-medium"><?php echo htmlspecialchars($room['type']); ?></small>
                                        <small class="text-muted"><?php echo $room['occupied']; ?>/<?php echo $room['total_rooms']; ?> occupied</small>
                                    </div>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar-stacked">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: <?php echo ($available / $room['total_rooms']) * 100; ?>%"
                                                 data-bs-toggle="tooltip" title="Available: <?php echo $available; ?>">
                                                <?php if ($available > 0): ?>Available<?php endif; ?>
                                            </div>
                                            <div class="progress-bar bg-warning" role="progressbar"
                                                 style="width: <?php echo ($unavailable / $room['total_rooms']) * 100; ?>%"
                                                 data-bs-toggle="tooltip" title="Unavailable: <?php echo $unavailable; ?>">
                                                <?php if ($unavailable > 0): ?>Unavailable<?php endif; ?>
                                            </div>
                                            <div class="progress-bar bg-danger" role="progressbar"
                                                 style="width: <?php echo ($room['occupied'] / $room['total_rooms']) * 100; ?>%"
                                                 data-bs-toggle="tooltip" title="Occupied: <?php echo $room['occupied']; ?>">
                                                <?php if ($room['occupied'] > 0): ?>Occupied<?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end mt-1">
                                        <small class="fw-bold"><?php echo round($percentage, 1); ?>% Occupied</small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <i class="fas fa-bed fa-lg text-muted mb-2"></i>
                                <p class="small text-muted mb-0">No room data available</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Customers -->
                <?php if ($_SESSION['role'] == 'admin' && !empty($recentUsers)): ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-2 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>Recent Customers
                        </h6>
                        <span class="badge bg-info"><?php echo count($recentUsers); ?> new</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php foreach ($recentUsers as $user): ?>
                            <a href="index.php?action=admin/users&sub_action=edit&id=<?php echo $user['id']; ?>"
                            class="list-group-item list-group-item-action border-0 py-2 px-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-circle-sm bg-light">
                                            <span class="avatar-text-sm">
                                                <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <small class="fw-medium d-block"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></small>
                                            <small class="text-muted"><?php echo date('M d', strtotime($user['created_at'])); ?></small>
                                        </div>
                                        <small class="text-muted d-block"><?php echo htmlspecialchars($user['email']); ?></small>
                                        <?php if ($user['total_bookings'] > 0): ?>
                                        <small class="text-primary">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            <?php echo $user['total_bookings']; ?> booking<?php echo $user['total_bookings'] != 1 ? 's' : ''; ?>
                                        </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="card-footer bg-white py-2">
                        <a href="index.php?action=admin/users" class="small text-decoration-none d-flex align-items-center">
                            <i class="fas fa-arrow-right me-1"></i>View all customers
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Initialize datepicker
            flatpickr('.datepicker', {
                dateFormat: 'Y-m-d',
                maxDate: 'today'
            });

            // Initialize DataTable for Recent Reservations
            $('#recentReservationsTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                pageLength: 10,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                order: [[0, 'desc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search reservations..."
                }
            });

            // Initialize Chart.js for Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: [
                        <?php foreach ($revenueData as $data): ?>
                            '<?php echo date('M d', strtotime($data['date'])); ?>',
                        <?php endforeach; ?>
                    ],
                    datasets: [{
                        label: 'Daily Revenue',
                        data: [
                            <?php foreach ($revenueData as $data): ?>
                                <?php echo $data['daily_revenue']; ?>,
                            <?php endforeach; ?>
                        ],
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#3498db',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += '₱' + context.parsed.y.toLocaleString('en-PH', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                    // Add reservation count to tooltip
                                    const dataIndex = context.dataIndex;
                                    const reservationCount = <?php echo json_encode(array_column($revenueData, 'reservation_count')); ?>[dataIndex];
                                    return [label, 'Reservations: ' + reservationCount];
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString('en-PH');
                                }
                            },
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'nearest'
                    }
                }
            });

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Add click event to reservation rows
            document.querySelectorAll('#recentReservationsTable tbody tr').forEach(row => {
                row.addEventListener('click', function() {
                    const reservationId = this.cells[0].textContent.trim();
                    window.location.href = `index.php?action=admin/reservations&sub_action=view&id=${reservationId}`;
                });
            });
        });
    </script>
</body>
</html>
