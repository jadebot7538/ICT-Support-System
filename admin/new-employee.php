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
$csrf_token = generateCSRFToken();


// Fetch pending employee requests
try {
    $pendingEmployees = $pdo->query("SELECT * FROM new_employee WHERE status = 'pending'")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $pendingEmployees = [];
}

$successMessage = $_SESSION['success_message'] ?? '';
$errorMessage = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee Requests | Support System</title>
    <link rel="icon" href="../img/website-logo.svg" type="image/png">
    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/new-employee.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
</head>

<body class="h-100">
    <header class="page-header"
        style="background-color:#fff; box-shadow:0 0.125rem 0.25rem rgba(0,0,0,0.075); margin-bottom:1.5rem; padding:1rem 0">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <a class="btn btn-outline-secondary me-3" href="index.php">&larr; Back</a>
                        <h4 class="m-0">Employee Requests</h4>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="user-info me-3">
                        <i class="bi bi-person-circle me-2"></i>
                        <span><?= htmlspecialchars($currentUser) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                Pending Employee Requests
            </div>
            <div class="card-body">
                <?php if ($pendingEmployees): ?>
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Gov ID</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Ext</th>
                                <th>Status</th>
                                <th style="width:180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingEmployees as $emp): ?>
                                <tr>
                                    <td><?= htmlspecialchars($emp['gov_id']) ?></td>
                                    <td><?= htmlspecialchars($emp['first_name']) ?></td>
                                    <td><?= htmlspecialchars($emp['middle_name']) ?></td>
                                    <td><?= htmlspecialchars($emp['last_name']) ?></td>
                                    <td><?= htmlspecialchars($emp['ext']) ?></td>
                                    <td><?= htmlspecialchars($emp['status']) ?></td>
                                    <td>
                                        <form action="process/new-employee.php" method="post" style="display:inline;">
                                            <input type="hidden" name="employee_id" value="<?= $emp['id'] ?>">
                                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                            <button type="submit" name="action" value="accepted"
                                                class="btn btn-success btn-sm">Accept</button>
                                        </form>
                                        <form action="process/new-employee.php" method="post" style="display:inline;">
                                            <input type="hidden" name="employee_id" value="<?= $emp['id'] ?>">
                                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                            <button type="submit" name="action" value="rejected"
                                                class="btn btn-danger btn-sm">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No pending employee requests.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>