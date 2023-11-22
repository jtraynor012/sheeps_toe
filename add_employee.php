<?php
    include "db.php";
    session_start();

    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];

    $dob = $_POST['DOB'];
    $dob = date("Y-m-d", strtotime($dob));

    $startDate = $_POST['start_date'];
    $startDate = date("Y-m-d", strtotime($startDate));

    $phoneNumber = $_POST['phone_number'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $salary = $_POST['salary'];
    $branchID = $_SESSION['branch'];

    try{
    $query = "INSERT into Staff (BranchID, FirstName, LastName, DOB, DateEmployed, PhoneNumber, EmailAddress, Role, Salary) 
                VALUES ($branchID, $firstName, $lastName, $dob, $startDate, $phoneNumber, $email, $email, $role, $salary)";
    $stmt = $mysql->prepare($query);
    $stmt->execute();
    echo "Employee $firstName added successfully";
    } catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }


?>