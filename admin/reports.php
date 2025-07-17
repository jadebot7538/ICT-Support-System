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

// Get current user
$currentUser = $_SESSION['user_id'];

// Get filter parameters with proper validation
$startDate = isset($_GET['start_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['end_date']) ? $_GET['end_date'] : '';
$category = isset($_GET['category']) && is_numeric($_GET['category']) ? (int) $_GET['category'] : '';
$personnelId = isset($_GET['maintenancePersonnel']) && is_numeric($_GET['maintenancePersonnel']) ? (int) $_GET['maintenancePersonnel'] : '';
$status = 'completed'; // Always use completed status

// Build the where clause and parameters for all queries
$whereClause = ' R.status = :status';
$params = [':status' => $status];

// Add date filters if provided
if (!empty($startDate)) {
    $whereClause .= " AND R.created_at >= :start_date";
    $params[':start_date'] = $startDate . " 00:00:00";
}

if (!empty($endDate)) {
    $whereClause .= " AND R.created_at <= :end_date";
    $params[':end_date'] = $endDate . " 23:59:59";
}

// Add category filter if provided
if (!empty($category)) {
    $whereClause .= " AND S.id = :category";
    $params[':category'] = $category;
}

// Add personnel filter if provided
if (!empty($personnelId)) {
    $whereClause .= " AND MP.id = :personnel_id";
    $params[':personnel_id'] = $personnelId;
}

// Common table joins for all queries
$commonJoins = 'FROM 
    request R
    LEFT JOIN sub_service SS ON R.sub_category_id = SS.id
    LEFT JOIN service S ON SS.service_id = S.id
    INNER JOIN location L ON R.location_id = L.location_id
    LEFT JOIN maintenance_activity MA ON R.id = MA.request_id
    LEFT JOIN service_status SStatus ON MA.service_status_id = SStatus.id
    INNER JOIN maintenance_personnel MP ON MA.personnel_id = MP.id';

// Build the main query
$sql = 'SELECT 
            R.ref_no as ref_no,
            MA.remarks as remarks,
            IFNULL(SStatus.name, MA.other_status) as service_status,
            IFNULL(SS.name, R.other_category) as sub_category, 
            IFNULL(S.name, "Others") as category,
            DATE_FORMAT(R.created_at, "%M %e, %Y - %l:%i%p") as created_at, 
            DATE_FORMAT(MA.created_at, "%M %e, %Y - %l:%i%p") as finished_at, 
            CONCAT(
                FLOOR(TIMESTAMPDIFF(SECOND, R.created_at, MA.created_at) / 86400), "d ",  
                LPAD(FLOOR(MOD(TIMESTAMPDIFF(SECOND, R.created_at, MA.created_at), 86400) / 3600), 2, "0"), "h:",
                LPAD(FLOOR(MOD(TIMESTAMPDIFF(SECOND, R.created_at, MA.created_at), 3600) / 60), 2, "0"), "m:",
                LPAD(MOD(TIMESTAMPDIFF(SECOND, R.created_at, MA.created_at), 60), 2, "0"), "s"
            ) as duration,
            R.emp_id as requestor_id,
            R.emp_name as requestor_name, 
            CONCAT(MP.first_name, " ", MP.last_name) as maintenance_personnel,
            L.location_name
        ' . $commonJoins . '
        WHERE ' . $whereClause . '
        ORDER BY R.created_at DESC';

// Reuse the common parts for statistics queries
$totalCountSql = 'SELECT COUNT(*) as count ' . $commonJoins . ' WHERE ' . $whereClause;
$earliestDateSql = 'SELECT MIN(created_at) as earliest_date FROM request WHERE status = "completed"';

try {
    // Fetch data with a single database connection
    
    // Prepare and execute the main query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get categories for filter dropdown
    $categoriesStmt = $pdo->prepare("SELECT id, name FROM service ORDER BY name");
    $categoriesStmt->execute();
    $categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get maintenance personnel for filter dropdown
    $personnelStmt = $pdo->prepare("SELECT id, CONCAT(first_name, ' ', last_name) as name FROM maintenance_personnel ORDER BY first_name");
    $personnelStmt->execute();
    $personnelList = $personnelStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get filtered statistics
    $totalStmt = $pdo->prepare($totalCountSql);
    $totalStmt->execute($params);
    $totalCount = $totalStmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // The completed count is the same as total count since we're only showing completed requests
    $completedCount = $totalCount;

    // GET earliest date for date picker
    $earliestStmt = $pdo->prepare($earliestDateSql);
    $earliestStmt->execute();
    $earliestDate = $earliestStmt->fetch(PDO::FETCH_ASSOC)['earliest_date'];
} catch (PDOException $e) {
    // Log the error instead of displaying it
    error_log('Database error: ' . $e->getMessage());
    $requests = [];
    $categories = [];
    $personnelList = [];
    $totalCount = 0;
    $completedCount = 0;
    $earliestDate = date('Y-m-d');
}

// Set default date ranges if not provided
if (empty($endDate)) {
    $endDate = date('Y-m-d');
}
if (empty($startDate) && !empty($earliestDate)) {
    $startDate = date('Y-m-d', strtotime($earliestDate));
}

// Helper function to check if a filter is active
function isFilterActive($value) {
    return !empty($value);
}

// Helper function to get category name by ID
function getCategoryNameById($categoryId, $categories) {
    foreach ($categories as $cat) {
        if ($cat['id'] == $categoryId) {
            return $cat['name'];
        }
    }
    return "Unknown";
}

// Helper function to get personnel name by ID
function getPersonnelNameById($personnelId, $personnelList) {
    foreach ($personnelList as $person) {
        if ($person['id'] == $personnelId) {
            return $person['name'];
        }
    }
    return "Unknown";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reports | Support System</title>
    <link rel="icon" href="../img/website-logo.svg" type="image/png">
    <link rel="shortcut icon" href="../img/website-logo.svg" type="image/png">
    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/reports.css">
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">
</head>

<body>
    <header class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center ">
                        <a class="btn btn-outline-secondary me-3" href="index.php">
                            &larr; Back
                        </a>
                        <h4 class="m-0">Report Generation</h4>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="user-info me-3">
                        <i class="bi bi-person-circle me-2"></i>
                        <span><?php echo htmlspecialchars($currentUser); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Filters -->
        <div class="report-container">
            <h5 class="card-title">Report Filters</h5>
            <form method="get" action="" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                            value="<?php echo htmlspecialchars($startDate); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                            value="<?php echo htmlspecialchars($endDate); ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="maintenancePersonnel" class="form-label">Maintenance Personnel</label>
                        <select class="form-select" id="maintenancePersonnel" name="maintenancePersonnel">
                            <option value="">All Maintenance Personnel</option>
                            <?php foreach ($personnelList as $person): ?>
                                <option value="<?php echo htmlspecialchars($person['id']); ?>" 
                                    <?php if ($personnelId == $person['id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($person['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="category" class="form-label">Service Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['id']); ?>" 
                                    <?php if ($category == $cat['id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="reports.php" class="btn btn-outline-secondary ms-2">Clear Filters</a>
                </div>

                <?php if (isFilterActive($startDate) || isFilterActive($endDate) || isFilterActive($category) || isFilterActive($personnelId)): ?>
                    <div class="mt-3">
                        <div class="filter-summary">
                            <strong>Active Filters:</strong>
                            <?php if (isFilterActive($startDate)): ?>
                                <span class="filter-badge"><i class="bi bi-calendar-event"></i>From:
                                    <?php echo htmlspecialchars($startDate); ?></span>
                            <?php endif; ?>

                            <?php if (isFilterActive($endDate)): ?>
                                <span class="filter-badge"><i class="bi bi-calendar-event"></i>To:
                                    <?php echo htmlspecialchars($endDate); ?></span>
                            <?php endif; ?>

                            <?php if (isFilterActive($category)): ?>
                                <span class="filter-badge"><i class="bi bi-tag"></i>Category:
                                    <?php echo htmlspecialchars(getCategoryNameById($category, $categories)); ?></span>
                            <?php endif; ?>
                            
                            <?php if (isFilterActive($personnelId)): ?>
                                <span class="filter-badge"><i class="bi bi-person"></i>Personnel:
                                    <?php echo htmlspecialchars(getPersonnelNameById($personnelId, $personnelList)); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </form>
        </div>
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="stats-card total-requests">
                    <div class="stats-icon">
                        <i class="bi bi-ticket-detailed"></i>
                    </div>
                    <div class="stats-number">
                        <?php echo $totalCount; ?>
                    </div>
                    <div class="stats-title">
                        <?php echo (isFilterActive($startDate) || isFilterActive($endDate) || isFilterActive($category) || isFilterActive($personnelId)) ? 'Filtered' : 'Total'; ?>
                        Requests
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="stats-card resolved-requests">
                    <div class="stats-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stats-number">
                        <?php echo $completedCount; ?>
                    </div>
                    <div class="stats-title">Completed Requests</div>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="report-container">
            <h5 class="card-title">Support Request Reports</h5>
            <?php if (empty($requests)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i> No records found matching your criteria.
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table id="reportsTable" class="table table-borderless table-hover">
                        <thead>
                            <tr>
                                <th>Request No.</th>
                                <th>Requesting Personnel</th>
                                <th>ID Number</th>
                                <th>Type of Support</th>
                                <th>Date Requested</th>
                                <th>Date Resolved</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $request): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($request['ref_no']); ?></td>
                                    <td><?php echo htmlspecialchars($request['requestor_name']); ?></td>
                                    <td><?php echo htmlspecialchars($request['requestor_id']); ?></td>
                                    <td>
                                        <?php
                                        $support = htmlspecialchars($request['category']);
                                        if ($request['sub_category'] != null && $request['sub_category'] != "Others") {
                                            $support .= " - " . htmlspecialchars($request['sub_category']);
                                        }
                                        echo $support;
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($request['created_at']); ?></td>
                                    <td>
                                        <?php echo !empty($request['finished_at']) ? htmlspecialchars($request['finished_at']) : '—'; ?>
                                    </td>
                                    <td>
                                        <?php echo !empty($request['remarks']) ? htmlspecialchars($request['remarks']) : '—'; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>


    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize DataTable only if there's data
            if ($('#reportsTable tbody tr').length > 0) {
                $('#reportsTable').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excel',
                            text: '<i class="bi bi-file-earmark-excel me-1"></i> Export Excel',
                            className: 'btn-sm',
                            title: 'ICT SUPPORT SERVICES ACCOMPLISHMEND REPORT HEAD OFFICE - <?php echo date("Y-m-d"); ?>'
                        },
                        {
                            text: '<i class="bi bi-file-pdf me-1"></i> Export PDF',
                            className: 'btn-sm',
                            action: function (e, dt, node, config) {
                                // Get current filter parameters
                                const urlParams = new URLSearchParams(window.location.search);
                                const startDate = urlParams.get('start_date') || '';
                                const endDate = urlParams.get('end_date') || '';
                                const category = urlParams.get('category') || '';
                                const maintenancePersonnel = urlParams.get('maintenancePersonnel') || '';
                                
                                // Create URL with parameters
                                let url = 'process/generate-report.php?';
                                url += 'start_date=' + encodeURIComponent(startDate) + '&';
                                 url += 'end_date=' + encodeURIComponent(endDate) + '&';
                                url += 'category=' + encodeURIComponent(category) + '&';
                              url += 'maintenancePersonnel=' + encodeURIComponent(maintenancePersonnel);
                                
                                // Remove trailing & if present
                                if (url.endsWith('&')) {
                                    url = url.slice(0, -1);
                                }

                                // Show loading indicator
                                Swal.fire({
                                    title: 'Generating PDF...',
                                    text: 'Please wait while the document is being prepared.',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });

                                // Fetch the PDF from server
                                fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded'
                                    },
                                })
                                .then(res => {
                                    if (!res.ok) {
                                        throw new Error(`HTTP error! Status: ${res.status}`);
                                    }
                                    return res.blob();
                                })
                                .then(blob => {
                                    // Create a URL for the PDF blob
                                    let pdfUrl = URL.createObjectURL(blob);
                                    
                                    // Open the PDF in a new tab
                                    window.open(pdfUrl, '_blank');
                                    
                                    // Close the SweetAlert2 loading popup
                                    Swal.close();
                                })
                                .catch(err => {
                                    console.error("Fetch Error:", err);
                                    
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: 'Failed to generate PDF. Please try again.',
                                    });
                                });
                            }
                        },
                    ],
                    "pageLength": 10,
                    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    "order": [[0, "desc"]]
                });
            }

            // Validate date inputs
            $('#start_date, #end_date').change(function () {
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();

                if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Date Range',
                        text: 'End date must be after start date',
                    });
                    $(this).val('');
                }
            });
        });
    </script>
</body>
</html>