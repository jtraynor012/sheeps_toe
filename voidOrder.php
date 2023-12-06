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
        
        $orderId = intval($orderId);

        // Void the order in the database
        $voidOrder = "CALL voidOrder(:orderId)";
        $stmt = $mysql->prepare($voidOrder);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();


        // Return a success response
        echo json_encode(array('success' => true));
    } catch (PDOException $e) {
        // Return an error response
        echo json_encode(array('success' => false, 'error' => $e->getMessage()));
    }
?>