<?php
    session_start();
    include "db.php";

    $dateFrom = $_GET['dateFrom'];
    $dateFrom = date("Y-m-d", strtotime($dateFrom));

    $dateTo = $_GET['dateTo'];
    $dateTo = date("Y-m-d", strtotime($dateTo));

    $category = $_GET['category'];
    $branchID = $_SESSION['branch'];

    try{
        /*
        $query = "SELECT p.ProductName, p.ProductType, SUM(op.Quantity) AS UnitsSold, 
                SUM((op.RetailPriceAtOrder - op.ProductOrderCostAtOrder)*op.Quantity) AS Profit
                FROM ORDER_PRODUCTS op
                JOIN PRODUCTS p ON op.ProductID = p.ProductID
                JOIN ORDERS o ON op.OrderID = o.OrderID
                WHERE o.TimeCompleted BETWEEN :dateFrom AND :dateTo
                AND p.ProductType = :category
                AND o.BranchID = :branchID
                GROUP BY p.ProductName, p.ProductType";
        */
        $query = "SELECT ProductName, ProductType, totalSold, Profit
                FROM OrderStatisticsView
                WHERE TimeCompleted BETWEEN :dateFrom AND :dateTo
                AND productType = :category
                AND BranchID = :branchID";

        $stmt = $mysql->prepare($query);
        $stmt->bindParam(":dateFrom", $dateFrom, PDO::PARAM_STR);
        $stmt->bindParam(":dateTo", $dateTo, PDO::PARAM_STR);
        $stmt->bindParam(":category", $category, PDO::PARAM_STR);
        $stmt->bindParam(":branchID", $branchID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $response = json_encode($result);
        echo $response;

    }catch(PDOException $e){
        echo $sql ."<br>". $e->getMessage();
    }

?>