document.addEventListener("DOMContentLoaded", () => {
  let statusRequest = "request";
  let finalInterval;
  let initialInterval;

  fetchData(statusRequest);
  initialInterval = setInterval(() => fetchData(statusRequest), 5000);
  document.querySelectorAll(".tab-link").forEach((tab) => {
    tab.addEventListener("click", (event) => {
      event.preventDefault();
      document
        .querySelectorAll(".tab-link")
        .forEach((t) => t.classList.remove("active"));
      event.target.classList.add("active");
      statusRequest = event.target.getAttribute("data-status");

      fetchData(statusRequest);
      NewInterval();
    });
  });

  function NewInterval() {
    clearInterval(initialInterval);
    clearInterval(finalInterval);
    finalInterval = setInterval(() => fetchData(statusRequest), 5000);
  }

  const markAsDoneForm = document.getElementById("markAsDone");
  markAsDoneForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const refNo = markAsDoneForm.querySelector("input[name='markAsDoneRefNo']");
    const maintenanceId = markAsDoneForm.querySelector(
      "select[name='markAsDoneMaintenancePersonnel']"
    );
    const serviceStatusId = markAsDoneForm.querySelector(
      "select[name='markAsDoneStatus']"
    );
    const others = markAsDoneForm.querySelector(
      "textarea[name='markAsDoneOthers']"
    );
    const remarks = markAsDoneForm.querySelector(
      "textarea[name='markAsDoneRemarks']"
    );
    const csrfToken = markAsDoneForm.querySelector("input[name='csrfToken']");

    let inputData = {
      requestId: refNo.value,
      personnelId: maintenanceId.value,
      serviceStatusId: serviceStatusId.value,
      otherServiceStatus: others.value,
      remarks: remarks.value,
      csrfToken: csrfToken.value,
    };

    fetch("process/markAsDoneForm.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(inputData),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          Swal.fire("Success", "Request marked as done!", "success").then(
            () => {
              statusRequest = "pending";
              fetchData(statusRequest);
            }
          );
          // Close the markAsDone modal
          const markAsDoneModal = document.querySelector(".modal");
          if (markAsDoneModal) {
            const bootstrapModal = bootstrap.Modal.getInstance(markAsDoneModal);
            if (bootstrapModal) {
              bootstrapModal.hide();
            } else {
              markAsDoneModal.style.display = "none";
            }
          }
        } else {
          Swal.fire("Error", data.error || "Failed to update record.", "error");
        }
      })
      .catch((error) => {
        Swal.fire("Error", "Something went wrong!", "error");
        console.error("Error sending request:", error);
      });
  });
});
