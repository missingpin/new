<head>
    <link rel="stylesheet" href="header.css">
    <script src="header.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar">
        <h1 class="alerts" onclick="toggleAlerts()">Alerts</h1>
        </div>
    </nav>

    <div id="alert-container" class="alert-container">
        <div id="low-stock-alerts" class="low-stock-alerts"></div>
        <a href="alerts.php" class="go-to-alerts">Go to Alerts</a>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        fetchLowStockProducts();
        setInterval(fetchLowStockProducts, 10000);  
        document.addEventListener('click', function(event) {
            const alertContainer = document.getElementById('alert-container');
            if (!alertContainer.contains(event.target) && !event.target.classList.contains('alerts')) {
                alertContainer.style.display = 'none';
            }
        });
    });

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
            alertItem.innerHTML = product.message; // Change from textContent to innerHTML
            alertContainer.appendChild(alertItem);
        });
    }
}

</script>

</body>
