<?php
session_start();
include("db.php");
    
if(isset($_POST["username"]) && isset($_POST["password"])){
    $name = str_replace("'", "", $_POST["name"]);
    $username = str_replace("'", "", $_POST["username"]);
    $password = str_replace("'", "", $_POST["password"]);
    $mobile = $_POST["mobile"];
    
    $type = "premium-user";
    
    $sql = "INSERT into login (Name, Username, Password, Type, Mobile) VALUES('".$name."', '".$username."', '".$password."', '".$type."', '".$mobile."')";
    $result = $con1->query($sql);
    
    if($result){
        $_SESSION["username"] = $username;
        $_SESSION["logintype"] = $type;
        echo $type;
    }else{
        echo "False";
    }
    
}else{
    echo "False";    
}

?>