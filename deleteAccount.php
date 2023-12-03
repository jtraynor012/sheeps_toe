<?php
    session_start();
    include "db.php";

    try{
        $query = "CALL deleteCustomer(:custID)";
        $stmt = $mysql->prepare($query);
        $stmt->bindParam(":custID", $_SESSION['id'], PDO::PARAM_INT);
        $stmt->execute();

        header("location: login.php");
        exit;

    }catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }



?>