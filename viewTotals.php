<?php
session_start();

error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', 1);
if(!isset($_SESSION['role']) || $_SESSION['role'] != "Manager"){
    header("location: login.php");
}
if(isset($_GET["logout"])){
    unset($_SESSION['user']);
    unset($_SESSION['branch']);
    unset($_SESSION['role']);
    header("location: login.php");
}
?>

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
                    <a class="nav-link" href="?logout"><?php echo $_SESSION['user']?> - Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Sub Header -->
    <div class="sub-header">
        <h4>Totals</h4>
    </div>

    <div class="container my-3">
        <a class="nav-link" href="manage.php">Go back to Manager Access</a>
    </div>

    <div class="container text-center">
        <div class="row">
            <h4 id="total-today"></h4> 
        </div>
    </div>

    <!-- Chart Container -->
    <div class="container text-center">
        <div class="row mt-5">
            <div class="col-md-6" id="product-by-day-section">
                <h4 class="text-center">Product by day of the week (average)</h4>
                <!--Product by day of the week avg price-->
                <canvas id="product-by-day"></canvas>
                <label for="product-select">Product:</label>
                <select id="product-select"></select>
            </div>
            <div class="col-md-6" id="product-total-period-section">
                <h4 class="text-center">Products over Time</h4>
                <!--products over time period - Sum total of profit vs units sold-->
                <canvas id="product-total-period"></canvas>
                <label for="product-period-from">Date From:</label>
                <input type="date" name="date-from" id="product-period-from">
                <label for="product-period-to">Date To:</label>
                <input type="date" name="date-to" id="product-period-to">
                <label for="category-select">Category:</label>
                <select id="category-select"></select>
                <button class="btn btn-primary" id="product-time-submit">Run</button>
            </div>
        </div>
        <br>
        <div class="row mt-10">
            <div class="col-md-6" id="profit-by-day-section">
                <h4 class="text-center">Profit by day of the week (average)</h4>
                <!--profit by day of the week, avg over dates-->
                <canvas id="profit-by-day"></canvas>
                <label for="profit-avg-from">Date From:</label>
                <input type="date" name="date-from-profit-by-day" id="profit-avg-from">
                <label for="profit-avg-to">Date To:</label>
                <input type="date" name="date-to-profit-by-day" id="profit-avg-to">
                <button class="btn btn-primary" id="profit-avg-submit">Run</button>
            </div>
            <div class="col-md-6" id="profit-by-time-section">
                <h4 class="text-center"> Profit over Time of Day</h4>
                <!--profit by time of day-->
                <canvas id="profit-by-time"></canvas>
                <label for="profit-date">Date:</label>
                <input type="date" name="profit-time-day" id="profit-date">
                <button class="btn btn-primary" id="profit-time-submit">Run</button>
            </div>
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
    
    <!--Chart.js-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        
        document.addEventListener("DOMContentLoaded", function () {
            //fetch products
            fetch("getProductOptions.php")
            .then(response => {
                if(!response.ok){
                    throw new Error("Network response was not ok");
                }
                return response.text();
            })
            .then(data => {
                const productSelect = document.getElementById("product-select");
                const products = data.split("<br>");
                
                for(var i = 0; i < products.length; i++){
                    var pair = products[i].split("$");
                    var productName = pair[0];
                    var productID = pair[1];
                    // Create an option element
                    var optionElement = document.createElement("option");
                    optionElement.value = productID;
                    optionElement.text = productName;

                    // Append the option to the select element
                    productSelect.appendChild(optionElement);  
                }
            })

            
            //fetch product categories
            fetch("getCategories.php")
            .then(response => {
                if(!response.ok){
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(data => {
                const categorySelect = document.getElementById("category-select");
                for(var i = 0; i < data.length; i++){
                    var optionElement = document.createElement("option");
                    optionElement.value = data[i];
                    optionElement.text = data[i];

                    categorySelect.appendChild(optionElement);
                }
            })

            todayDate = new Date().toJSON().slice(0,10);

            fetch("getProfitByDay.php?dateFrom="+todayDate+"&dateTo="+todayDate)
            .then(response => {
                if(!response.ok){
                    throw new Error("Network response not ok");
                }
                return response.json();
            })
            .then(data =>{
                let totalProfit = 0.0;
                data.forEach(today =>{
                    totalProfit += today.Profit;
                    console.log(today.profit);                
                })
                todayTotal = document.getElementById("total-today");
                todayTotal.innerHTML = 'Todays Totals: £' + totalProfit;
            })



            document.getElementById("product-select").onchange = grabProductByDay;
            document.getElementById("profit-avg-submit").onclick = grabProfitByDay;
            document.getElementById("product-time-submit").onclick = grabProductByTime;
            document.getElementById("profit-time-submit").onclick = grabProfitByTime;

            function grabProductByDay(){
                const product = this.value;
                fetch(`getProductByDay.php?product='${product}'`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then(data => {
                    graphProductByDay(data);
                })
                .catch(error => {
                    console.error("Error during fetch operation:", error);
                });
            }
            function graphProductByDay(data){
                const data_labels = [];
                const data_points = [];
                data.forEach(productDay => {
                    data_labels.push(productDay.day);
                    data_points.push(productDay.average);
                })
                const productbd = document.getElementById("product-by-day").getContext("2d");
                // Check if the Chart instance already exists
                console.log(data_points);

                if (window.productbdChart) {
                    window.productbdChart.destroy();
                }

                const product_by_day_data = {
                    labels: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
                    datasets: [
                    {
                        label: "Product by Day",
                        data: data_points,
                        backgroundColor: "rgba(75, 192, 192, 0.2)",
                        borderColor: "rgba(75, 192, 192, 1)",
                        borderWidth: 1,
                    },
                    ],
                }; 
                
                window.productbdChart = new Chart(productbd, {
                type: "bar",
                data: product_by_day_data,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            },
                        },
                    },
                });
            }

            function grabProfitByDay(){
                const dateFromInput = document.getElementById("profit-avg-from");
                const dateToInput = document.getElementById("profit-avg-to");

                if(!dateFromInput.value && !dateToInput.value){
                    return;
                }
                const dateFrom = dateFromInput.value;
                const dateTo = dateToInput.value;
                fetch("getProfitByDay.php?dateFrom="+dateFrom+"&dateTo="+dateTo)
                .then(response => {
                    if(!response.ok){
                        throw new Error("Network response not ok")
                        
                    }
                    return response.json(); 
                })
                .then(data => {
                    graphProfitByDay(data);
                })
                .catch(error => {
                    console.error("Error during fetch operation:", error);
                })
            }

            function graphProfitByDay(data){
                try {
                    console.log("Received data: ", data);
                    const daySales = {
                        'Monday': { totalProfit: 0, count: 0, uniqueWeeks: new Set() },
                        'Tuesday': { totalProfit: 0, count: 0, uniqueWeeks: new Set() },
                        'Wednesday': { totalProfit: 0, count: 0, uniqueWeeks: new Set() },
                        'Thursday': { totalProfit: 0, count: 0, uniqueWeeks: new Set() },
                        'Friday': { totalProfit: 0, count: 0, uniqueWeeks: new Set() },
                        'Saturday': { totalProfit: 0, count: 0, uniqueWeeks: new Set() },
                        'Sunday': { totalProfit: 0, count: 0, uniqueWeeks: new Set() },
                    };


                    data.forEach(entry => {
                        const dayOfWeek = new Date(entry.TimeCompleted).toLocaleDateString('en-US', { weekday: 'long' });
                        const profit = entry.Profit;

                        daySales[dayOfWeek].totalProfit += profit;
                        daySales[dayOfWeek].count += 1;
                        daySales[dayOfWeek].uniqueWeeks.add(getWeekNumber(new Date(entry.TimeCompleted)));
                    });

                    const averageSales = {};
                    for (const dayOfWeek in daySales) {
                        const { totalProfit, count, uniqueWeeks} = daySales[dayOfWeek];
                        averageSales[dayOfWeek] = uniqueWeeks.size === 0 ? 0 : totalProfit / uniqueWeeks.size;
                    }

                    console.log("Average sales data: ", averageSales);


                    const profitbd = document.getElementById("profit-by-day").getContext("2d");

                    if (window.profitbdChart) {
                        window.profitbdChart.destroy();
                    }

                    const profit_by_day_data = {
                        labels: Object.keys(averageSales),
                        datasets: [
                            {
                                label: "Average Profit by Day",
                                data: Object.values(averageSales),
                                backgroundColor: "rgba(75, 192, 192, 0.2)",
                                borderColor: "rgba(75, 192, 192, 1)",
                                borderWidth: 1,
                            },
                        ],
                    };

                    window.profitbdChart = new Chart(profitbd, {
                        type: "bar",
                        data: profit_by_day_data,
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                },
                            },
                        },
                    });


                } catch (error) {
                    console.error("Error parsing JSON:", error);
                }
            }

            function getWeekNumber(date) {
                const oneJan = new Date(date.getFullYear(), 0, 1);
                const numberOfDays = Math.floor((date - oneJan) / (24 * 60 * 60 * 1000));
                return Math.ceil((date.getDay() + 1 + numberOfDays) / 7);
            }

            function grabProductByTime(){
                const dateFrom = document.getElementById("product-period-from");
                const dateTo = document.getElementById("product-period-to");
                const category = document.getElementById("category-select");
                fetch("getProductByTime.php?category="+category.value+"&dateFrom="+dateFrom.value+"&dateTo="+dateTo.value)
                .then(response => {
                    if(!response.ok){
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then(data => {
                    graphProductByTime(data);
                })
                .catch(error => {
                    console.error("Error during fetch operation:", error);
                })
            }

            function graphProductByTime(data){
                // Extract data for Chart.js
                var labels = [];
                var unitsSoldData = [];
                var profitData = [];

                // Iterate over each item in the array
                data.forEach(function (item) {
                    labels.push(item.ProductName);
                    unitsSoldData.push(parseInt(item.totalSold));
                    profitData.push(parseFloat(item.Profit));
                });

                // Now you can use these arrays to create a Chart.js chart
                // For example, a bar chart with two datasets for units sold and profit

                var productot = document.getElementById('product-total-period').getContext('2d');
                if (window.productotChart) {
                    window.productotChart.destroy();
                }
                
                window.productotChart = new Chart(productot, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Units Sold',
                            data: unitsSoldData,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }, {
                            label: 'Profit',
                            data: profitData,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

            }

            function grabProfitByTime(){
                const profitDate = document.getElementById("profit-date");
                if(!profitDate.value){
                    return;
                }
                fetch("getProfitByTime.php?date="+profitDate.value)
                .then(response => {
                    if(!response.ok){
                        throw new Error("Network response not ok");
                    }
                    return response.json();
                })
                .then(data => {
                    graphProfitByTime(data);
                })
                .catch(error => {
                    console.error("Error during fetch operation:", error);
                })
            }

            function graphProfitByTime(data){

                const labels = data.map(entry => entry.HourOfDay);
                const profitData = data.map(entry => entry.Profit);

                const profitbt = document.getElementById("profit-by-time");

                if(window.profitOverTimeChart){
                    window.profitOverTimeChart.destroy();
                }

                window.profitOverTimeChart = new Chart(profitbt, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Profit Over Time',
                            data: profitData,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                type: 'linear',
                                position: 'bottom',
                                max: 23,
                                min: 0,
                                stepSize: 1,
                                ticks: {
                                    callback: (value) => `${value.toString().padStart(2, '0')}:00`,
                                },
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

            }
        }
        );

</script>

</body>
</html>
