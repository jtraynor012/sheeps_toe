<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Sheep's Toe</title>
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

        .hero {
            background: url('pub_photo_jpg.jpg') no-repeat center center;
            padding: 250px 0;
            background-size: cover;
            text-align: center;
            
        }

        .hero h1 {
            font-size: 2.5rem;
        }

        .sub-hero {
            text-align: center;
            color: #000;
        }

        .sub-header {
            background-color: #FFFDF1;
            padding: 15px 0;
            text-align: center;
            text-decoration: underline;
        }

        #order-details-container {
            border: 1px solid #ccc;
        }

        .sub-header::after {
          content: "";
          display: block;
          border-bottom: 1px solid #000;
          margin-top: 15px;
        }

        .past-order-container {
            background-color: #FFFFFF;
            border-radius: 10px;
        }

    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="Home.html">The Sheep's Toe</a>
         <img src="sheep-logo_sm.png" alt="Sheep Logo">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                <?php
                    session_start();
                    if(isset($_GET['logout'])){
                        session_unset();
                    }
                    if(isset($_SESSION['user'])){
                        if(isset($_SESSION['role']) && $_SESSION['role'] == "Manager"){
                            echo '<li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="logoutDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.
                                    $_SESSION["user"]
                                .'</a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="logoutDropdown">
                                    <a class="dropdown-item" href="index.php?logout">'.$_SESSION["user"].' - Logout</a>
                                    <a class="dropdown-item" href="manage.php">Manage</a>
                                </div>
                            </li>';
                        }
                        elseif(isset($_SESSION['role']) && $_SESSION['role'] == "Staff"){
                            echo '<li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="logoutDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.
                                    $_SESSION["user"]
                                .'</a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="logoutDropdown">
                                    <a class="dropdown-item" href="index.php?logout">'.$_SESSION["user"].' - Logout</a>
                                    <a class="dropdown-item" href="mo1.php">View Orders</a>
                                </div>
                            </li>';
                        }
                        else{

                            echo '<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="logoutDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.
                                        $_SESSION["user"]
                                    .'</a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="logoutDropdown">
                                        <a class="dropdown-item" href="index.php?logout">'.$_SESSION["user"].' - Logout</a>
                                        <a class="dropdown-item text-danger" href="deleteAccount.php">Delete Account</a>
                                    </div>
                                </li>';
                            }

                    }
                ?>
            </ul>
        </div>
    </nav>

    <!-- Sub Header -->
    <div class="sub-header">
        <h4>Past Orders</h4>
    </div>

    <div class="container my-3">
        <a class="nav-link" href="order.php">Go back to order</a>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-5">
                <div class="container">
                    <h3 class="text-center">Order List</h3>
                    <!-- Order list -->
                    <ul class="order-list" id="order-list">
                    </ul>
                </div>
            </div>
            <div class="col-md-7 text-center">
                <h3>Order Details</h3>
                <div class="row">
                    <div class="col-md-6">
                        <!-- Placeholder for order details -->
                        <ul class="list-group" id="order-details-container">
                            <li class="list-group-item">Select an order to view details.</li>
                        </ul>
                        <!-- Button to generate PDF -->
                        <button id="generate-pdf-button" class="btn btn-primary mt-3">Generate PDF</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="container my-3">
        
    </div>

    <!-- Footer Section -->
    <footer class="bg-dark text-white text-center py-3">
        &copy; 2023 The Sheep's Toe Pub
    </footer>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script
			src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
			integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
			crossorigin="anonymous"
			referrerpolicy="no-referrer"
		></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        var generatePDFButton = document.getElementById("generate-pdf-button");
        generatePDFButton.addEventListener("click", generatePDF);
        createOrderList();

        function createOrderList(){
            fetch("getPastOrders.php?c=t")
            .then(response => {
                if(!response.ok){
                    throw new Error("Network response not ok");
                }
                return response.json();
            })
            .then(data => {
                var orderListContainer = document.getElementById("order-list");
                data.forEach(function (order) {
                    var li = document.createElement("li");
                    li.textContent = `Order ID: ${order.OrderID} | Customer Name: ${order.CustomerName} | Total: £${order.TotalOrderValue} | Date: ${order.TimeCompleted}`;
                    li.classList.add('list-group-item');
                    li.addEventListener("click", function () {
                        toggleHighlightOrder(li, order.OrderID);
                        fetchOrderDetails(order.OrderID);
                    });
                    orderListContainer.appendChild(li);
                });
            })
        }

        // Function to toggle the highlight of the selected order
        function toggleHighlightOrder(selectedOrder, orderID) {
                // Check if the selected order is already highlighted
                var isHighlighted = selectedOrder.classList.contains("active");

                // Remove previous highlights
                var previousHighlight = document.querySelector(".list-group-item.active");
                if (previousHighlight) {
                    previousHighlight.classList.remove("active");
                }

                // If the selected order was not previously highlighted, highlight it
                if (!isHighlighted) {
                    selectedOrder.classList.add("active");
                } else {
                    selectedOrder.classList.remove("active");
                    var detailsContainer = document.getElementById("order-details-container");
                    detailsContainer.innerHTML = `<h4>Select an order to view details...</h4>`;
                }
            }

            function fetchOrderDetails(orderID) {
                var detailsContainer = document.getElementById("order-details-container");
                detailsContainer.innerHTML = `<h4>Order Details</h4><p>Loading...</p>`;

                fetch("getOrderBreakdown.php?OrderID="+encodeURIComponent(orderID))
                .then(response => {
                    if(!response.ok){
                        throw new Error("Network response was no ok");
                    }
                    return response.json();
                })
                .then(data => {
                    const orderDetailsHTML =`
                        <div class="receipt">
                            <h5>Order ID: ${orderID}</h5>
                            <p>Customer Name: ${data.Details.CustomerName}</p>
                            <p>Branch Name: ${data.Details.BranchName}</p>
                            <p>Order Time: ${data.Details.OrderTime}</p>
                            <hr>
                            ${data.Products.map(product => `
                                <p>${product.ProductOrderedInfo.ProductName} x ${product.ProductOrderedInfo.Quantity} - £${parseFloat(product.ProductOrderedInfo.totalPricePerProduct).toFixed(2)}</p>
                            `).join('')}
                            <hr>
                            <p>Total: £${parseFloat(data.Details.TotalOrderValue).toFixed(2)}</p>
                        </div>`;
                    detailsContainer.innerHTML = orderDetailsHTML;

                })
                .catch(error => {
                    console.error("Error fetching order details: ", error);
                    detailsContainer.innerHTML = "<h4>Error</h4><p>Failed to fetch order details.</p>";
                })
            }

            function generatePDF(){
                const receipt = document.getElementById("order-details-container");
                html2pdf().from(receipt).save();
            }

        })


    </script>

</body>
</html>