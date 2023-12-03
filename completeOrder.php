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

$getOrderProductsQuery = "
    SELECT p.ProductID, op.Quantity
    FROM ORDER_PRODUCTS op
    INNER JOIN PRODUCTS p ON op.ProductID = p.ProductID
    WHERE op.OrderID = :orderId
";

try {
    // Update the order status in the database
    $updateOrderStatus = "UPDATE ORDERS SET `Status` = 'Completed' WHERE OrderID = :orderId AND BranchID = :branch";
    $stmt = $mysql->prepare($updateOrderStatus);
    $stmt->bindParam(':orderId', $orderId);
    $stmt->bindParam(':branch', $branch);
    $stmt->execute();

    // Fetch order products and quantities
    $stmt = $mysql->prepare($getOrderProductsQuery);
    $stmt->bindParam(':orderId', $orderId);
    $stmt->execute();
    $orderProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Update stock levels for each product in the order
    $updateStockLevelsQuery = "
        UPDATE STOCK
        SET Count = Count - :quantity
        WHERE ProductID = :productId
          AND BranchID = :branchId
    ";

    foreach ($orderProducts as $product) {
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
