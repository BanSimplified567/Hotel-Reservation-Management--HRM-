<?php
// app/views/layout/admin-header.php
// This header is for admin and staff only
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title ?? 'Hotel Bannie State of Cebu - Admin Panel'; ?></title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Custom Tailwind Config -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#1e40af', // Blue
            secondary: '#0f766e', // Teal
            accent: '#dc2626', // Red
            luxury: '#d97706', // Amber
            success: '#059669', // Emerald
          },
          fontFamily: {
            'display': ['Playfair Display', 'Georgia', 'serif'],
            'sans': ['Inter', 'system-ui', 'sans-serif'],
          }
        }
      }
    }
  </script>

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
