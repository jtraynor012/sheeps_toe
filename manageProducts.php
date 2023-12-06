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

        .sub-header::after {
          content: "";
          display: block;
          border-bottom: 1px solid #000;
          margin-top: 15px;
        }


        .carousel-card {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            background-color: #FFF;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .star-rating {
        color: #ffd700; /* Gold color for stars */
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
                    if(!isset($_SESSION['role']) || $_SESSION['role'] != 'Manager');
                    echo '<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="logoutDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.
                            $_SESSION["user"]
                        .'</a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="logoutDropdown">
                            <a class="dropdown-item" href="index.php?logout">'.$_SESSION["user"].' - Logout</a>
                            <a class="dropdown-item" href="manage.php">Manage</a>
                        </div>
                    </li>';
                ?>
            </ul>
        </div>
    </nav>

    <!-- Sub Header -->
    <div class="sub-header">
        <h4>Manage Products</h4>
    </div>

    <div class="container">
        <div class="row">
            <h3>The products here are sorted by urgency. This urgency is calculated by the current stock levels of 
                the product against its recent sales.
            </h3>
        </div>
    </div>

    <div class="container">
        <div class="row-mt-5">
            <ul class="product-list-container">
            </ul>
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
        document.addEventListener("DOMContentLoaded", function () {
            fetch("getCurrentStock.php")
            .then(response => {
                if(!response.ok){
                    throw new Error("Network response was no ok");
                }
                return response.json();
            })
            .then(data => {
                productListContainer = document.getElementById("product-list-container");
                data.forEach(function (product) {
                    
                })
            })


        });
    </script> 


</body>
</html>
