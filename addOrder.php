<?php
    include "db.php";
    function getBranchFromName($branchName,$mysql){
        $query = "SELECT BranchID FROM BRANCH WHERE BranchName = $branchName";
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rawData = file_get_contents('php://input');
        $orderData = json_decode($rawData,true);

        if($orderData) {
            $stringProducts = $orderData['stringProducts'];
            $currentDate = $orderData['currentDate'];
            $tableNumber = $orderData['tableNumber'];
            $customerID = $orderData['customerID'];
            $branch = $orderData['branch'];
            $branch = getBranchFromName($branch,$mysql);

            echo json_encode(["status" => "success", "message" => "order received"]);
        }else{
            echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
        }
    }else{
        echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    }
?>