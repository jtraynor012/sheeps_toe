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
       
        .hero h1 {
            font-size: 2.5rem;
        }
        .hero p {
            font-size: 1.2rem;
        }
        .sub-header {
            background-color: #FFFDF1;
            padding: 15px 0;
            text-align: center;
            text-decoration: underline;
        }

        .sub-header:after {
          content: "";
          display: block;
          border-bottom: 1px solid #000;
          margin-top: 15px;

        }
        .menu-item {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 100px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <?php
        ob_start();
        session_start();
        if(!isset($_SESSION["user"])){
            header("location: order.html");
        }
    ?>


    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">The Sheep's Toe</a>
         <img src="sheep-logo_sm.png" alt="Sheep Logo">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.html">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Order</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.html">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?logout"><?php echo $_SESSION["user"]." - Logout" ?></a>
                </li>
            </ul>
        </div>
    </nav>

    <?php 
        if(isset($_GET["logout"])){
            unset($_SESSION['user']);
            header("location: order.html");
        }
    ?>

    <!-- Sub Header -->
    <div class="sub-header">
        <h4>Welcome <?php echo $_SESSION['user']?> - Order to Your Table</h4>
    </div>

    <!--table number drop down select-->
    <section class="container-fluid mt-1">
        <div class="row mt-1">
            <div class="col-4">
            <label for="table-number">Table Number  </label>
            <h5> </h5>
            <select id="table-number" name="table-number">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
            </select>
            </div>
        </div>
    </section>

    <section>
        <div class="container mt-5">
            <div class="row mt-5">
                <div class="col-md-4">
                    <ul class="categories" id="categories">
                    </ul>
                </div>
                <div class="col-md-8">
                    <ul class="products" id="products">
                    </ul>
                </div>
            </div>
        </div>
    </section> 
    
    <!-- Pagination -->
    <div class="container my-3">
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
        </ul>
    </div>

    <!-- Footer Section -->
    <footer class="bg-dark text-white text-center py-3">
        &copy; 2023 The Sheep's Toe Pub
    </footer>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>