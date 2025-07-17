<?php
// Set proper 404 status code
http_response_code(404);

// Get the requested URL
$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// Use the provided user and date information
$user_login = "Jade-7538"; // Your current user
$date_time_utc = "2025-03-25 01:48:44"; // Your current time

// Ensure the logs directory exists
$log_dir = __DIR__ . '/logs';
if (!file_exists($log_dir)) {
    mkdir($log_dir, 0755, true);
}

// Log the 404 error to a file
$log_file = $log_dir . '/404_errors.log';
$log_message = "$date_time_utc  | 404 Error: $current_url\n";
@file_put_contents($log_file, $log_message, FILE_APPEND);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 90%;
        }

        .header {
            background-color: #e74c3c;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 72px;
            margin: 0;
            line-height: 1;
        }

        .header h2 {
            font-size: 24px;
            font-weight: normal;
            margin: 10px 0 0;
        }

        .content {
            padding: 30px;
        }

        .error-details {
            background-color: #f8f9fa;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
            color: #666;
        }

        .buttons {
            margin: 20px 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
            font-weight: bold;
        }

        .btn.secondary {
            background-color: #7f8c8d;
        }

        .footer {
            text-align: center;
            padding: 15px;
            background-color: #f8f9fa;
            color: #7f8c8d;
            font-size: 14px;
        }

        img.error-image {
            display: block;
            max-width: 200px;
            margin: 20px auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>404</h1>
            <h2>Page Not Found</h2>
        </div>

        <div class="content">
            <p>The page you are looking for does not exist. It might have been moved or deleted.</p>

            <div class="error-details">
                <strong>Requested URL:</strong> <?php echo htmlspecialchars($current_url); ?><br>
            </div>

            <div class="buttons">
                <a href="https://rms.niaupriis.com/" class="btn">Go Home</a>
                <a href="javascript:history.back()" class="btn secondary">Go Back</a>
            </div>
        </div>

        <div class="footer">
            &copy; <?php echo date('Y'); ?> Your Website. All rights reserved.
        </div>
    </div>
</body>

</html>