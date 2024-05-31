<?php
session_start();
include("db.php");
    
if(isset($_POST["username"]) && isset($_POST["password"])){
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $sql = "SELECT * from login where `username` like '%".$username."%' and `password` like '%".$password."%'";
    $result = $con1->query($sql);
    
    if($result->num_rows > 0){
        $_SESSION["username"] = $username;
        while($row = $result->fetch_assoc()){
            $logintype = $row["Type"];
            $_SESSION["logintype"] = $logintype;
        }
        echo $logintype;
    }else{
        echo "False";
    }
    
}else{
    echo "False";    
}

?>