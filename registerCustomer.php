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
        <h4>Register</h4>
    </div>

    <div class="container text-center">
        <h4>Hey there! Thinking about registering to our site? If you become a member,
            you will be able to order drinks and snacks from our site directly to your table,
            with a 20% discount to boot! So what are you waiting for...
        </h4>
    </div>
    <hr>
    <div class="container text-center">
        <div class="row">
            <div class="col-md-12">
                <form class="form-content" action="addCustomer.php" method="POST">
                    <label for="first-name">*First Name:</label>
                    <input type="text" id="first-name" name="first-name" require>
                    <br>
                    <br>
                    <label for="last-name">*Last Name:</label>
                    <input type="text" id="last-name" name="last-name" require>
                    <br>
                    <br>
                    <label for="email">*Email Address:</label>
                    <input type="email" id="email" name="email" require>
                    <br>
                    <br>
                    <label for="phone-number">*Phone Number [xxxxxxxxxxx]:</label>
                    <input type="tel" id="phone-number" name="phone-number" require>
                    <br>
                    <br>
                    <label for="pword">*Password</label>
                    <input type="password" id="pword" name="pword" require>
                    <br>
                    <br>
                    <input type="submit" id="add_submit" value="submit">
                </form>
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
