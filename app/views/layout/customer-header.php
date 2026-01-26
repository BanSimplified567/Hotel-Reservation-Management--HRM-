<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title ?? 'Hotel Management - Customer Portal'; ?></title>

  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-color: #1e40af;
      --secondary-color: #0f766e;
      --accent-color: #dc2626;
      --luxury-color: #d97706;
      --success-color: #059669;
    }

    body {
      font-family: 'Inter', system-ui, sans-serif;
      background-color: #f8fafc;
    }

    .navbar-brand {
      font-family: 'Playfair Display', serif;
      font-weight: 600;
    }

    .customer-nav .nav-link {
      color: #374151;
      font-weight: 500;
    }

    .customer-nav .nav-link:hover {
      color: var(--primary-color);
    }

    .dropdown-menu {
      border: none;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
  </style>
</head>

<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand text-primary fw-bold" href="index.php">
        <i class="fas fa-hotel me-2"></i>Hotel Management
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto customer-nav">
          <li class="nav-item">
            <a class="nav-link" href="index.php?action=dashboard">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?action=book-room">Book Room</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?action=my-reservations">My Reservations</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?action=reservation-guests">Reservation Guests</a>
          </li>
        </ul>

        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
              <i class="fas fa-user-circle me-1"></i>
              <?php echo htmlspecialchars($_SESSION['first_name'] ?? 'Customer'); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="index.php?action=profile"><i class="fas fa-user me-2"></i>Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="index.php?action=logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky pt-3">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="index.php?action=dashboard">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?action=book-room">
                <i class="fas fa-calendar-plus me-2"></i>Book Room
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?action=my-reservations">
                <i class="fas fa-list me-2"></i>My Reservations
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?action=reservation-guests">
                <i class="fas fa-users me-2"></i>Reservation Guests
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?action=profile">
                <i class="fas fa-user me-2"></i>Profile
              </a>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Main content -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
