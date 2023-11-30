<?php
        session_start();
        include "db.php";
        

            $uname=$_POST['uname'];
            $pword=$_POST['pword'];
            $pword = hash("sha256", $pword);

            //If username is email, check customer table, else check staff table
            if(filter_var($uname, FILTER_VALIDATE_EMAIL)){
           
                $query = "SELECT c.EmailAddress, c.FirstName cc.Password
                FROM CUSTOMERS c
                JOIN CUSTOMER_CREDENTIALS cc ON c.CustomerID = cc.CustomerID
                WHERE c.EmailAddress = :email";

                $stmt = $mysql->prepare($query);
                $stmt->bindParam(":email", $uname, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetchAll();

                $Cust_ID = -1;

                $cust_auth = False;
                $staff_auth = False;
                
                foreach($result as $row){
                    if($uname == $row['EmailAddress'] && $pword == $row['Password']){
                        $Cust_ID = $row['CustomerID'];
                        $cust_auth = True;
                    }
                }

                if($cust_auth){
                    $query = "SELECT FirstName, CustomerID FROM CUSTOMERS WHERE CustomerID = $Cust_ID";
                    $stmt = $mysql->prepare($query);
                    $stmt->execute();
                    $result = $stmt->fetchAll();

                    if($result > 0){
                        foreach($result as $row){
                            $_SESSION['user'] = $row['FirstName'];
                            $_SESSION['id'] = $row['CustomerID'];
                            header("location: order.php");
                            exit;
                        }
                    }
                }
            }
            else{
                $query = "SELECT * FROM STAFF_CREDENTIALS";
                $stmt = $mysql->prepare($query);
                $stmt->execute();
                $result = $stmt->fetchAll();

                $staff_ID = -1;

                foreach($result as $row){
                    //echo $row['StaffID']." ".$row['Password']."<br>";
                    if($uname == $row['StaffID'] && $pword == $row['Password']){
                        $staff_ID = $row['StaffID'];
                        $staff_auth = True;
                    }
                }

                if($staff_auth){
                    $role = "";
                    $query = "SELECT FirstName, Role, BranchID FROM STAFF WHERE StaffID = $staff_ID";
                    $stmt = $mysql->prepare($query);
                    $stmt->execute();
                    $result = $stmt->fetchAll();

                    if($result > 0){
                        foreach($result as $row){
                            if($row['Role'] == "Manager"){
                                $_SESSION['user'] = $row['FirstName'];
                                $_SESSION['role'] = $row['Role'];
                                $_SESSION['branch'] = $row['BranchID'];
                                header("location: manage.php");
                                exit;
                            }
                            else{
                                $_SESSION['user'] = $row['FirstName'];
                                $_SESSION['role'] = $row['Role'];
                                $_SESSION['branch'] = $row['BranchID'];
                                header("location: order.php");
                                exit;
                            }
                        }
                    }
                }

            }

            if(!$staff_auth && !$cust_auth){
                header("location: login.php?msg=unauth");
                exit;
            }
    ?>