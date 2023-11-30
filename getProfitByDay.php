<?php
    session_start();
    include "db.php";

    $dateFrom = $_GET['dateFrom'];
    $dateTo = $_GET['dateTo'];

    $dateFrom = date("Y-m-d", strtotime($dateFrom));
    $dateTo = date("Y-m-d", strtotime($dateTo));
    $branchID = intval($_SESSION['branch']);

    try{

        $query = "SELECT op.RetailPriceAtOrder, op.ProductOrderCostAtOrder, o.TimeCompleted
                FROM ORDER_PRODUCTS op, ORDERS o
                WHERE op.OrderID = o.OrderID
                AND (o.TimeCompleted BETWEEN :dateFrom AND :dateTo)
                AND o.BranchID = :branchID";

        $stmt = $mysql->prepare($query);
        $stmt->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
        $stmt->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
        $stmt->bindParam(':branchID', $branchID, PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetchAll();
        $response = array();

        foreach($result as $row){
            $entry = array(
                'retailPrice' => $row['RetailPriceAtOrder'],
                'costPrice' => $row['ProductOrderCostAtOrder'],
                'timeCompleted' => $row['TimeCompleted']
            );
            $response[] = $entry;
        }
        echo json_encode($response);

    } catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }

?>