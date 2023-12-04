<?php
    session_start();
    include "db.php";

    $date = $_GET['date'];
    $dateLeft = date("Y-m-d H:i:s", strtotime($date . '00:00:00'));
    $dateRight = date("Y-m-d H:i:s", strtotime($date . '23:59:59'));
    $branchID = $_SESSION['branch'];

    try{
        $query = "SELECT HOUR(TimeCompleted) as HourOfDay, Profit
                FROM OrderStatisticsView
                WHERE TimeCompleted BETWEEN :profitDateLeft AND :profitDateRight
                AND BranchID = :branch
                GROUP BY HOUR(TimeCompleted)";

        $stmt = $mysql->prepare($query);
        $stmt->bindParam(":profitDateLeft", $dateLeft, PDO::PARAM_STR);
        $stmt->bindParam(":profitDateRight", $dateRight, PDO::PARAM_STR);
        $stmt->bindParam(":branch", $branchID, PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetchAll();
        $response = json_encode($result);
        echo $response;


    }catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage(); 
    }


?>