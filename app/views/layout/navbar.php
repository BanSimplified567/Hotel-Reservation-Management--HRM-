<?php
// app/views/layout/header.php
$role = $_SESSION['role'] ?? 'guest';
$isCustomer = $role === 'customer';
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container px-3">
    <!-- Logo/Brand -->
    <a class="navbar-brand fw-bold text-dark" href="index.php">
      <i class="fas fa-hotel text-primary me-2"></i>
      <span class="d-none d-sm-inline">Hotel Management</span>
      <span class="d-inline d-sm-none">Hotel</span>
    </a>

    <!-- Mobile menu toggle -->
    <button class="navbar-toggler border-0 p-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
      aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-bars"></i>
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
              <i class="fas fa-user-circle text-secondary"></i>
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

<style>
  /* Custom styles */
  .navbar {
    padding-top: 0.4rem;
    padding-bottom: 0.4rem;
  }

  .navbar-brand {
    font-size: 1.25rem;
  }

  .nav-link {
    padding: 0.4rem 0.5rem !important;
    font-size: 0.875rem;
    border-radius: 4px;
    transition: all 0.2s ease;
    margin: 0 2px;
  }

  .nav-link:hover {
    background-color: rgba(13, 110, 253, 0.08);
  }

  .dropdown-toggle {
    font-size: 0.875rem;
  }

  .dropdown-menu {
    font-size: 0.875rem;
    min-width: 180px;
    border: 1px solid #dee2e6;
  }

  .dropdown-item {
    padding: 0.5rem 1rem;
  }

  .badge {
    font-size: 0.65rem;
    padding: 0.2em 0.5em;
  }

  .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
  }

  /* Mobile styles */
  @media (max-width: 991.98px) {
    .navbar-collapse {
      padding: 0.75rem 0;
    }

    .nav-item {
      margin-bottom: 0.2rem;
    }

    .nav-link {
      padding: 0.5rem 0.75rem !important;
    }

    .d-flex.align-items-center {
      flex-direction: column;
      width: 100%;
      margin-top: 0.5rem;
    }

    .btn {
      width: 100%;
      margin-bottom: 0.25rem;
    }
  }

  @media (max-width: 576px) {
    .navbar-brand {
      font-size: 1.1rem;
    }
  }
</style>
