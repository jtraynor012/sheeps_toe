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

        .carousel-inner {
            text-align: center;
        }

        .carousel-item {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 200px; /* Adjust the height as needed */
        }

        .carousel-card {
            max-width: 500px; /* Adjust the maximum width as needed */
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
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Log in </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Sub Header -->
    <div class="sub-header">
        <h4>About Us</h4>
    </div>


    <!-- Hero -->
    <section class="hero">
        <div class="container">
        </div>
    </section>

    <br>

    <section class="container">
        <div class="sub-hero">
            <h4>Welcome to The Toe</h4>
            <h5>The Herds all here, Cheers M' Dear</h5>
            <br>
            <h5>Come in, pull up a pew. We have the finest selection of local craft brews and the classic staples. We have been serving the public with a cosy atmosphere and ice cold pints for the 
                past 20 years. See something you like? Simply order to your table through our website or head up to the bar! 
            </h5>
        </div>
    </section>

    <hr>

    <section class="container">
        <div id="reviewsCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <!-- Fetched reviews will be dynamically inserted here -->
            </div>
            <a class="carousel-control-prev" href="#reviewsCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#reviewsCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const carouselSection = document.querySelector(".carousel-inner");
            fetch("getReviews.php")
            .then(response => {
                if(!response.ok){
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(data => {
                data.forEach((review, index) => {
                    const activeClass = index === 0 ? "active" : "";
                    const carouselItem = document.createElement("div");
                    carouselItem.classList.add("carousel-item", activeClass);

                    const carouselCard = document.createElement("div");
                    carouselCard.classList.add("carousel-card");
                    carouselCard.innerHTML = `
                        <p>${review.FirstName} - Rating: ${generateStarRating(review.Rating)}</p>
                        <p>${review.Comment}</p>
                        <p>Date: ${review.ReviewDate}</p>
                        <p>${review.Name} - ${review.City}</p>
                    `;

                    carouselItem.appendChild(carouselCard);
                    carouselSection.appendChild(carouselItem);
                });
            })
            .catch(error => {
                console.error("Error during fetch operation:", error);
            })

            function generateStarRating(rating) {
                const roundedRating = Math.round(rating);
                const stars = '★'.repeat(roundedRating) + '☆'.repeat(5 - roundedRating);
                return `<span class="star-rating">${stars}</span>`;
            }


        });


    </script> 


</body>
</html>
