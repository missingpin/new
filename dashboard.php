<?php
session_start();
include 'connect.php';
include 'sidebar.php';

if(isset($_SESSION['username'])) {
    echo null;
} else {
    header("Location:login.php"); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header Example</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <h1> Dashboard </h1>
    <div class="container">
        <div class="frame">
            <div class="text-container">
                <div class="header-text">Total Products</div>
                <div class="number">1</div>
            </div>
        </div>
        <div class="frame">
            <div class="text-container">
                <div class="header-text">Total Categories</div>
                <div class="number">2</div>
            </div>
        </div>
        <div class="frame">
            <div class="text-container">
                <div class="header-text">Overall Quantity</div>
                <div class="number">3</div>
            </div>
        </div>
    </div>
    <div class="gray-box">
        <div class = "text-box">Low Stock Items
            <div class = "rnumber"> 0 
            </div>
            </div>
        <div class = "text-box">About to expire Items
            <div class = "rnumber"> 0 
            </div>
            </div>
        <div class = "text-box">No Stock Products
            <div class = "rnumber"> 0 
            </div>
            </div>
        </div>
    </div>
</body>

</html>
