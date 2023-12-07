<?php
session_start();
include "db.php";

$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);

// Log the received JSON data for debugging
error_log('Received JSON data: ' . print_r($data, true));

$orderId = $data['orderId'];
$orderId = intval($orderId);
$status = $data['status'];

$branch = $_SESSION['branch'];
echo json_encode($status);
$getOrderProductsQuery = "
    SELECT p.ProductID, op.Quantity
    FROM ORDER_PRODUCTS op
    INNER JOIN PRODUCTS p ON op.ProductID = p.ProductID
    WHERE op.OrderID = :orderId
";

$response = array(); // Initialize the response array
try {

    $orderId = intval($orderId);
    $status = $data['status'];


    if ($status == 'Completed') {
        // Update stock levels for each product in the order
        

        

        $inProgressOrder = "CALL inProgressOrder(:orderId)";
        $stmt = $mysql->prepare($inProgressOrder);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();


        // Fetch order products, quantities, and order status
        $stmt = $mysql->prepare($getOrderProductsQuery);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        $orderData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orderData as $product) {
            $updateStockLevelsQuery = "CALL UpdateStockLevels(:branchID, :productID, :quantity)";

            $stmt = $mysql->prepare($updateStockLevelsQuery);
            $stmt->bindParam(':quantity', $product['Quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':productID', $product['ProductID'], PDO::PARAM_INT);
            $stmt->bindParam(':branchID', $branch, PDO::PARAM_INT);
            $stmt->execute();
        }

        // Set success response in the response array
        $response['success'] = true;
        $response['orderId'] = $orderId;
    } else {
        // Order was not originally completed, skip stock update
        $response['success'] = $status;
        // Void the order in the database
    $inProgressOrder = "CALL inProgressOrder(:orderId)";
    $stmt = $mysql->prepare($inProgressOrder);
    $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch order products, quantities, and order status
    $stmt = $mysql->prepare($getOrderProductsQuery);
    $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
    $stmt->execute();
    $orderData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }    
        // Check if the order was originally completed
        
    
} catch (PDOException $e) {
    // Log the exception for debugging
    error_log('PDOException: ' . $e->getMessage());

    // Set error response in the response array
    $response['success'] = false;
    $response['error'] = $e->getMessage();
}

// Always echo valid JSON
echo json_encode($response);
?>
