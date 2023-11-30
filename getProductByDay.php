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

        $query = "SELECT TimeCompleted, Quantity, MIN(TimeCompleted) AS minDate, MAX(TimeCompleted) as maxDate 
                FROM ORDERS, ORDER_PRODUCTS 
                WHERE ORDERS.BranchID = :branchID AND
                    ORDER_PRODUCTS.ProductID = :productID AND
                    ORDER_PRODUCTS.OrderID = ORDERS.OrderID";


        $stmt = $mysql->prepare($query);
        $stmt->bindParam(':branchID', $branch, PDO::PARAM_INT);
        $stmt->bindParam(':productID', $product, PDO::PARAM_INT);

        
        $stmt->execute();
        $result = $stmt->fetchAll();

        foreach($result as $row){
            $timeCompleted = $row['TimeCompleted'];
            $quantity = $row['Quantity'];
            $minDate = $row['minDate'];
            $maxDate = $row['maxDate'];


            $dayOfWeek = date("l", strtotime($timeCompleted));
            $daySales[$dayOfWeek] += $quantity;
        }

        $averageSales = array();

        if($minDate && $maxDate){
            $weeks = (int)date("W", strtotime($maxDate)) - (int)date("W", strtotime($minDate));
            if($weeks==0){
                $weeks=1;
            }
        }
        else{
            $weeks = 1;
        }

        foreach($daySales as $day =>$totalSales){
            $averageSales[$day] = $totalSales / $weeks;
        }

        $reply = "";
        foreach ($averageSales as $day => $average){
            $reply .=$day.",".$average."<br>";
        }
        echo $reply;

    } catch (PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }


?>