<?php
    session_start();
    include "db.php";

    $dateFrom = $_GET['dateFrom'];
    $dateTo = $_GET['dateTo'];

    $dateFrom = date("Y-m-d H:i:s", strtotime($dateFrom . '00:00:00'));
    $dateTo = date("Y-m-d H:i:s", strtotime($dateTo . '23:59:59'));
    $branchID = intval($_SESSION['branch']);

    try{
        $query = "SELECT Profit, TimeCompleted
                FROM OrderStatisticsView
                WHERE TimeCompleted BETWEEN :dateFrom AND :dateTo
                AND BranchID = :branchID";

        $stmt = $mysql->prepare($query);
        $stmt->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
        $stmt->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
        $stmt->bindParam(':branchID', $branchID, PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetchAll();
        $response = array();

        foreach($result as $row){
            $entry = array(
                'Profit' => floatval($row['Profit']),
                'TimeCompleted' => $row['TimeCompleted']
            );
            $response[] = $entry;
        }
        echo json_encode($response);

    } catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }

?>