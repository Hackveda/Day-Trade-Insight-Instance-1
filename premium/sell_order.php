<?php
session_start();
include("../db.php");

if(isset($_SESSION["username"]) && strcmp($_SESSION["username"], "") != 0){
    $username = $_SESSION["username"];
    
    $symbol = $_POST["symbol"];
    $price = $_POST["price"];
    $qty = $_POST["qty"];
    $margin = round($price * $qty, 2);
    $hold_qty = "";
    
    
    $funds = 0;
    
    // Check funds in account 
    $sql = "SELECT * from funds where Username like '".$username."'";
    $result = $con1->query($sql);
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $funds = $row["Funds"];
        }
    }else{
        $funds = 0;
    }
    
    // Check Holdings
    
    $sql2 = "SELECT * from orders where `Username` like '".$username."' and `Symbol` like '".$symbol."' and BuyStatus = 'True'";
            
            $result2 = $con1->query($sql2);
            
            if($result2->num_rows > 0){
                while($row = $result2->fetch_assoc()){
                    $hold_qty = $row["BuyQty"];
                }
            }else{
                // Do nothing
            }
            
    $margin = $margin - ($hold_qty * $price);  
    
    if($margin < $funds){
        
        // Set the default time zone to Asia/Kolkata
        date_default_timezone_set('Asia/Kolkata');

        // Get the current date and time in Kolkata time zone
        $dateTime = date('Y-m-d H:i:s');

        // Place Order 
        
        $sql1 = "INSERT into orders (`Symbol`, `SellPrice`, `SellQty`, `SellDate`, `Username`) VALUES('".$symbol."', '".$price."', '".$qty."', '".$dateTime."', '".$username."')";
        
        $result1 = $con1->query($sql1);
        
        if($result1){
            echo "Sell Order Placed. ";
            
            $funds = round($funds - $margin);
            
            // Deduct Margin
            $sql2 = "Update funds set `Funds` = '".$funds."' where Username like '".$username."'";
            
            $result2 = $con1->query($sql2);
            if($result2){
                // Do Nothing
            }else{
                // Do Nothing
            }
            
        }else{
            echo "Order Failed. ";
        }
        
    }else{
        echo "Insufficient Funds. ";
    }
    
}else{
    echo "Login to continue ...";
}

?>