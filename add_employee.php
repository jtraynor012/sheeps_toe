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

    $salary = intval($salary);
    $branchID = intval($branchID);

    try{
        $query = "INSERT INTO STAFF (`BranchID`, `FirstName`, `LastName`, `DOB`, `DateEmployed`, `PhoneNumber`, `EmailAddress`, `Role`, `Salary`) 
        VALUES (:branchID, :firstName, :lastName, :dob, :startDate, :phoneNumber, :email, :role, :salary)";
        
        $stmt = $mysql->prepare($query);
        $stmt->bindParam(':branchID', $branchID, PDO::PARAM_INT);
        $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
        $stmt->bindParam(':dob', $dob, PDO::PARAM_STR);
        $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
        $stmt->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->bindParam(':salary', $salary, PDO::PARAM_INT);

        $stmt->execute();
        echo "Employee $firstName added successfully";
        header("location: man_employees.php");
    } catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }


?>