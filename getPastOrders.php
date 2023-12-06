<?php
    session_start();
    include "db.php";

    $branchID = $_SESSION['branch'];
    $customer = false;

    try{
        if(isset($_GET['c'])){
            $query = "SELECT * FROM OrderSummary WHERE CustomerID = :custID";
            $customer = true;
        }
        else{
            $query = "SELECT * FROM OrderSummary WHERE BranchID = :branchID";
        }

        $stmt = $mysql->prepare($query);
        $stmt->bindParam(":branchID", $branchID, PDO::PARAM_INT);

        if($customer){
            $stmt->bindParam(":custID", $_SESSION['id'], PDO::PARAM_INT);
        }
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);


    }catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }


?>