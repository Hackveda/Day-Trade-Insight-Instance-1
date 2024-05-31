<?php
include("../db.php");

if(isset($_POST["symbol"]) && strcmp($_POST["symbol"], "") != 0){
    $symbol = $_POST["symbol"];
    $target = $_POST["target"];
    $signal = $_POST["signal"];
    $strike = $_POST["strike"];
    $recommend = $_POST["recommend"];
    $entry_price = $_POST["entry_price"];
    $option_target = $_POST["option_target"];
    $fundNeeded = $_POST["fundNeeded"];
    $view = $_POST["view"];
    
    // Update Target View Date
    // Extract the number of days from the string
    $matches = [];
    if (preg_match('/(\d+)\s*-\s*(\d+)\s*Days/', $view, $matches)) {
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
        $view = $futureDate;
    } else {
        //echo "Invalid input format";
    }
    
    // Set the timezone to IST (Indian Standard Time - Delhi).
    date_default_timezone_set('Asia/Kolkata');
    
    // Get the current date and time.
    //$currentDatetime = date('j M Y \a\t h:i a');
    $currentDatetime = date('j M, h:i a');
    
    
    $sql = "UPDATE stock set `Target` = '".$target."', `Signal` = '".$signal."', `Recommend` = '".$recommend."', `EntryPrice` = '".$entry_price."', `OptionTarget` = '".$option_target."', `FundNeed` = '".$fundNeeded."', `View` = '".$view."', `Strike` = '".$strike."', `PostDate` = '".$currentDatetime."' where Symbol like '".$symbol."' and Recommend like '".$recommend."'";
    $result = $con1->query($sql);
    
    if($result){
        echo $symbol . " data updated successfully";
    }else{
        echo $symbol . " data updated failed";
    }
    
    $con1->close();
    
}else{
    echo "No Data";
}
?>