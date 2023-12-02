<?php
    session_start();
    include "db.php";

    $branch = $_GET['Branch']; // Make sure to use this if it's needed
    $response = "";
    $currentOrders = "";

    function getOrders($branch,$mysql){
        $getOrders = "SELECT OrderID, TableNumber FROM ORDERS WHERE BranchID = $branch AND `Status` = 'In progress'";
        $stmt = $mysql->prepare($getOrders);
        $stmt->execute();
        $getOrders = $stmt->fetchAll();
        return $getOrders;
    }
    function getProductName($productID,$mysql){
        try{
            $getProductName = "SELECT ProductName FROM PRODUCTS WHERE ProductID = $productID";
            $stmt = $mysql->prepare($getProductName);
            $stmt->execute();
            $getProductNames = $stmt->fetchAll();
            $productName = "";
            foreach($getProductNames as $row){
                $productName = $row['ProductName'];
            }
            return $productName;
        } catch(PDOException $e){
            echo "getProductName Failure:" . "<br>" . $e->getMessage();
        }
    }
    try {
        $orders = getOrders($branch,$mysql);
        foreach($orders as $row) {
            $currentOrders .= $row['OrderID']."$".$row['TableNumber']."!";
        }
        $splitOrders = explode("!", $currentOrders);
        $splitOrders = array_filter($splitOrders, function($value) {
            return !empty($value);
        });
        foreach($splitOrders as $order){
            $orderInfo = explode("$",$order);
            $orderID = $orderInfo[0];
            $tableNumber = $orderInfo[1];
            $getOrderItems = "SELECT OrderID, ProductID, Quantity FROM ORDER_PRODUCTS WHERE OrderID = '$orderID'";
            $stmt = $mysql->prepare($getOrderItems);
            $stmt->execute();
            $getOrderItems = $stmt->fetchAll();
            foreach($getOrderItems as $OrderItems){
                $productName = getProductName($OrderItems['ProductID'],$mysql);
                $response .= $OrderItems['OrderID']."$".$tableNumber."$".$productName."$".$OrderItems['Quantity']."!";
            }
            $response .= ":";
        }
        echo $response;
    } catch (PDOException $e) {
        echo json_encode($query . "<br>" . $e->getMessage());
    }
?>
