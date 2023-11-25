<?php
    session_start();
    include "db.php";

    $category = $_GET['product'];
    $response = "";

    try{
        $query = "SELECT ProductID, Price, ProductImage FROM PRODUCTS WHERE `Type` = 'Vodka'";
        $stmt = $mysql->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach($result as $row){
            $response += $row['ProductName'].",".$row['Price'].",".$row['ProductImage'].":";
        }
        echo $response;
    } catch (PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }
?>