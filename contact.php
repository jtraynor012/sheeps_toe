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
            background: url('pub_photo.png') no-repeat center center;
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

    </style>
</head>
<body>
    <?php
        session_start();
    ?>

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
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Log in </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Sub Header -->
    <div class="sub-header">
        <h4>Contact Us</h4>
    </div>


    <!-- Hero -->
    <section class="hero">
        <div class="container">
        </div>
    </section>

    <br>

    <section class="container">
        <div class="sub-hero">
            <h5>Phone: 12345678910</h5>
            <h5>Email: fakesheepstoe@notreal.com</h5>
            <h5>Post: Toe HQ, Dundee</h5>
        </div>
    </section>

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
</body>
</html>
