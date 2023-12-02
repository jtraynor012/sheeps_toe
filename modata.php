<?php
ob_start();
include "db.php";
session_start();

// Check if the user is logged in, adjust as needed
if (!isset($_SESSION["user"])) {
    echo '<script>window.location.href = "login.php";</script>';
    exit();
}

$branchID = $_SESSION['branch'];

// Fetch current orders
function getCurrentOrders($branchID, $mysql) {
    $query = "SELECT OrderID, TableNumber FROM ORDERS WHERE BranchID = $branchID AND `Status` = 'In progress'";
    $stmt = $mysql->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Fetch order items for a specific order
function getOrderItems($orderID, $mysql) {
    $query = "SELECT op.OrderID, op.ProductID, op.Quantity, p.ProductName
              FROM ORDER_PRODUCTS op
              JOIN PRODUCTS p ON op.ProductID = p.ProductID
              WHERE op.OrderID = '$orderID'";
    $stmt = $mysql->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

if (isset($_GET["logout"])) {
    unset($_SESSION['user']);
    unset($_SESSION['branch']);
    unset($_SESSION['role']);
    echo '<script>window.location.href = "login.php";</script>';
    exit();
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

        .order-container {
            border-radius: 10px;
            margin: 20px 0;
            padding: 20px;
        }

        .order-actions {
            margin-top: 10px;
        }

        .order-details {
            margin-top: 20px;
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
            try {
                $currentOrders = getCurrentOrders($branchID, $mysql);

                var_dump($currentOrders);

                $orderCount = 0;
                foreach ($currentOrders as $order) {
                    echo '<div class="order-container">';
                    echo '<h5>Order ID: ' . $order['OrderID'] . '</h5>';
                    echo '<p>Table Number: ' . $order['TableNumber'] . '</p>';

                    $orderItems = getOrderItems($order['OrderID'], $mysql);

                    $orderCount++;

                    echo '<pre>';
                    print_r($orderCount);
                    echo '</pre>';

                    echo '<pre>';
                    print_r($currentOrders);
                    echo '</pre>';

                    echo '<pre>';
                    print_r($orderItems);
                    echo '</pre>';


                    foreach ($orderItems as $item) {
                        echo '<p>Product: ' . $item['ProductName'] . ', Quantity: ' . $item['Quantity'] . '</p>';
                    }

                    // Add void and complete buttons
                    echo '<div class="order-actions">';
                    echo '<form method="post" action="updateOrderStatus.php">';
                    echo '<input type="hidden" name="orderID" value="' . $order['OrderID'] . '">';
                    echo '<button type="submit" class="btn btn-danger" name="void">Void</button>';
                    echo '<button type="submit" class="btn btn-success" name="complete">Complete</button>';
                    echo '</form>';
                    echo '</div>'; // .order-actions

                    echo '</div>'; // .order-container
                }
            } catch (PDOException $e) {
                echo $query . "<br>" . $e->getMessage();
            }
            ?>
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
            alert('Order marked as completed. Refresh the page to see changes.');
        }

        function voidOrder(orderId) {
            // Implement logic to void the order in the database
            alert('Order voided. Refresh the page to see changes.');
        }
    </script>
</body>

</html>
