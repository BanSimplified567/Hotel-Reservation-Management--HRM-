<?php
// app/views/layout/header.php
$role = $_SESSION['role'] ?? 'guest';
$isCustomer = $role === 'customer';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title ?? 'Hotel Management System'; ?></title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- FontAwesome - Using CDN instead of kit to avoid CORS issues -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    /* Your existing styles here */
    .navbar {
      padding-top: 0.4rem;
      padding-bottom: 0.4rem;
    }

    /* ... rest of your styles ... */
  </style>
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container px-3">
      <!-- Logo/Brand -->
      <a class="navbar-brand fw-bold text-dark" href="index.php">
        <i class="fas fa-hotel text-primary me-2"></i>
        <span class="d-none d-sm-inline">Hotel Management</span>
        <span class="d-inline d-sm-none">Hotel</span>
      </a>

      <!-- Mobile menu toggle - Fixed: removed unnecessary classes -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navigation Content -->
      <div class="collapse navbar-collapse" id="navbarContent">
        <!-- Desktop Navigation Links -->
        <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
          <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['role'] === 'customer'): ?>
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center px-2" href="index.php?action=dashboard">
                  <i class="fas fa-tachometer-alt me-1"></i> <span class="d-none d-md-inline">Dashboard</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center px-2" href="index.php?action=my-reservations">
                  <i class="fas fa-calendar-check me-1"></i> <span class="d-none d-md-inline">Reservations</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center px-2" href="index.php?action=book-room">
                  <i class="fas fa-bed me-1"></i> <span class="d-none d-md-inline">Book Room</span>
                </a>
              </li>
            <?php elseif (in_array($_SESSION['role'], ['admin', 'staff'])): ?>
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center px-2" href="index.php?action=admin/dashboard">
                  <i class="fas fa-tachometer-alt me-1"></i> <span class="d-none d-md-inline">Dashboard</span>
                </a>
              </li>
              <?php if ($_SESSION['role'] === 'admin'): ?>
                <li class="nav-item">
                  <a class="nav-link d-flex align-items-center px-2" href="index.php?action=admin/users">
                    <i class="fas fa-users me-1"></i> <span class="d-none d-md-inline">Users</span>
                  </a>
                </li>
              <?php endif; ?>
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center px-2" href="index.php?action=admin/reservations">
                  <i class="fas fa-calendar-alt me-1"></i> <span class="d-none d-md-inline">Reservations</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center px-2" href="index.php?action=admin/rooms">
                  <i class="fas fa-bed me-1"></i> <span class="d-none d-md-inline">Rooms</span>
                </a>
              </li>
            <?php endif; ?>
          <?php endif; ?>

          <!-- Public Links -->
          <?php if (!isset($_SESSION['user_id']) || $_SESSION['role'] === 'customer'): ?>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center px-2" href="index.php?action=rooms">
                <i class="fas fa-door-open me-1"></i> <span class="d-none d-md-inline">Rooms</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center px-2" href="index.php?action=room-search">
                <i class="fas fa-search me-1"></i> <span class="d-none d-md-inline">Search</span>
              </a>
            </li>
          <?php endif; ?>

          <!-- Common Links -->
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center px-2" href="index.php?action=about">
              <i class="fas fa-info-circle me-1"></i> <span class="d-none d-md-inline">About</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center px-2" href="index.php?action=contact">
              <i class="fas fa-envelope me-1"></i> <span class="d-none d-md-inline">Contact</span>
            </a>
          </li>
        </ul>

        <!-- Right side items -->
        <div class="d-flex align-items-center">
          <?php if (isset($_SESSION['user_id'])): ?>
            <!-- User dropdown -->
            <div class="dropdown">
              <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center p-0"
                type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="me-2 text-end d-none d-md-block">
                  <span class="d-block text-dark small">
                    <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                  </span>
                  <span class="badge <?php
                                      echo $_SESSION['role'] === 'admin' ? 'bg-danger' : ($_SESSION['role'] === 'staff' ? 'bg-warning text-dark' : 'bg-primary');
                                      ?> small">
                    <?php echo ucfirst($_SESSION['role']); ?>
                  </span>
                </div>
                <i class="fas fa-user-circle fa-lg text-secondary"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-1" aria-labelledby="userDropdown">
                <li>
                  <a class="dropdown-item d-flex align-items-center py-2" href="index.php?action=profile">
                    <i class="fas fa-user me-2 small"></i> Profile
                  </a>
                </li>
                <li>
                  <hr class="dropdown-divider my-1">
                </li>
                <li>
                  <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="index.php?action=logout">
                    <i class="fas fa-sign-out-alt me-2 small"></i> Logout
                  </a>
                </li>
              </ul>
            </div>
          <?php else: ?>
            <!-- Login/Register -->
            <a href="index.php?action=login" class="btn btn-outline-primary btn-sm me-2">
              <i class="fas fa-sign-in-alt me-1"></i> <span class="d-none d-md-inline">Login</span>
            </a>
            <a href="index.php?action=register" class="btn btn-primary btn-sm">
              <i class="fas fa-user-plus me-1"></i> <span class="d-none d-md-inline">Register</span>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

