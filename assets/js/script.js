function togglePanel(panel) {
    document.getElementById("loginPanel").classList.add("hidden");
    document.getElementById("registerPanel").classList.add("hidden");
    document.getElementById(panel).classList.remove("hidden");
}