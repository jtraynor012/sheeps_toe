<?php
    session_start();
    include "db.php";

    try{
        $query = "SELECT DISTINCT ProductType FROM PRODUCTS";
        $stmt = $mysql->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $response = [];
        foreach($result as $row){
            $response[] = $row['ProductType'];
        }
        echo json_encode($response);

    } catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }
?>