<?php
session_start();
include("../db.php");

if(isset($_POST["stockSymbol"]) && strcmp($_POST["stockSymbol"], "") != 0){
    
    $stock_symbol = $_POST["stockSymbol"];
    $target = $_POST["target"];
    $signal = $_POST["signal"];
    $strike = $_POST["strike"];
    $recommend = $_POST["recommendation"];
    $entryPrice = $_POST["entryPrice"];
    $option_target = $_POST["optionTarget"];
    $target_view = $_POST["targetView"];
    $fundNeeded = $_POST["fundNeeded"];
    $lotSize = $_POST["lotSize"];
    $broker = $_SESSION["broker"];
    $broker_name = $_SESSION["broker_name"];
    
    // Update Target View Date
    // Extract the number of days from the string
    $matches = [];
    if (preg_match('/(\d+)\s*-\s*(\d+)\s*Days/', $target_view, $matches)) {
        $minDays = intval($matches[1]);
        $maxDays = intval($matches[2]);
    
        // Calculate a random number of days within the range specified
        //$randomDays = rand($minDays, $maxDays);
    
        // Calculate the future date
        $currentDate = new DateTime();
        $currentDate->add(new DateInterval("P{$maxDays}D"));
    
        // Check if the future date falls on a Saturday or Sunday and move it to the next weekday if necessary
        while (in_array($currentDate->format('N'), [6, 7])) {
            $currentDate->add(new DateInterval("P1D"));
        }
    
        // Format the future date as "5 Oct 2023"
        $futureDate = $currentDate->format('j M Y');
    
        //echo $futureDate;
        $target_view = $futureDate;
    } else {
        //echo "Invalid input format";
    }
    
    // Set the timezone to IST (Indian Standard Time - Delhi).
    date_default_timezone_set('Asia/Kolkata');
    
    // Get the current date and time.
    //$currentDatetime = date('j M Y \a\t h:i a');
    $currentDatetime = date('j M, h:i a');
    
    
    $sql = "SELECT * from stock where `Symbol` like '".$stock_symbol."' and `Recommend` like '".$recommend."' and Broker like '".$broker."'";
    $result = $con1->query($sql);
    
    if($result->num_rows > 0){
        // Update Data
        $sql1 = "UPDATE stock set `Target` = '".$target."', `Signal` = '".$signal."', `Recommend` = '".$recommend."', `EntryPrice` = '".$entryPrice."', `OptionTarget` = '".$option_target."', `FundNeed` = '".$fundNeeded."', `View` = '".$target_view."', `Strike` = '".$strike."', `PostDate` = '".$currentDatetime."' where `Symbol` like '".$stock_symbol."' and `Recommend` like '".$stock_symbol."' and Broker like '".$broker."'";
        $result1 = $con1->query($sql1);
        if($result1){
            echo "Recommendation Updated Successfully. ";
        }else{
            echo "Recommendation Update Failed. ";
        }
        
    }else{
        // Insert Data
        $sql2 = "INSERT into stock (`Symbol`, `Target`, `Signal`, `Recommend`, `EntryPrice`, `OptionTarget`, `FundNeed`, `View`, `Chart`, `Strike`, `LotSize`, `Broker`, `BrokerName`, `PostDate`) VALUES('".$stock_symbol."', '".$target."', '".$signal."', '".$recommend."', '".$entryPrice."', '".$option_target."', '".$fundNeeded."', '".$target_view."', 'True', '".$strike."', '".$lotSize."', '".$broker."', '".$broker_name."', '".$currentDatetime."')";
        $result2 = $con1->query($sql2);
        if($result2){
            echo "Recommendation Added Successfully. ";
        }else{
            echo "Recommendation Add Failed. ";
        }
        
        // Insert Data in Indicators
        $sql3 = "INSERT into indicators (`Symbol`) VALUES('".$stock_symbol."')";
        $result3 = $con1->query($sql3);
        if($result3){
            echo $symbol . " Added to Indicators Successfully.";
        }else{
            echo $symbol . " Add Failed to Indicators.";
        }
        
        // Insert Data in Oscillators
        $sql4 = "INSERT into oscillators (`Symbol`) VALUES('".$stock_symbol."')";
        $result4 = $con1->query($sql4);
        if($result4){
            echo $symbol . " Added to Oscillators Successfully.";
        }else{
            echo $symbol . " Add Failed to Oscillators.";
        }
        
        // Insert Data in Moving Averages
        $sql5 = "INSERT into moving_averages (`Symbol`) VALUES('".$stock_symbol."')";
        $result5 = $con1->query($sql5);
        if($result5){
            echo $symbol . " Added to Moving Averages Successfully.";
        }else{
            echo $symbol . " Add Failed to Moving Averages.";
        }
        
        // Insert Data in Summary
        $sql6 = "INSERT into summary (`Symbol`) VALUES('".$stock_symbol."')";
        $result6 = $con1->query($sql6);
        if($result6){
            echo $symbol . " Added to Summary Successfully.";
        }else{
            echo $symbol . " Add Failed to Summary.";
        }
    }
    
    $con1->close();
    
}else{
    echo "No Data";
}

?>