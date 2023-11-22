<?php
    include "db.php";
    session_start();

    $employee = $_POST['employeeDetails'];
    $branchID = $_SESSION['branch'];

    $employee_details = explode(" ", $employee);

    try{
    $query = "DELETE FROM STAFF WHERE BranchID = $BranchID AND StaffID = $employee_details[0]";
    $stmt = $mysql->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch();
    echo "Employee removed successfully...";
    } catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();

    }

    if($result)





?>