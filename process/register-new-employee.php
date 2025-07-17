<?php
// This file should be called via AJAX POST from the Access Denied page

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

$employee_id = $data['employee_id'] ?? null;
$first_name = $data['first_name'] ?? null;
$middle_name = $data['middle_name'] ?? null;
$last_name = $data['last_name'] ?? null;
$ext_name = $data['ext_name'] ?? null;
$csrf_token = $data['csrf_token'] ?? null;

require_once '../database/config.php';
require_once '../security/session.php';
require_once '../security/csrf.php';
// crsf token check
if (!isset($csrf_token) || $csrf_token !== $_SESSION['csrf_token']) {
    echo json_encode(['csrf_token' => true, 'message' => 'Invalid CSRF token.']);
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM new_employee WHERE gov_id = ? AND status = 'pending'");
$stmt->execute([$employee_id]);
$existing_employee = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing_employee) {
    echo json_encode(['duplicate' => true, 'message' => 'You have already submitted. Please wait for the ICT Unit to accept your registration.']);
    exit;
}
if (
    isset($employee_id, $first_name, $middle_name, $last_name)
) {
    try {
        $stmt = $pdo->prepare("INSERT INTO new_employee (gov_id, first_name, middle_name, last_name, ext) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            strtoupper($data['employee_id']),
            strtoupper($data['first_name']),
            strtoupper($data['middle_name']),
            strtoupper($data['last_name']),
            strtoupper($data['ext_name'])
        ]);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
}