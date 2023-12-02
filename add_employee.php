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
    $pword = $_POST['pword'];
    $pword = hash("sha256", $pword);

    $salary = intval($salary);
    $branchID = intval($branchID);
    

    try{
        $query = "CALL AddStaff(:branchID, :firstName, :lastName, :dob, :dateEmployed, :phoneNumber, :emailAddress, :role, :salary, :password)";
        $stmt = $mysql->prepare($query);
        if (!$stmt) {
            echo "\nPDO::errorInfo():\n";
            print_r($mysql->errorInfo());
        }

        
        // Bind parameters
        $stmt->bindParam(':branchID', $branchID, PDO::PARAM_INT);
        $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
        $stmt->bindParam(':dob', $dob, PDO::PARAM_STR);
        $stmt->bindParam(':dateEmployed', $startDate, PDO::PARAM_STR);
        $stmt->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
        $stmt->bindParam(':emailAddress', $email, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->bindParam(':salary', $salary, PDO::PARAM_INT);
        $stmt->bindParam(':password', $pword, PDO::PARAM_STR);

        // Execute the stored procedure
        $stmt->execute();
        if ($stmt->errorCode() != 0) {
            echo "\nPDO::errorInfo():\n";
            print_r($stmt->errorInfo());
        }

        header("location: man_employees.php");
    } catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }


?>