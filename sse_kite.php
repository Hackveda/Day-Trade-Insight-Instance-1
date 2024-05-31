<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
include("db.php");

function sendSSE($data) {
    echo "data: $data\n\n";
    ob_flush();
    flush();
}
 
while (true) {
    
        $data = '';
        
        $sql = "SELECT * from stock";
        $result = $con1->query($sql);
        
        if($result->num_rows > 0){
            
            $stock_symbols = array();
            $stock_recommend = array();
            
            while($row = $result->fetch_assoc()){
            $stock_symbol = $row["Symbol"];
            
            array_push($stock_symbols, $stock_symbol);
            }
            
            /*$sql7 = "SELECT * from plots";
            $result7->$con1->query($sql7);
            if($result7->num_rows > 0){
                while($row = $result7->fetch_assoc()){
                    $stock_symbol = $row["symbol"];
                    array_push($stock_symbols, $stock_symbol);
                }
            }*/
          
            $recommend = "";
            
          foreach ($stock_symbols as $stock_symbol){
              //sendSSE($stock_symbol);
              
              $sql3 = "SELECT Recommend from stock where `Symbol` like '".$stock_symbol."'";
              $result3 = $con1->query($sql3);
              
              if($result3->num_rows > 0){
                  while($row = $result3->fetch_assoc()){
                      $recommend = $row["Recommend"];
                  }
              }
              
              // Kite API endpoint to fetch quote
            $kite_api_url = 'https://api.kite.trade/quote?i=NSE:' . $stock_symbol;
            
            // Fetch the stock price from the Kite API
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $kite_api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'X-Kite-Version: 3',
                'Authorization: token ' . $api_key . ':' . $access_token,
            ));
            
            $response = curl_exec($ch);
            sendSSE($response);
            curl_close($ch);
            
            // Kite API endpoint to fetch quote
            $kite_api_url_option = 'https://api.kite.trade/quote?i=NFO:' . $recommend;
            
            // Fetch the stock price from the Kite API
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL, $kite_api_url_option);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
                'X-Kite-Version: 3',
                'Authorization: token ' . $api_key . ':' . $access_token,
            ));
            
            $response1 = curl_exec($ch1);
            curl_close($ch1);
            
            //sendSSE($kite_api_url_option);
            sendSSE($response1);
        
            if ($response === false) {
                sendSSE('Error fetching data from Kite API');
            } else {
                $data = json_decode($response, true);
                $data1 = json_decode($response1, true);
                
                //sendSSE($response);
                if (isset($data['data']['NSE:' . $stock_symbol])) {
                    $stock_data = $data['data']['NSE:' . $stock_symbol];
                    $last_price = $stock_data['last_price'];
                    
                    // Get OHLC Data
                    $stock_ohlc = $stock_data["ohlc"];
                    $stock_open = $stock_ohlc["open"];
                    $stock_high = $stock_ohlc["high"];
                    $stock_low = $stock_ohlc["low"];
                    $stock_close = $stock_ohlc["close"];
                    
                    // Option Data
                    $option_data = $data1["data"]['NFO:' . $recommend];
                    $last_price_option = $option_data['last_price'];
                    
                    if(strcmp($last_price_option, "") !== 0){
                        //$last_price_option = 0;
                    
                    
                    // Get OHLC Data
                    $option_ohlc = $option_data["ohlc"];
                    $option_open = $option_ohlc["open"];
                    $option_high = $option_ohlc["high"];
                    $option_low = $option_ohlc["low"];
                    $option_close = $option_ohlc["close"];
                    
                    $sql1 = "UPDATE stock set `Price` = '".$last_price."', `OptionPrice` = '".$last_price_option."', `Open` = '".$stock_open."', `High` = '".$stock_high."', `Low` = '".$stock_low."', `Close` = '".$stock_close."', `OptionOpen` = '".$option_open."', `OptionHigh` = '".$option_high."', `OptionLow` = '".$option_low."', `OptionClose` = '".$option_close."' where Symbol like '".$stock_symbol."'";
                    
                    $result1 = $con1->query($sql1);
                    
                    if($result1){
                        //sendSSE($stock_symbol . " last price " . $last_price . " updated successfully");
                    }else{
                         //sendSSE($stock_symbol . " last price " . $last_price . " update failed"); 
                    }
                    
                    // Check if Target Hit
                    $sql2 = "SELECT * from stock where Symbol like '".$stock_symbol."' and Recommend like '".$recommend."'";
                    $result2 = $con1->query($sql2);
                    if($result2->num_rows > 0){
                        while($row = $result2->fetch_assoc()){
                            $target = $row["Target"];
                            $signal = $row["Signal"];
                            
                            $op_target = $row["OptionTarget"];
                            $postdate = $row["PostDate"];
                            
                            // Set Target Hit Date
                            date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
                            #$date = date('d M Y H:i:s');
                            $hit_date = date('d M Y');
                            
                            $tradeDate = DateTime::createFromFormat('d M Y', $hit_date);
                            $postDate = DateTime::createFromFormat('d M Y', $postdate);
                            
                            if((strcmp($signal, "Buy") == 0) && ($postDate <= $tradeDate)){
                                
                                if($last_price >= $target || $last_price_option >= $op_target){
                                $sql3 = "UPDATE stock set Hit = 'True', TradeDate = '".$hit_date."' where Symbol like '".$stock_symbol."' and Recommend like '".$recommend."'";
                                $result3 = $con1->query($sql3);
                                if($result3){
                                    //sendSSE($stock_symbol . " target hit");
                                }else{
                                    //Do Nothing
                                }
                                }else{
                                    // Do nothing
                                }
                                
                            }else if((strcmp($signal, "Sell") == 0) && ($postDate <= $tradeDate)){
                                if($last_price <= $target || $last_price_option >= $op_target){
                                $sql3 = "UPDATE stock set Hit = 'True', TradeDate = '".$hit_date."' where Symbol like '%".$stock_symbol."%' and Recommend like '".$recommend."'";
                                $result3 = $con1->query($sql3);
                                if($result3){
                                    //sendSSE($stock_symbol . " target hit");
                                }else{
                                    //Do Nothing
                                }
                                }else{
                                    // Do nothing
                                }
                                }
                        }
                    }else{
                        // Do Nothing
                    }
                    
                    // Execute Stock Orders 
                    
                    $sql3 = "SELECT * from orders where `Symbol` like '".$stock_symbol."'";
                    $result3 = $con1->query($sql3);
                    if($result3->num_rows > 0){
                        while($row = $result3->fetch_assoc()){
                            $id = $row["ID"];
                            $buy_price = $row["BuyPrice"];
                            $sell_price = $row["SellPrice"];
                            
                            $buy_status = $row["BuyStatus"];
                            $sell_status = $row["SellStatus"];
                            
                            // Execute Buy Order
                            if(strcmp($buy_status, "") == 0){
                                if($last_price <= $buy_price && strcmp($buy_price, "") != 0){
                                    $sql4 = "UPDATE `orders` SET `BuyStatus` = 'True' where ID=".$id;
                                    $result4 = $con1->query($sql4);
                                    //sendSSE($last_price . " = " . $buy_price);
                                }
                            }
                            
                            // Execute Sell Order
                            if(strcmp($sell_status, "") == 0){
                                if($last_price >= $sell_price && strcmp($sell_price, "") != 0){
                                    $sql4 = "UPDATE `orders` SET `SellStatus` = 'True' where ID=".$id;
                                    $result4 = $con1->query($sql4);
                                    
                                }
                            }
                            
                        }
                    }else{
                        // Do Nothing
                    }
                    
                    // Execute Option Orders 
                    
                    $sql5 = "SELECT * from orders where `Recommend` like '".$recommend."'";
                    $result5 = $con1->query($sql5);
                    if($result5->num_rows > 0){
                        while($row = $result5->fetch_assoc()){
                            $id = $row["ID"];
                            $buy_price = $row["BuyOpPrice"];
                            $sell_price = $row["SellOpPrice"];
                            
                            $buy_status = $row["BuyOpStatus"];
                            $sell_status = $row["SellOpStatus"];
                            
                            // Execute Buy Order
                            if(strcmp($buy_status, "") == 0){
                                if($last_price_option <= $buy_price && strcmp($buy_price, "") != 0){
                                    $sql6 = "UPDATE `orders` SET `BuyOpStatus` = 'True' where ID=".$id;
                                    $result6 = $con1->query($sql6);
                                    
                                }
                            }
                            
                            // Execute Sell Order
                            if(strcmp($sell_status, "") == 0){
                                if($last_price_option >= $sell_price && strcmp($sell_price, "") != 0){
                                    $sql6 = "UPDATE `orders` SET `SellOpStatus` = 'True' where ID=".$id;
                                    $result6 = $con1->query($sql6);
                                    
                                }
                            }
                            
                        }
                    }else{
                        // Do Nothing
                    }
                    
                    } 
                    
                } else {
                    //sendSSE('Stock data not found for symbol: ' . $stock_symbol);
                }
            }
        
            }
            
            // Delay before sending the next update (you can adjust this as needed)
            sleep(10);
            
          }else{
              // Do nothing
          }
          
          //echo "data: $data\n\n";
          ob_flush();
          flush();
    }
?>
