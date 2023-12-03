<?php
    session_start();
    include "db.php";

    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);
    $orderId = $data['orderId'];
    $orderId = intval($orderId);
  

    // Retrieve the branch from the session
    $branch = $_SESSION['branch'];

    try {
        // Void the order in the database
        $voidOrder = "UPDATE ORDERS SET `Status` = 'Voided' WHERE OrderID = :orderId AND BranchID = :branch";
        $stmt = $mysql->prepare($voidOrder);
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
