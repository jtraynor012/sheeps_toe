<?php
    session_start();
    include "db.php";

    $category = $_GET['product'];
    $response = "";

    try {
        $query = "SELECT ProductName, Price, ProductImage FROM PRODUCTS WHERE ProductType = $category";
        $stmt = $mysql->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach($result as $row) {
            $response .= $row['ProductName']."$".$row['Price']."$".$row['ProductImage']."!"; // Use .= for string concatenation
        }
        echo $response;
    } catch (PDOException $e) {
        echo $query . "<br>" . $e->getMessage(); 
    }
?>
