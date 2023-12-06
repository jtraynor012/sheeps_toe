<?php
session_start();
include "db.php";

function verify($email, $password) {
    try{
    global $mysql;
    // Hash the provided password
    $hashedPassword = hash("sha256", $password);

    // Query the userCredentials view for the email and hashed password
    $query = "SELECT UserID, FirstName, UserType, `Password`
              FROM UserCredentials
              WHERE EmailAddress = :email";

    $stmt = $mysql->prepare($query);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($result) {
        // Email found, check password
        if ($hashedPassword == $result['Password']) {
            // Password is correct
            $_SESSION['user'] = ($result['FirstName']) ? $result['FirstName'] : $result['UserID'];
            $_SESSION['id'] = $result['UserID'];
            $_SESSION['role'] = $result['UserType'];

            if ($_SESSION['role'] == 'Customer') {
                header("location: order.php");
            }
            else{ 
                $query = "SELECT BranchID FROM BranchStaffList WHERE StaffID = :StaffID";
                $stmt = $mysql->prepare($query);
                $stmt->bindParam(":StaffID", $_SESSION['id'], PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                $_SESSION['branch'] = $result['BranchID'];



                if ($_SESSION['role'] == 'Manager') {
                    header("location: manage.php");
                }
                else {
<<<<<<< Updated upstream
                    echo $_SESSION['branch'];
                    if(!$_SESSION['branch']){
                        echo "BranchID not fetched...";
                    }
                    header("location: order.php"); //CHANGE THIS TO STAFF LANDING PAGE
=======
                    header("location: mo1.php"); //CHANGE THIS TO STAFF LANDING PAGE
>>>>>>> Stashed changes
                }
            }
            exit;
        } else {
            header("location: login.php?msg=unauth");
            exit;
        }
    } else {
        header("location: login.php?msg=unauth");
        exit;
    }
    }catch(PDOException $e){
        echo "Error: " . $e->getMessage();
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['uname'];
    $password = $_POST['pword'];
    echo "I am in main";
    verify($email, $password);
}
?>
