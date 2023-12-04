<?php
    session_start();
    include "db.php";

    $product = $_GET['product'];
    $product = intval(trim($product, "'"));

    $branch = $_SESSION['branch'];

    $daySales = array(
        'Monday' => 0,
        'Tuesday' => 0,
        'Wednesday' => 0,
        'Thursday' => 0,
        'Friday' => 0,
        'Saturday' => 0,
        'Sunday' => 0
    );

    try{

        $query = "SELECT TimeCompleted, Quantity, NumberOfWeeks
                FROM OrderStatisticsView
                WHERE BranchID = :branchID AND
                    ProductID = :productID";


        $stmt = $mysql->prepare($query);
        $stmt->bindParam(':branchID', $branch, PDO::PARAM_INT);
        $stmt->bindParam(':productID', $product, PDO::PARAM_INT);

        
        $stmt->execute();
        $result = $stmt->fetchAll();

        foreach($result as $row){
            $timeCompleted = $row['TimeCompleted'];
            $quantity = $row['Quantity'];
            $minDate = $row['mostDistantDate'];
            $maxDate = $row['MostRecentDate'];
            $weeks = $row['NumberOfWeeks'] == 0 ? 1 : $row['NumberOfWeeks'];


            $dayOfWeek = date("l", strtotime($timeCompleted));
            $daySales[$dayOfWeek] += $quantity;
        }

        $averageSales = array();

        foreach($daySales as $day =>$totalSales){
            $averageSales[$day] = $totalSales / $weeks;
        }

        $response = [];
        foreach ($averageSales as $day => $average){
            $entry = array(
                'day' => $day,
                'average' => $average
            );
            $response[] = $entry;
        }
        echo json_encode($response);

    } catch (PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }


?>