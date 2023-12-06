<?php
    session_start();
    include "db.php";

    $category = $_GET['product'];
    $response = "";

    try {
        $query = "SELECT ProductName, ProductID FROM PRODUCTS";
        $stmt = $mysql->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach($result as $row) {
            $response .= $row['ProductName']."$".$row['ProductID']."<br>"; 
        }
        echo $response;
    } catch (PDOException $e) {
        echo $query . "<br>" . $e->getMessage(); 
    }
?>