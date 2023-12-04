<?php
    session_start();
    include "db.php";

    // Assuming you receive the orderId as a POST parameter
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);
    $orderId = $data['orderId'];
    $orderId = intval($orderId);
  

    // Retrieve the branch from the session
    $branch = $_SESSION['branch'];

    try {
        // Void the order in the database
        $inProgressOrder = "UPDATE ORDERS SET `Status` = 'In Progress' WHERE OrderID = :orderId AND BranchID = :branch";
        $stmt = $mysql->prepare($inProgressOrder);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->bindParam(':branch', $branch);
        $stmt->execute();

        // Return a success response
        echo json_encode(array('success' => true));
    } catch (PDOException $e) {
        // Return an error response
        echo json_encode(array('success' => false, 'error' => $e->getMessage()));
    }
?>