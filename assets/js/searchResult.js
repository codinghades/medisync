document.addEventListener("DOMContentLoaded", function () {
    const searchForm = document.getElementById("searchForm");
    const searchBar  = document.getElementById("searchBar");
    const resetBtn   = document.getElementById("reset");
    const sortSelect = document.getElementById("filter");
    const tbody      = document.querySelector(".list table tbody");
    let   searchTimeout;
  
    // 1) On load, fetch the default ordering:
    fetchDefaultPatients();
  
    // 2) Reset button reloads default:
    resetBtn.addEventListener("click", e => {
      e.preventDefault();
      searchBar.value = "";
      sortSelect.value = "";
      fetchDefaultPatients();
    });
  
    // 3) Live search (+ sort) uses the search endpoint:
    searchBar.addEventListener("input", () => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        fetchPatients(searchBar.value.trim(), sortSelect.value);
      }, 300);
    });
  
    sortSelect.addEventListener("change", () => {
      fetchPatients(searchBar.value.trim(), sortSelect.value);
    });
  
    searchForm.addEventListener("submit", e => e.preventDefault());
  
    function fetchDefaultPatients() {
      fetch("../process/adminPatients.php")
        .then(res => res.json())
        .then(updateTable)
        .catch(err => console.error(err));
    }
  
    function fetchPatients(search = "", filter = "") {
      fetch("../process/searchPatient.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ search, filter })
      })
      .then(res => res.json())
      .then(updateTable)
      .catch(err => console.error(err));
    }
  
    function updateTable(data) {
      tbody.innerHTML = "";
      if (!Array.isArray(data) || data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5">No results found.</td></tr>`;
        return;
      }
      data.forEach(pt => {
        const rd = pt.registration_date
          ? new Date(pt.registration_date)
              .toLocaleDateString("en-US",{year:"numeric",month:"long",day:"numeric"})
          : "Unknown";
        const ub = parseFloat(pt.unpaid_bill)>0
          ? `<span style="color:red;font-weight:700;">₱${pt.unpaid_bill}</span>`
          : `<span style="color:gray;font-weight:700;">None</span>`;
        const ap = pt.closest_appointment!=="None"
          ? `<span style="font-weight:700;">${pt.closest_appointment}</span>`
          : `<span style="color:gray;font-weight:700;">None</span>`;
        tbody.innerHTML += `
          <tr>
            <td>${pt.name}</td>
            <td>${rd}</td>
            <td>${
              pt.active_prescription==="Yes"
                ? `<span style="color:green;font-weight:700;">Active</span>`
                : `<span style="color:gray;font-weight:700;">None</span>`
            }</td>
            <td>${ub}</td>
            <td>${ap}</td>
          </tr>`;
      });
    }
  });
  