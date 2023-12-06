<?php
    session_start();
    include "db.php";

    $branchID = $_SESSION['branch'];

    try{
        $query = "SELECT * FROM OrderSummary WHERE BranchID = :branchID";
        $stmt = $mysql->prepare($query);
        $stmt->bindParam(":branchID", $branchID, PDO::PARAM_INT);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);

    }catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }


?>