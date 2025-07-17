// Add this to the end of your JavaScript or include in a separate file
document.addEventListener("DOMContentLoaded", function () {
  const navToggleBtn = document.getElementById("navToggleBtn");
  const navbarContainer = document.getElementById("navbarContainer");
  const tableContainer = document.querySelector(".table-container");

  // Toggle navbar visibility when button is clicked
  navToggleBtn.addEventListener("click", function () {
    navbarContainer.classList.toggle("collapsed");

    // Update table height based on navbar visibility
    if (navbarContainer.classList.contains("collapsed")) {
      tableContainer.style.height = "calc(100vh - 70px)";
    } else {
      tableContainer.style.height = "calc(100vh - 190px)";
    }

    // If you're using DataTables, you might need to adjust:
    if (typeof $.fn.dataTable !== "undefined") {
      $(window).trigger("resize"); // Help DataTables adjust column widths
    }
  });

  // Add FontAwesome if not already included in your project
  if (!document.querySelector('link[href*="fontawesome"]')) {
    const fontAwesome = document.createElement("link");
    fontAwesome.rel = "stylesheet";
    fontAwesome.href =
      "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css";
    document.head.appendChild(fontAwesome);
  }
});
