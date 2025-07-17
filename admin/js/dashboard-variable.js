function loadRequestHead(tr) {
  const refNo = document.createElement("th");
  refNo.textContent = "Reference No.";
  refNo.style.display = "none";
  refNo.style.textAlign = "center";
  refNo.style.fontWeight = "bold";
  refNo.style.height = "70px";
  tr.appendChild(refNo);

  const category = document.createElement("th");
  category.textContent = "Category";
  category.style.textAlign = "center";
  category.style.fontWeight = "bold";
  category.style.height = "70px";
  tr.appendChild(category);

  const subCategory = document.createElement("th");
  subCategory.textContent = "Sub Category";
  subCategory.style.textAlign = "center";
  subCategory.style.fontWeight = "bold";
  tr.appendChild(subCategory);

  const location = document.createElement("th");
  location.textContent = "Location";
  location.style.textAlign = "center";
  location.style.fontWeight = "bold";
  tr.appendChild(location);

  const empName = document.createElement("th");
  empName.textContent = "Name";
  empName.style.textAlign = "center";
  empName.style.fontWeight = "bold";
  tr.appendChild(empName);

  const dateTd = document.createElement("th");
  dateTd.style.width = "250px";
  dateTd.textContent = "Request Date";
  dateTd.style.textAlign = "center";
  dateTd.style.fontWeight = "bold";
  tr.appendChild(dateTd);

  const action = document.createElement("th");
  action.textContent = "Action";
  action.style.textAlign = "center";
  action.style.fontWeight = "bold";
  tr.appendChild(action);
}
function loadCompletedHead(tr) {
  const refNo = document.createElement("th");
  refNo.textContent = "Reference No.";
  refNo.style.display = "none";
  refNo.style.textAlign = "center";
  refNo.style.fontWeight = "bold";
  refNo.style.height = "70px";
  tr.appendChild(refNo);
  const category = document.createElement("th");
  category.textContent = "Category";
  category.style.textAlign = "center";
  category.style.fontWeight = "bold";
  tr.appendChild(category);

  const subCategory = document.createElement("th");
  subCategory.textContent = "Sub Category";
  subCategory.style.textAlign = "center";
  subCategory.style.fontWeight = "bold";
  subCategory.style.padding = "5px";
  tr.appendChild(subCategory);

  const location = document.createElement("th");
  location.textContent = "Location";
  location.style.textAlign = "center";
  location.style.fontWeight = "bold";
  location.style.padding = "5px";
  tr.appendChild(location);

  const empName = document.createElement("th");
  empName.textContent = "Name";
  empName.style.textAlign = "center";
  empName.style.fontWeight = "bold";
  empName.style.padding = "5px";
  tr.appendChild(empName);

  const createdDateTd = document.createElement("th");
  createdDateTd.style.width = "250px";
  createdDateTd.textContent = "Request Date";
  createdDateTd.style.textAlign = "center";
  createdDateTd.style.fontWeight = "bold";
  createdDateTd.style.padding = "5px";
  tr.appendChild(createdDateTd);

  const finishedAt = document.createElement("th");
  finishedAt.style.width = "250px";
  finishedAt.textContent = "Completed Date";
  finishedAt.style.textAlign = "center";
  finishedAt.style.fontWeight = "bold";
  finishedAt.style.padding = "5px";
  tr.appendChild(finishedAt);
}
function loadRequest(tr, row) {
  /* 
  tr.setAttribute("data-bs-category", row.category);
  tr.setAttribute("data-bs-subcategory", row.sub_category);
  tr.setAttribute("data-bs-location", row.location_name);
  tr.setAttribute("data-bs-requester", row.emp_name);
  tr.setAttribute("data-bs-createdAt", row.created_at); */
  const refNo = document.createElement("td");
  refNo.textContent = row.ref_no;
  refNo.style.display = "none";
  tr.appendChild(refNo);

  const category = document.createElement("td");
  category.textContent = row.category;
  category.style.textAlign = "center";
  category.style.padding = "0";
  category.style.margin = "0";
  tr.appendChild(category);
  category.addEventListener("click", () => openModal(row));

  const subCategory = document.createElement("td");
  subCategory.textContent = row.sub_category;
  subCategory.style.textAlign = "center";
  tr.appendChild(subCategory);
  subCategory.addEventListener("click", () => openModal(row));

  const location = document.createElement("td");
  location.textContent = row.location_name;
  location.style.textAlign = "center";
  tr.appendChild(location);
  location.addEventListener("click", () => openModal(row));

  const empName = document.createElement("td");
  empName.textContent = row.requestor_name;
  empName.style.textAlign = "center";
  tr.appendChild(empName);
  empName.addEventListener("click", () => openModal(row));

  const dateTd = document.createElement("td");
  dateTd.style.width = "250px";
  dateTd.textContent = row.created_at;
  dateTd.style.textAlign = "center";
  tr.appendChild(dateTd);
  dateTd.addEventListener("click", () => openModal(row));
  // Attach event

  //add button each row
  const buttonGroup = document.createElement("td");
  buttonGroup.style.width = "200px";
  buttonGroup.style.textAlign = "center";
  const acceptButton = document.createElement("button");
  acceptButton.type = "button";
  acceptButton.classList.add("btn", "btn-success", "me-2");
  acceptButton.textContent = "Accept";

  const rejectButton = document.createElement("button");
  rejectButton.type = "button";
  rejectButton.classList.add("btn", "btn-danger", "me-2");
  rejectButton.textContent = "Reject";
  buttonGroup.appendChild(rejectButton);
  buttonGroup.appendChild(acceptButton);

  tr.appendChild(buttonGroup);

  //events for accept button
  acceptButton.addEventListener("click", () => {
    fetch(`process/accept.php?id=${row.ref_no}`, {
      method: "POST",
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          Swal.fire({
            icon: "success",
            title: "Success",
            text: "Accepted successfully!",
          }).then(() => {
            window.location.reload();
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Failed",
            text: "Failed to accept request!",
          });
        }
      })
      .catch((error) => console.error("Error sending request:", error));
  });
  rejectButton.addEventListener("click", () => {
    Swal.fire({
      title: "Are you sure?",
      text: "Do you want to reject this request?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Yes, reject it!",
    }).then((result) => {
      if (result.isConfirmed) {
        fetch(`process/reject.php?id=${row.ref_no}`, {
          method: "POST",
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.success) {
              Swal.fire({
                icon: "success",
                title: "Success",
                text: "Request rejected successfully!",
              }).then(() => {
                window.location.reload();
              });
            } else {
              Swal.fire({
                icon: "error",
                title: "Failed",
                text: "Failed to reject request!",
              });
            }
          })
          .catch((error) => console.error("Error sending request:", error));
      }
    });
  });
}

function loadPending(tr, row) {
  /* 
  tr.setAttribute("data-bs-category", row.category);
  tr.setAttribute("data-bs-subcategory", row.sub_category);
  tr.setAttribute("data-bs-location", row.location_name);
  tr.setAttribute("data-bs-requester", row.emp_name);
  tr.setAttribute("data-bs-createdAt", row.created_at); */
  const refNo = document.createElement("td");
  refNo.textContent = row.ref_no;
  refNo.style.display = "none";
  tr.appendChild(refNo);
  const category = document.createElement("td");
  category.textContent = row.category;
  category.style.textAlign = "center";
  tr.appendChild(category);
  category.addEventListener("click", () => openModal(row));

  const subCategory = document.createElement("td");
  subCategory.textContent = row.sub_category;
  subCategory.style.textAlign = "center";
  tr.appendChild(subCategory);
  subCategory.addEventListener("click", () => openModal(row));

  const location = document.createElement("td");
  location.textContent = row.location_name;
  location.style.textAlign = "center";
  tr.appendChild(location);
  location.addEventListener("click", () => openModal(row));

  const empName = document.createElement("td");
  empName.textContent = row.requestor_name;
  empName.style.textAlign = "center";
  tr.appendChild(empName);
  empName.addEventListener("click", () => openModal(row));

  const dateTd = document.createElement("td");
  dateTd.style.width = "250px";
  dateTd.textContent = row.created_at;
  dateTd.style.textAlign = "center";
  tr.appendChild(dateTd);
  dateTd.addEventListener("click", () => openModal(row));
  // Attach event

  //add button each row
  const buttonGroup = document.createElement("td");
  buttonGroup.style.width = "200px";
  const markAsDone = document.createElement("button");
  markAsDone.type = "button";
  markAsDone.classList.add("btn", "btn-success", "me-2");
  markAsDone.textContent = "Mark as Done";
  buttonGroup.appendChild(markAsDone);
  tr.appendChild(buttonGroup);
  markAsDone.addEventListener("click", () => {
    // Open the modal and pass row data
    const markAsDoneModal = document.getElementById("markAsDone");
    const markAsDoneForm = markAsDoneModal.querySelector("#markAsDoneForm");

    const refNoText = markAsDoneForm.querySelector("#markAsDoneRefNoText");
    const refNo = markAsDoneForm.querySelector("[name='markAsDoneRefNo']");

    refNoText.innerHTML = row.ref_no;
    refNo.value = row.ref_no;
    const bootstrapModal = new bootstrap.Modal(markAsDoneModal);
    bootstrapModal.show();
  });
}

function loadCompleted(tr, row) {
  const refNo = document.createElement("td");
  refNo.textContent = row.ref_no;
  refNo.style.display = "none";
  tr.appendChild(refNo);
  const category = document.createElement("td");
  category.textContent = row.category;
  category.style.textAlign = "center";
  tr.appendChild(category);
  category.addEventListener("click", () => openModalCompleted(row));

  const subCategory = document.createElement("td");
  subCategory.textContent = row.sub_category;
  subCategory.style.textAlign = "center";
  tr.appendChild(subCategory);
  subCategory.addEventListener("click", () => openModalCompleted(row));

  const location = document.createElement("td");
  location.textContent = row.location_name;
  location.style.textAlign = "center";
  tr.appendChild(location);
  location.addEventListener("click", () => openModalCompleted(row));

  const empName = document.createElement("td");
  empName.textContent = row.requestor_name;
  empName.style.textAlign = "center";
  tr.appendChild(empName);
  empName.addEventListener("click", () => openModalCompleted(row));

  const createdAt = document.createElement("td");
  createdAt.style.width = "250px";
  createdAt.textContent = row.created_at;
  createdAt.style.textAlign = "center";
  tr.appendChild(createdAt);
  createdAt.addEventListener("click", () => openModalCompleted(row));

  const finishedAt = document.createElement("td");
  finishedAt.style.width = "250px";
  finishedAt.textContent = row.finished_at;
  finishedAt.style.textAlign = "center";
  tr.appendChild(finishedAt);
  finishedAt.addEventListener("click", () => openModalCompleted(row));
  // Attach event
}

function openModalMarkAsDone(rowData) {
  const modal = document.getElementById("viewDetails");
  modal.dataset.rowData = JSON.stringify(rowData); // Store rowData in dataset

  const bootstrapModal = new bootstrap.Modal(modal);
  bootstrapModal.show();
}

function openModal(rowData) {
  const modal = document.getElementById("viewDetails");
  modal.dataset.rowData = JSON.stringify(rowData); // Store rowData in dataset

  const bootstrapModal = new bootstrap.Modal(modal);
  bootstrapModal.show();
}

function openModalCompleted(rowData) {
  const modal = document.getElementById("viewDetailsCompleted");
  modal.dataset.rowData = JSON.stringify(rowData); // Store rowData in dataset

  const bootstrapModal = new bootstrap.Modal(modal);
  bootstrapModal.show();
}

const viewDetailsModal = document.getElementById("viewDetails");
const viewDetailsModalCompleted = document.getElementById(
  "viewDetailsCompleted"
);

viewDetailsModal.addEventListener("show.bs.modal", () => {
  const modal = document.getElementById("viewDetails"); // Get the modal element
  const rowData = JSON.parse(modal.dataset.rowData || "{}"); // Retrieve stored data
  // Check if data is correctly retrieved
  document.getElementById("refNo").textContent = rowData.ref_no || "N/A";
  document.getElementById("requestorId").textContent =
    rowData.requestor_id || "N/A";
  document.getElementById("requestorName").textContent =
    rowData.requestor_name || "N/A";
  document.getElementById("category").textContent = rowData.category || "N/A";
  document.getElementById("subCategory").textContent =
    rowData.sub_category || "N/A";
  document.getElementById("location").textContent =
    rowData.location_name || "N/A";
  document.getElementById("requestDate").textContent =
    rowData.created_at || "N/A";
});

viewDetailsModalCompleted.addEventListener("show.bs.modal", () => {
  const modal = document.getElementById("viewDetailsCompleted"); // Get the modal element
  const rowData = JSON.parse(modal.dataset.rowData || "{}"); // Retrieve stored data
  // Check if data is correctly retrieved

  document.getElementById("refNoCompleted").textContent =
    rowData.ref_no || "N/A";
  document.getElementById("serviceStatusCompleted").textContent =
    rowData.service_status || "N/A";
  document.getElementById("requestorIdCompleted").textContent =
    rowData.requestor_id || "N/A";
  document.getElementById("requestorNameCompleted").textContent =
    rowData.requestor_name || "N/A";
  document.getElementById("categoryCompleted").textContent =
    rowData.category || "N/A";
  document.getElementById("subCategoryCompleted").textContent =
    rowData.sub_category || "N/A";
  document.getElementById("locationCompleted").textContent =
    rowData.location_name || "N/A";
  document.getElementById("requestDateCompleted").textContent =
    rowData.created_at || "N/A";
  document.getElementById("dateCompleted").textContent =
    rowData.finished_at || "N/A";
  document.getElementById("duration").textContent = rowData.duration || "N/A";
  document.getElementById("maintenancePersonnelCompleted").textContent =
    rowData.maintenance_personnel || "N/A";
  document.getElementById("remarks").textContent = rowData.remarks || "N/A";
});

// Function to show notification
function showNotification(title, body) {
  // Check if the browser supports notifications
  if (!("Notification" in window)) {
    console.error("This browser does not support desktop notifications.");
    return;
  }

  // Request permission if not already granted
  if (Notification.permission === "granted") {
    // If permission is granted, create a notification
    new Notification(title, { body });
  } else if (Notification.permission !== "denied") {
    Notification.requestPermission().then((permission) => {
      if (permission === "granted") {
        new Notification(title, { body });
      }
    });
  }
}
