<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title ?? 'Hotel Management System'; ?></title>

  <!-- Tailwind CSS -->
  <!-- In your main layout file -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- In your head.php file -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <!-- Custom Tailwind Config -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#667eea',
            secondary: '#764ba2',
            success: '#48bb78',
            danger: '#f56565',
            warning: '#ed8936',
            info: '#4299e1',
          },
          fontFamily: {
            'sans': ['Segoe UI', 'Tahoma', 'Geneva', 'Verdana', 'sans-serif'],
          },
        }
      }
    }
  </script>

  <!-- Custom CSS for additional styles -->
  <style>
    .sidebar-gradient {
      background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
    }

    .stat-gradient-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-gradient-success {
      background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    }

    .stat-gradient-warning {
      background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
    }

    .stat-gradient-info {
      background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
    }

    .sidebar-link {
      transition: all 0.3s;
    }

    .sidebar-link:hover,
    .sidebar-link.active {
      background-color: rgba(255, 255, 255, 0.1);
      border-left: 4px solid white;
    }

    .card-hover {
      transition: transform 0.3s;
    }

    .card-hover:hover {
      transform: translateY(-5px);
    }
  </style>
</head>

<body class="bg-gray-50 font-sans">
  <!-- Navigation will be included from navbar.php -->
