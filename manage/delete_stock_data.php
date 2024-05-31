<?php
include("../db.php");

if(isset($_POST["symbol"]) && strcmp($_POST["symbol"], "") != 0){
    $symbol = $_POST["symbol"];
    $recommend = $_POST["recommend"];
    
    $sql = "DELETE from stock where Symbol like '".$symbol."' and Recommend like '".$recommend."'";
    $result = $con1->query($sql);
    
    if($result){
        echo $symbol . " deleted successfully. ";
    }else{
        echo $symbol . " delete failed. ";
    }
    
    /*$sql1 = "DELETE from indicators where Symbol like '".$symbol."'";
    $result1 = $con1->query($sql1);
    
    if($result1){
        echo $symbol . " Indicators deleted successfully.";
    }else{
        echo $symbol . " Indicators delete failed.";
    }
    
    $sql2 = "DELETE from summary where Symbol like '".$symbol."'";
    $result2 = $con1->query($sql2);
    
    if($result2){
        echo $symbol . " Summary deleted successfully.";
    }else{
        echo $symbol . " Summary delete failed.";
    }
    
    $sql3 = "DELETE from moving_averages where Symbol like '".$symbol."'";
    $result3 = $con1->query($sql3);
    
    if($result3){
        echo $symbol . " Moving Averages deleted successfully.";
    }else{
        echo $symbol . " Moving Averages delete failed.";
    }
    
    $sql4 = "DELETE from oscillators where Symbol like '".$symbol."'";
    $result4 = $con1->query($sql4);
    
    if($result4){
        echo $symbol . " Oscillators deleted successfully.";
    }else{
        echo $symbol . " Oscillators delete failed.";
    } */
    
    $con1->close();
    
}else{
    echo "No Data";
}

?>