<?php
    session_start();
    include "db.php";

    $date = $_GET['date'];
    $date = date("Y-m-d", strtotime($date));
    $branchID = $_SESSION['branch'];

    try{
        $query = "SELECT HOUR(o.TimeCompleted) AS HourOfDay, SUM((op.RetailPriceAtOrder - op.ProductOrderCostAtOrder)*op.Quantity) AS Profit
        FROM ORDERS o
        JOIN ORDER_PRODUCTS op on o.OrderID = op.OrderID
        WHERE DATE(o.TimeCompleted) = :profitDate
        AND o.BranchID = :branch
        GROUP BY HOUR(TimeCompleted)";

        $stmt = $mysql->prepare($query);
        $stmt->bindParam(":profitDate", $date, PDO::PARAM_STR);
        $stmt->bindParam(":branch", $branchID, PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetchAll();
        $response = json_encode($result);
        echo $response;


    }catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage(); 
    }


?>