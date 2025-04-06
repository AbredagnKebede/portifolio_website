// Function to load components (header and footer)
function loadComponent(id, file) {
    fetch(file)
        .then(response => response.text())
        .then(data => {
            document.getElementById(id).innerHTML = data;
        })
        .catch(error => console.error(`Error loading ${file}:`, error));
}

// Load header and footer dynamically
document.addEventListener("DOMContentLoaded", function () {
    loadComponent("header-container", "header.html");
    loadComponent("footer-container", "footer.html");
});
