<?php
    session_start();
    include "db.php";

    $branch = $_GET['Branch']; // Make sure to use this if it's needed
    $response = "";
    $currentOrders = "";

    try {
        $getOrders = "SELECT OrderID, TableNumber FROM ORDERS WHERE BranchID = $branch AND `Status` = 'In progress'";
        $stmt = $mysql->prepare($getOrders);
        $stmt->execute();
        $getOrders = $stmt->fetchAll();
        foreach($getOrders as $row) {
            $currentOrders .= $row['OrderID']."$".$row['TableNumber']."!";
        }
        $splitOrders = explode("!", $currentOrders);
        foreach($splitOrders as $order){
            $orderInfo = explode("$",$order);
            $orderID = $orderInfo[0];
            $tableNumber = $orderInfo[1];
            $getOrderItems = "SELECT OrderID, ProductID, Quantity FROM ORDER_PRODUCTS WHERE OrderID = '$orderID'";
            $stmt = $mysql->prepare($getOrderItems);
            $stmt->execute();
            $getOrderItems = $stmt->fetchAll();
            foreach($getOrderItems as $OrderItems){
                $response .= $OrderItems['OrderID']."$".$OrderItems['ProductID']."$".$OrderItems['Quantity']."!";
            }
        }
        echo $response;
    } catch (PDOException $e) {
        echo $query . "<br>" . $e->getMessage();
    }
?>
