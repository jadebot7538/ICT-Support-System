<?php
require_once "../security/session.php";
require_once "../security/sessionRegeneration.php";
require_once "../security/sessionValidation.php";
require_once '../security/csrf.php';
require_once "../database/config.php";

if (!isSessionValid($pdo) || !isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit;
}

$currentUser = $_SESSION['user_id'];

// Fetch the number of employee requests (dummy query, adjust table/column as necessary)
$employeeRequestCount = 0;

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM new_employee WHERE status = 'pending'");
    $stmt->execute();
    $employeeRequestCount = $stmt->fetchColumn();
} catch (PDOException $e) {
    // Handle error if needed
    error_log("Database error: " . $e->getMessage());
}


?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Support System | ADMIN</title>
    <link rel="icon" href="../img/website-logo.svg" type="image/png">
    <link rel="shortcut icon" href="../img/website-logo.svg" type="image/png">
    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/index.css">
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <style>
    </style>
</head>

<body class="h-100">
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md">
                    <h4 class="m-0">ICT Support System</h4>
                </div>
                <div class="col-md text-end">
                    <div class="user-info me-3">
                        <i class="bi bi-person-circle me-2"></i>
                        <span><?php echo $currentUser; ?></span>
                    </div>
                    <div class="d-inline-block ms-3">
                        <a href="process/logout.php" class="btn btn-danger d-flex align-items-center" type="button">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container with centered content -->
    <div class="container h-75">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-12 text-center">
                <h2 class="admin-title">Admin Control Panel</h2>
                <div class="d-flex justify-content-center flex-wrap gap-3">
                    <!-- Dashboard Card -->
                    <div class="admin-card dashboard-card" onclick="window.location.href='dashboard.php'">
                        <i class="bi bi-ticket-detailed text-primary m-0"></i>
                        <div class="admin-card-title">Ticket List</div>
                        <div class="admin-card-desc">Manage and track submitted tickets and system activity</div>
                    </div>

                    <!-- Reports Card -->
                    <div class="admin-card report-card" onclick="window.location.href='reports.php'">
                        <i class="bi bi-file-earmark-bar-graph text-success m-0"></i>
                        <div class="admin-card-title">Generate Report</div>
                        <div class="admin-card-desc">Create and export custom reports</div>
                    </div>

                    <!-- Content Management Card -->
                    <div class="admin-card content-card px-2 py-2"
                        onclick="window.location.href='content-management.php'">
                        <i class="bi bi-pencil-square text-warning m-0"></i>
                        <div class="admin-card-title">Content Management</div>
                        <div class="admin-card-desc">Update website content, FAQs, and announcements</div>
                    </div>

                    <!-- Employee Requests Card (matches style of other cards) -->

                    <div class="admin-card employee-card px-2 py-2" onclick="window.location.href='new-employee.php'">
                        <i class="bi bi-people-fill text-info m-0"></i>
                        <div class="admin-card-title">New Employee Request</div>
                        <div class="admin-card-desc">Register a new employee in the system</div>
                        <span class="request-badge" id="employeeRequestBadge"
                            style="display: <?= ($employeeRequestCount > 0 ? 'inline-block' : 'none') ?>;">
                            <?= $employeeRequestCount ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="fixed-bottom admin-footer mb-4">
        Support System | CICT Unit
    </div>

    <!-- Scripts -->
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateEmployeeRequestBadge(count) {
            var badge = document.getElementById('employeeRequestBadge');
            if (!badge) return;
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }

        // Initial update if needed
        updateEmployeeRequestBadge(<?= (int) $employeeRequestCount ?>);

        setInterval(function () {
            fetch('process/fetch-new-employee.php')
                .then(response => response.json())
                .then(data => {
                    if (typeof data.count !== "undefined") {
                        updateEmployeeRequestBadge(data.count);
                    }
                })
                .catch(() => { });
        }, 10000);
    </script>
</body>

</html>