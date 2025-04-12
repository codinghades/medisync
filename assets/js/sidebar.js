function loadPage(page) {
    window.location.href = page + ".php";
}

document.addEventListener("DOMContentLoaded", function () {
    // Fetch user data for the sidebar
    fetch("../process/getUser.php")
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(".firstName").textContent = data.firstName;
                document.querySelector(".lastName").textContent = data.lastName;
            }
        })
        .catch(() => console.error("Failed to load user data"));

    const logoutOverlay = document.getElementById('logoutOverlay');
    const logoutBtn = document.querySelector('#logoutButton button');
    const confirmLogout = document.getElementById('confirmLogout');
    const cancelLogout = document.getElementById('cancelLogout');

    logoutBtn.addEventListener('click', () => {
        logoutOverlay.style.display = 'flex';
    });

    cancelLogout.addEventListener('click', () => {
        logoutOverlay.style.display = 'none';
    });

    confirmLogout.addEventListener('click', () => {
        fetch("../process/logout.php", { method: "POST" })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = "../index.php";
                } else {
                    alert("Logout failed. Try again.");
                }
            })
            .catch(() => alert("An error occurred. Please try again."));
    });

    const buttons = document.querySelectorAll(".navigation button");

    buttons.forEach(btn => {
        btn.addEventListener("click", function () {
            buttons.forEach(button => button.classList.remove("active"));
            btn.classList.add("active");
        });
    });

    const currentPage = window.location.pathname.split("/").pop().replace(".php", "");

    buttons.forEach(btn => {
        const btnPage = btn.getAttribute("onclick")?.match(/loadPage\(['"](.+?)['"]\)/)?.[1];
        if (btnPage === currentPage) {
            btn.classList.add("active");
        }
    });
});
