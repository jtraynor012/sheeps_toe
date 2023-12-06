<?php
    session_start();
    include "db.php";

    $branchID = $_SESSION['branch'];
    $customer = false;

    try{
        //if a customer wants their previous orders
        if(isset($_GET['c'])){
            $query = "SELECT * FROM OrderSummary WHERE CustomerID = :custID";
            $stmt = $mysql->prepare($query);
            $stmt->bindParam(":custID", $_SESSION['id'], PDO::PARAM_INT);
            $customer = true;
        }
        //if manager wants previous orders for branch
        else{
            $query = "SELECT * FROM OrderSummary WHERE BranchID = :branchID";
            $stmt = $mysql->prepare($query);
            $stmt->bindParam(":branchID", $branchID, PDO::PARAM_INT);
        }
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);


    }catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }


?>