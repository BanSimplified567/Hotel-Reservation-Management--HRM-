<?php
// app/views/layout/header.php
// This header is for customers only
$role = $_SESSION['role'] ?? 'guest';
$isCustomer = $role === 'customer';
?>

<?php if ($isCustomer || $role === 'guest'): ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title ?? 'Hotel Bannie State of Cebu Reservation System'; ?></title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    .sidebar-link {
      transition: all 0.3s ease;
    }

    .sidebar-link:hover {
      background-color: rgba(255, 255, 255, 0.1);
      transform: translateX(5px);
    }

    .sidebar-link.active {
      background-color: rgba(255, 255, 255, 0.15);
      border-left: 4px solid white;
    }
  </style>
</head>

<body class="bg-gray-50 font-sans">
<?php endif; ?>
