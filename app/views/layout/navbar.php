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

    /* Dropdown hover effect */
    .dropdown:hover .dropdown-menu {
      display: block;
      margin-top: 0;
    }

    .dropdown-menu {
      border-radius: 8px;
      border: 1px solid rgba(0,0,0,.1);
      box-shadow: 0 4px 12px rgba(0,0,0,.1);
    }

    .dropdown-item {
      border-radius: 4px;
      margin: 2px 4px;
    }

    .dropdown-item:hover {
      background-color: #f8f9fa;
    }

    .nav-link {
      position: relative;
    }

    /* Make dropdown menus appear on hover for desktop */
    @media (min-width: 992px) {
      .dropdown:hover .dropdown-menu {
        display: block;
        margin-top: 0;
      }
    }

    /* Styling for active nav items */
    .nav-link.active {
      color: #0d6efd !important;
      font-weight: 500;
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

      <!-- Mobile menu toggle -->
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
              <!-- Customer Navigation -->
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center px-2" href="index.php?action=dashboard">
                  <i class="fas fa-home d-md-none me-2"></i>
                  <span class="d-none d-md-inline">Home</span>
                </a>
              </li>

              <!-- Reservations Dropdown for Customer -->
              <li class="nav-item dropdown">
                <a class="nav-link d-flex align-items-center px-2 dropdown-toggle" href="#"
                   id="reservationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-calendar-check d-md-none me-2"></i>
                  <span class="d-none d-md-inline">Reservations</span>
                </a>
                <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="reservationsDropdown">
                  <li>
                    <a class="dropdown-item d-flex align-items-center py-2" href="index.php?action=my-reservations">
                      <i class="fas fa-list me-2 small"></i> My Reservations
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item d-flex align-items-center py-2" href="index.php?action=reservation-guests">
                      <i class="fas fa-users me-2 small"></i> Reservation Guests
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item d-flex align-items-center py-2" href="index.php?action=book-room">
                      <i class="fas fa-book me-2 small"></i> Book Room
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item d-flex align-items-center py-2" href="index.php?action=rooms">
                      <i class="fas fa-bed me-2 small"></i> View Rooms
                    </a>
                  </li>
                </ul>
              </li>

            <?php elseif (in_array($_SESSION['role'], ['admin', 'staff'])): ?>
              <!-- Admin/Staff Navigation -->
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center px-2" href="index.php?action=admin/dashboard">
                  <i class="fas fa-home d-md-none me-2"></i>
                  <span class="d-none d-md-inline">Dashboard</span>
                </a>
              </li>

              <?php if ($_SESSION['role'] === 'admin'): ?>
                <li class="nav-item">
                  <a class="nav-link d-flex align-items-center px-2" href="index.php?action=admin/users">
                    <i class="fas fa-users d-md-none me-2"></i>
                    <span class="d-none d-md-inline">Users</span>
                  </a>
                </li>
              <?php endif; ?>

              <!-- Reservations Dropdown for Admin/Staff -->
              <li class="nav-item dropdown">
                <a class="nav-link d-flex align-items-center px-2 dropdown-toggle" href="#"
                   id="adminReservationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-calendar-alt d-md-none me-2"></i>
                  <span class="d-none d-md-inline">Reservations</span>
                </a>
                <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="adminReservationsDropdown">
                  <li>
                    <a class="dropdown-item d-flex align-items-center py-2" href="index.php?action=admin/reservations">
                      <i class="fas fa-list me-2 small"></i> All Reservations
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item d-flex align-items-center py-2" href="index.php?action=admin/rooms">
                      <i class="fas fa-bed me-2 small"></i> Manage Rooms
                    </a>
                  </li>
                  <?php if ($_SESSION['role'] === 'staff'): ?>
                    <li>
                      <a class="dropdown-item d-flex align-items-center py-2" href="index.php?action=book-room">
                        <i class="fas fa-book me-2 small"></i> Book for Customer
                      </a>
                    </li>
                  <?php endif; ?>
                </ul>
              </li>

            <?php endif; ?>
          <?php endif; ?>

          <!-- Public Links (for guests or always visible) -->
          <?php if (!isset($_SESSION['user_id']) || $_SESSION['role'] === 'customer'): ?>
            <!-- For guests OR customers, show these items -->
            <?php if (!isset($_SESSION['user_id'])): ?>
              <!-- Guest users see these as separate menu items -->
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center px-2" href="index.php?action=rooms">
                  <i class="fas fa-bed d-md-none me-2"></i>
                  <span class="d-none d-md-inline">Rooms</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center px-2" href="index.php?action=book-room">
                  <i class="fas fa-book d-md-none me-2"></i>
                  <span class="d-none d-md-inline">Book Room</span>
                </a>
              </li>
            <?php endif; ?>

            <li class="nav-item">
              <a class="nav-link d-flex align-items-center px-2" href="index.php?action=room-search">
                <i class="fas fa-search d-md-none me-2"></i>
                <span class="d-none d-md-inline">Search</span>
              </a>
            </li>
          <?php endif; ?>

          <!-- Common Links (visible to everyone) -->
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center px-2" href="index.php?action=about">
              <i class="fas fa-info-circle d-md-none me-2"></i>
              <span class="d-none d-md-inline">About</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center px-2" href="index.php?action=contact">
              <i class="fas fa-envelope d-md-none me-2"></i>
              <span class="d-none d-md-inline">Contact</span>
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

                <i class="fas fa-user-circle fa-lg text-secondary"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-1" aria-labelledby="userDropdown">
              <li>
  <a class="dropdown-item d-flex align-items-center py-2" href="index.php?action=profile">
    <i class="fas fa-user me-2 fs-4"></i> Profile
  </a>
</li>

                <?php if ($_SESSION['role'] === 'customer'): ?>
                  <li>
                    <a class="dropdown-item d-flex align-items-center py-2" href="index.php?action=my-reservations">
                      <i class="fas fa-calendar-check me-2 small"></i> My Reservations
                    </a>
                  </li>
                <?php endif; ?>
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
            <!-- Login/Register for guests -->
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

<script>
  // Add active class to current page nav item
  document.addEventListener('DOMContentLoaded', function() {
    const currentUrl = window.location.href;
    const navLinks = document.querySelectorAll('.nav-link:not(.dropdown-toggle), .dropdown-item');

    navLinks.forEach(link => {
      if (link.href === currentUrl ||
          currentUrl.includes(link.getAttribute('href').replace('index.php', ''))) {
        link.classList.add('active');

        // If it's a dropdown item, also highlight the parent dropdown
        if (link.classList.contains('dropdown-item')) {
          const dropdownToggle = link.closest('.dropdown').querySelector('.dropdown-toggle');
          if (dropdownToggle) {
            dropdownToggle.classList.add('active');
          }
        }
      }
    });
  });
</script>
