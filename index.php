<?php
define('APP_INITIALIZED', true);
require_once "security/session.php";
require_once "security/csrf.php";
require_once "database/config.php";

function decryptData($encryptedData)
{
    $key = "RMS2025";
    $cipher = "AES-256-CBC";
    $data = base64_decode($encryptedData);

    $iv_length = openssl_cipher_iv_length($cipher);
    $iv = substr($data, 0, $iv_length); // Extract IV
    $encryptedText = substr($data, $iv_length); // Extract encrypted text

    return openssl_decrypt($encryptedText, $cipher, $key, 0, $iv);
}

/* $employee_id = decryptData($_POST['employeeId']);
$first_name = decryptData($_POST['fname']);
$middle_name = decryptData($_POST['mname']);
$last_name = decryptData($_POST['lname']);
$ext_name = decryptData($_POST['extname']);
 */
$employee_id = "224092";
$first_name = "Jessica";
$middle_name = "Evangelista";
$last_name = "Franco";
$ext_name = "Jr.";

if ($employee_id == null || $employee_id == "" || $first_name == null || $first_name == "" || $middle_name == null || $middle_name == "" || $last_name == null || $last_name == "") {
    echo "<script>alert('Access Denied'); window.location.href = 'errors/error_page.php';</script>";
    exit();
}

// Decode the base64-encoded values
$stmt = $pdo->prepare('SELECT * FROM employee WHERE gov_id = :id');
$stmt->execute(['id' => $employee_id]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if ($employee) {
    $employee_id = $employee['gov_id'];
    $firstName = ucfirst(strtolower($employee['first_name']));
    $middleInitial = $employee['middle_name'] ? strtoupper(substr($employee['middle_name'], 0, 1)) . '.' : '';
    $lastName = ucfirst(strtolower($employee['last_name']));

    // Normalize extension
    $ext_nameRaw = trim($employee['ext']);
    $ext_name = '';
    if ($ext_nameRaw !== '') {
        $ext = strtoupper(rtrim($ext_nameRaw, ". ")); // Remove trailing dot(s) and spaces, make uppercase

        // Add dot for JR, SR only; else, no dot
        if (in_array($ext, ['JR', 'SR'])) {
            $ext_name = $ext . '.';
        } else {
            $ext_name = $ext; // e.g. III, IV, etc.
        }
    }

    // Construct the name, add ext only if not empty
    $employeeName = trim("{$firstName} {$middleInitial} {$lastName}" . ($ext_name ? " {$ext_name}" : ''));
}
?>

<?php
if ($employee) {
    ?>

    <!doctype html>
    <html lang="en" class="h-100">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ICT Service Request</title>
        <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
        <link rel="stylesheet" href="node_modules/sweetalert2/dist/sweetalert2.min.css">
        <link rel="icon" href="img/website-logo.svg" type="image/png">
        <link rel="shortcut icon" href="img/website-logo.svg" type="image/png">
        <link rel="stylesheet" href="css/global.css">
        <style>
            #form {
                @media screen and (min-width: 768px) {
                    width: 550px;

                }
            }
        </style>
    </head>

    <body class="h-100">


        <div class=" d-flex flex-column  w-100 h-100 ">
            <!-- height:calc(100vh - 100px); -->
            <div class="m-auto d-flex align-items-center ">
                <form id="form" method="POST" class="card overflow-auto h-100" style=" max-height: 950px; ">
                    <input type="hidden" name="csrfToken" id="csrfToken" value="<?php echo generateCSRFToken() ?>">
                    <div class="card-header text-center d-flex flex-column ">
                        <div class="d-flex flex-row align-items-center justify-content-center gap-2 pt-1">
                            <img src="img/nia-logo.png" alt="logo" style="width: 25px; height: 25px;">
                            <p class="m-0 fs-6 fw-bold p-0 ">NIA UPRIIS</p>
                        </div>
                        <div class="py-1">
                            <h4 class=" m-0 fw-bold">ICT Service </h4>
                            <p class="m-0 fw-light" style="font-size: .85rem;">Please submit your request for assistance.
                            </p>
                        </div>
                    </div>
                    <div class="card-body p-4 d-flex flex-column gap-3"
                        style="overflow: auto; max-height: calc(100vh - 200px); ">
                        <!--  <h5 class=" m-0 fw-bold ">ICT Service Request Form</h5> -->
                        <p class="fw-medium m-0 ">Employee Information</p>
                        <div class="input-field">
                            <label class="form-label fw-light" for="employeeId">Employee ID</label>
                            <input class="form-control" type="text" id="employeeId" name="employeeId"
                                value="<?php echo htmlspecialchars($employee_id, ENT_QUOTES, 'UTF-8'); ?>" disabled>
                            <div id="employeeIdFeedback" class="valid-feedback">
                            </div>
                        </div>
                        <div class="input-field">
                            <label class="form-label fw-light" for="employeeName">Employee Name</label>
                            <input class="form-control" type="text" id="employeeName" name="employeeName"
                                value="<?php echo htmlspecialchars($employeeName, ENT_QUOTES, 'UTF-8'); ?>" disabled>
                            <div id="employeeNameFeedback" class="valid-feedback">
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <p class="fw-medium m-0 p-0 mt-2">Location Details</p>

                            <div class="input-field">
                                <label class="form-label fw-light" for="department">Department/Division</label>
                                <select class="form-select" name="department" id="department">
                                    <option value=""></option>
                                    <?php

                                    $stmt = $pdo->prepare("SELECT l.location_id as id, 
                                                              l.location_name as name, 
                                                              lt.name as type  
                                                              FROM location l
                                                              LEFT JOIN location_type lt ON l.location_type_id = lt.id 
                                                              WHERE lt.name = 'department' OR lt.name = 'division'");
                                    $stmt->execute();
                                    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    foreach ($departments as $department) {
                                        $id = htmlspecialchars($department['id'], ENT_QUOTES, 'UTF-8');
                                        $type = htmlspecialchars($department['type'], ENT_QUOTES, 'UTF-8');
                                        $name = htmlspecialchars($department['name'], ENT_QUOTES, 'UTF-8');
                                        echo "<option value=\"{$id}\" data-type=\"{$type}\">{$name}</option>";
                                    }
                                    ?>
                                </select>
                                <div id="selectedLocationTypeFeedback" class="valid-feedback">
                                </div>
                            </div>



                            <div class="input-field">
                                <label class="form-label fw-light" for="section">Section</label>
                                <select class="form-select" name="section" id="section" disabled>
                                    <option value="" selected></option>
                                </select>
                            </div>

                            <div class="input-field">
                                <label class="form-label fw-light" for="unit">Unit</label>
                                <select class="form-select" name="unit" id="unit" disabled>
                                    <option value="" selected></option>
                                </select>
                            </div>
                            <div class="input-field">
                                <p class="fw-medium m-0 mt-2 py-2">Service Details</p>
                                <label class="form-label fw-light" for="typeOfService">Service Category</label>
                                <select class="form-select" name="typeOfService" id="typeOfService">
                                    <option value="" selected></option>
                                    <?php

                                    $stmt = $pdo->prepare("SELECT id as id, name as name  FROM service");
                                    $stmt->execute();
                                    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    foreach ($services as $service) {
                                        $id = htmlspecialchars($service['id'], ENT_QUOTES, 'UTF-8');
                                        $name = htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8');
                                        echo "<option value=\"{$id}\">{$name}</option>";
                                    }
                                    ?>
                                </select>
                                <div id="typeOfServiceFeedback" class="valid-feedback">
                                </div>
                            </div>
                            <div class="input-field mb-2">
                                <label class="form-label fw-light" for="typeOfSubService">Service Sub Category</label>
                                <select class="form-select" name="typeOfSubService" id="typeOfSubService" disabled>
                                    <option value="" selected></option>
                                </select>
                                <div id="typeOfSubServiceFeedback" class="valid-feedback">
                                </div>
                                <div id="CustomOthersContainer"
                                    class="d-flex align-items-end justify-content-end mt-2  d-none "
                                    style="vertical-align:bottom">
                                    <label class="form-label m-0 " for="CustomOthers" style="width:180px">Please
                                        Specify:</label>
                                    <style>
                                        .s:focus {
                                            border: 1px solid black !important;
                                        }
                                    </style>
                                    <input class="form-control s border-success border-top-0 border-start-0 border-end-0  "
                                        type="text" id="CustomOthers" style="">
                                </div>
                                <div id="customOthersFeedback" class="valid-feedback">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-1 py-3">
                        <button class="btn btn-success w-100" type="submit">Submit Request</button>

                    </div>
                </form>
            </div>
            <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
            <script src="js/validation.js"></script>
            <script src="js/index.js"></script>
            <script>
                function loadServices(serviceId) {
                    let subServiceSelect = document.getElementById("typeOfSubService");
                    let customService = document.getElementById("CustomOthersContainer");

                    subServiceSelect.innerHTML = '<option value="">Loading...</option>'; // Show loading text
                    subServiceSelect.disabled = true;

                    // Validate serviceId is a number to prevent injection
                    if (!serviceId || isNaN(parseInt(serviceId))) {
                        subServiceSelect.innerHTML = '<option value="">Invalid service selected</option>';
                        return;
                    }

                    // Sanitize the serviceId
                    const sanitizedServiceId = parseInt(serviceId).toString();

                    fetch(`process/get-sub-services.php?service_id=${sanitizedServiceId}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin' // Ensures cookies are sent with the request
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            subServiceSelect.innerHTML = '<option value=""></option>'; // Reset options
                            if (data && Array.isArray(data) && data.length > 0) {
                                data.forEach(item => {
                                    // Sanitize the values before adding to DOM
                                    const id = item.id ? String(item.id).replace(/[^\w-]/g, '') : '';
                                    const name = item.name ? String(item.name)
                                        .replace(/&/g, '&amp;')
                                        .replace(/</g, '&lt;')
                                        .replace(/>/g, '&gt;')
                                        .replace(/"/g, '&quot;')
                                        .replace(/'/g, '&#039;') : '';

                                    if (id && name) {
                                        subServiceSelect.innerHTML += `<option value="${id}">${name}</option>`;
                                    }
                                });
                                subServiceSelect.innerHTML += `<option value="others">Others</option>`;
                                subServiceSelect.disabled = false;
                            } else {
                                subServiceSelect.innerHTML = '<option value="">No sub-services available</option>';
                            }
                        })
                        .catch(error => {
                            console.error("Error fetching sub-services:", error);
                            subServiceSelect.innerHTML = '<option value="">Error loading sub-services</option>';
                            subServiceSelect.disabled = true;
                        });
                }

                document.getElementById("typeOfService").addEventListener("change", function () {
                    let serviceId = this.value;
                    let subServiceSelect = document.getElementById("typeOfSubService");
                    let customService = document.getElementById("CustomOthersContainer");

                    subServiceSelect.innerHTML = '<option value=""></option>';
                    subServiceSelect.disabled = true;
                    customService.classList.add("d-none"); // Hide custom input initially

                    if (serviceId) {
                        loadServices(serviceId); // Load sub-services based on selected service
                    }
                });

                document.getElementById("typeOfSubService").addEventListener("change", function () {
                    let customService = document.getElementById("CustomOthersContainer");
                    if (this.value === "others") {
                        customService.classList.remove("d-none");
                    } else {
                        customService.classList.add("d-none");
                    }
                });
            </script>


            <script>
                function loadLocations(parentId, childSelectId, type) {
                    let childSelect = document.getElementById(childSelectId);
                    childSelect.innerHTML = '<option value="">Loading...</option>'; // Show loading text
                    childSelect.disabled = true;

                    // Validate and sanitize inputs
                    if (!parentId || isNaN(parseInt(parentId))) {
                        childSelect.innerHTML = '<option value="">Invalid parent ID</option>';
                        return;
                    }

                    // Sanitize the type parameter
                    const validTypes = ['section', 'unit'];
                    if (!type || !validTypes.includes(type)) {
                        childSelect.innerHTML = '<option value="">Invalid location type</option>';
                        return;
                    }

                    // Use the sanitized values
                    const sanitizedParentId = parseInt(parentId).toString();
                    const sanitizedType = encodeURIComponent(type);

                    console.log(sanitizedType);
                    fetch(`process/get_location.php?parent_id=${sanitizedParentId}&type=${sanitizedType}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        },
                        credentials: 'same-origin' // Ensures cookies are sent with the request
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            childSelect.innerHTML = '<option value=""></option>'; // Reset options
                            if (data && Array.isArray(data) && data.length > 0) {
                                data.forEach(item => {
                                    // Sanitize values before adding to DOM
                                    const id = item.location_id ? String(item.location_id).replace(/[^\w-]/g, '') : '';
                                    const name = item.location_name ? String(item.location_name)
                                        .replace(/&/g, '&amp;')
                                        .replace(/</g, '&lt;')
                                        .replace(/>/g, '&gt;')
                                        .replace(/"/g, '&quot;')
                                        .replace(/'/g, '&#039;') : '';

                                    if (id && name) {
                                        childSelect.innerHTML += `<option value="${id}">${name}</option>`;
                                    }
                                });
                                childSelect.disabled = false;
                            } else {
                                childSelect.innerHTML = '<option value="">No locations available</option>';
                            }
                        })
                        .catch(error => {
                            console.error("Error fetching locations:", error);
                            childSelect.innerHTML = '<option value="">Error loading locations</option>';
                            childSelect.disabled = true;
                        });
                }

                Input.department.addEventListener("change", function () {
                    let departmentId = this.value;

                    // Reset all dropdowns
                    Input.section.innerHTML = '<option value=""></option>';
                    Input.unit.innerHTML = '<option value=""></option>';
                    Input.section.disabled = true;
                    Input.unit.disabled = true;

                    if (departmentId) {

                        loadLocations(departmentId, "section", "section"); // Load units under department
                        loadLocations(departmentId, "unit", "unit"); // Load units under department
                    }
                });



                Input.section.addEventListener("change", function () {
                    let sectionId = this.value;
                    let unitSelect = Input.unit;

                    unitSelect.innerHTML = '<option value=""></option>';

                    if (sectionId) {
                        loadLocations(sectionId, "unit", "unit"); // Load units under section
                    }
                });
            </script>
            <script>
                document.getElementById("form").addEventListener("submit", function (event) {
                    event.preventDefault();
                    let selectedLocationType = Input.unit.value ?
                        "Unit" :
                        Input.section.value ?
                            "Section" :
                            Input.department.value ?
                                Input.department.options[Input.department.selectedIndex].getAttribute('data-type') :
                                null;
                    InputValue = {
                        employeeId: Input.employeeId.value,
                        employeeName: Input.employeeName.value,
                        department: Input.department.value,
                        section: Input.section.value,
                        unit: Input.unit.value,
                        typeOfService: Input.typeOfService.value,
                        typeOfSubService: Input.typeOfSubService.value,
                        selectedLocationType: selectedLocationType,
                        CustomOther: Input.CustomOther.value,
                        csrfToken: Input.csrfToken.value
                    };

                    fetch("process/validate.php", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(InputValue),
                        credentials: 'same-origin' // Ensures cookies are sent with the request
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.csrf === false) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'An error occurred while submitting the form. Please try again.',
                                    showCancelButton: false,
                                    confirmButtonText: 'OK',
                                    allowOutsideClick: false,
                                });
                                return
                            }
                            InputValidation = {
                                employeeId: data.validation.employeeId,
                                employeeName: data.validation.employeeName,
                                selectedLocationType: data.validation.selectedLocationType,
                                typeOfService: data.validation.typeOfService,
                                typeOfSubService: data.validation.typeOfSubService,
                                CustomOther: data.validation.customOther
                            };

                            checkForm(InputValue, Input, InputFeedback, InputValidation, messageFeedback);
                            if (data.success == true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Your request has been successfully submitted',
                                    showCancelButton: false,
                                    confirmButtonText: 'OK',
                                    allowOutsideClick: false,
                                    heightAuto: false,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        Input.department.value = null;
                                        Input.section.value = null;
                                        Input.unit.value = null;
                                        Input.typeOfService.value = null;
                                        Input.typeOfSubService.value = null;
                                        Input.CustomOther.value = null;

                                        window.location.reload();
                                        window.location.href = "https://rms.niaupriis.com/";
                                    }
                                });

                            } else {

                            }
                        })
                        .catch(error => console.error("Error:", error));


                });
            </script>
    </body>

    </html>
    <?php
} else { ?>     <?php
       $generatedToken = generateCSRFToken(); // Generate a new CSRF token for the page
       $csrfToken = htmlspecialchars($generatedToken, ENT_QUOTES, 'UTF-8'); // Sanitize the token for output
       ?>
    <!doctype html>
    <html lang="en" class="h-100">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ICT Service Request</title>
        <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
        <link rel="stylesheet" href="node_modules/sweetalert2/dist/sweetalert2.min.css">
        <link rel="icon" href="img/website-logo.svg" type="image/png">
        <link rel="shortcut icon" href="img/website-logo.svg" type="image/png">
        <link rel="stylesheet" href="css/global.css">
    </head>

    <body class="h-100">
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Access Denied',
                text: 'You do not have permission to access this page. If you are a new employee, please register your account to request access.',
                showCancelButton: true,
                confirmButtonText: 'Register Now',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false,
                heightAuto: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send registration details via Fetch
                    fetch('process/register-new-employee.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            employee_id: "<?= htmlspecialchars($employee_id) ?>",
                            first_name: "<?= htmlspecialchars($first_name) ?>",
                            middle_name: "<?= htmlspecialchars($middle_name) ?>",
                            last_name: "<?= htmlspecialchars($last_name) ?>",
                            ext_name: "<?= htmlspecialchars($ext_name) ?>",
                            csrf_token: "<?= $csrfToken ?>"
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Request Submitted!',
                                    text: 'Please wait for admin or ICT approval. You can come back later to check your status.',
                                    confirmButtonText: 'OK',
                                    allowOutsideClick: false,
                                    heightAuto: false,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "https://rms.niaupriis.com/";
                                    }
                                });
                            } else {
                                if (data.duplicate) {
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Already Submitted',
                                        text: 'Your request is already in our system. Please wait for approval from admin or ICT.',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = "https://rms.niaupriis.com/";
                                        }
                                    });
                                } else if (data.csrf_token) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Invalid CSRF Token',
                                        text: data.message || 'An error occurred while submitting your request. Please try again later.',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = "https://rms.niaupriis.com/";
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Registration Failed',
                                        text: data.message || 'An error occurred while submitting your request. Please try again later.',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = "https://rms.niaupriis.com/";
                                        }
                                    });
                                }

                            }

                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Registration Failed',
                                text: 'An error occurred while submitting your request. Please try again later.',
                                confirmButtonText: 'OK'
                            });
                        });
                } else {
                    window.location.href = "https://rms.niaupriis.com/";

                }

            });
        </script>
    </body>

    </html>
    <?php

} ?>