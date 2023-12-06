<?php
    include "db.php";
    session_start();
    $branchID = intval($_SESSION['branch']);
    $response = array();

    try {
        $query = "SELECT ProductID, ProductName, ProductType, AmountSoldLastWeek FROM StockQuantityView WHERE BranchID = :branchID ORDER BY UrgencyRatio DESC";
        $stmt = $mysql->prepare($query);
        $stmt->bindParam(':branchID', $branchID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $stockInfo) {
            $stockQuantity = array(
                'StockInformation' => [
                    'ProductID' => $stockInfo['ProductID'],
                    'ProductName' => $stockInfo['ProductName'],
                    'ProductType' => $stockInfo['ProductType'],
                    'AmountSoldLastWeek' => $stockInfo['AmountSoldLastWeek']
                ]
            );
            $response[] = $stockQuantity;
        }
        echo json_encode($response);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
?>
