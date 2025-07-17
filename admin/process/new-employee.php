<?php
require_once "../../database/config.php";
require_once "../../security/session.php";
require_once "../../security/csrf.php";

// Accept/Reject logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['employee_id'], $_POST['csrf_token'])) {
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        $_SESSION['error_message'] = "Invalid CSRF token.";
        header('Location: ../new_employee.php');
        exit;
    }
    $employee_id = intval($_POST['employee_id']);
    $action = $_POST['action'];

    // Fetch the new_employee record for 'accepted' action
    if ($action === 'accepted') {
        $stmt = $pdo->prepare("SELECT gov_id, first_name, middle_name, last_name, ext FROM new_employee WHERE id = ?");
        $stmt->execute([$employee_id]);
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($employee) {
            // Insert into employee table (prevent duplicates based on gov_id)
            $check = $pdo->prepare("SELECT COUNT(*) FROM employee WHERE gov_id = ?");
            $check->execute([$employee['gov_id']]);
            $exists = $check->fetchColumn();
            if ($exists) {
                $_SESSION['error_message'] = "This employee is already in the employee table.";
            } else {
                $ins = $pdo->prepare("INSERT INTO employee (gov_id, first_name, middle_name, last_name, ext) VALUES (?, ?, ?, ?, ?)");
                $ins->execute([
                    strtoupper($employee['gov_id']),
                    strtoupper($employee['first_name']),
                    strtoupper($employee['middle_name']),
                    strtoupper($employee['last_name']),
                    strtoupper($employee['ext'])
                ]);
                $_SESSION['success_message'] = "Employee request accepted and added to employee table!";
            }
            // Update status to accepted regardless
            $up = $pdo->prepare("UPDATE new_employee SET status = 'accepted' WHERE id = ?");
            $up->execute([$employee_id]);
        } else {
            $_SESSION['error_message'] = "Request not found.";
        }
    } elseif ($action === 'rejected') {
        $stmt = $pdo->prepare("UPDATE new_employee SET status = 'rejected' WHERE id = ?");
        $stmt->execute([$employee_id]);
        $_SESSION['success_message'] = "Employee request rejected!";
    } else {
        $_SESSION['error_message'] = "Invalid action.";
    }
    header('Location: ../new-employee.php');
    exit;
}