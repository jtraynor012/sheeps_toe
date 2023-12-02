<?php
    include "db.php";
    session_start();

    $employee = $_POST['employeeDetails'];

    $employee_details = explode(" ", $employee);
    $employeeID = intval($employee_details[0]);

    try{
    $query = "DELETE FROM STAFF WHERE StaffID = $employeeID";
    $query = "CALL deleteStaff(:staffID)";
    $stmt = $mysql->prepare($query);
    $stmt->bindParam(":staffID", $employeeID, PDO::PARAM_INT);
    $stmt->execute();
    header("location: man_employees.php");
    } catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();

    }

?>