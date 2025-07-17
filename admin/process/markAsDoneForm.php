<?php
header("Content-Type: application/json");
require_once '../../security/session.php';
require_once '../../database/config.php';
require_once '../../security/csrf.php';


// Handle form submission and database insertion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['personnelId']) || !isset($data['serviceStatusId']) || !isset($data['requestId'])) {
        echo json_encode(["success" => false, "error" => "Invalid input"]);
        exit;
    }

    $personnelId = $data['personnelId'];
    $serviceStatusId = $data['serviceStatusId'];
    $otherServiceStatus = $data['otherServiceStatus'];
    $requestRefNo = $data['requestId'];
    $remarks = $data['remarks'];
    $csrfToken = $data['csrfToken'];


    if ($csrfToken !== $_SESSION['csrf_token']) {
        echo json_encode(["success" => false, "error" => "Invalid CSRF token"]);
        exit;
    }

    if (empty($personnelId)) {
        echo json_encode(["success" => false, "error" => "Please provide a personnel ID"]);
        exit;
    }
    if (empty($serviceStatusId)) {
        echo json_encode(["success" => false, "error" => "Please provide a service status"]);
        exit;
    } else {
        if ($serviceStatusId == 'others' && empty($otherServiceStatus)) {
            echo json_encode(["success" => false, "error" => "Please provide a service status"]);
            exit;
        }
    }

    $stmt = $pdo->prepare("SELECT id FROM request WHERE ref_no = :refNo LIMIT 1");
    $stmt->execute(['refNo' => $requestRefNo]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);
    $requestId = $request['id'];

    try {
        if ($serviceStatusId == 'others') {
            $stmt = $pdo->prepare("INSERT INTO maintenance_activity (personnel_id, other_status, service_status_id, request_id, remarks) VALUES (:personnelId, :otherStatus, :serviceStatusId, :requestId, :remarks)");
            $stmt->execute(['personnelId' => $personnelId, 'otherStatus' => $otherServiceStatus, 'serviceStatusId' => null, 'requestId' => $requestId, 'remarks' => $remarks]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO maintenance_activity (personnel_id, service_status_id, request_id, remarks) VALUES (:personnelId, :serviceStatusId, :requestId, :remarks)");
            $stmt->execute(['personnelId' => $personnelId, 'serviceStatusId' => $serviceStatusId, 'requestId' => $requestId, 'remarks' => $remarks]);
        }

        if ($stmt) {
            try {
                $stmt = $pdo->prepare('UPDATE request SET status = :status WHERE id = :requestId');
                $stmt->execute(['status' => 'completed', 'requestId' => $requestId]);
            } catch (PDOException $e) {
                // echo json_encode(["success" => false, "error" => "Database error: " . $e->getMessage()]);
                echo json_encode(["success" => false, "error" => "Database error"]);
                exit;
            }
        }
        echo json_encode(["success" => true, "message" => "Request marked as done"]);
    } catch (PDOException $e) {/* 
echo json_encode(["success" => false, "error" => "Database error: " . $e->getMessage()]); */
        echo json_encode(["success" => false, "error" => "Database error"]);
    }
} else {
    header("HTTP/1.1 405 Method Not Allowed");
}
