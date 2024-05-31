<?php
include("../db.php");

if(isset($_POST["status"])){
    $status = $_POST["status"];
    $sql = "INSERT into promotions (`Status`) VALUES('".$status."')";
    $con1->set_charset('utf8mb4');
    $result = $con1->query($sql);
    if($result){
        echo "Status Updated Successfully. "; 
    }else{
        echo "Status Update Failed. "; 
    }
}else{
    echo "No Status Message. ";
}

?>