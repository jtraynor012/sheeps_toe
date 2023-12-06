<?php
    session_start();
    include "db.php";

    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);
    $orderId = $data['orderId'];
    $orderId = intval($orderId);

    $branch = $_SESSION['branch'];

    $getOrderProductsQuery = "
    SELECT p.ProductID, op.Quantity, o.Status
    FROM ORDER_PRODUCTS op
    INNER JOIN PRODUCTS p ON op.ProductID = p.ProductID
    INNER JOIN ORDERS o ON op.OrderID = o.OrderID
    WHERE op.OrderID = :orderId
    ";

    try {
        $orderId = intval($orderId);

        // Void the order in the database
        $inProgressOrder = "CALL inProgressOrder(:orderId)";
        $stmt = $mysql->prepare($inProgressOrder);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch order products, quantities, and order status
        $stmt = $mysql->prepare($getOrderProductsQuery);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->execute();
        $orderData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orderData as $order) {
            // Check if the order was originally completed
            if ($order['status'] === 'Completed') {
                // Update stock levels for each product in the order
                $updateStockLevelsQuery = "
                    UPDATE STOCK
                    SET Count = Count + :quantity
                    WHERE ProductID = :productId
                    AND BranchID = :branchId
                ";

                foreach ($orderData as $product) {
                    $stmt = $mysql->prepare($updateStockLevelsQuery);
                    $stmt->bindParam(':quantity', $product['Quantity'], PDO::PARAM_INT);
                    $stmt->bindParam(':productId', $product['ProductID'], PDO::PARAM_INT);
                    $stmt->bindParam(':branchId', $branch, PDO::PARAM_INT);
                    $stmt->execute();
                }

                // Return a success response
                echo json_encode(array('success' => true));
            } else {
                // Order was not originally completed, skip stock update
                echo json_encode(array('success' => false, 'error' => 'Order was not originally completed.'));
            }
        }
    } catch (PDOException $e) {
        // Return an error response
        echo json_encode(array('success' => false, 'error' => $e->getMessage()));
    }
?>
