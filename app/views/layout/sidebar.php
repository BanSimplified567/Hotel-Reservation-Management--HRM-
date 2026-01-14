<?php
// app/views/layout/header.php
$role = $_SESSION['role'] ?? 'guest';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Bannie State Of Cebu System</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="bg-light col-auto col-md-3 col-xl-2 px-sm-2 px-0">
        <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 min-vh-100">
          <!-- Brand -->
          <a href="index.php?action=dashboard"
            class="d-flex align-items-center gap-2 pb-2 mb-md-0 me-md-auto text-decoration-none">

            <img src="../assets/Sibonga.jpg"
              alt="Sibonga Barangay Seal"
              class="img-fluid"
              style="width: 32px; height: 32px; object-fit: cover;">

            <span class="small fw-semibold d-none d-sm-inline">
              Barangay Bansimplified
            </span>
          </a>

          <!-- Navigation -->
          <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
            <li class="nav-item">
              <a href="index.php?action=dashboard" class="nav-link align-middle px-0">
                <i class="fs-4 bi-house-door"></i>
                <span class="ms-1 d-none d-sm-inline">Dashboard</span>
              </a>
            </li>
            <?php if (in_array($role, ['admin', 'staff'])): ?>
              <li>
                <a href="index.php?action=residents" class="nav-link px-0 align-middle">
                  <i class="fs-4 bi-people"></i>
                  <span class="ms-1 d-none d-sm-inline">Residents</span>
                </a>
              </li>
              <li>
                <a href="index.php?action=certifications" class="nav-link px-0 align-middle">
                  <i class="fs-4 bi-file-earmark-check"></i>
                  <span class="ms-1 d-none d-sm-inline">Certifications</span>
                </a>
              </li>
              <li>
                <a href="index.php?action=blotters" class="nav-link px-0 align-middle">
                  <i class="fs-4 bi-journal-text"></i>
                  <span class="ms-1 d-none d-sm-inline">Blotters</span>
                </a>
              </li>
              <li>
                <a href="index.php?action=crimes" class="nav-link px-0 align-middle">
                  <i class="fs-4 bi-exclamation-triangle"></i>
                  <span class="ms-1 d-none d-sm-inline">Crimes</span>
                </a>
              </li>
              <li>
                <a href="index.php?action=events" class="nav-link px-0 align-middle">
                  <i class="fs-4 bi-calendar-event"></i>
                  <span class="ms-1 d-none d-sm-inline">Events</span>
                </a>
              </li>
              <li>
                <a href="index.php?action=officials" class="nav-link px-0 align-middle">
                  <i class="fs-4 bi-person-badge"></i>
                  <span class="ms-1 d-none d-sm-inline">Officials</span>
                </a>
              </li>
              <li>
                <a href="index.php?action=announcements" class="nav-link px-0 align-middle">
                  <i class="fs-4 bi-megaphone"></i>
                  <span class="ms-1 d-none d-sm-inline">Announcements</span>
                </a>
              </li>
              <li>
                <a href="index.php?action=logs" class="nav-link px-0 align-middle">
                  <i class="fs-4 bi-clock-history"></i>
                  <span class="ms-1 d-none d-sm-inline">Logs</span>
                </a>
              </li>
              <li>
                <a href="index.php?action=projects" class="nav-link px-0 align-middle">
                  <i class="fs-4 bi-gear-wide-connected"></i>
                  <span class="ms-1 d-none d-sm-inline">Projects</span>
                </a>
              </li>
            <?php endif; ?>
            <?php if ($role === 'admin'): ?>
              <li>
                <a href="index.php?action=settings" class="nav-link px-0 align-middle">
                  <i class="fs-4 bi-gear"></i>
                  <span class="ms-1 d-none d-sm-inline">Settings</span>
                </a>
              </li>
            <?php endif; ?>
            <?php if (in_array($role, ['admin', 'staff'])): ?>
              <li>
                <a href="index.php?action=my_profile" class="nav-link px-0 align-middle">
                  <i class="fs-4 bi-person-circle"></i>
                  <span class="ms-1 d-none d-sm-inline">My Profile</span>
                </a>
              </li>
            <?php endif; ?>
            <?php if ($role === 'resident'): ?>
              <li>
                <a href="index.php?action=profile" class="nav-link px-0 align-middle">
                  <i class="fs-4 bi-person"></i>
                  <span class="ms-1 d-none d-sm-inline">Profile</span>
                </a>
              </li>
              <li>
                <a href="index.php?action=request-certification" class="nav-link px-0 align-middle">
                  <i class="fs-4 bi-file-earmark-plus"></i>
                  <span class="ms-1 d-none d-sm-inline">Request Certification</span>
                </a>
              </li>
            <?php endif; ?>
          </ul>
          <hr>
          <!-- Logout -->
          <div class="pb-4">
            <a href="index.php?action=logout" class="d-flex align-items-center text-decoration-none">
              <i class="fs-4 bi-box-arrow-right"></i>
              <span class="ms-1 d-none d-sm-inline">Logout</span>
            </a>
          </div>
        </div>
      </div>
      <!-- Main Content Start -->
      <div class="col py-3">
