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

        span {cursor:pointer; }
		.number{
			margin:100px;
		}
		.minus, .plus{
			width:40px;
			height:40px;
			background:#f2f2f2;
			border-radius:4px;
			padding:8px 5px 8px 5px;
			border:1px solid #ddd;
            display: inline-block;
            vertical-align: middle;
            text-align: center;
		}

		.quantity {
			height: 34px;
            width: 100px;
            text-align: center;
            font-size: 26px;
			border:1px solid #ddd;
			border-radius:4px;
            display: inline-block;
            vertical-align: middle;
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
        .product-image{
            width:100%;
            max-width:300px;
            max-height: 300px;
        }
        .product-container,
        .basket-container {
            display: none;
        }

        .basket-icon {
            position: fixed;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        .basket-container {
            position: fixed;
            top: 50px; /* Adjust as needed */
            right: 10px;
            border: 1px solid #ccc;
            background-color: #fff;
            padding: 10px;
            max-width: 200px;
        }
    </style>
</head>
<body>
    <?php
        ob_start();
        session_start();
        if(!isset($_SESSION["user"])){
            header("location: login.php");
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
            header("location: login.php");
        }
    ?>

    <!-- Sub Header -->
    <div class="sub-header">
        <h4>Welcome <?php echo $_SESSION['user']?> - Order to Your Table</h4>
    </div>

    <!--table number drop down select-->
    <section class="container-fluid mt-1">
        <div class="row mt-1">
            <div class="col-3">
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
            <div class="col-3">
                <label for="branch-select">Branch</label>
                <select id="branch-select" name="branch-select">
                    <option value="The Woolly Ram">The Wooly Ram - Dundee</option>
                    <option value="The Baa Baa Bar">The Baa Baa Bar - Aberdeen</option>
                    <option value="Shear Delight">Shear Delight - Perth</option>
                    <option value="The Tipsy Ewe">The Tipsy Ewe - Edinburgh</option>
                    <option value="The Golden Fleece">The Golden Fleece - Stirling</option>
                    <option value="The Shepherds Spirits">The Shephards Spirits - Glasgow</option>
                </select>
            </div>
        </div>
    </section>

    <section>
        <div class="container mt-5">
            <div class="row mt-5">
                <div class="col-md-3">
                    <div class="categories list-group" id="categories">
                        <button class="list-group-item" onclick="getProducts('Draught')">Draught</li>
                        <button class="list-group-item" onclick="getProducts('Bottles')">Bottles</li>
                        <button class="list-group-item" onclick="getProducts('Vodka')">Vodka</li>
                        <button class="list-group-item" onclick="getProducts('Gins')">Gin</li>
                        <button class="list-group-item" onclick="getProducts('Rums')">Rum</li>
                        <button class="list-group-item" onclick="getProducts('Snacks')">Snacks</li>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="products list-group" id="products">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" id="checkoutButton" onclick="checkout()">Checkout</button>
                    <div id="basket">
                        <div id="basket-items"></div>
                        <hr>
                        <div id="basket-total"></div>
                    </div>
                </div>
            </div>
        </div>
    </section> 

    <!-- Footer Section -->
    <footer class="bg-dark text-white text-center py-3">
        &copy; 2023 The Sheep's Toe Pub
    </footer>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        const productQuantities = [];
        let currentCategory = '';
        const productPrices = new Map();

        $(document).ready(function() {
			$(document).on('click', '.minus', function () {
				updateQuantity($(this), -1);
                updateBasketDisplay();
			});
			$(document).on('click', '.plus', function () {
				updateQuantity($(this), 1);
                updateBasketDisplay();
			});
            $('#checkoutButton').click(function () {
                console.log("Performing checkout...");
                // Reset all quantities to 0
                resetQuantities();
            });
		});

        function getProducts(category) {
            currentCategory = category;
            console.log(category);
            fetch(`getProduct.php?product='${category}'`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.text();
                })
                .then(data => {
                    showProducts(data);
                })
                .catch(error => {
                    console.error("Error during fetch operation:", error);
                });
        }


        function getSelectedProducts() {
            const selectedProducts = [];
            productQuantities.forEach(product => {
                if (product.quantity > 0) {
                    const productName = product.id.replace('_', ' ');  // Convert back to the original product name
                    selectedProducts.push(`${productName} - Quantity: ${product.quantity}`);
                }
            });
            return selectedProducts.join('\n');
        }

        function resetQuantities() {
            productQuantities.forEach(product => {
                product.quantity = 0;
            });

            // Update all input values to 0
            $('.quantity').val(0).change();
        }

        function updateQuantity(button, change) {
            const $input = button.parent().find('input');
            const productId = `${$input.data('product-id')}`;

            // Find the index of the existing product in the array
            let existingProductIndex = productQuantities.findIndex(product => product.id === productId);

            if (existingProductIndex !== -1) {
                // Update existing product quantity
                productQuantities[existingProductIndex].quantity = Math.max(productQuantities[existingProductIndex].quantity + change, 0);
            } else {
                // Add new product to the array
                productQuantities.push({
                    id: productId,
                    quantity: change > 0 ? 1 : 0  // Set initial quantity to 1 if adding
                });

                // Update the index for the newly added product
                existingProductIndex = productQuantities.length - 1;
            }

            // Update the input value
            $input.val(productQuantities[existingProductIndex].quantity);
            $input.change();

            // Update the basket display
            updateBasketDisplay();
        }

        function showProducts(products){
            product_area = document.getElementById("products");
            product_area.innerHTML = '';
            const product_list = products.split("!").filter(p=>p);
            product_list.forEach(product => {
                const product_details = product.split("$");
                const productId = `${currentCategory}_${product_details[0].toLowerCase().replace(' ', '_')}`;
                productPrices.set(productId, parseFloat(product_details[1]));
                var product_button = document.createElement("button");
                product_button.classList.add("list-group-item");
                product_button.innerHTML = '<div class="d-flex w-300 justify-content-between"><h5 class="mb-1">'+product_details[0]+'</h5><small id='+productId+'>£'+product_details[1]+'</small><img class="product-image" src="'+product_details[2]+'"></img></div><div class="number"><span class="minus" data-product-id="'+productId+'">-</span><input class="quantity" type="text" value="'+getProductQuantity(productId)+'" data-product-id="'+productId+'"/><span class="plus" data-product-id="'+productId+'">+</span></div>';
                product_area.appendChild(product_button);
            })
        }

        function getProductQuantity(productId) {
            const product = productQuantities.find(product => product.id === productId);
            return product ? product.quantity : 0;
        }

         // https://codedamn.com/news/javascript/post-request-http how i send the data.. 
         function sendOrder(orderData) {
            fetch('addOrder.php', {
                method: 'POST',
                headers: {
                    'Content-Type':'application/json'
                },
                body: JSON.stringify(orderData),
            })
            .then((response)=>{
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                } else {
                    return response.json();
                }
            })
            .then((data)=>console.log("Success:",data))
            .catch((error)=>console.error("Error:",error))
        }

        function checkout() {
            const selectedProducts = productQuantities.filter(product => product.quantity > 0);
            const currentDate = new Date().toISOString().slice(0, 19).replace('T', ' ');
            const tableNumber = $('#table-number').val();
            const branch = $('#branch-select').val();
            const customerID = <?php echo $_SESSION['id']?>;
            
            let stringProducts = "";
            console.log("selectedProducts: ", selectedProducts);
            for (let i = 0; i<selectedProducts.length;i++){
                stringProducts += selectedProducts[i]['id'] + "$" + selectedProducts[i]['quantity'] + "!";
            }
            const orderData = {
                stringProducts,
                currentDate,
                customerID,
                tableNumber,
                branch
            };
            console.log("orderData: " + orderData);
            sendOrder(orderData);
            // For example, clear the basket after checkout
            productQuantities.length = 0;
            updateBasketDisplay();
        }

        function updateBasketDisplay() {
            const basketItemsContainer = document.getElementById('basket-items');
            const basketTotalContainer = document.getElementById('basket-total');
            const basketTotal = calculateBasketTotal();

            // Clear previous content
            basketItemsContainer.innerHTML = '';
            basketTotalContainer.innerHTML = '';

            // Display basket items
            productQuantities.forEach(product => {
                if (product.quantity > 0) {
                    const [category, productName] = product.id.split('_');  // Convert back to the original product name
                    const productPrice = getProductPrice(product.id);
                    const subtotal = product.quantity * productPrice;
                    basketItemsContainer.innerHTML += `${product.quantity} x ${productName} (${category}) - £${subtotal.toFixed(2)}<br>`;
                }
            });

            // Display total
            basketTotalContainer.innerHTML = `<hr><strong>Sub-Total: £${basketTotal.toFixed(2)}</strong><br>`;
            basketTotalContainer.innerHTML += `<strong>Loyalty Discount: 20%</strong>`;
            let discountedBasketTotal = basketTotal * 0.8;
            basketTotalContainer.innerHTML += `<hr><strong>Total: £${discountedBasketTotal.toFixed(2)}</strong>`;
        }

        function calculateBasketTotal() {
            let total = 0;
            productQuantities.forEach(product => {
                if (product.quantity > 0) {
                    const productPrice = getProductPrice(product.id);
                    total += product.quantity * productPrice;
                }
            });
            return total;
        }

        function getProductPrice(productId) {
            return productPrices.get(productId) || 0.00;
        }


        // Function to show the basket and toggle Checkout button visibility
        function showBasket() {
            document.getElementById('categories').style.display = 'none';
            document.getElementById('products').innerHTML = '';
            document.getElementById('basket').style.display = 'block';
            document.getElementById('checkoutButton').style.display = 'block';
        }

        // Function to hide the basket and Checkout button
        function showCategories() {
            document.getElementById('categories').style.display = 'flex';
            document.getElementById('products').innerHTML = '';
            document.getElementById('basket').style.display = 'none';
            document.getElementById('checkoutButton').style.display = 'none';
        }

    </script>
</body>
</html>
