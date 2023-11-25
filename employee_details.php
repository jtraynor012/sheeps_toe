<?php
    include "db.php";
    session_start();

    $employee = $_GET['name'];
    $branchID = $_SESSION['branch'];
    $employee_details = explode(" ", $employee);
    $employee_branch = intval($employee_details[0]);
    try{
        $query = "SELECT * FROM STAFF WHERE StaffID = $employee_branch";
        $stmt = $mysql->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();

        foreach($result as $row){
            $dob = date('Y-m-d', strtotime($row['DOB']));
            $startdate = date('Y-m-d', strtotime($row['DateEmployed']));

            echo "BranchID: ".$row['BranchID']."<br>StaffID: ".$row['StaffID']."<br>First Name: ".$row['FirstName']."<br>Last Name: ".$row['LastName']."<br>Date of Birth: ".$dob."<br>Date Started: ".$startdate."<br>Email Address: ".$row['EmailAddress']."<br>Phone Number: ".$row['PhoneNumber']."<br>Role: ".$row['Role']."<br>Salary: £".$row['Salary'];
        }
    } catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }

?>