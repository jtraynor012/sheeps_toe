<?php
    session_start();
    include "db.php";

    try{
        $query="SELECT r.Rating, r.Comment, r.ReviewDate, b.Name, b.City, c.FirstName
                FROM REVIEWS r
                INNER JOIN BRANCH b ON r.BranchID = b.BranchID
                INNER JOIN CUSTOMERS c ON r.CustomerID = c.CustomerID";
        $stmt=$mysql->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $response = json_encode($result);
        echo $response;
    } catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }


?>