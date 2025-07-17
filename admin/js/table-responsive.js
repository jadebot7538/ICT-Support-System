// Add this to enhance table responsiveness
document.addEventListener("DOMContentLoaded", function () {
  // Function to adjust table height on viewport change
  function adjustTableHeight() {
    const navbarContainer = document.getElementById("navbarContainer");
    const tableContainer = document.querySelector(".table-container");

    if (navbarContainer.classList.contains("collapsed")) {
      tableContainer.style.height = "calc(100vh - 0px)";
    } else {
      tableContainer.style.height = "calc(100vh - 190px)";
    }
  }

  // Call on resize and orientation change
  window.addEventListener("resize", adjustTableHeight);
  window.addEventListener("orientationchange", adjustTableHeight);

  // Initial adjustment
  adjustTableHeight();

  // Enhance table cell handling for better mobile view
  const tableHeaders = document.querySelectorAll("#mytable th");
  const tableRows = document.querySelectorAll("#mytable tbody tr");

  // Function to check if we should simplify the table for very small screens
  function handleSmallScreens() {
    if (window.innerWidth < 576) {
      // Add data-label attributes to cells for mobile view
      tableRows.forEach((row) => {
        const cells = row.querySelectorAll("td");
        cells.forEach((cell, index) => {
          if (tableHeaders[index]) {
            cell.setAttribute("data-label", tableHeaders[index].textContent);
          }
        });
      });
    }
  }

  // Call on resize
  window.addEventListener("resize", handleSmallScreens);

  // Initial call
  handleSmallScreens();
});
