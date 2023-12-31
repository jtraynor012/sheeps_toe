<?php
session_start();
include "db.php";

$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);
$orderId = $data['orderId'];
$orderId = intval($orderId);
$status = $data['status'];


// Retrieve the branch from the session
$branch = $_SESSION['branch'];

$getOrderProductsQuery = "
    SELECT p.ProductID, op.Quantity
    FROM ORDER_PRODUCTS op
    INNER JOIN PRODUCTS p ON op.ProductID = p.ProductID
    WHERE op.OrderID = :orderId
";

try {
        $orderId = intval($orderId);

        // Void the order in the database
        $updateOrderStatus = "CALL completeOrder(:orderId)";
        $stmt = $mysql->prepare($updateOrderStatus);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch order products, quantities, and order status
        $stmt = $mysql->prepare($getOrderProductsQuery);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        $orderData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        

        foreach ($orderData as $product) {
            $updateStockLevelsQuery = "
            UPDATE STOCK
            SET Count = Count - :quantity
            WHERE ProductID = :productId
            AND BranchID = :branchId
        ";
            $stmt = $mysql->prepare($updateStockLevelsQuery);
            $stmt->bindParam(':quantity', $product['Quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':productId', $product['ProductID'], PDO::PARAM_INT);
            $stmt->bindParam(':branchId', $branch, PDO::PARAM_INT);
            $stmt->execute();
        }

        
    // Return a success response
    echo json_encode(array('success' => true));
} catch (PDOException $e) {
    // Return an error response
    echo json_encode(array('success' => false, 'error' => $e->getMessage()));
}
?>
