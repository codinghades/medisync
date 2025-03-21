function loadPage(page) {
    window.location.href = page + ".php";
}
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("accountButton").addEventListener("click", function () {
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