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
    <div class="container" id="ordersContainer">
        <!-- Orders will be displayed here -->
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
        // Fetch orders and update the DOM
        function fetchOrders() {
            fetch('getOrders.php') // Assuming you have a PHP file to fetch orders
                .then(response => response.json())
                .then(data => {
                    // Update the DOM with the fetched data
                    updateDOM(data);
                })
                .catch(error => console.error('Error:', error));
        }

        // Update the DOM with orders data
        function updateDOM(orders) {
            const ordersContainer = document.getElementById('ordersContainer');
            ordersContainer.innerHTML = ''; // Clear previous content

            orders.forEach(order => {
                const orderContainer = document.createElement('div');
                orderContainer.className = 'order-container';

                orderContainer.innerHTML = `
                    <h5>Order ID: ${order.OrderID}</h5>
                    <p>Table Number: ${order.TableNumber}</p>
                    <div class="order-details" id="orderDetails-${order.OrderID}"></div>
                    <div class="order-actions">
                        <button class="btn btn-danger" onclick="voidOrder(${order.OrderID})">Void</button>
                        <button class="btn btn-success" onclick="completeOrder(${order.OrderID})">Complete</button>
                    </div>
                `;

                ordersContainer.appendChild(orderContainer);

                // Fetch and update order details
                fetch(`getOrders.php?orderID=${order.OrderID}`)
                    .then(response => response.json())
                    .then(orderItems => {
                        const orderDetailsContainer = document.getElementById(`orderDetails-${order.OrderID}`);
                        orderItems.forEach(item => {
                            const itemDetails = document.createElement('p');
                            itemDetails.textContent = `Product: ${item.ProductName}, Quantity: ${item.Quantity}`;
                            orderDetailsContainer.appendChild(itemDetails);
                        });
                    })
                    .catch(error => console.error('Error fetching order items:', error));
            });
        }

        // JavaScript functions to handle order actions (complete, void)
        function completeOrder(orderId) {
            // Implement logic to mark the order as completed in the database
            alert('Order marked as completed. Refresh the page to see changes.');
        }

        function voidOrder(orderId) {
            // Implement logic to void the order in the database
            alert('Order voided. Refresh the page to see changes.');
        }

        // Fetch orders on page load
        fetchOrders();
    </script>
</body>

</html>