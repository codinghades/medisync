function togglePanel(panel) {
    document.getElementById("loginPanel").style.display = "none";
    document.getElementById("registerPanel").style.display = "none";
    document.getElementById("forgotPanel").style.display = "none";
    document.getElementById(panel).style.display = "block";
}