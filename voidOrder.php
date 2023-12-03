<?php
    session_start();
    include "db.php";

    // Assuming you receive the orderId as a POST parameter
    $orderId = $_POST['orderId'];

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
