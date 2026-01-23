<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title ?? 'Hotel Bannie State of Cebu - Admin Panel'; ?></title>

  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

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

    .sidebar-link {
      transition: all 0.3s ease;
    }

    .sidebar-link:hover {
      background-color: rgba(30, 64, 175, 0.1);
      transform: translateX(5px);
    }

    .sidebar-link.active {
      background-color: rgba(30, 64, 175, 0.15);
      border-left: 4px solid var(--primary-color);
    }

    .card {
      border: none;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      border-radius: 0.5rem;
    }

    .card-header {
      background-color: white;
      border-bottom: 1px solid #e5e7eb;
    }

    .table th {
      font-weight: 600;
      color: #374151;
      background-color: #f9fafb;
    }

    .status-badge {
      padding: 0.25rem 0.75rem;
      border-radius: 9999px;
      font-size: 0.875rem;
      font-weight: 500;
    }

    .status-pending { background-color: #fef3c7; color: #92400e; }
    .status-confirmed { background-color: #dbeafe; color: #1e40af; }
    .status-checked_in { background-color: #d1fae5; color: #065f46; }
    .status-checked_out { background-color: #e5e7eb; color: #374151; }
    .status-cancelled { background-color: #fee2e2; color: #991b1b; }
    .status-no_show { background-color: #f3f4f6; color: #4b5563; }

    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .btn-primary:hover {
      background-color: #1e3a8a;
      border-color: #1e3a8a;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(30, 64, 175, 0.25);
    }

    .alert {
      border: none;
      border-radius: 0.5rem;
    }
  </style>
</head>

<body class="bg-gray-50">
