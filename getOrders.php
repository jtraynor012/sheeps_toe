<?php
    session_start();
    include "db.php";

    $branch = $_SESSION['branch']; // Make sure to use this if it's needed
    $response = array(); // Initialize an empty array for the JSON response

    function getOrders($branch, $mysql){
        $getOrders = "SELECT OrderID, TableNumber FROM ORDERS WHERE BranchID = $branch AND `Status` = 'In progress'";
        $stmt = $mysql->prepare($getOrders);
        $stmt->execute();
        $getOrders = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array
        return $getOrders;
    }

    function getProductName($productID, $mysql){
        try {
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
            echo json_encode("getProductName Failure:" . "<br>" . $e->getMessage());
        }
    }

    try {
        $orders = getOrders($branch, $mysql);
        foreach($orders as $row) {
            $orderID = $row['OrderID'];
            $tableNumber = $row['TableNumber'];
            $orderItems = array();

            // Fetch order items for the current order
            $getOrderItems = "SELECT ProductID, Quantity FROM ORDER_PRODUCTS WHERE OrderID = '$orderID'";
            $stmt = $mysql->prepare($getOrderItems);
            $stmt->execute();
            $getOrderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach($getOrderItems as $OrderItems){
                $productName = getProductName($OrderItems['ProductID'], $mysql);
                $orderItems[] = array(
                    "ProductName" => $productName,
                    "Quantity" => $OrderItems['Quantity']
                );
            }

            $response[] = array(
                "OrderID" => $orderID,
                "TableNumber" => $tableNumber,
                "OrderItems" => $orderItems
            );
        }

        echo json_encode($response);
    } catch (PDOException $e) {
        echo json_encode("Error: " . $e->getMessage());
    }
?>
