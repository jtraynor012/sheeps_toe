<?php
    include "db.php";
    session_start();

    $employee = $_POST['employeeDetails'];
    $branchID = $_SESSION['branch'];

    $employee_details = explode(" ", $employee);
    $employeeID = intval($employee_details[0]);

    try{
    $query = "DELETE FROM STAFF WHERE StaffID = $employeeID";
    $stmt = $mysql->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch();
    echo "Employee removed successfully...";
    header("location: man_employees.php");
    } catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();

    }

    if($result)





?>