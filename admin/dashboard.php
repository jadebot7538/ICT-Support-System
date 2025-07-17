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
try {
    function getMaintenancePersonnel($pdo)
    {
        $sql = "SELECT * FROM maintenance_personnel";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $maintenancePersonnel = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $maintenancePersonnel;
    }

    function getServiceStatus($pdo)
    {
        $sql = "SELECT * FROM service_status";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $serviceStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $serviceStatus;
    }
} catch (Exception $e) {
    echo "Error occurred: " . $e->getMessage();
}

?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Support System</title>

    <link rel="icon" href="../img/website-logo.svg" type="image/png">
    <link rel="shortcut icon" href="../img/website-logo.svg" type="image/png">
    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/responsive-table.css">
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
</head>

<body class="h-100">
    <div class="container-fluid py-3 h-100 position-relative" style="max-width: 1600px;">
        <button id="navToggleBtn" class="nav-toggle-btn ">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Wrap the header and tabs in a container that can be toggled -->
        <div id="navbarContainer" class="navbar-container ">
            <!-- Header and tabs content remains the same -->
            <div class="header-container d-flex justify-content-start align-items-center rounded">
                <a class="btn btn-outline-secondary me-3" onclick="history.back()">
                    &larr; Back
                </a>
                <h3 class="fw-bold m-0">ICT Support System</h3>
            </div>

            <!-- Navigation Tabs -->
            <div class="nav-tabs-custom">
                <div class="d-flex flex-row w-100">
                    <a class="tab-link active text-center w-100" href="#" data-status="request">Requests</a>
                    <a class="tab-link text-center w-100" href="#" data-status="pending">On-Going</a>
                    <a class="tab-link text-center w-100" href="#" data-status="completed">Marked as Done</a>
                </div>
            </div>
        </div>
        <!-- Table Container with Fixed Layout -->
        <div class="table-container h-100 ">
            <!-- Fixed Header with Search Controls -->
            <div class="table-header">
                <div class="search-controls">
                    <div>
                        Show
                        <select id="customLength" class="length-select">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        entries
                    </div>
                    <div>
                        Search:
                        <input type="text" id="customSearch" class="search-input" placeholder="Search records...">
                    </div>
                </div>
            </div>

            <!-- Scrollable Table Content -->
            <div class="table-content">
                <table class="table table-hover" id="mytable">
                    <thead id="mytable-header"></thead>
                    <tbody id="mytable-body" style="vertical-align: middle;"></tbody>
                </table>
            </div>

            <!-- Fixed Footer with Pagination -->
            <div class="table-footer">
                <div id="counter" class="mb-2">Total Records: 0</div>
                <div id="customPagination" class="pagination-container">
                    <ul class="pagination">
                        <!-- Pagination will be inserted here by JavaScript -->
                    </ul>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="markAsDone" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="markAsDoneLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form id="markAsDoneForm" class="modal-content">
                    <input type="text" name="csrfToken" value="<?php echo generateCSRFToken() ?>" hidden>
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="markAsDoneLabel">Completed Form</h1>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex flex-column gap-3 p-3">
                            <div class="d-flex flex-row align-items-end gap-3 ">
                                <p class="m-0 text-secondary fw-light">Ref no:</p>
                                <h4 class="m-0" id="markAsDoneRefNoText"></h4>
                                <input type="text" name="markAsDoneRefNo" hidden>
                            </div>
                            <div class="input-field">
                                <label class="fw-light" for="markAsDoneMaintenancePersonnel">Maintenance
                                    Personnel</label>
                                <select class="form-select" name="markAsDoneMaintenancePersonnel"
                                    id="markAsDoneMaintenancePersonnel">
                                    <option value=""></option>
                                    <?php
                                    $maintenancePersonnel = getMaintenancePersonnel($pdo);
                                    foreach ($maintenancePersonnel as $personnel) {
                                        echo "<option value='" . htmlspecialchars($personnel['id'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($personnel['first_name'], ENT_QUOTES, 'UTF-8') . " " . htmlspecialchars($personnel['last_name'], ENT_QUOTES, 'UTF-8') . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="input-field">
                                <label class="fw-light" for="markAsDoneStatus">Status</label>
                                <select class="form-select" name="markAsDoneStatus" id="markAsDoneStatus">
                                    <option value=""></option>
                                    <?php
                                    $serviceStatus = getServiceStatus($pdo);
                                    foreach ($serviceStatus as $status) {
                                        echo "<option value='" . htmlspecialchars($status['id'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($status['name'], ENT_QUOTES, 'UTF-8') . "</option>";
                                    }
                                    ?>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                            <div class="input-field">
                                <label for="others">Please specify:</label>
                                <textarea type="text" name="markAsDoneOthers" id="markAsDoneOthers" class="form-control"
                                    hidden></textarea>
                            </div>
                            <div class="input-field">
                                <label class="fw-light" for="markAsDoneRemarks">Remarks</label>
                                <textarea class="form-control" type="text" name="markAsDoneRemarks"
                                    id="markAsDoneRemarks" placeholder="Enter the Remarks here.."></textarea>
                            </div>

                            </script>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Mark as Done</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Request Details Modal -->
        <div class="modal fade" id="viewDetails" tabindex="-1">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Service Request Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="modalTableBody" class="p-2">
                            <div class="detail-row">
                                <div class="detail-label">Reference No.:</div>
                                <div class="detail-value" id="refNo">N/A</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Requestor ID:</div>
                                <div class="detail-value" id="requestorId">N/A</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Requestor Name:</div>
                                <div class="detail-value" id="requestorName">N/A</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Category:</div>
                                <div class="detail-value" id="category">N/A</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Sub Category:</div>
                                <div class="detail-value" id="subCategory">N/A</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Location:</div>
                                <div class="detail-value" id="location">N/A</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Request Date & Time:</div>
                                <div class="detail-value" id="requestDate">N/A</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Request Details Modal -->
        <div class="modal fade" id="viewDetailsCompleted" tabindex="-1">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Service Request Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="modalTableBodyCompleted" class="px-2">
                            <div class="detail-row">
                                <div class="detail-label">Reference No.:</div>
                                <div class="detail-value" id="refNoCompleted">N/A</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Service Status:</div>
                                <div class="detail-value" id="serviceStatusCompleted">N/A</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Requestor ID:</div>
                                <div class="detail-value" id="requestorIdCompleted">N/A</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Requestor Name:</div>
                                <div class="detail-value" id="requestorNameCompleted">N/A</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Category:</div>
                                <div class="detail-value" id="categoryCompleted">N/A</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Sub Category:</div>
                                <div class="detail-value" id="subCategoryCompleted">N/A</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Location:</div>
                                <div class="detail-value" id="locationCompleted">N/A</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Request Date & Time:</div>
                                <div class="detail-value" id="requestDateCompleted">N/A</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Completion Date & Time:</div>
                                <div class="detail-value" id="dateCompleted">N/A</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Service Duration:</div>
                                <div class="detail-value" id="duration">N/A</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Servicing Personnel:</div>
                                <div class="detail-value" id="maintenancePersonnelCompleted">N/A</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Remarks:</div>
                                <div class="detail-value" id="remarks">N/A</div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary-custom w-100 mt-4" onclick="getRefNo()">Generate
                            Receipt</button>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <!-- Include your original scripts -->
    <script src="js/dashboard-variable.js"></script>
    <script>
        // Function to toggle visibility of "Others" field
        function toggleOthersField() {
            const statusSelect = document.getElementById('markAsDoneStatus');
            const othersInput = document.getElementById('markAsDoneOthers');
            const othersLabel = othersInput.previousElementSibling;

            if (statusSelect.value === 'others') {
                othersInput.removeAttribute('hidden');
                othersLabel.style.display = 'block';
            } else {
                othersInput.setAttribute('hidden', '');
                othersLabel.style.display = 'none';
                othersInput.value = ''; // Clear the value when hidden
            }
        }

        // Run on page load
        document.addEventListener('DOMContentLoaded', toggleOthersField);

        // Run when status dropdown changes
        document.getElementById('markAsDoneStatus').addEventListener('change', toggleOthersField);
    </script>

    <!-- Custom scripts for handling DataTables -->
    <script>
        // Override the original DataTables initialization in fetchData function
        // Store the original jQuery.fn.DataTable function
        const originalDataTable = $.fn.DataTable;

        // Global variable to store table data
        let tableData = [];
        let currentPage = 0;
        let pageSize = 10;
        let filteredData = [];
        let searchTerm = '';
        let currentStatus = 'request';
        let isAutoRefresh = false;

        // Override DataTable constructor
        $.fn.DataTable = function (options) {
            // Use our custom table handling instead
            const table = $(this);

            // If it's an API call, pass through to the original
            if (typeof options === 'string' || options instanceof String) {
                return originalDataTable.apply(this, arguments);
            }

            // Return a dummy object with DataTables API methods
            return {
                search: function (term) {
                    searchTerm = term;
                    $('#customSearch').val(term);
                    filterTable();
                    return this;
                },
                page: {
                    len: function (length) {
                        pageSize = parseInt(length);
                        $('#customLength').val(length);
                        filterTable();
                        return this;
                    }
                },
                draw: function () {
                    // Rendering is handled by our custom functions
                    return this;
                },
                destroy: function () {
                    // Allow destroy to pass through
                    return this;
                }
            };
        };

        // Add a new method to jQuery that checks if DataTable is instantiated
        $.fn.dataTable.isDataTable = function () {
            // Always return false to prevent the original code from thinking there's a DataTable
            return false;
        };

        // Utility function to render a specific page of data
        function renderTable() {
            const start = currentPage * pageSize;
            const end = start + pageSize;
            const displayData = filteredData.slice(start, end);

            const tableBody = document.getElementById('mytable-body');
            tableBody.innerHTML = '';

            // Render table rows using the displayData
            displayData.forEach(row => {
                const tr = document.createElement('tr');

                if (currentStatus === 'request') {
                    loadRequest(tr, row);
                } else if (currentStatus === 'pending') {
                    loadPending(tr, row);
                } else if (currentStatus === 'completed') {
                    loadCompleted(tr, row);
                }

                tableBody.appendChild(tr);
            });

            // Update pagination
            renderPagination();

            // Update counter
            document.getElementById('counter').textContent = `Total Records: ${filteredData.length}`;
        }

        // Filter table based on search term
        function filterTable() {
            searchTerm = $('#customSearch').val().toLowerCase();

            if (searchTerm === '') {
                filteredData = [...tableData];
            } else {
                filteredData = tableData.filter(row => {
                    // Search in all properties
                    return Object.values(row).some(value =>
                        value && value.toString().toLowerCase().includes(searchTerm)
                    );
                });
            }

            // Only reset to first page when manually filtering (not during auto-refresh)
            if (!isAutoRefresh) {
                currentPage = 0;
            }

            // Make sure current page is valid based on filtered data
            const totalPages = Math.ceil(filteredData.length / pageSize);
            if (currentPage >= totalPages && totalPages > 0) {
                currentPage = totalPages - 1;
            }

            renderTable();
        }

        // Render pagination controls
        function renderPagination() {
            const totalPages = Math.ceil(filteredData.length / pageSize);
            const paginationElement = document.getElementById('customPagination').querySelector('ul');
            paginationElement.innerHTML = '';

            // Previous button
            const prevLi = document.createElement('li');
            prevLi.className = currentPage === 0 ? 'disabled' : '';
            const prevA = document.createElement('a');
            prevA.textContent = 'Previous';
            prevA.onclick = () => {
                if (currentPage > 0) {
                    currentPage--;
                    renderTable();
                }
            };
            prevLi.appendChild(prevA);
            paginationElement.appendChild(prevLi);

            // Page numbers
            const maxPages = 5; // Maximum page numbers to show
            const startPage = Math.max(0, Math.min(currentPage - Math.floor(maxPages / 2), totalPages - maxPages));
            const endPage = Math.min(startPage + maxPages, totalPages);

            for (let i = startPage; i < endPage; i++) {
                const li = document.createElement('li');
                li.className = i === currentPage ? 'active' : '';
                const a = document.createElement('a');
                a.textContent = i + 1;
                a.onclick = () => {
                    currentPage = i;
                    renderTable();
                };
                li.appendChild(a);
                paginationElement.appendChild(li);
            }

            // Next button
            const nextLi = document.createElement('li');
            nextLi.className = currentPage >= totalPages - 1 ? 'disabled' : '';
            const nextA = document.createElement('a');
            nextA.textContent = 'Next';
            nextA.onclick = () => {
                if (currentPage < totalPages - 1) {
                    currentPage++;
                    renderTable();
                }
            };
            nextLi.appendChild(nextA);
            paginationElement.appendChild(nextLi);
        }

        // Override the fetchData function to use our custom table
        let previousRequestCount = 0;

        function fetchData(status) {
            // Set flag to identify auto-refresh vs manual tab change
            console.log(status);
            isAutoRefresh = (status === currentStatus);
            currentStatus = status;

            fetch(`process/fetch-database.php?status=${status}`)
                .then((response) => response.json())
                .then((data) => {
                    // Store the data globally
                    tableData = data;
                    // Apply existing search filter to new data
                    if (searchTerm === '') {
                        filteredData = [...data];
                    } else {
                        filteredData = data.filter(row => {
                            return Object.values(row).some(value =>
                                value && value.toString().toLowerCase().includes(searchTerm)
                            );
                        });
                    }

                    // Handle notifications
                    if (status === "request") {
                        const newRequestCount = data.length - previousRequestCount;
                        if (newRequestCount > 0) {
                            showNotification(
                                "New Requests Added",
                                `You have ${newRequestCount} new request(s).`
                            );
                        }
                        previousRequestCount = data.length;
                    }

                    // Clear and rebuild the table header
                    const tableHeader = document.getElementById("mytable-header");
                    tableHeader.innerHTML = "";

                    // Add header
                    const trHead = document.createElement("tr");
                    if (status === "request" || status === "pending") {
                        loadRequestHead(trHead);
                    } else if (status === "completed") {
                        loadCompletedHead(trHead);
                    }
                    tableHeader.appendChild(trHead);

                    // Make sure current page is valid based on new data
                    const totalPages = Math.ceil(filteredData.length / pageSize);
                    if (currentPage >= totalPages && totalPages > 0) {
                        currentPage = totalPages - 1;
                    }

                    // Render with existing page position and search term
                    renderTable();
                })
                .catch((error) => console.error("Error fetching data:", error));
        }

        // Event listeners
        $(document).ready(function () {
            // Set up event handlers for custom controls
            $('#customSearch').on('keyup', function () {
                isAutoRefresh = false; // Manual search
                filterTable();
            });

            $('#customLength').on('change', function () {
                isAutoRefresh = false; // Manual page size change
                pageSize = parseInt($(this).val());
                currentPage = 0; // Reset to first page when changing page size
                renderTable();
            });

            // Initial fetch for the active tab
            const activeTab = document.querySelector('.tab-link.active');
            if (activeTab) {
                currentStatus = activeTab.dataset.status;
                fetchData(currentStatus);
            }

            // Handle tab clicks to set current status
            $(document).on('click', '.tab-link', function () {
                isAutoRefresh = false; // Manual tab change
                currentPage = 0; // Reset page when changing tabs
                currentStatus = this.dataset.status;
            });
        });

        function getRefNo() {
            let rowData = document.getElementById('viewDetailsCompleted').dataset.rowData;
            let parsedData = JSON.parse(rowData);

            // Show SweetAlert2 loading
            Swal.fire({
                title: 'Generating PDF...',
                text: 'Please wait while the document is being prepared.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading(); // Show loading animation
                }
            });

            fetch('process/generate.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `ref_no=${encodeURIComponent(parsedData.ref_no)}`
            })
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`HTTP error! Status: ${res.status}`);
                    }
                    return res.blob(); // Convert response to binary (PDF)
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

                    // Show error message if PDF fails to open
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to generate PDF. Please try again.',
                    });
                });
        }
    </script>
    <script src="js/navbar-toggle.js"></script>
    <script src="js/table-responsive.js"></script>
    <!-- Load your event handling script last -->
    <script src="js/dashboard.js"></script>

</body>

</html>