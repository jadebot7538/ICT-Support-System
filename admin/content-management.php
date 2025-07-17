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

$validTabs = ['services', 'personnel', 'locations', 'employees'];
$activeTab = in_array($_GET['tab'] ?? '', $validTabs) ? $_GET['tab'] : 'services';

$currentUser = $_SESSION['user_id'];
$csrf_token = generateCSRFToken();
$successMessage = $_SESSION['success_message'] ?? '';
$errorMessage = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

switch ($activeTab) {
    case 'personnel':
        try {
            $personnel = $pdo->query(
                "SELECT id, first_name, middle_name, last_name
                 FROM maintenance_personnel 
                 WHERE is_deleted = '0'
                 ORDER BY last_name, first_name"
            )->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            //  $errorMessage = "Database error" . $e->getMessage();
            $errorMessage = "Database error";
            $personnel = [];
        }
        break;
    case 'locations':
        try {
            $locationTypes = $pdo->query(
                "SELECT DISTINCT lt.name, lt.id
                 FROM location l
                 LEFT JOIN location_type lt ON l.location_type_id = lt.id
                 WHERE lt.name IS NOT NULL 
                 AND lt.name != '' 
                 ORDER BY lt.name"
            )->fetchAll(PDO::FETCH_ASSOC);
            $locations = $pdo->query(
                "SELECT l.location_id, l.location_name, lt.name as location_type_name, lt.id as location_type_id, l.parent_location_id, 
                        p.location_name as parent_name
                 FROM location l
                LEFT JOIN location_type lt ON l.location_type_id = lt.id
                 LEFT JOIN location p ON l.parent_location_id = p.location_id
                 WHERE l.is_deleted = '0'
                 ORDER BY l.location_name"
            )->fetchAll(PDO::FETCH_ASSOC);
            $parentLocations = $pdo->query(
                "SELECT location_id, location_name FROM location ORDER BY location_name"
            )->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $errorMessage = "Database error" . $e->getMessage();
            $locationTypes = $locations = $parentLocations = [];
        }
        break;
    case 'services':
        try {
            $parentServices = $pdo->query(
                "SELECT id, name FROM service ORDER BY name"
            )->fetchAll(PDO::FETCH_ASSOC);
            $services = $pdo->query(
                "SELECT s.id, s.name, s.service_id, p.name as parent_service_name
                 FROM sub_service s
                 LEFT JOIN service p ON s.service_id = p.id
                 WHERE s.is_deleted = '0'
                 ORDER BY p.name, s.name"
            )->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $errorMessage = "Database error";
            $parentServices = $services = [];
        }
        break;
    case 'employees':
        try {
            $employees = $pdo->query(
                "SELECT id, first_name, last_name, gov_id 
                 FROM employee
                 WHERE is_deleted = '0'
                 ORDER BY  first_name"
            )->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $errorMessage = "Database error" . $e->getMessage();
            $employees = [];
        }
        break;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Content Management | Support System</title>
    <link rel="icon" href="../img/website-logo.svg" type="image/png">
    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/content-management.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <style>
        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .table thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            font-weight: 600;
            border-bottom: 2px solid #0d6efd;
        }
    </style>
</head>

<body class="h-100">
    <header class="page-header"
        style="background-color:#fff; box-shadow:0 0.125rem 0.25rem rgba(0,0,0,0.075); margin-bottom:1.5rem; padding:1rem 0">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <a class="btn btn-outline-secondary me-3" href="index.php">&larr; Back</a>
                        <h4 class="m-0">Content Management</h4>
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
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($successMessage) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($errorMessage) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <ul class="nav nav-tabs mb-4">
            <?php
            $tabs = [
                'services' => ['icon' => 'bi-list-check', 'label' => 'Sub-Services'],
                'personnel' => ['icon' => 'bi-people-fill', 'label' => 'Maintenance Personnel'],
                'locations' => ['icon' => 'bi-geo-alt-fill', 'label' => 'Locations'],
                'employees' => ['icon' => 'bi-person-badge-fill', 'label' => 'Employees']
            ];
            foreach ($tabs as $tab => $info): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $activeTab === $tab ? 'active' : '' ?>" href="?tab=<?= $tab ?>">
                        <i class="bi <?= $info['icon'] ?> me-1"></i> <?= $info['label'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($activeTab === 'personnel'): ?>
            <div class="card mb-4 h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Maintenance Personnel</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addPersonnelModal">
                        <i class="bi bi-plus-circle me-1"></i> Add Personnel
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover datatable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($personnel ?? [] as $index => $person): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($person['first_name']) ?></td>
                                        <td><?= htmlspecialchars($person['middle_name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($person['last_name']) ?></td>
                                        <td class="action-buttons">
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick='editPersonnel(
                                                    <?= (int) $person["id"] ?>,
                                                    <?= json_encode($person["first_name"]) ?>,
                                                    <?= json_encode($person["middle_name"] ?? "") ?>,
                                                    <?= json_encode($person["last_name"]) ?>
                                                )'>
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick='confirmDeletePersonnel(
                                                    <?= (int) $person["id"] ?>,
                                                    <?= json_encode($person["first_name"] . " " . $person["last_name"]) ?>
                                                )'>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($personnel ?? [])): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No personnel records found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php elseif ($activeTab === 'locations'): ?>
            <div class="card mb-4 h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Maintenance Locations</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addLocationModal">
                        <i class="bi bi-plus-circle me-1"></i> Add Location
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover datatable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Location Name</th>
                                    <th>Location Type</th>
                                    <th>Parent Location</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($locations ?? [] as $index => $location): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($location['location_name']) ?></td>
                                        <td><?= htmlspecialchars($location['location_type_name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($location['parent_name'] ?? '') ?></td>
                                        <td class="action-buttons">
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick='editLocation(
                                                <?= (int) $location["location_id"] ?>,
                                                <?= json_encode($location["location_name"]) ?>,
                                                <?= $location["location_type_id"] ? (int) $location["location_type_id"] : "null" ?>,
                                                <?= $location["parent_location_id"] ? (int) $location["parent_location_id"] : "null" ?>
                                            )'>
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick='confirmDeleteLocation(
                                                    <?= (int) $location["location_id"] ?>,
                                                    <?= json_encode($location["location_name"]) ?>
                                                )'>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($locations ?? [])): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No location records found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php elseif ($activeTab === 'services'): ?>
            <div class="card mb-4 h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sub-Services</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addServiceModal">
                        <i class="bi bi-plus-circle me-1"></i> Add Sub-Service
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover datatable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Sub-Service Name</th>
                                    <th>Parent Service</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($services ?? [] as $index => $service): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($service['name']) ?></td>
                                        <td><?= htmlspecialchars($service['parent_service_name'] ?? 'None') ?></td>
                                        <td class="action-buttons">
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick='editService(
                                                    <?= (int) $service["id"] ?>,
                                                    <?= json_encode($service["name"]) ?>,
                                                    <?= $service["service_id"] ? (int) $service["service_id"] : "null" ?>
                                                )'>
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick='confirmDeleteService(
                                                    <?= (int) $service["id"] ?>,
                                                    <?= json_encode($service["name"]) ?>
                                                )'>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($services ?? [])): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No sub-service records found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php elseif ($activeTab === 'employees'): ?>
            <div class="card mb-4 h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Employees</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addEmployeeModal">
                        <i class="bi bi-plus-circle me-1"></i> Add Employee
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover datatable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Government ID</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($employees ?? [] as $index => $employee): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($employee['first_name']) ?></td>
                                        <td><?= htmlspecialchars($employee['last_name']) ?></td>
                                        <td><?= htmlspecialchars($employee['gov_id'] ?? '') ?></td>
                                        <td class="action-buttons">
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick='editEmployee(
                                                    <?= (int) $employee["id"] ?>,
                                                    <?= json_encode($employee["first_name"]) ?>,
                                                    <?= json_encode($employee["last_name"]) ?>,
                                                    <?= json_encode($employee["gov_id"] ?? "") ?>
                                                )'>
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick='confirmDeleteEmployee(
                                                    <?= (int) $employee["id"] ?>,
                                                    <?= json_encode($employee["first_name"] . " " . $employee["last_name"]) ?>
                                                )'>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($employees ?? [])): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No employee records found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'content-management-modals.php'; ?>

    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(function () {
            $('.datatable').DataTable({ pageLength: 10, lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]] });
        });

        // Personnel functions
        function editPersonnel(id, firstName, middleName, lastName) {
            document.getElementById('edit_personnel_id').value = id;
            document.getElementById('edit_first_name').value = firstName;
            document.getElementById('edit_middle_name').value = middleName;
            document.getElementById('edit_last_name').value = lastName;
            var editModal = new bootstrap.Modal(document.getElementById('editPersonnelModal'));
            editModal.show();
        }

        function confirmDeletePersonnel(id, name) {
            Swal.fire({
                title: 'Confirm Delete',
                text: 'Are you sure you want to delete ' + name + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete_personnel_id').value = id;
                    document.getElementById('deletePersonnelForm').submit();
                }
            });
        }

        // Location functions
        function editLocation(id, name, locationTypeId, parentId) {
            document.getElementById('edit_location_id').value = id;
            document.getElementById('edit_location_name').value = name;
            document.getElementById('edit_location_type').value = locationTypeId !== null ? locationTypeId : '';
            var parentSelect = document.getElementById('edit_parent_location_id');
            parentSelect.value = parentId !== null ? parentId : '';
            var editModal = new bootstrap.Modal(document.getElementById('editLocationModal'));
            editModal.show();
        }

        function confirmDeleteLocation(id, name) {
            Swal.fire({
                title: 'Confirm Delete',
                text: 'Are you sure you want to delete location "' + name + '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete_location_id').value = id;
                    document.getElementById('deleteLocationForm').submit();
                }
            });
        }

        // Service functions
        function validateAddServiceForm() {
            var serviceName = document.getElementById('service_name').value.trim();
            var serviceId = document.getElementById('service_id').value;
            if (!serviceName) {
                Swal.fire('Error', 'Please enter a sub-service name', 'error');
                return false;
            }
            if (!serviceId) {
                Swal.fire('Error', 'Please select a parent service', 'error');
                return false;
            }
            return true;
        }

        function editService(id, name, parentId) {
            document.getElementById('edit_service_id').value = id;
            document.getElementById('edit_service_name').value = name;
            var parentSelect = document.getElementById('edit_parent_service_id');
            parentSelect.value = parentId === null ? '' : parentId;
            var editModal = new bootstrap.Modal(document.getElementById('editServiceModal'));
            editModal.show();
        }

        function validateEditServiceForm() {
            var serviceName = document.getElementById('edit_service_name').value.trim();
            var parentServiceId = document.getElementById('edit_parent_service_id').value;
            if (!serviceName) {
                Swal.fire('Error', 'Please enter a sub-service name', 'error');
                return false;
            }
            if (!parentServiceId) {
                Swal.fire('Error', 'Please select a parent service', 'error');
                return false;
            }
            return true;
        }

        function confirmDeleteService(id, name) {
            Swal.fire({
                title: 'Confirm Delete',
                text: 'Are you sure you want to delete sub-service "' + name + '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete_service_id').value = id;
                    document.getElementById('deleteServiceForm').submit();
                }
            });
        }

        // Employee functions
        function editEmployee(id, firstName, lastName, govId) {
            document.getElementById('edit_employee_id').value = id;
            document.getElementById('edit_emp_first_name').value = firstName;
            document.getElementById('edit_emp_last_name').value = lastName;
            document.getElementById('edit_emp_gov_id').value = govId || '';
            var editModal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
            editModal.show();
        }

        function confirmDeleteEmployee(id, name) {
            Swal.fire({
                title: 'Confirm Delete',
                text: 'Are you sure you want to delete employee "' + name + '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete_employee_id').value = id;
                    document.getElementById('deleteEmployeeForm').submit();
                }
            });
        }

    </script>
</body>

</html>