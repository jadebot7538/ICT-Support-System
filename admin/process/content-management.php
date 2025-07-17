<?php
require_once "../../security/session.php";
require_once '../../security/csrf.php';
require_once "../../database/config.php";

// Get the tab from query string for redirects
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'services';

// Handle form submissions - Using POST-REDIRECT-GET pattern to prevent duplicate submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            // ==== PERSONNEL ACTIONS ====
            case 'create_personnel':
                $firstName = trim($_POST['first_name'] ?? '');
                $middleName = trim($_POST['middle_name'] ?? '');
                $lastName = trim($_POST['last_name'] ?? '');

                if (empty($firstName) || empty($lastName)) {
                    $_SESSION['error_message'] = "First name and last name cannot be empty.";
                } else {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO maintenance_personnel (first_name, middle_name, last_name) 
                                             VALUES (:first_name, :middle_name, :last_name)");
                        $stmt->execute([
                            'first_name' => $firstName,
                            'middle_name' => $middleName,
                            'last_name' => $lastName
                        ]);

                        // Set success message in session
                        $_SESSION['success_message'] = "Personnel added successfully.";
                    } catch (PDOException $e) {
                        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
                    }
                }
                header("Location: ../content-management.php?tab=personnel");
                exit;
                break;

            case 'update_personnel':
                $personnelId = intval($_POST['personnel_id'] ?? 0);
                $firstName = trim($_POST['first_name'] ?? '');
                $middleName = trim($_POST['middle_name'] ?? '');
                $lastName = trim($_POST['last_name'] ?? '');

                if (empty($firstName) || empty($lastName) || $personnelId <= 0) {
                    $_SESSION['error_message'] = "Invalid data for personnel update.";
                } else {
                    try {
                        $stmt = $pdo->prepare("UPDATE maintenance_personnel 
                                             SET first_name = :first_name, middle_name = :middle_name, last_name = :last_name
                                             WHERE id = :id");
                        $stmt->execute([
                            'first_name' => $firstName,
                            'middle_name' => $middleName,
                            'last_name' => $lastName,
                            'id' => $personnelId
                        ]);
                        $_SESSION['success_message'] = "Personnel updated successfully.";
                    } catch (PDOException $e) {
                        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
                    }
                }
                header("Location: ../content-management.php?tab=personnel");
                exit;
                break;

            case 'delete_personnel':
                $personnelId = intval($_POST['personnel_id'] ?? 0);

                if ($personnelId <= 0) {
                    $_SESSION['error_message'] = "Invalid personnel ID.";
                } else {
                    try {
                        // Use transaction to ensure data integrity
                        $pdo->beginTransaction();


                        $stmt = $pdo->prepare("UPDATE maintenance_personnel SET is_deleted = '1' WHERE id = :id");
                        $stmt->execute(['id' => $personnelId]);
                        $pdo->commit();

                        $_SESSION['success_message'] = "Personnel deleted successfully.";

                    } catch (PDOException $e) {
                        $pdo->rollBack();
                        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
                    }
                }
                header("Location: ../content-management.php?tab=personnel");
                exit;
                break;

            // ==== LOCATION ACTIONS ====
            case 'create_location':
                $locationName = trim($_POST['location_name'] ?? '');
                $locationType = trim($_POST['location_type'] ?? '');
                $parentLocationId = !empty($_POST['parent_location_id']) ? intval($_POST['parent_location_id']) : null;

                if (empty($locationName)) {
                    $_SESSION['error_message'] = "Location name cannot be empty.";
                } else if (empty($locationType)) {
                    $_SESSION['error_message'] = "Location type cannot be empty.";

                } else {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO location (location_name, location_type_id, parent_location_id) 
                                             VALUES (:location_name, :location_type, :parent_location_id)");
                        $stmt->execute([
                            'location_name' => $locationName,
                            'location_type' => $locationType,
                            'parent_location_id' => $parentLocationId
                        ]);

                        $_SESSION['success_message'] = "Location added successfully.";
                    } catch (PDOException $e) {
                        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
                    }
                }
                header("Location: ../content-management.php?tab=locations");
                exit;
                break;

            case 'update_location':
                $locationId = intval($_POST['location_id'] ?? 0);
                $locationName = trim($_POST['location_name'] ?? '');
                $locationType = trim($_POST['location_type'] ?? '');
                $parentLocationId = !empty($_POST['parent_location_id']) ? intval($_POST['parent_location_id']) : null;

                if (empty($locationName) || $locationId <= 0) {
                    $_SESSION['error_message'] = "Invalid data for location update.";
                } else if ($parentLocationId === $locationId) {
                    $_SESSION['error_message'] = "A location cannot be its own parent.";
                } else {
                    try {
                        // Check for circular references
                        if ($parentLocationId !== null) {
                            $circularRef = false;
                            $currentParent = $parentLocationId;

                            // Traverse up the hierarchy to check for circular references
                            while ($currentParent !== null && !$circularRef) {
                                $parentCheck = $pdo->prepare("SELECT parent_location_id FROM location WHERE location_id = :id");
                                $parentCheck->execute(['id' => $currentParent]);
                                $nextParent = $parentCheck->fetchColumn();

                                if ($nextParent == $locationId) {
                                    $circularRef = true;
                                }
                                $currentParent = $nextParent;
                            }

                            if ($circularRef) {
                                $_SESSION['error_message'] = "Cannot create circular reference in location hierarchy.";
                                header("Location: ../content-management.php?tab=locations");
                                exit;
                            }
                        }

                        if (empty($locationType)) {
                            $_SESSION['error_message'] = "Location type cannot be empty.";
                            header("Location: ../content-management.php?tab=locations");
                            exit;
                        }

                        $stmt = $pdo->prepare("UPDATE location 
                                             SET location_name = :location_name, 
                                                 location_type_id = :location_type, 
                                                 parent_location_id = :parent_location_id
                                             WHERE location_id = :location_id");
                        $stmt->execute([
                            'location_name' => $locationName,
                            'location_type' => $locationType,
                            'parent_location_id' => $parentLocationId,
                            'location_id' => $locationId
                        ]);

                        $_SESSION['success_message'] = "Location updated successfully.";
                    } catch (PDOException $e) {
                        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
                    }
                }
                header("Location: ../content-management.php?tab=locations");
                exit;
                break;

            case 'delete_location':
                $locationId = intval($_POST['location_id'] ?? 0);

                if ($locationId <= 0) {
                    $_SESSION['error_message'] = "Invalid location ID.";
                } else {
                    try {
                        // Use transaction for data integrity
                        $pdo->beginTransaction();


                        // Check if there are child locations
                        $checkChildStmt = $pdo->prepare("SELECT COUNT(*) FROM location WHERE parent_location_id = :id");
                        $checkChildStmt->execute(['id' => $locationId]);
                        $childCount = $checkChildStmt->fetchColumn();
                        if ($childCount > 0) {
                            $pdo->rollBack();
                            $_SESSION['error_message'] = "Cannot delete this location because it has child locations.";
                        } else {
                            $stmt = $pdo->prepare("UPDATE location SET is_deleted = '1' WHERE location_id = :id");
                            $stmt->execute(['id' => $locationId]);
                            $pdo->commit();

                            $_SESSION['success_message'] = "Location deleted successfully.";
                        }
                    } catch (PDOException $e) {
                        $pdo->rollBack();
                        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
                    }
                }
                header("Location: ../content-management.php?tab=locations");
                exit;
                break;

            // ==== SUB-SERVICE ACTIONS ====
            case 'create_service':
                $serviceName = trim($_POST['service_name'] ?? '');
                $serviceId = isset($_POST['service_id']) && !empty($_POST['service_id']) ? intval($_POST['service_id']) : null;

                if (empty($serviceName)) {
                    $_SESSION['error_message'] = "Sub-service name cannot be empty.";
                } elseif (empty($serviceId)) {
                    // Custom error for missing parent service
                    $_SESSION['error_message'] = "Please select a parent service for this sub-service.";
                } else {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO sub_service (name, service_id) 
                                             VALUES (:name, :service_id)");
                        $stmt->execute([
                            'name' => $serviceName,
                            'service_id' => $serviceId
                        ]);

                        $_SESSION['success_message'] = "Sub-service added successfully.";
                    } catch (PDOException $e) {
                        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
                    }
                }
                header("Location: ../content-management.php?tab=services");
                exit;
                break;

            case 'update_service':
                $subServiceId = intval($_POST['service_id'] ?? 0);
                $serviceName = trim($_POST['service_name'] ?? '');
                $parentServiceId = isset($_POST['parent_service_id']) && !empty($_POST['parent_service_id'])
                    ? intval($_POST['parent_service_id']) : null;

                if (empty($serviceName) || $subServiceId <= 0) {
                    $_SESSION['error_message'] = "Invalid data for sub-service update.";
                } elseif (empty($parentServiceId)) {
                    // Custom error for missing parent service
                    $_SESSION['error_message'] = "Please select a parent service for this sub-service.";
                } else {
                    try {
                        $stmt = $pdo->prepare("UPDATE sub_service 
                                             SET name = :name, service_id = :service_id
                                             WHERE id = :id");
                        $stmt->execute([
                            'name' => $serviceName,
                            'service_id' => $parentServiceId,
                            'id' => $subServiceId
                        ]);

                        $_SESSION['success_message'] = "Sub-service updated successfully.";
                    } catch (PDOException $e) {
                        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
                    }
                }
                header("Location: ../content-management.php?tab=services");
                exit;
                break;

            case 'delete_service':
                $subServiceId = intval($_POST['service_id'] ?? 0);

                if ($subServiceId <= 0) {
                    $_SESSION['error_message'] = "Invalid sub-service ID.";
                } else {
                    try {
                        // Use transaction for data integrity
                        $pdo->beginTransaction();


                        $stmt = $pdo->prepare("UPDATE sub_service SET is_deleted = '1' WHERE id = :id");
                        $stmt->execute(['id' => $subServiceId]);
                        $pdo->commit();

                        $_SESSION['success_message'] = "Sub-service deleted successfully.";

                    } catch (PDOException $e) {
                        $pdo->rollBack();
                        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
                    }
                }
                header("Location: ../content-management.php?tab=services");
                exit;
                break;


            // ==== EMPLOYEE ACTIONS ====
            case 'create_employee':
                $firstName = trim($_POST['first_name'] ?? '');
                $lastName = trim($_POST['last_name'] ?? '');
                $govId = trim($_POST['gov_id'] ?? '');

                if (empty($firstName) || empty($lastName)) {
                    $_SESSION['error_message'] = "First name and last name are required fields.";
                } elseif (!empty($govId) && !is_numeric($govId)) {
                    $_SESSION['error_message'] = "Government ID must contain only numbers.";
                } else {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO employee (first_name, last_name, gov_id) 
                                             VALUES (:first_name, :last_name, :gov_id)");
                        $stmt->execute([
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                            'gov_id' => $govId
                        ]);

                        $_SESSION['success_message'] = "Employee added successfully.";
                    } catch (PDOException $e) {
                        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
                    }
                }
                header("Location: ../content-management.php?tab=employees");
                exit;
                break;

            case 'update_employee':
                $employeeId = intval($_POST['employee_id'] ?? 0);
                $firstName = trim($_POST['first_name'] ?? '');
                $lastName = trim($_POST['last_name'] ?? '');
                $govId = trim($_POST['gov_id'] ?? '');

                if (empty($firstName) || empty($lastName) || $employeeId <= 0) {
                    $_SESSION['error_message'] = "Invalid data for employee update.";
                } elseif (!empty($govId) && !is_numeric($govId)) {
                    $_SESSION['error_message'] = "Government ID must contain only numbers.";
                } else {
                    try {
                        $stmt = $pdo->prepare("UPDATE employee 
                                             SET first_name = :first_name, 
                                                 last_name = :last_name, 
                                                 gov_id = :gov_id
                                             WHERE id = :id");
                        $stmt->execute([
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                            'gov_id' => $govId,
                            'id' => $employeeId
                        ]);

                        $_SESSION['success_message'] = "Employee updated successfully.";
                    } catch (PDOException $e) {
                        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
                    }
                }
                header("Location: ../content-management.php?tab=employees");
                exit;
                break;

            case 'delete_employee':
                $employeeId = intval($_POST['employee_id'] ?? 0);

                if ($employeeId > 0) {
                    try {
                        // Use transaction for data integrity
                        $pdo->beginTransaction();


                        $stmt = $pdo->prepare("UPDATE employee SET is_deleted = '1' WHERE id = :id");
                        $stmt->execute(['id' => $employeeId]);
                        $pdo->commit();

                        $_SESSION['success_message'] = "Employee deleted successfully.";

                    } catch (PDOException $e) {
                        $pdo->rollBack();
                        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
                    }
                }
                header("Location: ../content-management.php?tab=employees");
                exit;
                break;
        }
    }
} else {
    // Access denied - redirect to index page
    echo "<script>alert('Access Denied'); window.location.href = '../index.php';</script>";
    exit;
}
?>