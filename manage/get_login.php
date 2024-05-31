<?php
session_start();
include("../db.php");
    
if(isset($_POST["username"]) && isset($_POST["password"])){
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $sql = "SELECT * from admin_login where `Username` like '".$username."' and `Password` like '".$password."'";
    $result = $con1->query($sql);
    
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $_SESSION["broker"] = $row["Username"];
            $_SESSION["broker_name"] = $row["Name"];
        }
        echo "True";
    }else{
        echo "False";
    }
    
}else{
    echo "False";    
}

?>