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

        .container {
            margin-top: 20px;
        }

        .employee-list {
            border: 1px solid #ccc;
        }

        #employee-details-container {
            border: 1px solid #ccc;
            padding: 10px;
            margin-left: 10px;
        }

        .vertical-line{
            border-left: 1px solid #e0e0e0;
            height: 100%;
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

    </style>
</head>
<body>
    <?php
        ob_start();
        session_start();
        if(!isset($_SESSION['role']) || $_SESSION['role'] != "Manager"){
            header("location: login.php");
        }
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
                    <a class="nav-link" href="index.html">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="order.html">Order</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.html">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?logout"><?php echo $_SESSION['user']?> - Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <?php
    if(isset($_GET["logout"])){
            unset($_SESSION['user']);
            unset($_SESSION['branch']);
            unset($_SESSION['role']);
            header("location: login.php");
        }
    ?>
    <!-- Sub Header -->
    <div class="sub-header">
        <h4>Welcome <?php echo $_SESSION['user']?> - Manager Access</h4>
    </div>

    <!-- Pagination -->
    <div class="container my-3">
        <a class="nav-link" href="manage.php">Go back to Manager Access</a>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-4">
                <div class="container">
                    <h3>Add Employee</h3>
                    <!--Add employee form-->
                    <div class="form-content">
                    <form action="add_employee.php" method="POST">
                        First Name:
                        <input type="text" name="first_name" required>
                        <br>
                        <br>
                        Last Name: 
                        <input type="text" name="last_name" required>
                        <br>
                        <br>
                        Date of Birth: 
                        <input type="date" name="DOB" required>
                        <br>
                        <br>
                        Start Date:
                        <input type="date" name="start_date" required>
                        <br>
                        <br>
                        Phone Number:
                        <input type="tel" name="phone_number" pattern="[0-9]{3}-[0-9]{4}" required>
                        <br>
                        <br>
                        Email:
                        <input type="email" name="email" pattern=".+@.+\.com" required>
                        <br>
                        <br>
                        Role:
                        <input type="radio" name="role" id="role_bart" value="Bartender" checked>
                        <label for="role_bart">Bartender</label>
                        <input type="radio" name="role" id="role_supe" value="Supervisor">
                        <label for="role_supe">Supervisor</label>
                        <input type="radio" name="role" id="role_wait" value="Waiter">
                        <label for="role_wait">Waiter</label>
                        <input type="radio" name="role" id="role_mana" value="Manager">
                        <label for="role_mana">Manager</label>
                        <br>
                        <br>
                        Salary:
                        <input type="number" name="salary" min="12000" max="40000" required>
                        <br>
                        <br>
                        <input type="submit" id="add_submit" value="submit">
                    </form>
                </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="row">
                <h3>Remove Employee</h3>
                    <div class="col-md-4">
                       
                        <!--Employee list from managers branch-->
                        <ul class="employee-list" id="employee-list">
                        </ul>
                        <button id="remove-button" class="btn btn-danger mt-3">Remove</button>
                    </div>
                    <div class="col-md-4" id="employee-details-container">
                        <h4>Employee Details</h4>
                        <p> Select an employee to view details.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="container my-3">
        
    </div>

    <div id="dom-target" style="display:none">
    <?php
        include "db.php";
        session_start();
    
        $branch = $_SESSION['branch'];
        $employees = [];
    
        $query = "SELECT * FROM STAFF WHERE BranchID = $branch";
        $stmt = $mysql->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
    
        foreach($result as $row){
            echo " ".$row['StaffID']." ".$row['FirstName']." ".$row['LastName'].",<br><br>";
        }
    
    ?>
    
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
        createEmployeeList();

        // Function to create the employee list
        function createEmployeeList() {
            var employees = document.getElementById("dom-target");
            let employeeList = employees.textContent;
            const employeeArray = employeeList.split(",");

            var employeeListContainer = document.getElementById("employee-list");

            employeeArray.forEach(function (employee, index) {
                if (employee.trim() !== "") {
                    var li = document.createElement("li");
                    li.textContent = employee.trim();
                    li.classList.add('list-group-item');
                    li.addEventListener("click", function() {
                        toggleHighlightEmployee(li, employee.trim());
                        fetchEmployeeDetails(employee.trim());
                    })
                    li.addEventListener("click", toggleHighlightEmployee);
                    employeeListContainer.appendChild(li);
                }
            });
        }

        // Function to toggle the highlight of the selected employee
        function toggleHighlightEmployee(selectedEmployee, employeeName) {

            // Check if the selected employee is already highlighted
            var isHighlighted = selectedEmployee.classList.contains("active");

            // Remove previous highlights
            var previousHighlight = document.querySelector(".list-group-item.active");
            if (previousHighlight) {
                previousHighlight.classList.remove("active");
            }

            // If the selected employee was not previously highlighted, highlight it
            if (!isHighlighted) {
                selectedEmployee.classList.add("active");
            }
        }

        function fetchEmployeeDetails(employeeName){
            var detailsContainer = document.getElementById("employee-details-container");
            detailsContainer.innerHTML = "<h4>Details for " + employeeName + "</h4><p>Loading...</p>";

            fetch("employee_details.php?name="+encodeURIComponent(employeeName))
                .then(response => {
                    if(!response.ok){
                        throw new Error("Network response was not ok");
                    }
                    return response.text();
                })
                .then(data => {
                    detailsContainer.innerHTML = "<h4>Details for " + employeeName + "</h4><p>"+data+"</p>";
                })
                .catch(error => {
                    console.error("Error fetching employee details:", error);
                    detailsContainer.innerHTML = "<h4>Error</h4><p>Failed to fetch employee details.</p>";
                })
        }

        // Function to log details of the selected employee
        function removeSelectedEmployee() {
            var selectedEmployee = document.querySelector(".list-group-item.active");

            // Check if an employee is selected
            if (selectedEmployee) {
                var employeeDetails = selectedEmployee.textContent.trim();
                var isConfirmed = confirm("Are you sure you want to remove: " + employeeDetails);

                if (isConfirmed) {
                    console.log("Removed: " + employeeDetails);
                    selectedEmployee.classList.remove("active");

                    fetch("remove_employee.php", {
                        method: "POST",
                        headers: {
                            "Content-type": "application/x-www-form-urlencoded"
                        },
                        body: "employeeDetails=" + encodeURIComponent(employeeDetails)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Network response was not ok");
                        }
                        return response.text();
                    })
                    .then(data => {
                        console.log(data);
                    })
                    .catch(error => {
                        console.error("Error during fetch operation:", error);
                    });
                } else {
                    console.log("Removal Cancelled");
                }
            } else {
                console.log("No employee selected.");
            }
        }

        // Attach the removeSelectedEmployee function to the "Remove" button
        var removeButton = document.getElementById("remove-button");
        removeButton.addEventListener("click", removeSelectedEmployee);
        });
    </script>
</body>
</html>


    





