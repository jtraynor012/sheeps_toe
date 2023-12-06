<?php
    session_start();
    include "db.php";

    try{
        


    }catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }


?>