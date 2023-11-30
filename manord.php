<?php
ob_start();
session_start();

error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', 1);
if (!isset($_SESSION['role']) || ($_SESSION['role'] != "Manager" && $_SESSION['role'] != "Employee")) {
    header("location: login.php");
}
if (isset($_GET["logout"])) {
    unset($_SESSION['user']);
    unset($_SESSION['branch']);
    unset($_SESSION['role']);
    header("location: login.php");
}
ob_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Sheep's Toe - View Orders</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #FFFDF1;
        }

        .navbar {
            background-color: #FFFDF1;
            border-bottom: 4px solid #000;
            padding: 0;
        }

        .sub-header {
            background-color: #FFFDF1;
            padding: 15px 0;
            text-align: center;
            text-decoration: underline;
        }

        .sub-header::after {
            content: "";
            display: block;
            border-bottom: 1px solid #000;
            margin-top: 15px;
        }

        .order-box {
            border-radius: 10px;
            margin: 20px 0;
            padding: 20px;
        }

        .order-actions {
            margin-top: 10px;
        }

        /* Dynamic background colors based on order status */
        .inprogress {
            background-color: yellow;
        }

        .void {
            background-color: red;
        }

        .completed {
            background-color: green;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="Home.html">The Sheep's Toe</a>
        <img src="sheep-logo_sm.png" alt="Sheep Logo">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.html">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="order.html">Order</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.html">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?logout"><?php echo $_SESSION['user'] ?> - Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Sub Header -->
    <div class="sub-header">
        <h4><?php echo $_SESSION['user'] ?> - Todays Orders</h4>
    </div>

    <!-- Order Display Section -->
    <div class="container">
        <div class="row">
        <?php
// Sample order data (replace with your database query)
$orders = [
    [
        'OrderID' => 1,
        'TableNumber' => 4,
        'Details' => 'Burger, Fries',
        'Quantities' => '1, 2',
        'Price' => 15.99,
        'Status' => 'In Progress'
    ],
    [
        'OrderID' => 2,
        'TableNumber' => 8,
        'Details' => 'Pizza, Salad',
        'Quantities' => '1, 1',
        'Price' => 22.50,
        'Status' => 'Void'
    ],
    [
        'OrderID' => 3,
        'TableNumber' => 12,
        'Details' => 'Steak, Mashed Potatoes',
        'Quantities' => '1, 1',
        'Price' => 30.00,
        'Status' => 'Completed'
    ],
];

foreach ($orders as $order) :
    switch (strtolower($order['Status'])) {
        case 'in progress':
            $orderStatusClass = 'inprogress';
            break;
        case 'void':
            $orderStatusClass = 'void';
            break;
        case 'completed':
            $orderStatusClass = 'completed';
            break;
        default:
            $orderStatusClass = '';
            break;
    }
?>

<div class="col-md-4">
    <div class="order-box <?php echo $orderStatusClass; ?>">
        <p><strong>Order ID:</strong> <?php echo $order['OrderID']; ?></p>
        <p><strong>Table Number:</strong> <?php echo $order['TableNumber']; ?></p>
        <p><strong>Details:</strong> <?php echo $order['Details']; ?></p>
        <p><strong>Quantities:</strong> <?php echo $order['Quantities']; ?></p>
        <p><strong>Price:</strong> $<?php echo number_format($order['Price'], 2); ?></p>
        <p><strong>Status:</strong> <?php echo $order['Status']; ?></p>
        <div class="order-actions">
            <button class="btn btn-success" onclick="completeOrder(<?php echo $order['OrderID']; ?>)">Complete Order</button>
            <button class="btn btn-danger" onclick="voidOrder(<?php echo $order['OrderID']; ?>)">Void Order</button>
        </div>
    </div>
</div>

<?php endforeach; ?>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="bg-dark text-white text-center py-3">
        &copy; 2023 The Sheep's Toe Pub
    </footer>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // JavaScript functions to handle order actions (complete, void)
        function completeOrder(orderId) {
            // Implement logic to mark the order as completed in the database
