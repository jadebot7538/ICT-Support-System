/**
 * Validates the add service form to ensure parent service is selected
 */
function validateAddServiceForm() {
  const serviceName = document.getElementById("service_name").value;
  const serviceId = document.getElementById("service_id").value;

  if (serviceName.trim() === "") {
    Swal.fire({
      icon: "error",
      title: "Validation Error",
      text: "Sub-service name cannot be empty!",
    });
    return false;
  }

  if (serviceId === "") {
    Swal.fire({
      icon: "error",
      title: "Validation Error",
      text: "Please select a parent service for this sub-service!",
    });
    return false;
  }

  return true;
}

/**
 * Validates the edit service form to ensure parent service is selected
 */
function validateEditServiceForm() {
  const serviceName = document.getElementById("edit_service_name").value;
  const parentServiceId = document.getElementById(
    "edit_parent_service_id"
  ).value;

  if (serviceName.trim() === "") {
    Swal.fire({
      icon: "error",
      title: "Validation Error",
      text: "Sub-service name cannot be empty!",
    });
    return false;
  }

  if (parentServiceId === "") {
    Swal.fire({
      icon: "error",
      title: "Validation Error",
      text: "Please select a parent service for this sub-service!",
    });
    return false;
  }

  return true;
}

/**
 * Initializes DataTables when document is ready
 */
$(document).ready(function () {
  $(".datatable").DataTable({
    pageLength: 10,
    order: [[0, "asc"]],
  });

  // Check for circular references in parent location
  $("#editLocationForm").on("submit", function (e) {
    var locationId = $("#edit_location_id").val();
    var parentId = $("#edit_parent_location_id").val();

    if (locationId === parentId && parentId !== "") {
      e.preventDefault();
      alert("A location cannot be its own parent!");
    }
  });
});

/**
 * Edit personnel function - populates the edit personnel form
 */
function editPersonnel(id, firstName, middleName, lastName) {
  document.getElementById("edit_personnel_id").value = id;
  document.getElementById("edit_first_name").value = firstName;
  document.getElementById("edit_middle_name").value = middleName;
  document.getElementById("edit_last_name").value = lastName;
  new bootstrap.Modal(document.getElementById("editPersonnelModal")).show();
}

/**
 * Edit location function - populates the edit location form
 */
function editLocation(id, name, type, parentId) {
  document.getElementById("edit_location_id").value = id;
  document.getElementById("edit_location_name").value = name;
  document.getElementById("edit_location_type").value = type || "";

  if (parentId === null) {
    document.getElementById("edit_parent_location_id").value = "";
  } else {
    document.getElementById("edit_parent_location_id").value = parentId;
  }

  new bootstrap.Modal(document.getElementById("editLocationModal")).show();
}

/**
 * Edit service function - populates the edit service form
 */
function editService(id, name, parentId) {
  document.getElementById("edit_service_id").value = id;
  document.getElementById("edit_service_name").value = name;

  if (parentId === null) {
    document.getElementById("edit_parent_service_id").value = "";
  } else {
    document.getElementById("edit_parent_service_id").value = parentId;
  }

  new bootstrap.Modal(document.getElementById("editServiceModal")).show();
}

/**
 * Edit status function - populates the edit status form
 */
function editStatus(id, name) {
  document.getElementById("edit_status_id").value = id;
  document.getElementById("edit_status_name").value = name;
  new bootstrap.Modal(document.getElementById("editStatusModal")).show();
}

/**
 * Delete confirmation functions
 */
function confirmDeletePersonnel(id, name) {
  Swal.fire({
    title: "Delete Personnel",
    text: `Are you sure you want to delete ${name}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete it",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById("delete_personnel_id").value = id;
      document.getElementById("deletePersonnelForm").submit();
    }
  });
}

function confirmDeleteLocation(id, name) {
  Swal.fire({
    title: "Delete Location",
    text: `Are you sure you want to delete ${name}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete it",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById("delete_location_id").value = id;
      document.getElementById("deleteLocationForm").submit();
    }
  });
}

function confirmDeleteService(id, name) {
  Swal.fire({
    title: "Delete Sub-Service",
    text: `Are you sure you want to delete ${name}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete it",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById("delete_service_id").value = id;
      document.getElementById("deleteServiceForm").submit();
    }
  });
}

function confirmDeleteStatus(id, name) {
  Swal.fire({
    title: "Delete Status",
    text: `Are you sure you want to delete ${name}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete it",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById("delete_status_id").value = id;
      document.getElementById("deleteStatusForm").submit();
    }
  });
}
