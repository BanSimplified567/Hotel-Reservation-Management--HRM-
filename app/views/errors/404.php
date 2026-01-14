<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - Hotel Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .error-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        .error-code {
            font-size: 72px;
            font-weight: bold;
            color: #f5576c;
            margin: 0;
        }
        .error-message {
            font-size: 24px;
            color: #333;
            margin: 20px 0;
        }
        .error-description {
            color: #666;
            margin: 20px 0;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            background: #f5576c;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
            margin: 10px;
        }
        .btn:hover {
            background: #e53e3e;
        }
        .btn-secondary {
            background: #718096;
        }
        .btn-secondary:hover {
            background: #4a5568;
        }
        .search-box {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 80%;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1 class="error-code">404</h1>
        <h2 class="error-message">Page Not Found</h2>
        <p class="error-description">
            The page you are looking for might have been removed,
            had its name changed, or is temporarily unavailable.
        </p>
        <div>
            <a href="index.php" class="btn">Go to Homepage</a>
            <a href="javascript:history.back()" class="btn btn-secondary">Go Back</a>
        </div>
        <p style="margin-top: 20px; color: #888;">
            <a href="index.php?action=room-search" style="color: #f5576c;">Search for rooms</a> |
            <a href="index.php?action=contact" style="color: #f5576c;">Contact Support</a>
        </p>
    </div>
</body>
</html>
