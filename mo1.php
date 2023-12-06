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
            background-color: #FFFFFF; 
        }

        .order-actions {
            margin-top: 10px;
        }

        .order-details {
            margin-top: 20px;
        }

        @media (min-width: 768px) {
            /* Adjust styles for larger screens */
            .order-container {
                width: 48%; /* Display two containers side by side */
                display: inline-block;
                margin-right: 1%;
            }
        }

        @media (min-width: 992px) {
            /* Adjust styles for screens larger than 992 pixels wide (lg) */
            .order-container {
                width: 31%; /* Display three containers side by side */
                margin-right: 2%; /* Adjust margin between containers */
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="index.php">The Sheep's Toe</a>
        <img src="sheep-logo_sm.png" alt="Sheep Logo">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="order.php">Order</a>
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

    <?php
        if(!isset($_SESSION['user']) || $_SESSION['role'] != "Manager" && $_SESSION['role'] != "Staff"){
            header("location: login.php");
        }
        if($_SESSION['role'] == "Manager"){
            echo '<div class="container my-3">
                    <a class="nav-link" href="manage.php">Go back to Manager Access</a>
                </div>';
        }
    ?>

    <div class="btn-group" role="group" aria-label="Filter Orders">
        <button type="button" class="btn btn-secondary" onclick="fetchOrders('In progress')">In Progress</button>
        <button type="button" class="btn btn-secondary" onclick="fetchOrders('Voided')">Voided</button>
        <button type="button" class="btn btn-secondary" onclick="fetchOrders('Completed')">Completed</button>
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
    function fetchOrders(status) {
        fetch(`getOrders.php?status=${status}`)
            .then(response => response.json())
            .then(data => {
                console.log(status);
                // Update the DOM with the fetched data
                updateDOM(data, status);
            })
            .catch(error => console.error('Error:', error));
    }

    // Update the DOM with orders data
    function updateDOM(orders, status) {
        const ordersContainer = document.getElementById('ordersContainer');
        ordersContainer.innerHTML = ''; // Clear previous content

        orders.forEach(order => {
            const orderContainer = document.createElement('div');
            orderContainer.className = 'order-container';
            
            let actionButtonsHTML = '';
            if (status === 'In progress') {
                actionButtonsHTML = `
                    <button class="btn btn-danger" onclick="voidOrder(${order.OrderID})">Void</button>
                    <button class="btn btn-success" onclick="completeOrder(${order.OrderID})">Complete</button>
                `;
            } else if (status === 'Voided') {
                actionButtonsHTML = `                
                    <button class="btn btn-primary" onclick="inProgressOrder(${order.OrderID})">Undo</button>
                `;
            } else if (status === 'Completed') {
                actionButtonsHTML = `
                    <button class="btn btn-primary" onclick="inProgressOrder(${order.OrderID})">Undo</button>
                `;
            }
            
            console.log(order.OrderId);
            console.log(order.TableNumber);


            orderContainer.innerHTML = `
                <h5>Order ID: ${order.OrderID}</h5>
                <p>Table Number: ${order.TableNumber}</p>
                <div class="order-details" id="orderDetails-${order.OrderID}"></div>
                <p>Product(s): </p>
                <div class="order-actions">
                    ${actionButtonsHTML}
                </div>
            `;

            ordersContainer.appendChild(orderContainer);

            // Fetch and update order details
            const orderDetailsContainer = document.getElementById(`orderDetails-${order.OrderID}`);
            order.OrderItems.forEach(item => {
                const itemDetails = document.createElement('p');
                itemDetails.textContent = `${item.ProductName}, Quantity: ${item.Quantity}`;
                orderDetailsContainer.appendChild(itemDetails);
            });
        });
    }


    // JavaScript functions to handle order actions (complete, void)
    function completeOrder(orderId) {
        
        fetch('completeOrder.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                orderId: orderId,
            }),
        })
        .then(response => response.json())
        .then(data => {
            // Check if the order was completed successfully
            if (data.success) {
                // Remove the order from the DOM
                const orderContainers = document.getElementsByClassName('order-container');
                for (const orderContainer of orderContainers) {
        const orderOrderId = orderContainer.querySelector('h5').textContent.split(': ')[1]; // Assuming h5 contains "Order ID: xxx"
        
        if (orderOrderId === orderId.toString()) {
            orderContainer.remove();
            break; // Once the order is found and removed, exit the loop
        }
    }

                alert('Order marked as completed. Refresh the page to see changes.');
            } else {
                alert('Failed to mark the order as completed. Please try again.');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function voidOrder(orderId) {
        
        fetch('voidOrder.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                orderId: orderId,
            }),
        })
        .then(response => response.json())
        .then(data => {
            // Check if the order was voided successfully
            if (data.success) {
                // Remove the order from the DOM
                const orderContainers = document.getElementsByClassName('order-container');
                for (const orderContainer of orderContainers) {
                const orderOrderId = orderContainer.querySelector('h5').textContent.split(': ')[1]; // Assuming h5 contains "Order ID: xxx"
        
                if (orderOrderId === orderId.toString()) {
                    orderContainer.remove();
                break; // Once the order is found and removed, exit the loop
                }   
            }   

                alert('Order voided. Refresh the page to see changes.');
            } else {
                alert('Failed to void the order. Please try again.');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // JavaScript functions to handle order actions (complete, void, in-progress)
    function inProgressOrder(orderId) {
        fetch('inProgressOrder.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                orderId: orderId,
            }),
        })
        .then(response => response.json())
        .then(data => {
            // Check if the order is set to in progress successfully
            if (data.success) {
                // Remove the order from the DOM
                const orderContainers = document.getElementsByClassName('order-container');
                for (const orderContainer of orderContainers) {
                    const orderOrderId = orderContainer.querySelector('h5').textContent.split(': ')[1]; // Assuming h5 contains "Order ID: xxx"

                    if (orderOrderId === orderId.toString()) {
                        orderContainer.remove();
                        break; // Once the order is found and removed, exit the loop
                    }
                }

                alert('Order set to In Progress. Refresh the page to see changes.');
            } else {
                alert('Failed to set the order to In Progress. Please try again.');
            }
        })
        .catch(error => console.error('Error:', error));
    }


    // Fetch orders on page load
    fetchOrders();
</script>

</body>

</html>