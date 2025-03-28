function loadPage(page) {
    window.location.href = page + ".php";
}

document.addEventListener("DOMContentLoaded", function () {
    fetch("../process/getUser.php")
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(".firstName").textContent = data.firstName;
            document.querySelector(".lastName").textContent = data.lastName;
        }
    })
    .catch(() => console.error("Failed to load user data"));

    // Logout function
    document.getElementById("logoutButton").addEventListener("click", function () {
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
});
