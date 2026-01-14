<?php
require_once '../../layout/admin-header.php';
require_once '../../layout/admin-sidebar.php';

$old = $_SESSION['old'] ?? [];
$error = $_SESSION['error'] ?? '';
$reservation = $old ?: $reservation;
unset($_SESSION['old']);
unset($_SESSION['error']);

$check_in = new DateTime($reservation['check_in']);
$check_out = new DateTime($reservation['check_out']);
$nights = $check_in->diff($check_out)->days;
?>

<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Reservation</h1>
    <div>
      <a href="index.php?action=admin/reservations&sub_action=view&id=<?php echo $reservation['id']; ?>"
        class="btn btn-info shadow-sm mr-2">
        <i class="fas fa-eye fa-sm text-white-50"></i> View
      </a>
      <a href="index.php?action=admin/reservations" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to
