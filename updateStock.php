<?php

    session_start();
    include "db.php";

    $productID = $_GET['productID'];
    $quantity = $_GET['quantity'];
    $branchID = $_SESSION['branch'];

    try{
        $query = "CALL UpdateStockLevels(:branchID, :productID, :quantity)";
        $stmt = $mysql->prepare($query);
        $stmt->bindParam(":branchID", $branchID, PDO::PARAM_INT);
        $stmt->bindParam(":productID", $productID, PDO::PARAM_INT);
        $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(array(["success" => "True"]));

    }catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }


?>