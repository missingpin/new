<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="header.css"> <!-- Include header CSS -->
    <script src="header.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div id="menu-toggle">
    <div class="topbar">
        <i class='bx bx-bell' style="font-size: 24px; color: white; margin-left: 20px; cursor: pointer;" onclick="toggleAlerts(); fetchLowStockProducts();"></i>
        <div class="user-info" style="position: relative;">
            <i id="user-icon" class='bx bx-user' style="font-size: 24px; color: white; margin-left: 20px; cursor: pointer;" onclick="toggleUserMenu();"></i>
            <div id="user-menu" class="dropdown-menu" style="display: none;">
                <a href="#">Settings</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>



    <div id="alert-container" class="alert-container" style="display: none;">
        <div id="low-stock-alerts" class="low-stock-alerts"></div>
        <a href="alerts.php" class="go-to-alerts">Go to Alerts</a>
    </div>

    <div class="sidebar">
        <div class="logo-details">
            <i class='bx bx-package icon'></i>
            <div class="logo_name">PROJECT</div>
            <i class='bx bx-menu' id="btn"></i>
        </div>
        <ul class="nav-list">
            <li>
                <a href="dashboard.php">
                    <i class='bx bx-grid-alt'></i>
                    <span class="links_name">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>
            <li>
                <a href="table.php">
                    <i class='bx bx-pie-chart-alt-2'></i>
                    <span class="links_name">Inventory</span>
                </a>
                <span class="tooltip">Inventory</span>
            </li>
            </li>
        </ul>
    </div>

    <script>
        let sidebar = document.querySelector(".sidebar");
        let closeBtn = document.querySelector("#btn");

        closeBtn.addEventListener("click", () => {
            sidebar.classList.toggle("open");
            menuBtnChange(); // calling the function (optional)
        });

        function menuBtnChange() {
            if (sidebar.classList.contains("open")) {
                closeBtn.classList.replace("bx-menu", "bx-menu-alt-right"); // replacing the icons class
            } else {
                closeBtn.classList.replace("bx-menu-alt-right", "bx-menu"); // replacing the icons class
            }
        }

        // Alert functionality
        document.addEventListener("DOMContentLoaded", function() {
            fetchLowStockProducts();
            setInterval(fetchLowStockProducts, 10000);
            document.addEventListener('click', function(event) {
                const alertContainer = document.getElementById('alert-container');
                if (!alertContainer.contains(event.target) && !event.target.classList.contains('bx-bell')) {
                    alertContainer.style.display = 'none';
                }
            });
        });

        function toggleAlerts() {
            const alertContainer = document.getElementById('alert-container');
            alertContainer.style.display = alertContainer.style.display === 'none' ? 'block' : 'none';
        }

        function fetchLowStockProducts() {
            console.log("Fetching low stock products...");
            fetch('alertcheck.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Fetched alerts:", data);
                    displayLowStockAlerts(data);
                })
                .catch(error => console.error('Error fetching low stock products:', error));
        }

        function displayLowStockAlerts(products) {
    const alertContainer = document.getElementById('low-stock-alerts');
    alertContainer.innerHTML = '';

    if (products.length === 0) {
        alertContainer.innerHTML = '<p>No low stock alerts.</p>';
    } else {
        const limitedProducts = products.slice(0, 5);
        limitedProducts.forEach(product => {
            const alertItem = document.createElement('div');
            alertItem.className = 'alert alert-warning';
            alertItem.innerHTML = product.message; // Ensure product.message is defined
            alertContainer.appendChild(alertItem);
        });
    }
}

    </script>
</body>
</html>
