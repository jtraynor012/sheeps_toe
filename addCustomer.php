<?php
    session_start();
    include "db.php";

    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $emailAddress = $_POST['email'];
    $phoneNumber = $_POST['phone-number'];
    $pword = $_POST['pword'];

    try{
        $query = "CALL AddCustomer(:firstName, :lastName, :emailAddress, :phoneNumber, :password)";
        $stmt = $mysql->prepare($query);

        // Bind parameters
        $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
        $stmt->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
        $stmt->bindParam(':emailAddress', $emailAddress, PDO::PARAM_STR);
        $stmt->bindParam(':password', $pword, PDO::PARAM_STR);

        // Execute the stored procedure
        $stmt->execute();


    }catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage(); 
    }



?>