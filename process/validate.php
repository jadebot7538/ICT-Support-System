<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once "../database/config.php";
    session_start();
    require_once "../security/csrf.php";
    $data = json_decode(file_get_contents("php://input"), true);

    $InputValue = [
        "employeeId" => $data["employeeId"],
        "employeeName" => $data["employeeName"],
        "department" => $data["department"],
        "section" => $data["section"],
        "unit" => $data["unit"],
        "typeOfService" => $data["typeOfService"],
        "typeOfSubService" => $data["typeOfSubService"],
        "selectedLocationType" => $data["selectedLocationType"],
        "customOther" => $data["CustomOther"],
        'csrfToken' => $data['csrfToken'],
        "customService" => false,
        "locationType" => "",
        "locationId" => "",
    ];

    $Validation = [
        "employeeId" => "",
        "employeeName" => "",
        "typeOfService" => "",
        "typeOfSubService" => "",
        "selectedLocationType" => "as",
        "customOther" => ""
    ];
    $numberPattern = '/^[0-9]+$/';
    $stringPattern = '/^[A-Za-z]+$/';
    function generateRefNo($pdo)
    {
        try {
            // Get the current year and month
            $year = date('Y');
            $month = date('m'); // '01' for January, '02' for February, etc.
            $prefix = $year . '-' . $month; // Example: 2025-01

            // Query to get the latest ref_number of the current month
            $stmt = $pdo->prepare("SELECT ref_no FROM request 
        WHERE ref_no LIKE :prefix
        ORDER BY created_at DESC 
        LIMIT 1");

            $prefixWithWildcard = $prefix . '%'; // Add '%' in PHP
            $stmt->execute(['prefix' => $prefixWithWildcard]);

            if ($stmt->rowCount() > 0) {
                // Extract the last incrementing number
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $lastRef = $row['ref_no'];

                // Extract the last number (Example: 2025-010001 → "0001")
                $lastNumber = intval(substr($lastRef, -4));

                // Increment it
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                // If no records for this month, start at 00001
                $newNumber = '0001';
            }

            // Generate new reference number
            return $prefix . $newNumber;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    if (empty($InputValue['employeeId'])) {
        $Validation['employeeId'] = -1;
    } else {
        $Validation['employeeId'] = preg_match($numberPattern, $InputValue['employeeId']);
    }
    if (empty($InputValue['employeeName'])) {
        $Validation['employeeName'] = -1;
    } else {
        $Validation['employeeName'] = 1;
    }

    if (!empty($InputValue['selectedLocationType'])) {
        if (preg_match($stringPattern, $InputValue['selectedLocationType'])) {
            if ($InputValue['selectedLocationType'] == 'Unit') {
                $InputValue['locationType'] = $InputValue['selectedLocationType'];
                $InputValue['locationId'] = $InputValue['unit'];
            } else if ($InputValue['selectedLocationType'] == 'Section') {
                $InputValue['locationType'] = $InputValue['selectedLocationType'];
                $InputValue['locationId'] = $InputValue['section'];
            } else if ($InputValue['selectedLocationType'] == 'Department') {
                $InputValue['locationType'] = $InputValue['selectedLocationType'];
                $InputValue['locationId'] = $InputValue['department'];
            }
            $Validation['selectedLocationType'] = 1; // Valid
        } else {
            $Validation['selectedLocationType'] = 0; // Invalid
        }
    } else {
        $Validation['selectedLocationType'] = -1; // Empty
    }
    if (empty($InputValue['typeOfService'])) {
        $Validation['typeOfService'] = -1;
    } else {
        $Validation['typeOfService'] = 1;
    }

    if (empty($InputValue['typeOfSubService'])) {
        $Validation['typeOfSubService'] = -1;
    } else {
        $Validation['typeOfSubService'] = 1;
        if ($InputValue['typeOfSubService'] == 'others') {
            $InputValue['customService'] = true;
        }
        if ($InputValue['customService']) {
            if (empty($InputValue['customOther'])) {
                $Validation['customOther'] = -1;
            } else {
                $Validation['customOther'] = 1;
            }
        }
    }

    if (verifyCSRFToken($InputValue['csrfToken']) == false) {
        echo json_encode(['csrf' => false, 'error' => 'CSRF token validation failed', 'csrfToken' => $InputValue['csrfToken']]);
        exit;
    }

    if ($InputValue['customService']) {
        if ($Validation['employeeId'] == 1 && $Validation['employeeName'] == 1 && $Validation['selectedLocationType'] == 1 && $Validation['typeOfService'] == 1 && $Validation['typeOfSubService'] == 1 && $Validation['customOther'] == 1) {
            $refNo = generateRefNo($pdo);
            try {
                $stmt = $pdo->prepare("INSERT INTO request(ref_no,other_category, location_id, emp_id, emp_name)VALUES(?,?,?,?,?)");
                $stmt->execute([$refNo, $InputValue['customOther'], $InputValue['locationId'], $InputValue['employeeId'], $InputValue['employeeName']]);
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
            echo json_encode(['success' => true, 'validation' => $Validation, 'custom' => $InputValue['customService']]);
        } else {
            echo json_encode(['success' => false, 'validation' => $Validation, 'custom' => $InputValue['customService']]);
        }
    } else {
        if ($Validation['employeeId'] == 1 && $Validation['employeeName'] == 1 && $Validation['selectedLocationType'] == 1 && $Validation['typeOfService'] == 1 && $Validation['typeOfSubService'] == 1) {
            try {
                $refNo = generateRefNo($pdo);
                $stmt = $pdo->prepare("INSERT INTO request(ref_no,sub_category_id, location_id, emp_id, emp_name)VALUES(?,?,?,?,?)");
                $stmt->execute([$refNo, $InputValue['typeOfSubService'], $InputValue['locationId'], $InputValue['employeeId'], $InputValue['employeeName']]);
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
            echo json_encode(['success' => true, 'validation' => $Validation, 'custom' => $InputValue['customService']]);
        } else {
            echo json_encode(['success' => false, 'validation' => $Validation, 'custom' => $InputValue['customService']]);
        }
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
