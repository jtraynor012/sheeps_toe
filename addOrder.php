<?php
    include "db.php";
    //finds the branch id from the name of the branch the customer clicked that they were at.. 
    function getBranchFromName($branchName,$mysql){
        $query = "SELECT BranchID FROM BRANCH WHERE `Name` = :branchName";
        $stmt = $mysql->prepare($query);
        $stmt->bindParam(':branchName',$branchName);
        $stmt->execute();
        $query = $stmt->fetchAll();
        $branchID = "";
        foreach($query as $row){
            $branchID = $row['BranchID'];
        }
        return $branchID;
    }
    //adds the order to the ORDERS table. Since order has not been completed, TimeCompleted is null and Status is 'In progress'.
    function addOrder($branchID,$customerID,$timePlaced,$tableNumber,$mysql){
        $query = "INSERT INTO ORDERS (`BranchID`, `CustomerID`,`TimePlaced`,`TimeCompleted`,`Status`,`TableNumber`) VALUES (?,?,?,NULL,'In progress',?)";
        $stmt = $mysql->prepare($query);
        $stmt->bindParam(1,$branchID);
        $stmt->bindParam(2,$customerID);
        $stmt->bindParam(3,$timePlaced);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
        $stmt->bindParam(4,$tableNumber);
        $result = $stmt->execute();
        if ($result) {
            $inserted_id = $mysql->lastInsertId();
            return intval($inserted_id);
        }else{
            $errorInfo = $stmt->errorInfo();
            return "Error" . implode(";",$errorInfo);
        }
    }
    //splits the product's in the order to just one product each and their respective quantity ordered. 
    function splitProducts($stringProducts){
        $splitUpProducts = explode("!", $stringProducts);
        $splitUpProducts = array_filter($splitUpProducts, function($value) {
            return !empty($value);
        });
        return $splitUpProducts;
    }
    
    //gets product name, replaces _'s with spaces, get's rid of the product type and capitalises the first letter of each 
    //part of the string
    function getProductName($product){
        $productName = explode("_",$product);
        $name = "";
        $size = sizeof($productName);
        if($size>1){
          //Capitalise each character in the word
          for($i=1;$i<$size;$i++){
            $productName[$i] = ucfirst($productName[$i]);
          }
          array_splice($productName,0,1);
          $name = implode(" ", $productName);
          return $name;
        }
    }

    function getProductID($productName,$mysql){
        try{
            $getProductID = "SELECT ProductID FROM PRODUCTS WHERE ProductName = ?";
            $stmt = $mysql->prepare($getProductID);
            $stmt->bindParam(1,$productName);
            $stmt->execute();
            $getProductID = $stmt->fetchAll();
            $productID = "";
            foreach($getProductID as $row){
                $productID = $row['ProductID'];
            }
            return intval($productID);
        } catch(PDOException $e){
            echo "getProductName Failure:" . "<br>" . $e->getMessage();
        }    
    }

    function getPrice($productID,$mysql){
        try{
            $query = "SELECT Price FROM PRODUCTS WHERE ProductID = :productID";
            $stmt = $mysql->prepare($query);
            $stmt->bindParam(':productID',$productID);
            $stmt->execute();
            $query = $stmt->fetchAll();
            $price = "";
            foreach($query as $row){
                $price = $row['Price'];
            }
            return floatval($price);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    function getOrderCost($productID,$mysql){
        try{
            $query = "SELECT ProductOrderCost FROM PRODUCTS WHERE ProductID = :productID";
            $stmt = $mysql->prepare($query);
            $stmt->bindParam(':productID',$productID);
            $stmt->execute();
            $query = $stmt->fetchAll();
            $orderCost = "";
            foreach($query as $row){
                $orderCost = $row['ProductOrderCost'];
            }
            return floatval($orderCost);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    function addOrderProducts($orderID, $productArray, $mysql) {
        $productID = "";
        $quantity = "";
        $price = "";
        $orderCost = "";
        $result = array();
        try {
            foreach ($productArray as $productItem) {
                $breakdown = explode("$", $productItem);
                $productName = getProductName($breakdown[0]);
                $quantity = intval($breakdown[1]);
                $productID = intval(getProductID($productName, $mysql));
                $price = number_format(floatval(getPrice($productID,$mysql)),2,'.','');
                $orderCost = number_format(floatval(getOrderCost($productID,$mysql)),2,'.','');
                $price = floatval($price);
                $orderCost = floatval($orderCost);

                // return $string = $orderID." ".$productID." ".$quantity;
                $query = "INSERT INTO ORDER_PRODUCTS (`OrderID`, `ProductID`, `Quantity`, `RetailPriceAtOrder`, `ProductOrderCostAtOrder`) VALUES (:orderID, :productID, :quantity, :price, :productOrderCost)";
                $stmt = $mysql->prepare($query);
                $stmt->bindParam(':orderID', $orderID);
                $stmt->bindParam(':productID', $productID);
                $stmt->bindParam(':quantity', $quantity);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':productOrderCost', $orderCost);
                $queryResult = $stmt->execute();
                if($queryResult){
                    array_push($result,intval($mysql->lastInsertId()));
                }
            }
            return [
                'params' => [
                    'result' => json_encode($result),
                    'productArray' => json_encode($productArray),
                    '$productItem' => json_encode($productItem),
                    'orderID' => $orderID,
                    'productID' => $productID,
                    'quantity' => $quantity,
                    'price' => $price,
                    'orderCost' => $orderCost
                ]
            ];
        } catch (PDOException $e) {
            return [
                'error' => $e->getMessage(),
                'params' => [
                    'orderID' => $orderID,
                    'productID' => $productID,
                    'quantity' => $quantity,
                    'price' => $price,
                    'orderCost' => $orderCost
                ]
            ];
        }
    }    

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rawData = file_get_contents('php://input');
        $orderData = json_decode($rawData,true);

        if($orderData) {
            $stringProducts = $orderData['stringProducts'];
            $timePlaced = $orderData['currentDate'];
            $tableNumber = $orderData['tableNumber'];
            $customerID = $orderData['customerID'];
            $branch = $orderData['branch'];
            $branchID = getBranchFromName($branch,$mysql);
            $orderID = addOrder($branchID,$customerID,$timePlaced,$tableNumber,$mysql);
            $productArray = splitProducts($stringProducts);
            $addToOrder_Products = addOrderProducts($orderID,$productArray,$mysql);
            echo json_encode(["status" => "success", "message" => $addToOrder_Products]);
        }else{
            echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
        }
    }else{
        echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    }

?>