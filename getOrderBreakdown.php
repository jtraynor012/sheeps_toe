<?php
    session_start();
    include "db.php";

    $orderID = intval($_GET['OrderID']);
    $products = array();
    $response = array();
    $customerName = "";
    $branchName = "";
    $orderTime = "";
    $totalOrderValue = "";

    try{
        $query = "SELECT branchName, customerName, ProductName, Quantity, unitPrice, totalPricePerProduct, orderTime, totalOrderValue FROM OrderBreakdown WHERE OrderID = :orderID";
        $stmt = $mysql->prepare($query);
        $stmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach($result as $orderInfo){
            $branchName = $orderInfo['branchName'];
            $customerName = $orderInfo['customerName'];
            $orderTime = $orderInfo['orderTime'];
            $totalOrderValue = $orderInfo['totalOrderValue'];
            $product = array(
                'ProductOrderedInfo' => [
                    'ProductName' => $orderInfo['ProductName'],
                    'Quantity' => $orderInfo['Quantity'],
                    'unitPrice' => $orderInfo ['unitPrice'],
                    'totalPricePerProduct' => $orderInfo['totalPricePerProduct']
                ]
            );
            $products[] = $product;
        }
        $response = [
            'Details' => [
                'CustomerName' => $customerName,
                'BranchName' => $branchName,
                'OrderTime' => $orderTime,
                'TotalOrderValue' => $totalOrderValue
            ],
            'Products' => $products
        ];
        echo json_encode($response);
    } catch(PDOEXception $e){
        echo $e->getMessage();
    }
?>