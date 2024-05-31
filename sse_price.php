<?php
session_start();
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include("db.php");

if(isset($_SESSION["username"])){
    $username = $_SESSION["username"];
    $sql17 = "SELECT Status from login where Username like '".$username."'";
    $result17 = $con1->query($sql17);
    if($result17->num_rows > 0){
        while($row = $result17->fetch_assoc()){
            $status = $row["Status"];
                
        }
    }
    //session_write_close();
}else{
    $username = "";
}

if(isset($_SESSION["broker"])){
    $sbroker = $_SESSION["broker"];
    $sbroker_name = $_SESSION["broker_name"];
}

session_write_close();

date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
#$date = date('d M Y H:i:s');
$date = date('d M Y');
//$date = "24 July 2023";

// Simulate real-time data updates
while (true) {
    // Replace this with your actual data retrieval logic
    $data = array();
    
    #$sql = "Select * from stock where TradeDate like '%.$date.%'";
    // Handle Recommendations 
    
    $sql = "select * from stock where `Hit` like '' ORDER BY ID DESC";
    
    $show_count = 3;
    $show_counter = 0;
    
    $result = $con1->query($sql);
    
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $symbol = $row["Symbol"];
            $price = $row["Price"];
            $target = $row["Target"];
            $signal = $row["Signal"];
            $recommend = $row["Recommend"];
            $op_price = $row["OptionPrice"];
            $op_target = $row["OptionTarget"];
            $view = $row["View"];
            $chart = $row["Chart"];
            $hit = $row["Hit"];
            
            $close = $row["Close"];
            $open = $row["Open"];
            
            $option_close = $row["OptionClose"];
            $option_open = $row["OptionOpen"];
            
            $broker = $row["Broker"];
            $broker_name = $row["BrokerName"];
            
            $postdate = $row["PostDate"];
            $entryprice = $row["EntryPrice"];
            $entryhit = $row["EntryHit"];
            
            $trailing_stop_loss = $row["TrailingSL"];
            $highest_price = $row["HighestPrice"];
            
            if(empty($price) || empty($op_price)){
                continue;
            }
            
            if(strcmp($entryhit, "True") == 0){
                $entryhit_sign = '<span><img width="20" title="Entry Price Reached" src="https://img.icons8.com/ios-glyphs/30/000000/checkmark--v1.png" alt="checkmark--v1"/> Hit</span>';
            }else{
                $entryhit = "False";
                $entryhit_sign = "";
            }
            
            if(floatval($entryprice) > floatval($op_price)){
                //$entryprice = $op_price;
                $entryhit = "True";
            }
            
            // Handle Trailing Stop Loss
            // Initialize trailing stop variables if not already set
            if (!isset($_SESSION[$recommend . '_highest_price'])) {
                //setcookie($recommend . '_highest_price', $op_price, time() + 1);
                $_SESSION[$recommend . '_highest_price'] = $op_price;
                //setcookie($recommend . '_trailing_stop_loss', $op_price * (1 - 0.10), time() + 1);
                $_SESSION[$recommend . '_trailing_stop_loss'] = $op_price * (1 - 0.10);  // Assuming a 10% trailing stop loss
            }
            
            if(strcmp($trailing_stop_loss, "") == 0){
                $highest_price = $op_price;
                $trailing_stop_loss = round($op_price * (1 - 0.10), 2);
            }
    
            // Update the highest price and adjust the trailing stop loss
            if ($op_price >= $highest_price) {
                //setcookie($recommend . '_highest_price', $op_price, time());
                $highest_price = $op_price;
                //setcookie($recommend . '_trailing_stop_loss', $op_price * (1 - 0.10), time() + 1);
                $trailing_stop_loss = round($op_price * (1 - 0.10), 2);  // Update stop loss to 10% below the new highest price
            }
            
            $sql14 = "UPDATE stock SET TrailingSL = '".$trailing_stop_loss."', HighestPrice = '".$highest_price."' where Symbol like '".$symbol."' and Recommend like '".$recommend."'";
            $con1->query($sql14);
            
            /*
            // Set Lowest Entry Price
            if(isset($_SESSION[$recommend])){
            if($_SESSION[$recommend] >= $entryprice){
                $_SESSION[$recommend] = $entryprice;
            }else{
                $entryprice = $_SESSION[$recommend];
            }
            
            $sql14 = "UPDATE stock SET entryprice = ".$entryprice.", entryhit = '".$entryhit."' where Symbol like '".$symbol."' and Recommend like '".$recommend."'";
            $con1->query($sql14);
            }else{
                 $_SESSION[$recommend] = $entryprice;
            }
            */
            
            
            $lotsize = $row["LotSize"];
            
            $fundneeded = "&#x20B9 " . floatval($entryprice) * floatval($lotsize);
            
            
            
            if(strcmp($entryprice, "0.0") !== 0){
                $profit = (floatval($op_target) - floatval($entryprice)) * floatval($lotsize);
                $profit = "&#x20B9 ".$profit;
            }else{
                $profit = "NA";
            }
            
            
            $target_percent = floor((((floatval($op_target) - floatval($entryprice)) * floatval($lotsize))/(floatval($entryprice) * floatval($lotsize)))*100) . " %";
            
            
            if($price < $open){
                $color = "#df514c";
            }else{
                $color = "#4caf50";
            }
            
            if($op_price < $option_open){
                $op_color = "#df514c";
            }else{
                $op_color = "#4caf50";
            }
            
            if(strcmp($signal, "Buy") == 0){
                $signal_color = "#4caf50";
            }else{
                $signal_color = "#df514c";
            }
            
            $target_color = "#00b0ff";
            
             

        // Check if the current price is below the trailing stop loss to trigger a sell
        if ($op_price <= $trailing_stop_loss) {
            $hit = "True";  // Adjusting this to mark the stock as ready to sell
            $sql_update_hit = "UPDATE stock SET Hit = 'True', OptionTarget = '".$trailing_stop_loss."' WHERE Symbol = '".$symbol."'";
            $con1->query($sql_update_hit);
        }
        
        if(strcmp($entryprice, "0.0") !== 0){
                $c_profit = round((floatval($op_price) - floatval($entryprice)) * floatval($lotsize), 2);
                $c_profit = "&#x20B9 ".$c_profit;
            }else{
                $c_profit = "NA";
        }
        
        $c_target_percent = floor((((floatval($op_price) - floatval($entryprice)) * floatval($lotsize))/(floatval($entryprice) * floatval($lotsize)))*100) . " %";
            
            
            // Check Stock Uptrend of Downtrend
            $sql15 = "SELECT * from indicators where Symbol like '".$symbol."'";
            $result15 = $con1->query($sql15);
            if($result15->num_rows > 0){
                while($row = $result15->fetch_assoc()){
                    $mom_val = $row["Mom"];
                    $rsi = $row["RSI"];
                    $adxdi = $row["ADXDI"];
                    $adx_di = $row["ADX_DI"];
                    if($adxdi > $adx_di){
                        //$adx = '<a class="text-center" style="color: #28a745;">Trend is Bullish</a>';
                        $trend_sign = '<br ><span title="Moving Upwards" style="color:green;">⬆️ <a style="font-size:5;">Trend</a></span>';
                    }else{
                        //$adx = '<a class="text-center" style="color: #dc3545;">Trend is Bearish</a>';
                        //$trend = "Bearish";
                        $trend_sign = '<br ><span title="Moving Downwards" style="color:red;">⬇️ <a style="font-size:5;">Trend</a></span>';
                    }
                }
            }
            
            //if($show_counter <= $show_count){
                
                //$data["free"] .= '<tp><td>'.$postdate.'</td><td style="color:'.$color.';">'.$symbol.'</td><td style="color:'.$color.';">'.$price.'</td><td style="color: '.$target_color.';">'.$target.'</td><td style="color: '.$signal_color.'">'.$signal.'</td><td>'.$recommend.'</td><td style="color: '.$op_color.';">'.$op_price.'</td><td>'.$entryprice.'</td><td style="color: '.$target_color.';">'.$op_target.'</td><td>'.$view.'</td>';
              
              $data["free"] .= '<tp><td>'.$postdate.'</td><td style="color:'.$color.';">'.$symbol.' </td><td style="color:'.$color.';">'.$price.' '.$trend_sign.'</td><td style="color: '.$target_color.';">🔒</td><td style="color: '.$signal_color.'">🔒</td><td>🔒</td><td style="color: '.$op_color.';">'.$op_price.'</td><td>🔒</td><td style="color: '.$target_color.';">🔒</td><td>'.$fundneeded.'</td><td>'.$profit.'</td><td>'.$target_percent.'</td><td>'.$view.'</td>';
              
              
              if(strcmp($chart, '') != 0){
                  
              //$data["free"] .= '<td><a class="btn btn-primary btn-sm" href="https://rzp.io/l/3XQXcZv">Upgrade</a></td>';
              
              }else{
                $data["free"] .= '<td>No Data</td>';  
              }
              
              /*
              if(strcmp($hit, "True") == 0){
                 $data["free"] .= '<td><img title="Target Hit" width="24" height="24" src="https://img.icons8.com/color/48/checked--v1.png" alt="checked--v1"/></td>'; 
              }else if(strcmp($hit, "False") == 0){
                 $data["free"] .= '<td><img title="Target Missed" width="24" height="24" src="https://img.icons8.com/external-others-agus-raharjo/64/external-incorrect-flat-website-ui-others-agus-raharjo.png" alt="external-incorrect-flat-website-ui-others-agus-raharjo"/></td>';
              }else{
                  $data["free"] .= '<td><img title="Target Pending" width="24" height="24" src="https://img.icons8.com/office/16/hourglass--v1.png" alt="hourglass--v1"/></td>';
              }
              
              $data["free"] .= '<td>'.$broker_name.'</td>';
              
              */
              
              $data["free"] .= '</tr>';
              
              
                
            /*}else{
                
               
            }*/
            
            $show_counter .= 1;
            
            // Premium Users
            
            $data["premium"] .= '<tp><td>'.$postdate.'</td><td style="color:'.$color.';"><a target="_blank" title="'.$symbol.'" class="btn" data-symbol="'.$symbol.'" data-action="analysis" style="color:'.$color.';">'.$symbol.'</a></td><td style="color:'.$color.';">'.$price.'</td><td style="color: '.$target_color.';">'.$target.'</td><td style="color: '.$signal_color.'">'.$signal.'</td><td>'.$recommend.'</td><td style="color: '.$op_color.';">'.$op_price.'</td><td>'.$entryprice.' '.$entryhit_sign.'</td><td style="color: '.$target_color.';">'.$op_target.'</td><td>'.$view.'</td>';
              
              if(strcmp($chart, '') != 0){
                  
              //$data["premium"] .= '<td><center><a target="_blank" title="'.$symbol.'" class="btn" data-symbol="'.$symbol.'"><img width="24" height="24" src="https://img.icons8.com/color/48/candle-sticks.png" alt="candle-sticks"/></a></center></td>';
              
              }else{
                $data["premium"] .= '<td>No Data</td>';  
              }
              
              /*
              if(strcmp($hit, "True") == 0){
                 $data["premium"] .= '<td><img title="Target Hit" width="24" height="24" src="https://img.icons8.com/color/48/checked--v1.png" alt="checked--v1"/></td>'; 
              }else if(strcmp($hit, "False") == 0){
                 $data["premium"] .= '<td><img title="Target Missed" width="24" height="24" src="https://img.icons8.com/external-others-agus-raharjo/64/external-incorrect-flat-website-ui-others-agus-raharjo.png" alt="external-incorrect-flat-website-ui-others-agus-raharjo"/></td>';
              }else{
                  $data["premium"] .= '<td><img title="Target Pending" width="24" height="24" src="https://img.icons8.com/office/16/hourglass--v1.png" alt="hourglass--v1"/></td>';
              }
              */
              
              $data["premium"] .= '<td><button class="btn btn-primary btn-sm buy-btn" id="buy_'.$symbol.'" data-symbol="'.$symbol.'" data-recommend="'.$recommend.'" data-action="Buy">Buy</button></td><td><button class="btn btn-danger btn-sm sell-btn" id="sell_'.$symbol.'" data-symbol="'.$symbol.'" data-recommend="'.$recommend.'" data-action="Sell">Sell</button></td></tr>';
              
              $data["premium"] .= '</tr>';
              
              // Premium Monthly 
              
              $data["monthly"] .= '<tp><td>'.$postdate.'</td><td style="color:'.$color.';"><a target="_blank" title="'.$symbol.'" class="btn" data-symbol="'.$symbol.'" data-action="analysis" style="color:'.$color.';">'.$symbol.'</a></td><td style="color:'.$color.';">'.$price.' '.$trend_sign.'</td><td style="color: '.$target_color.';">'.$target.'</td><td style="color: '.$signal_color.'">'.$signal.'</td><td>'.$recommend.'</td><td style="color: '.$op_color.';">'.$op_price.'</td><td>'.$entryprice.' '.$entryhit_sign.'</td><td>'.$trailing_stop_loss.'</td><td>'.$c_profit.'</td><td>'.$c_target_percent.'</td><td>'.$highest_price.'</td><td style="color: '.$target_color.';">'.$op_target.'</td><td>'.$fundneeded.'</td><td>'.$profit.'</td><td>'.$view.'</td>';
              
              if(strcmp($chart, '') != 0){
                  
              //$data["premium"] .= '<td><center><a target="_blank" title="'.$symbol.'" class="btn" data-symbol="'.$symbol.'"><img width="24" height="24" src="https://img.icons8.com/color/48/candle-sticks.png" alt="candle-sticks"/></a></center></td>';
              
              }else{
                $data["monthly"] .= '<td>No Data</td>';  
              }
              
              /*
              if(strcmp($hit, "True") == 0){
                 $data["monthly"] .= '<td><img title="Target Hit" width="24" height="24" src="https://img.icons8.com/color/48/checked--v1.png" alt="checked--v1"/></td>'; 
              }else if(strcmp($hit, "False") == 0){
                 $data["monthly"] .= '<td><img title="Target Missed" width="24" height="24" src="https://img.icons8.com/external-others-agus-raharjo/64/external-incorrect-flat-website-ui-others-agus-raharjo.png" alt="external-incorrect-flat-website-ui-others-agus-raharjo"/></td>';
              }else{
                  $data["monthly"] .= '<td><img title="Target Pending" width="24" height="24" src="https://img.icons8.com/office/16/hourglass--v1.png" alt="hourglass--v1"/></td>';
              }
              */
              
              //$data["monthly"] .= '<td><button class="btn btn-primary btn-sm buy-btn" id="buy_'.$symbol.'" data-symbol="'.$symbol.'" data-recommend="'.$recommend.'" data-action="Buy">Buy</button></td><td><button class="btn btn-danger btn-sm sell-btn" id="sell_'.$symbol.'" data-symbol="'.$symbol.'" data-recommend="'.$recommend.'" data-action="Sell">Sell</button></td><td>'.$broker_name.'</td></tr>';
              
              $data["monthly"] .= '</tr>';
              
              $data["manage"] .= '<tp><td style="color:'.$color.';">'.$symbol.'</td><td style="color:'.$color.';">'.$price.' '.$trend_sign.'</td><td style="color: '.$target_color.';">'.$target.'</td><td style="color: '.$signal_color.'">'.$signal.'</td><td>'.$recommend.'</td><td style="color: '.$op_color.';">'.$op_price.'</td><td>'.$entryprice.' '.$entryhit_sign.'</td><td style="color: '.$target_color.';">'.$op_target.'</td><td>'.$view.'</td>';
              
              if(strcmp($chart, '') != 0){
                  
              $data["manage"] .= '<td><center><a target="_blank" title="'.$symbol.'" href="/analysis/?symbol='.$symbol.'"><img width="24" height="24" src="https://img.icons8.com/color/48/candle-sticks.png" alt="candle-sticks"/></a></center></td>';
              
              }else{
                $data["manage"] .= '<td>No Data</td>';  
              }
              
              if(strcmp($hit, "True") == 0){
                 $data["manage"] .= '<td><img title="Target Hit" width="24" height="24" src="https://img.icons8.com/color/48/checked--v1.png" alt="checked--v1"/></td>'; 
              }else if(strcmp($hit, "False") == 0){
                 $data["manage"] .= '<td><img title="Target Missed" width="24" height="24" src="https://img.icons8.com/external-others-agus-raharjo/64/external-incorrect-flat-website-ui-others-agus-raharjo.png" alt="external-incorrect-flat-website-ui-others-agus-raharjo"/></td>';
              }else{
                  $data["manage"] .= '<td><img title="Target Pending" width="24" height="24" src="https://img.icons8.com/office/16/hourglass--v1.png" alt="hourglass--v1"/></td>';
              }
              
              $data["manage"] .= '<td><button class="btn btn-primary btn-sm edit-btn" id="edit_'.$symbol.'" data-symbol="'.$symbol.'" data-recommend="'.$recommend.'">Edit</button></td><td><button class="btn btn-danger btn-sm delete-btn" id="del_'.$symbol.'" data-symbol="'.$symbol.'" data-recommend="'.$recommend.'">Delete</button></td></tr>';
            
              
            }
            
            
            #$data = $msg;
            
            
            
    }else{
           $data["free"] .= "Analysis in Progress";
           $data["premium"] .= "Analysis in Progress";
           $data["monthly"] .= "Analysis in Progress";
           $data["manage"] .= "Analysis in Progress";
    }
    
    
    $sql18 = "select * from stock where `Hit` like '' and Broker like '%".$sbroker."%' ORDER BY ID DESC";
    $result18 = $con1->query($sql18);
    
    if($result18->num_rows > 0){
        while($row = $result18->fetch_assoc()){
            
            $symbol = $row["Symbol"];
            $price = $row["Price"];
            $target = $row["Target"];
            $signal = $row["Signal"];
            $recommend = $row["Recommend"];
            $op_price = $row["OptionPrice"];
            $op_target = $row["OptionTarget"];
            $view = $row["View"];
            $chart = $row["Chart"];
            $hit = $row["Hit"];
            
            $close = $row["Close"];
            $open = $row["Open"];
            
            $option_close = $row["OptionClose"];
            $option_open = $row["OptionOpen"];
            
            $broker = $row["Broker"];
            $broker_name = $row["BrokerName"];
            
            $postdate = $row["PostDate"];
            $entryprice = $row["EntryPrice"];
            $entryhit = $row["EntryHit"];
            
            $data["manage2"] .= '<tp><td style="color:'.$color.';">'.$symbol.'</td><td style="color:'.$color.';">'.$price.' '.$trend_sign.'</td><td style="color: '.$target_color.';">'.$target.'</td><td style="color: '.$signal_color.'">'.$signal.'</td><td>'.$recommend.'</td><td style="color: '.$op_color.';">'.$op_price.'</td><td>'.$entryprice.' '.$entryhit_sign.'</td><td style="color: '.$target_color.';">'.$op_target.'</td><td>'.$view.'</td>';
              
              if(strcmp($chart, '') != 0){
                  
              $data["manage2"] .= '<td><center><a target="_blank" title="'.$symbol.'" href="/analysis/?symbol='.$symbol.'"><img width="24" height="24" src="https://img.icons8.com/color/48/candle-sticks.png" alt="candle-sticks"/></a></center></td>';
              
              }else{
                $data["manage2"] .= '<td>No Data</td>';  
              }
              
              if(strcmp($hit, "True") == 0){
                 $data["manage2"] .= '<td><img title="Target Hit" width="24" height="24" src="https://img.icons8.com/color/48/checked--v1.png" alt="checked--v1"/></td>'; 
              }else if(strcmp($hit, "False") == 0){
                 $data["manage2"] .= '<td><img title="Target Missed" width="24" height="24" src="https://img.icons8.com/external-others-agus-raharjo/64/external-incorrect-flat-website-ui-others-agus-raharjo.png" alt="external-incorrect-flat-website-ui-others-agus-raharjo"/></td>';
              }else{
                  $data["manage2"] .= '<td><img title="Target Pending" width="24" height="24" src="https://img.icons8.com/office/16/hourglass--v1.png" alt="hourglass--v1"/></td>';
              }
              
              $data["manage2"] .= '<td><button class="btn btn-primary btn-sm edit-btn" id="edit_'.$symbol.'" data-symbol="'.$symbol.'" data-recommend="'.$recommend.'">Edit</button></td><td><button class="btn btn-danger btn-sm delete-btn" id="del_'.$symbol.'" data-symbol="'.$symbol.'" data-recommend="'.$recommend.'">Delete</button></td></tr>';
            
        }
    }else{
        $data["manage2"] = "No Recommendations";
    }
    
    
    // Target Hits 
    
    $sql1 = "select * from stock where `Hit` like 'True' ORDER BY ID DESC";
    
    $show_count1 = 11;
    $show_counter1 = 0;
    
    $result1 = $con1->query($sql1);
    
    $hit_count = $result1->num_rows;
    
    if($result1->num_rows > 0){
        while($row = $result1->fetch_assoc()){
            $symbol = $row["Symbol"];
            $price = $row["Price"];
            $target = $row["Target"];
            $signal = $row["Signal"];
            $recommend = $row["Recommend"];
            $op_price = $row["OptionPrice"];
            $op_target = $row["OptionTarget"];
            $view = $row["View"];
            $chart = $row["Chart"];
            $hit = $row["Hit"];
            
            $close = $row["Close"];
            $open = $row["Open"];
            
            $option_close = $row["OptionClose"];
            $option_open = $row["OptionOpen"];
            
            $broker = $row["Broker"];
            $broker_name = $row["BrokerName"];
            
            $postdate = $row["PostDate"];
            $entryprice = $row["EntryPrice"];
            $lotsize = $row["LotSize"];
            
            $fundneeded = "&#x20B9 " . floatval($entryprice) * floatval($lotsize);
            
            if(strcmp($entryprice, "") !== 0){
                $profit = (floatval($op_target) - floatval($entryprice)) * floatval($lotsize);
                $profit = "&#x20B9 ".$profit;
            }else{
                $profit = "NA";
            }
            
            
            if($price < $open){
                $color = "#df514c";
            }else{
                $color = "#4caf50";
            }
            
            if($op_price < $option_open){
                $op_color = "#df514c";
            }else{
                $op_color = "#4caf50";
            }
            
            if(strcmp($signal, "Buy") == 0){
                $signal_color = "#4caf50";
            }else{
                $signal_color = "#df514c";
            }
            
            $target_color = "#00b0ff";
                
                $data["hitcount"] = $hit_count; 
                
                //$data["free1"] .= '<tp><td style="color:'.$color.';">'.$symbol.'</td><td style="color:'.$color.';">'.$price.'</td><td style="color: '.$target_color.';">'.$target.'</td><td style="color: '.$signal_color.'">'.$signal.'</td><td>'.$recommend.'</td><td style="color: '.$op_color.';">'.$op_price.'</td><td style="color: '.$target_color.';">'.$op_target.'</td><td>'.$view.'</td>';
                $data["free1"] .= '<tp><td>'.$postdate.'</td><td style="color:'.$color.';">'.$symbol.'</td><td style="color: '.$target_color.';">'.$target.'</td><td style="color: '.$signal_color.'">'.$signal.'</td><td>'.$recommend.'</td><td style="color: '.$target_color.';">'.$op_target.'</td>';
              
              if(strcmp($chart, '') != 0){
                  
              //$data["free1"] .= '<td><a class="btn btn-primary btn-sm" href="https://rzp.io/l/3XQXcZv">Upgrade</a></td>';
              
              }else{
                $data["free1"] .= '<td>No Data</td>';  
              }
              
              if(strcmp($hit, "True") == 0){
                 $data["free1"] .= '<td><img title="Target Hit" width="24" height="24" src="https://img.icons8.com/color/48/checked--v1.png" alt="checked--v1"/></td>'; 
              }else if(strcmp($hit, "False") == 0){
                 $data["free1"] .= '<td><img title="Target Missed" width="24" height="24" src="https://img.icons8.com/external-others-agus-raharjo/64/external-incorrect-flat-website-ui-others-agus-raharjo.png" alt="external-incorrect-flat-website-ui-others-agus-raharjo"/></td>';
              }else{
                  $data["free1"] .= '<td><img title="Target Pending" width="24" height="24" src="https://img.icons8.com/office/16/hourglass--v1.png" alt="hourglass--v1"/></td>';
              }
              
              $data["free1"] .= '<td>'.$fundneeded.'</td><td>'.$profit.'</td></tr>';
            
            // Premium Admin
            
            $data["premium1"] .= '<tp><td style="color:'.$color.';"><a target="_blank" title="'.$symbol.'" class="btn" data-symbol="'.$symbol.'" data-action="analysis" style="color:'.$color.';">'.$symbol.'</a></td><td style="color:'.$color.';">'.$price.'</td><td style="color: '.$target_color.';">'.$target.'</td><td style="color: '.$signal_color.'">'.$signal.'</td><td>'.$recommend.'</td><td style="color: '.$op_color.';">'.$op_price.'</td><td style="color: '.$target_color.';">'.$op_target.'</td><td>'.$view.'</td>';
            //$data["premium1"] .= '<tp><td style="color:'.$color.';"><a target="_blank" title="'.$symbol.'" class="btn" data-symbol="'.$symbol.'" data-action="analysis" style="color:'.$color.';">'.$symbol.'</a></td><td style="color: '.$target_color.';">'.$target.'</td><td style="color: '.$signal_color.'">'.$signal.'</td><td>'.$recommend.'</td><td style="color: '.$target_color.';">'.$op_target.'</td>';
              
              if(strcmp($chart, '') != 0){
                  
              //$data["premium"] .= '<td><center><a target="_blank" title="'.$symbol.'" class="btn" data-symbol="'.$symbol.'"><img width="24" height="24" src="https://img.icons8.com/color/48/candle-sticks.png" alt="candle-sticks"/></a></center></td>';
              
              }else{
                $data["premium1"] .= '<td>No Data</td>';  
              }
              
              if(strcmp($hit, "True") == 0){
                 $data["premium1"] .= '<td><img title="Target Hit" width="24" height="24" src="https://img.icons8.com/color/48/checked--v1.png" alt="checked--v1"/></td>'; 
              }else if(strcmp($hit, "False") == 0){
                 $data["premium1"] .= '<td><img title="Target Missed" width="24" height="24" src="https://img.icons8.com/external-others-agus-raharjo/64/external-incorrect-flat-website-ui-others-agus-raharjo.png" alt="external-incorrect-flat-website-ui-others-agus-raharjo"/></td>';
              }else{
                  $data["premium1"] .= '<td><img title="Target Pending" width="24" height="24" src="https://img.icons8.com/office/16/hourglass--v1.png" alt="hourglass--v1"/></td>';
              }
              
              $data["premium1"] .= '<td><button class="btn btn-primary btn-sm buy-btn" id="buy_'.$symbol.'" data-symbol="'.$symbol.'" data-recommend="'.$recommend.'" data-action="Buy">Buy</button></td><td><button class="btn btn-danger btn-sm sell-btn" id="sell_'.$symbol.'" data-symbol="'.$symbol.'" data-recommend="'.$recommend.'" data-action="Sell">Sell</button></td><td>'.$broker_name.'</td></tr>';
              
              $data["premium1"] .= '</tr>';
              
              // Premium Users
              
              $data["monthly1"] .= '<tp><td>'.$postdate.'</td><td style="color:'.$color.';"><a target="_blank" title="'.$symbol.'" class="btn" data-symbol="'.$symbol.'" data-action="analysis" style="color:'.$color.';">'.$symbol.'</a></td><td>'.$recommend.'</td><td style="color: '.$op_color.';">'.$entryprice.'</td><td style="color: '.$target_color.';">'.$op_target.'</td><td>'.$view.'</td><td>'.$fundneeded.'</td><td>'.$profit.'</td>';
            //$data["premium1"] .= '<tp><td style="color:'.$color.';"><a target="_blank" title="'.$symbol.'" class="btn" data-symbol="'.$symbol.'" data-action="analysis" style="color:'.$color.';">'.$symbol.'</a></td><td style="color: '.$target_color.';">'.$target.'</td><td style="color: '.$signal_color.'">'.$signal.'</td><td>'.$recommend.'</td><td style="color: '.$target_color.';">'.$op_target.'</td>';
              
              if(strcmp($chart, '') != 0){
                  
              //$data["premium"] .= '<td><center><a target="_blank" title="'.$symbol.'" class="btn" data-symbol="'.$symbol.'"><img width="24" height="24" src="https://img.icons8.com/color/48/candle-sticks.png" alt="candle-sticks"/></a></center></td>';
              
              }else{
                $data["monthly1"] .= '<td>No Data</td>';  
              }
              
              /*
              if(strcmp($hit, "True") == 0){
                 $data["monthly1"] .= '<td><img title="Target Hit" width="24" height="24" src="https://img.icons8.com/color/48/checked--v1.png" alt="checked--v1"/></td>'; 
              }else if(strcmp($hit, "False") == 0){
                 $data["monthly1"] .= '<td><img title="Target Missed" width="24" height="24" src="https://img.icons8.com/external-others-agus-raharjo/64/external-incorrect-flat-website-ui-others-agus-raharjo.png" alt="external-incorrect-flat-website-ui-others-agus-raharjo"/></td>';
              }else{
                  $data["monthly1"] .= '<td><img title="Target Pending" width="24" height="24" src="https://img.icons8.com/office/16/hourglass--v1.png" alt="hourglass--v1"/></td>';
              }
              */
              
              //$data["monthly1"] .= '<td><button class="btn btn-primary btn-sm buy-btn" id="buy_'.$symbol.'" data-symbol="'.$symbol.'" data-recommend="'.$recommend.'" data-action="Buy">Buy</button></td><td><button class="btn btn-danger btn-sm sell-btn" id="sell_'.$symbol.'" data-symbol="'.$symbol.'" data-recommend="'.$recommend.'" data-action="Sell">Sell</button></td><td>'.$broker_name.'</td></tr>';
              
              $data["monthly1"] .= '</tr>';
              
              $data["manage1"] .= '<tp><td style="color:'.$color.';">'.$symbol.'</td><td style="color:'.$color.';">'.$price.'</td><td style="color: '.$target_color.';">'.$target.'</td><td style="color: '.$signal_color.'">'.$signal.'</td><td>'.$recommend.'</td><td style="color: '.$op_color.';">'.$op_price.'</td><td style="color: '.$target_color.';">'.$op_target.'</td><td>'.$view.'</td>';
              
              if(strcmp($chart, '') != 0){
                  
              $data["manage1"] .= '<td><center><a target="_blank" title="'.$symbol.'" href="/analysis/?symbol='.$symbol.'"><img width="24" height="24" src="https://img.icons8.com/color/48/candle-sticks.png" alt="candle-sticks"/></a></center></td>';
              
              }else{
                $data["manage1"] .= '<td>No Data</td>';  
              }
              
              if(strcmp($hit, "True") == 0){
                 $data["manage1"] .= '<td><img title="Target Hit" width="24" height="24" src="https://img.icons8.com/color/48/checked--v1.png" alt="checked--v1"/></td>'; 
              }else if(strcmp($hit, "False") == 0){
                 $data["manage1"] .= '<td><img title="Target Missed" width="24" height="24" src="https://img.icons8.com/external-others-agus-raharjo/64/external-incorrect-flat-website-ui-others-agus-raharjo.png" alt="external-incorrect-flat-website-ui-others-agus-raharjo"/></td>';
              }else{
                  $data["manage1"] .= '<td><img title="Target Pending" width="24" height="24" src="https://img.icons8.com/office/16/hourglass--v1.png" alt="hourglass--v1"/></td>';
              }
              
              $data["manage1"] .= '<td><button class="btn btn-primary btn-sm edit-btn" id="edit_'.$symbol.'" data-symbol="'.$symbol.'" data-recommend="'.$recommend.'">Edit</button></td><td><button class="btn btn-danger btn-sm delete-btn" id="del_'.$symbol.'" data-symbol="'.$symbol.'" data-recommend="'.$recommend.'">Delete</button></td></tr>';
            
            }
            
            
            #$data = $msg;
            
            
            
    }else{
           $data["free1"] .= "Analysis in Progress";
           $data["premium1"] .= "Analysis in Progress";
           $data["manage1"] .= "Analysis in Progress";
    }
    
    // Advisor Target Hits
    
    $sql19 = "select * from stock where `Hit` like 'True' and Broker like '%".$sbroker."%' ORDER BY ID DESC";
    $result19 = $con1->query($sql19);
    
    if($result19->num_rows > 0){
        while($row = $result19->fetch_assoc()){
            
            $symbol = $row["Symbol"];
            $price = $row["Price"];
            $target = $row["Target"];
            $signal = $row["Signal"];
            $recommend = $row["Recommend"];
            $op_price = $row["OptionPrice"];
            $op_target = $row["OptionTarget"];
            $view = $row["View"];
            $chart = $row["Chart"];
            $hit = $row["Hit"];
            
            $close = $row["Close"];
            $open = $row["Open"];
            
            $option_close = $row["OptionClose"];
            $option_open = $row["OptionOpen"];
            
            $broker = $row["Broker"];
            $broker_name = $row["BrokerName"];
            
            $postdate = $row["PostDate"];
            $entryprice = $row["EntryPrice"];
            $entryhit = $row["EntryHit"];
            
            $data["manage3"] .= '<tp><td style="color:'.$color.';">'.$symbol.'</td><td style="color:'.$color.';">'.$price.' '.$trend_sign.'</td><td style="color: '.$target_color.';">'.$target.'</td><td style="color: '.$signal_color.'">'.$signal.'</td><td>'.$recommend.'</td><td style="color: '.$op_color.';">'.$op_price.'</td><td style="color: '.$target_color.';">'.$op_target.' '.$entryhit_sign.'</td><td>'.$view.'</td>';
              
              if(strcmp($chart, '') != 0){
                  
              $data["manage3"] .= '<td><center><a target="_blank" title="'.$symbol.'" href="/analysis/?symbol='.$symbol.'"><img width="24" height="24" src="https://img.icons8.com/color/48/candle-sticks.png" alt="candle-sticks"/></a></center></td>';
              
              }else{
                $data["manage3"] .= '<td>No Data</td>';  
              }
              
              if(strcmp($hit, "True") == 0){
                 $data["manage3"] .= '<td><img title="Target Hit" width="24" height="24" src="https://img.icons8.com/color/48/checked--v1.png" alt="checked--v1"/></td>'; 
              }else if(strcmp($hit, "False") == 0){
                 $data["manage3"] .= '<td><img title="Target Missed" width="24" height="24" src="https://img.icons8.com/external-others-agus-raharjo/64/external-incorrect-flat-website-ui-others-agus-raharjo.png" alt="external-incorrect-flat-website-ui-others-agus-raharjo"/></td>';
              }else{
                  $data["manage3"] .= '<td><img title="Target Pending" width="24" height="24" src="https://img.icons8.com/office/16/hourglass--v1.png" alt="hourglass--v1"/></td>';
              }
              
              $data["manage3"] .= '<td><button class="btn btn-primary btn-sm edit-btn" id="edit_'.$symbol.'" data-symbol="'.$symbol.'" data-recommend="'.$recommend.'">Edit</button></td><td><button class="btn btn-danger btn-sm delete-btn" id="del_'.$symbol.'" data-symbol="'.$symbol.'" data-recommend="'.$recommend.'">Delete</button></td></tr>';
            
        }
    }else{
        $data["manage3"] = "No Target Hits";
    }
    
    // Handle Positions
    
    if(strcmp($username, "") != 0){
        $username = $_SESSION["username"];
        
        $sql2 = "SELECT * from orders where `username` like '".$username."'";
        $result2 = $con1->query($sql2);
        $trades = array();
        $symbol_names = array();
        
        if($result2->num_rows > 0){
           
           while($row = $result2->fetch_assoc()){
               
               // Buy Order, Sell Order, Buy Option Order, Sell Option Order
               if(strcmp($row["Symbol"], "") != 0){
                   $symbol = $row["Symbol"];
                   $trades[$symbol]["name"] = $symbol;
                   
                   // Check if Buy Order or Sell Order
                   if(strcmp($row["BuyStatus"], "True") == 0){
                       $buy_qty = $row["BuyQty"];
                       $buy_price = $row["BuyPrice"];
                       
                       $trades[$symbol]["Qty"] = $trades[$symbol]["Qty"] + $buy_qty;
                       
                       if($trades[$symbol]["BuyPrice"] > 0){
                           $trades[$symbol]["BuyPrice"] = round(($trades[$symbol]["BuyPrice"] + $buy_price)/2, 2);
                       }else{
                           $trades[$symbol]["BuyPrice"] = round(($trades[$symbol]["BuyPrice"] + $buy_price), 2);
                       }
                       
                       $trades[$symbol]["BuyBalance"] = $trades[$symbol]["BuyBalance"] + ($buy_qty * $buy_price);
                       
                   }else if(strcmp($row["SellStatus"], "True") == 0){
                       $sell_qty = $row["SellQty"];
                       $sell_price = $row["SellPrice"];
                       
                       $trades[$symbol]["Qty"] = $trades[$symbol]["Qty"] - $sell_qty;
                       
                       if($trades[$symbol]["SellPrice"] > 0){
                           $trades[$symbol]["SellPrice"] = round(($trades[$symbol]["SellPrice"] + $sell_price)/2, 2);
                       }else{
                           $trades[$symbol]["SellPrice"] = round(($trades[$symbol]["SellPrice"] + $sell_price), 2);
                       }
                       
                       $trades[$symbol]["SellBalance"] = $trades[$symbol]["SellBalance"] + ($sell_qty * $sell_price);
                       
                   }
                   
                   // Calculate Last Trade Price
                   $sql3 = "SELECT * from stock where `Symbol` like '".$symbol."'";
                   $result3 = $con1->query($sql3);
                   if($result3->num_rows > 0){
                       while($row = $result3->fetch_assoc()){
                           $ltp = $row["Price"];
                           $trades[$symbol]["ltp"] = $ltp;
                       }
                   }
                   
                   
               }else if(strcmp($row["Recommend"], "") != 0){
                   $symbol = $row["Recommend"];
                   $trades[$symbol]["name"] = $symbol;
                   
                   // Check if Buy Order or Sell Order
                   if(strcmp($row["BuyOpStatus"], "True") == 0){
                       $buy_qty = $row["BuyOpQty"];
                       $buy_price = $row["BuyOpPrice"];
                       
                       $trades[$symbol]["Qty"] = $trades[$symbol]["Qty"] + $buy_qty;
                       
                       if($trades[$symbol]["BuyPrice"] > 0){
                           $trades[$symbol]["BuyPrice"] = round(($trades[$symbol]["BuyPrice"] + $buy_price)/2, 2);
                       }else{
                           $trades[$symbol]["BuyPrice"] = round(($trades[$symbol]["BuyPrice"] + $buy_price), 2);
                       }
                       
                       $trades[$symbol]["BuyBalance"] = $trades[$symbol]["BuyBalance"] + ($buy_qty * $buy_price);
                       
                   }else if(strcmp($row["SellOpStatus"], "True") == 0){
                       $sell_qty = $row["SellOpQty"];
                       $sell_price = $row["SellOpPrice"];
                       
                       $trades[$symbol]["Qty"] = $trades[$symbol]["Qty"] - $sell_qty;
                       if($trades[$symbol]["SellPrice"] > 0){
                           $trades[$symbol]["SellPrice"] = round(($trades[$symbol]["SellPrice"] + $sell_price)/2, 2);
                       }else{
                           $trades[$symbol]["SellPrice"] = round(($trades[$symbol]["SellPrice"] + $sell_price), 2);
                       }
                       
                       $trades[$symbol]["SellBalance"] = $trades[$symbol]["SellBalance"] + ($sell_qty * $sell_price);
                       
                   }
                   
                   // Calculate Last Trade Price
                   $sql3 = "SELECT * from stock where `Recommend` like '".$symbol."'";
                   $result3 = $con1->query($sql3);
                   if($result3->num_rows > 0){
                       while($row = $result3->fetch_assoc()){
                           $ltp = $row["OptionPrice"];
                           $trades[$symbol]["ltp"] = $ltp;
                       }
                   }
                   
               }
               
           }
           
           $total_pl = 0;
           $trade_amount = 0;
           
           foreach($trades as $trade){
               
               if($trade["Qty"] == 0){
                   $trade["PL"] = $trade["SellBalance"] - $trade["BuyBalance"];
               }else if($trade["Qty"] > 0){
                   $trade["PL"] = ($trade["ltp"] * $trade["Qty"]) - ($trade["BuyPrice"] * $trade["Qty"]);
                   $trade_amount= $trade_amount + ($trade["BuyPrice"] * $trade["Qty"]);
               }else if($trade["Qty"] < 0){
                   $trade["PL"] = ($trade["ltp"] * $trade["Qty"]) - ($trade["SellPrice"] * $trade["Qty"]);
                   $trade_amount = $trade_amount + ($trade["SellPrice"] * $trade["Qty"]);
               }
               
               if($trade["PL"] > 0){
                      $trade["color"] = "#4caf50"; 
                    }else{
                      $trade["color"] = "#df514c ";
                    }
               
               $total_pl = $total_pl + $trade["PL"]; 
               $data["orders"] .= '<tr><td style="color: '.$trade["color"].'">'.$trade["name"].'</td><td style="color: '.$trade["color"].'">'.$trade["ltp"].'</td><td style="color: '.$trade["color"].'">'.$trade["BuyPrice"].'</td><td style="color: '.$trade["color"].'">'.$trade["Qty"].'</td><td style="color: '.$trade["color"].'">'.$trade["PL"].'</td></tr>';
           }
           
           if($total_pl > 0){
                      $pl_color = "#4caf50"; 
                    }else{
                      $pl_color = "#df514c ";
                    }
           
           $data["orders"] .= '<tr><td>Total (INR)</td><td></td><td></td><td></td><td style="color: '.$pl_color.';">'.$total_pl.'</td></tr>';
           
           $sql4 = "SELECT * from positions where Username like '".$username."'";
           $result4 = $con1->query($sql4);
           if($result4->num_rows > 0){
               // Update 
               $sql5 = "UPDATE positions SET `PL`='".$total_pl."', `Invested` = '".$trade_amount."' where Username like '".$username."'";
               $result5 = $con1->query($sql5);
               if($result5){
                   // PL Updated
               }else{
                   // PL Failed
               }
           }else{
               // Insert
               $sql6 = "INSERT into positions (Username, PL, Invested) VALUES('".$username."', '".$total_pl."', '".$trade_amount."')";
               $result6 = $con1->query($sql6);
               if($result6->num_rows > 0){
                   // Insert Success
               }else{
                   // Insert Failed
               }
           }
            
        }else{
            $data["orders"] .= '<tp><td>No Open Positions</td>';
        }
    }else{
        
    } 
    
    // Handle OI Change Stocks
    $sql7 = "SELECT * from plots Group By symbol ORDER BY ID DESC";
    $result7 = $con1->query($sql7);
    if($result7->num_rows > 0){
        while($row = $result7->fetch_assoc()){
            $symbol = $row["symbol"];
            $image_data = $row["plot"];
            $base64_image = base64_encode($image_data);
            $date = $row["date"];
            $strike_price = $row["strike_price"];
            $sentiment = $row["shortsentiment"];
            $optiondata = $row["optiondata"];
            
            $sql10 = "SELECT * from indicators where Symbol like '".$symbol."'";
            $result10 = $con1->query($sql10);
            if($result10->num_rows > 0){
                while($row = $result10->fetch_assoc()){
                    $mom_val = $row["Mom"];
                    $rsi = $row["RSI"];
                    $adxdi = $row["ADXDI"];
                    $adx_di = $row["ADX_DI"];
                    
                    $sql13 = "SELECT Mom from oscillators where Symbol='".$symbol."'";
                    $result13 = $con1->query($sql13);
                    
                    if($result13->num_rows > 0){
                        while($row13 = $result13->fetch_assoc()){
                            $mom_type = $row13["Mom"];
                        }
                    }
                    
                    if(strcmp($mom_type, "SELL") == 0){
                        $mom = '<a class="text-center" style="color: #dc3545;">Traders Booking Profit  ('.$mom_val.')</a>';
                    }else if(strcmp($mom_type, "STRONG_SELL") == 0){
                        $mom = '<a class="text-center" style="color: #dc3545;">Traders Selling ('.$mom_val.')</a>';
                    }else if((strcmp($mom_type, "BUY") == 0) || (strcmp($mom_type, "STRONG_BUY") == 0)){
                        $mom = '<a class="text-center" style="color: #28a745;">Traders Buying ('.$mom_val.')</a>';
                    }else{
                        $mom = '<a class="text-center">Momentum is Neutral</a>';
                    }
                    
                    if($rsi > 80){
                        $rsi = '<a class="text-center" style="color: #dc3545;">Stock Overbought</a>';
                        $rec_rsi = '<a class="text-center" style="color: #dc3545;">Analyse and Exit</a>';
                    }else if($rsi < 20){
                        $rsi = '<a class="text-center" style="color: #28a745;">Stock Oversold</a>';
                        $rec_rsi = '<a class="text-center" style="color: #28a745;">Analyse and Enter</a>';
                    }else{
                        $rsi = '<a class="text-center">RSI is '.$rsi.'</a>';
                        $rec_rsi = '<a class="text-center">Wait and Watch</a>';
                    }
                    
                    if($adxdi > $adx_di){
                        $adx = '<a class="text-center" style="color: #28a745;">Trend is Bullish</a>';
                        $trend = "Bullish";
                    }else{
                        $adx = '<a class="text-center" style="color: #dc3545;">Trend is Bearish</a>';
                        $trend = "Bearish";
                    }
                    
                    $ema50 = $row["EMA50"];
                    $ema200 = $row["EMA200"];
                    $supports = $row["PivotMClassicS1"] . ", " . $row["PivotMClassicS2"] . ", ". $row["PivotMClassicS3"];
                    $resistence = $row["PivotMClassicR1"] . ", ". $row["PivotMClassicR2"] . ", " . $row["PivotMClassicR3"];
                    
                    
                    // Calculate approx target
                    if($strike_price >= $row["PivotMClassicR3"]){
                        $target = $row["PivotMClassicR3"];
                    }else if($strike_price >= $row["PivotMClassicR2"]){
                        $target = $row["PivotMClassicR2"];
                    }else if($strike_price >= $row["PivotMClassicR1"]){
                        $target = $row["PivotMClassicR1"];
                    }else{
                        $target = $strike_price;
                    }
                    
                    $slider_data = ""; // Empty the slider data for new symbol;
                    
                    // Extract News for Symbol
                    $sql11 = "SELECT news from tweets where symbol like '%".$symbol."%' and verified = 'True' ORDER BY tweet_id DESC limit 1";
                    $con1->set_charset('utf8mb4');
                    $result11 = $con1->query($sql11);
                    $count = $result11->num_rows;
                    if($result11->num_rows > 0){
                        while($row = $result11->fetch_assoc()){
                            $news = $row["news"];
                        }
                    }else{
                        // Do Nothing News Slider
                        $news = "Searching Impacting News ...";
                    }
                    
                    
                    $table_data = '<style>
                                    table {
                                        width: 100%;
                                        border-collapse: collapse;
                                        text-align: center;
                                    }
                                
                                    td {
                                        border: 1px solid #000; /* Add borders to all table cells */
                                        padding: 8px; /* Add padding for spacing within cells */
                                    }
                                </style>
                    
                    <table class="table table-bordered table-hover">
                        <!--<th colspan="4">
                            <b>Market Sentiment of '.$symbol.'</b>
                        </th>
                        <tr>
                            <td colspan="4">
                            <div class="jumbotron">
                            <p>'.$sentiment.'</p>
                            </div></td>
                        </tr>-->
                        <!--<tr>
                            <td colspan="2">'.$rsi.'</td><td colspan="2">'.$adx.'</td>
                        </tr>
                        <tr>
                            <td colspan="2">50 day moving average is '.$ema50.'</td><td colspan="2">200 day moving average is '.$ema200.'</td>
                        </tr>
                        <tr>
                            <td colspan="2">Resistance Levels are '.$resistence.'</td><td colspan="2">Support Levels are '.$supports.'</td>
                        </tr>
                        <tr>
                            <td colspan="4">
                            <div class="jumbotron">
                            <h3>Analysis & Strategy</h3>
                            <p>'.$sentiment.'</p>
                            </div></td>
                        </tr>
                        '.$optiondata.'
                        <tr>
                        <td colspan="4">'.$news.'</td>
                        </tr>-->
                        <tr>';
                        
                        if(strcmp($status, 'true') == 0){
                           $table_data .= '<td colspan="4"><a class="btn btn-primary btn-sm buy-btn" target="_blank" href="/analyse.php?symbol='.$symbol.'">Analyze Data</a></td>'; 
                        }else{
                           $table_data .= '<td colspan="4"><a class="btn btn-primary btn-sm buy-btn" target="_blank" href="https://rzp.io/l/3XQXcZv">Unlock Analysis</a></td>';    
                        }
                        $table_data .= '</tr>
                        
                    </table> <style>
                        /* Add some basic styling for the Jumbotron */
                        .jumbotron {
                            background-color: #f8f9fa; /* Background color */
                            padding: 20px; /* Padding around the content */
                            text-align: center; /* Center-align the content */
                        }
                
                        /* Optional: Style the text inside the Jumbotron */
                        .jumbotron h1 {
                            font-size: 36px;
                        }
                
                        .jumbotron p {
                            font-size: 18px;
                        }
                    </style>';
                    
                    
                    
                }
            }else{
                // Do Nothing
                $table_data = '<style>
                                    table {
                                        width: 100%;
                                        border-collapse: collapse;
                                        text-align: center;
                                    }
                                
                                    td {
                                        border: 1px solid #000; /* Add borders to all table cells */
                                        padding: 8px; /* Add padding for spacing within cells */
                                    }
                                </style>
                    
                    <table style="width:100%; border: 1px solid #000; text-align: center;">
                        <tr>
                            <td><b>'.$symbol.'</b></td>
                        </tr>
                        <tr>
                            <td><i>Calculations in progress</i></td>
                        </tr>
                        <tr>
                        <td colspan="2">'.$news.'</td>
                        </tr>
                    </table>';
                
            }
            
            
            $data["plots"] .= '<div class="col-md-6 mb-4">
                                <h4 class="text-center">'.$symbol.'</h4>
                                <img src="data:image/png;base64,' . $base64_image . '" alt="'.$symbol.'" class="img-fluid">
                                <p class="text-center">Last Updated: '.$date.'</p>';
            
            $data["plots"] .= $table_data;    
            
            $data["plots"] .=  '</div>';
            
           
                                
            
        }
    }else{
            $data["plots"] = '<div class="col-md-6 mb-4">
                                <h4 class="text-center">Analysis in Progress ...</h4>
                                </div>';
    }
    
    // Handle News
    
    //$sql9 = "SELECT * FROM `tweets` WHERE 1 ORDER BY `created_at` DESC limit 10";
    $sql9 = "SELECT * FROM `tweets` WHERE 1 ORDER BY `url` DESC limit 10";
    $result9 = $con1->query($sql9);
    
    $news_data = "";
    
    if($result9->num_rows > 0){
        while($row = $result9->fetch_assoc()){
            $symbol = $row["symbol"];
            $news = $row["news"];
            $timestamp = $row["created_at"];
            $url = $row["url"];
            
            $fromTimezone = new DateTimeZone('UTC'); // Assuming the input timestamp is in UTC
            $toTimezone = new DateTimeZone('Asia/Kolkata'); // IST (Indian Standard Time) Timezone
            
            // Create a DateTime object from the input timestamp and set the input timezone
            $datetime = new DateTime($timestamp, $fromTimezone);
            
            // Convert the DateTime to the IST timezone
            $datetime->setTimezone($toTimezone);
            
            // Format the DateTime object as per your desired format
            $formattedDate = $datetime->format('j M Y \a\t g:i A');
            
            //echo $formattedDate; // Output: 10 Sep 2023 at 4:08 AM
            
            $news_data .= "<tr><td>".$formattedDate."</td><td>".$symbol."</td><td>".$news.". <a target='_blank' href='".$url."'>Read More</a></td></tr>";
        }
        
        $data["tweets"] = $news_data;
        
        
    }else{
            $data["tweets"] = "Searching News";
    }
    
    
    // Handle Analyse Stocks Update Real Time
    // Handle OI Change Stocks
    
    $sql16 = "SELECT AnalyseStock from login where Username like '".$username."'";
    $result16 = $con1->query($sql16);
    $row16 = $result16->fetch_assoc();
    $analyse_symbol = $row16["AnalyseStock"];
    
    $sql12 = "SELECT * from plots where symbol like '".$analyse_symbol."'";
    $result12 = $con1->query($sql12);
    if($result12->num_rows > 0){
        while($row = $result12->fetch_assoc()){
            $symbol = $row["symbol"];
            $image_data = $row["plot"];
            $base64_image = base64_encode($image_data);
            $date = $row["date"];
            $strike_price = $row["strike_price"];
            $sentiment = $row["sentiment"];
            $optiondata = $row["optiondata"];
            $detailtable = $row["detailtable"];
            $detailsentiment = $row["detailsentiment"];
            $strikesentiment = $row["strikesentiment"];
            
            $sql13 = "SELECT * from indicators where Symbol like '".$symbol."'";
            $result13 = $con1->query($sql13);
            if($result13->num_rows > 0){
                while($row = $result13->fetch_assoc()){
                    $mom_val = $row["Mom"];
                    $rsi = $row["RSI"];
                    $adxdi = $row["ADXDI"];
                    $adx_di = $row["ADX_DI"];
                    
                    $sql14 = "SELECT Mom from oscillators where Symbol='".$symbol."'";
                    $result14 = $con1->query($sql14);
                    
                    if($result14->num_rows > 0){
                        while($row14 = $result14->fetch_assoc()){
                            $mom_type = $row14["Mom"];
                        }
                    }
                    
                    if(strcmp($mom_type, "SELL") == 0){
                        $mom = '<a class="text-center" style="color: #dc3545;">Traders Booking Profit  ('.$mom_val.')</a>';
                    }else if(strcmp($mom_type, "STRONG_SELL") == 0){
                        $mom = '<a class="text-center" style="color: #dc3545;">Traders Selling ('.$mom_val.')</a>';
                    }else if((strcmp($mom_type, "BUY") == 0) || (strcmp($mom_type, "STRONG_BUY") == 0)){
                        $mom = '<a class="text-center" style="color: #28a745;">Traders Buying ('.$mom_val.')</a>';
                    }else{
                        $mom = '<a class="text-center">Momentum is Neutral</a>';
                    }
                    
                    if($rsi > 80){
                        $rsi = '<a class="text-center" style="color: #dc3545;">Stock Overbought</a>';
                        $rec_rsi = '<a class="text-center" style="color: #dc3545;">Analyse and Exit</a>';
                    }else if($rsi < 20){
                        $rsi = '<a class="text-center" style="color: #28a745;">Stock Oversold</a>';
                        $rec_rsi = '<a class="text-center" style="color: #28a745;">Analyse and Enter</a>';
                    }else{
                        $rsi = '<a class="text-center">RSI is '.$rsi.'</a>';
                        $rec_rsi = '<a class="text-center">Wait and Watch</a>';
                    }
                    
                    if($adxdi > $adx_di){
                        $adx = '<a class="text-center" style="color: #28a745;">Trend is Bullish</a>';
                        $trend = "Bullish";
                    }else{
                        $adx = '<a class="text-center" style="color: #dc3545;">Trend is Bearish</a>';
                        $trend = "Bearish";
                    }
                    
                    $ema50 = $row["EMA50"];
                    $ema200 = $row["EMA200"];
                    $supports = $row["PivotMClassicS1"] . ", " . $row["PivotMClassicS2"] . ", ". $row["PivotMClassicS3"];
                    $resistence = $row["PivotMClassicR1"] . ", ". $row["PivotMClassicR2"] . ", " . $row["PivotMClassicR3"];
                    
                    
                    // Calculate approx target
                    if($strike_price >= $row["PivotMClassicR3"]){
                        $target = $row["PivotMClassicR3"];
                    }else if($strike_price >= $row["PivotMClassicR2"]){
                        $target = $row["PivotMClassicR2"];
                    }else if($strike_price >= $row["PivotMClassicR1"]){
                        $target = $row["PivotMClassicR1"];
                    }else{
                        $target = $strike_price;
                    }
                    
                    $slider_data = ""; // Empty the slider data for new symbol;
                    
                    // Extract News for Symbol
                    $sql15 = "SELECT news from tweets where symbol like '%".$symbol."%' and verified = 'True' ORDER BY tweet_id DESC limit 1";
                    $con1->set_charset('utf8mb4');
                    $result15 = $con1->query($sql15);
                    $count = $result15->num_rows;
                    if($result15->num_rows > 0){
                        while($row = $result15->fetch_assoc()){
                            $news = $row["news"];
                        }
                    }else{
                        // Do Nothing News Slider
                        $news = "Searching Impacting News ...";
                    }
                    
                    
                    $table_data = '
                    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                      <!-- Add DataTables CSS -->
                        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
                      <!-- Add your custom CSS link here -->
                      <link rel="stylesheet" href="styles.css">
                    <style>
                                    table {
                                        width: 100%;
                                        border-collapse: collapse;
                                        text-align: center;
                                    }
                                
                                    td {
                                        border: 1px solid #000; /* Add borders to all table cells */
                                        padding: 8px; /* Add padding for spacing within cells */
                                    }
                                </style>
                    
                    <table class="table table-bordered table-hover">
                        <th colspan="4">
                            <b>Technical Analysis of '.$symbol.'</b>
                        </th>
                        <tr>
                            <td colspan="2">50 day moving average is '.$ema50.'</td><td colspan="2">200 day moving average is '.$ema200.'</td>
                        </tr>
                        <tr>
                            <td colspan="2">Resistance Levels are '.$resistence.'</td><td colspan="2">Support Levels are '.$supports.'</td>
                        </tr>
                        <tr>
                            <td colspan="4">
                            <div class="jumbotron">
                            <h3>Analysis & Strategy</h3>
                            <!--<p>'.$sentiment.'</p>-->
                            </div></td>
                        </tr>
                        '.$optiondata.'
                        <tr>
                        <td colspan="4">'.$news.'</td>
                        </tr>
                        
                    </table> 
                    
                    <style>
                                    table {
                                        width: 100%;
                                        border-collapse: collapse;
                                        text-align: center;
                                    }
                                
                                    td {
                                        border: 1px solid #000; /* Add borders to all table cells */
                                        padding: 8px; /* Add padding for spacing within cells */
                                    }
                                </style>
                                
                    <br ><br >
                    
                    <!--<table class="table table-bordered table-hover">
                        <th colspan="37">
                            <b>Details Sentiment of '.$symbol.'</b>
                        </th>
                    <td colspan=4>'.$detailsentiment.'</td>
                    </table>-->
                    
                    <br ><br >
                    
                    <table id="data-table" class="display">
                        <th colspan="37">
                            <b>Details Table of '.$symbol.'</b>
                        </th>
                    '.$detailtable.'
                    </table>
                    
                    <br ><br >
                    
                    <!--<table class="table table-bordered table-hover">
                        <th colspan="37">
                            <b>Strike Sentiment of '.$symbol.'</b>
                        </th>
                    <td colspan=4>'.$strikesentiment.'</td>
                    </table>-->
                    
                    <style>
                        /* Add some basic styling for the Jumbotron */
                        .jumbotron {
                            background-color: #f8f9fa; /* Background color */
                            padding: 20px; /* Padding around the content */
                            text-align: center; /* Center-align the content */
                        }
                
                        /* Optional: Style the text inside the Jumbotron */
                        .jumbotron h1 {
                            font-size: 36px;
                        }
                
                        .jumbotron p {
                            font-size: 18px;
                        }
                    </style>
                    <!-- Add DataTables JS and jQuery -->
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
                
                    <!-- Initialize DataTables and enable search -->
                    <script>
                        $(document).ready(function () {
                            $("#data-table").DataTable();
                        });
                    </script>';
                    
                    
                    
                }
            }else{
                // Do Nothing
                $table_data = '<style>
                                    table {
                                        width: 100%;
                                        border-collapse: collapse;
                                        text-align: center;
                                    }
                                
                                    td {
                                        border: 1px solid #000; /* Add borders to all table cells */
                                        padding: 8px; /* Add padding for spacing within cells */
                                    }
                                </style>
                    
                    <table style="width:100%; border: 1px solid #000; text-align: center;">
                        <tr>
                            <td><b>'.$symbol.'</b></td>
                        </tr>
                        <tr>
                            <td><i>Calculations in progress</i></td>
                        </tr>
                        <tr>
                        <td colspan="2">'.$news.'</td>
                        </tr>
                    </table>';
                
            }
            
            
            $data["analyse"] .= '<div class="col-md-12 mb-4">
                                <h4 class="text-center">'.$symbol.'</h4>
                                <img src="data:image/png;base64,' . $base64_image . '" alt="'.$symbol.'" class="img-fluid">
                                <p class="text-center">Last Updated: '.$date.'</p>';
            
            $data["analyse"] .= $table_data;    
            
            $data["analyse"] .=  '</div>';
            
        }
    }else{
            $data["analyse"] = '<div class="col-md-12 mb-4">
                                <h4 class="text-center">Analysis in Progress ...</h4>
                                </div>';
    }
    
    
    $data = json_encode($data);

    // Send the data to the client as an SSE event
    echo "data: $data\n\n";

    // Flush the output buffer to ensure real-time updates
    
    ob_flush();
    flush();
    

    // Sleep for 1 second before sending the next update
    sleep(1);
}
