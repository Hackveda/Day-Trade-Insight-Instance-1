<?php
session_start();

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
//header('Connection: keep-alive');

include("../db.php");

if(isset($_SESSION["symbol"])){
    $symbol = $_SESSION["symbol"];
    session_write_close(); // To release the session lock so as to avoid request blocking
}else{
    $symbol = "";
}

date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
#$date = date('d M Y H:i:s');
$date = date('d M Y');
$date = "24 July 2023";

//$data = '';

// Simulate real-time data updates
while (true) {
    
    // Replace this with your actual data retrieval logic
    $data = array();

    
    #$sql = "Select * from stock where TradeDate like '%.$date.%'";
    $sql = "select * from stock where Symbol like '".$symbol."'";
    
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
            $option_close = $row["OptionClose"];
            
            if($price < $close){
                $color = "#df514c";
            }else{
                $color = "#4caf50";
            }
            
            if($op_price < $option_close){
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
            
            $data["price"] .= '<tp><td style="color:'.$color.';">'.$symbol.'</td><td style="color:'.$color.';">'.$price.'</td><td style="color: '.$target_color.';">'.$target.'</td><td style="color: '.$signal_color.'">'.$signal.'</td><td>'.$recommend.'</td><td style="color: '.$op_color.';">'.$op_price.'</td><td style="color: '.$target_color.';">'.$op_target.'</td><td>'.$view.'</td>';
              
              /*if(strcmp($chart, '') != 0){
                  
              $data["price"] .= '<td><center><img width="24" height="24" src="https://img.icons8.com/color/48/candle-sticks.png" alt="candle-sticks"/></center></td>';
              
              }else{
                $data["price"] .= '<td>No Data</td>';  
              }*/
              
              if(strcmp($hit, "True") == 0){
                 $data["price"] .= '<td><img title="Target Hit" width="24" height="24" src="https://img.icons8.com/color/48/checked--v1.png" alt="checked--v1"/></td>'; 
              }else if(strcmp($hit, "False") == 0){
                 $data["price"] .= '<td><img title="Target Missed" width="24" height="24" src="https://img.icons8.com/external-others-agus-raharjo/64/external-incorrect-flat-website-ui-others-agus-raharjo.png" alt="external-incorrect-flat-website-ui-others-agus-raharjo"/></td>';
              }else{
                  $data["price"] .= '<td><img title="Target Pending" width="24" height="24" src="https://img.icons8.com/office/16/hourglass--v1.png" alt="hourglass--v1"/></td>';
              }
              
              $data["price"] .= '</tr>';
            
            }
            
            #$data = $msg;
            
    }else{
           $data["price"] .= "No Stock Data Found";
    }
    
    $sql1 = "select * from indicators where Symbol like '".$symbol."'";
    
    $result1 = $con1->query($sql1);
    
    if($result1->num_rows > 0){
        while($row = $result1->fetch_assoc()){
            $symbol = $row["Symbol"];
            $recommendother = $row['RecommendOther'];
            $recommendall = $row['RecommendAll'];
            $recommendma = $row['RecommendMA'];
            $rsi = $row['RSI'];
            $rsi1 = $row['RSI1'];
            $stochk = $row['StochK'];
            $stochd = $row['StochD'];
            $stochk1 = $row['StochK1'];
            $stochd1 = $row['StochD1'];
            $cci20 = $row['CCI20'];
            $cci201 = $row['CCI201'];
            $adx = $row['ADX'];
            $adxdi = $row['ADXDI'];
            $adx_di = $row['ADX_DI'];
            $adxdi1 = $row['ADXDI1'];
            $adx_di1 = $row['ADX_DI1'];
            $ao = $row['AO'];
            $ao1 = $row['AO1'];
            $mom = $row['Mom'];
            $mom1 = $row['Mom1'];
            $macdmacd = $row['MACDmacd'];
            $macdsignal = $row['MACDsignal'];
            $recstochrsi = $row['RecStochRSI'];
            $stochrsik = $row['StochRSIK'];
            $recwr = $row['RecWR'];
            $wr = $row['WR'];
            $recbbpower = $row['RecBBPower'];
            $bbpower = $row['BBPower'];
            $recuo = $row['RecUO'];
            $uo = $row['UO'];
            $close = $row['close'];
            $ema5 = $row['EMA5'];
            $sma5 = $row['SMA5'];
            $ema10 = $row['EMA10'];
            $sma10 = $row['SMA10'];
            $ema20 = $row['EMA20'];
            $sma20 = $row['SMA20'];
            $ema30 = $row['EMA30'];
            $sma30 = $row['SMA30'];
            $ema50 = $row['EMA50'];
            $sma50 = $row['SMA50'];
            $ema100 = $row['EMA100'];
            $sma100 = $row['SMA100'];
            $ema200 = $row['EMA200'];
            $sma200 = $row['SMA200'];
            $recichimoku = $row['RecIchimoku'];
            $ichimokubline = $row['IchimokuBLine'];
            $recvwma = $row['RecVWMA'];
            $vwma = $row['VWMA'];
            $rechullma9 = $row['RecHullMA9'];
            $hullma9 = $row['HullMA9'];
            $pivotmclassics3 = $row['PivotMClassicS3'];
            $pivotmclassics2 = $row['PivotMClassicS2'];
            $pivotmclassics1 = $row['PivotMClassicS1'];
            $pivotmclassicmiddle = $row['PivotMClassicMiddle'];
            $pivotmclassicr1 = $row['PivotMClassicR1'];
            $pivotmclassicr2 = $row['PivotMClassicR2'];
            $pivotmclassicr3 = $row['PivotMClassicR3'];
            $pivotmfibonaccis3 = $row['PivotMFibonacciS3'];
            $pivotmfibonaccis2 = $row['PivotMFibonacciS2'];
            $pivotmfibonaccis1 = $row['PivotMFibonacciS1'];
            $pivotmfibonaccimiddle = $row['PivotMFibonacciMiddle'];
            $pivotmfibonaccir1 = $row['PivotMFibonacciR1'];
            $pivotmfibonaccir2 = $row['PivotMFibonacciR2'];
            $pivotmfibonaccir3 = $row['PivotMFibonacciR3'];
            $pivotmcamarillas3 = $row['PivotMCamarillaS3'];
            $pivotmcamarillas2 = $row['PivotMCamarillaS2'];
            $pivotmcamarillas1 = $row['PivotMCamarillaS1'];
            $pivotmcamarillamiddle = $row['PivotMCamarillaMiddle'];
            $pivotmcamarillar1 = $row['PivotMCamarillaR1'];
            $pivotmcamarillar2 = $row['PivotMCamarillaR2'];
            $pivotmcamarillar3 = $row['PivotMCamarillaR3'];
            $pivotmwoodies3 = $row['PivotMWoodieS3'];
            $pivotmwoodies2 = $row['PivotMWoodieS2'];
            $pivotmwoodies1 = $row['PivotMWoodieS1'];
            $pivotmwoodiemiddle = $row['PivotMWoodieMiddle'];
            $pivotmwoodier1 = $row['PivotMWoodieR1'];
            $pivotmwoodier2 = $row['PivotMWoodieR2'];
            $pivotmwoodier3 = $row['PivotMWoodieR3'];
            $pivotmdemarks1 = $row['PivotMDemarkS1'];
            $pivotmdemarkmiddle = $row['PivotMDemarkMiddle'];
            $pivotmdemarkr1 = $row['PivotMDemarkR1'];
            $open = $row['open'];
            $psar = $row['PSAR'];
            $bblower = $row['BBlower'];
            $bbupper = $row['BBupper'];
            $ao2 = $row['AO2'];
            $volume = $row['volume'];
            $change = $row['change'];
            $low = $row['low'];
            $high = $row['high'];
            
            // Declare Variables
            
            $sum_recommendation = '';
            $sum_buy = '';
            $sum_sell = '';
            $sum_neutral = '';
            
            $ma_recommendation = '';
            $ma_buy = '';
            $ma_sell = '';
            $ma_neutral = '';
            $ma_ema10 = '';
            $ma_sma10 = '';
            $ma_ema20 = '';
            $ma_sma20 = '';
            $ma_ema30 = '';
            $ma_sma30 = '';
            $ma_ema50 = '';
            $ma_sma50 = '';
            $ma_ema100 = '';
            $ma_sma100 = '';
            $ma_ema200 = '';
            $ma_sma200 = '';
            $ma_ichimoku = '';
            $ma_vwma = '';
            $ma_hullma = '';
            
            $osc_recommendation = '';
            $osc_buy = '';
            $osc_sell = '';
            $osc_neutral = '';
            $osc_rsi = '';
            $osc_stochk = '';
            $osc_cci = '';
            $osc_adx = '';
            $osc_ao = '';
            $osc_mom = '';
            $osc_macd = '';
            $osc_stochrsi = '';
            $osc_wr = '';
            $osc_bbp = '';
            $osc_uo = '';
            
            // Get Summary Data
            $sql2 = "SELECT * from summary where Symbol like '".$symbol."'";
            $result2 = $con1->query($sql2);
            if($result2->num_rows > 0){
                while($row = $result2->fetch_assoc()){
                    $sum_recommendation = $row['RECOMMENDATION'];
                    $sum_buy = $row['BUY'];
                    $sum_sell = $row['SELL'];
                    $sum_neutral = $row['NEUTRAL'];
                }
            }
            
            // Get Moving Averages Data
            $sql3 = "SELECT * from moving_averages where Symbol like '".$symbol."'";
            $result3 = $con1->query($sql3);
            if($result3->num_rows > 0){
                while($row = $result3->fetch_assoc()){
                    $ma_recommendation = $row['RECOMMENDATION'];
                    $ma_buy = $row['BUY'];
                    $ma_sell = $row['SELL'];
                    $ma_neutral = $row['NEUTRAL'];
                    $ma_ema10 = $row['EMA10'];
                    $ma_sma10 = $row['SMA10'];
                    $ma_ema20 = $row['EMA20'];
                    $ma_sma20 = $row['SMA20'];
                    $ma_ema30 = $row['EMA30'];
                    $ma_sma30 = $row['SMA30'];
                    $ma_ema50 = $row['EMA50'];
                    $ma_sma50 = $row['SMA50'];
                    $ma_ema100 = $row['EMA100'];
                    $ma_sma100 = $row['SMA100'];
                    $ma_ema200 = $row['EMA200'];
                    $ma_sma200 = $row['SMA200'];
                    $ma_ichimoku = $row['Ichimoku'];
                    $ma_vwma = $row['VWMA'];
                    $ma_hullma = $row['HullMA'];
                }
            }
            
            
            // Get Oscillators Data
            $sql4 = "SELECT * from oscillators where Symbol like '".$symbol."'";
            $result4 = $con1->query($sql4);
            if($result4->num_rows > 0){
                while($row = $result4->fetch_assoc()){
                    $osc_recommendation = $row['RECOMMENDATION'];
                    $osc_buy = $row['BUY'];
                    $osc_sell = $row['SELL'];
                    $osc_neutral = $row['NEUTRAL'];
                    $osc_rsi = $row['RSI'];
                    $osc_stochk = $row['STOCHK'];
                    $osc_cci = $row['CCI'];
                    $osc_adx = $row['ADX'];
                    $osc_ao = $row['AO'];
                    $osc_mom = $row['Mom'];
                    $osc_macd = $row['MACD'];
                    $osc_stochrsi = $row['StochRSI'];
                    $osc_wr = $row['W%R'];
                    $osc_bbp = $row['BBP'];
                    $osc_uo = $row['UO'];
                }
            }
            
            
            $data["summary"] .= "<tr><td>Summary</td><td>".$sum_recommendation."</td><td>".$sum_buy."</td><td>".$sum_sell."</td><td>".$sum_neutral."</td></tr><tr><td>Moving Averages</td><td>".$ma_recommendation."</td> <td>".$ma_buy."</td> <td>".$ma_sell."</td> <td>".$ma_neutral."</td></tr><tr><td>Oscillators</td><td>".$osc_recommendation."</td> <td>".$osc_buy."</td> <td>".$osc_sell."</td> <td>".$osc_neutral."</td></tr>";
            $data["indicator"] .= "<tr><td>RSI</td><td>".$osc_rsi."</td><td>".$rsi."</td><td>Relative Strength Index (RSI) is a momentum oscillator that measures the speed and change of price movements. It ranges from 0 to 100. Values above 70 indicate overbought conditions, and values below 30 indicate oversold conditions.</td></tr>";
            $data["indicator"] .= "<tr><td>RSI[1]</td><td></td><td>".$rsi1."</td> <td>Previous period's Relative Strength Index (RSI) value.</td> </tr> <tr> <td>Stoch.K</td><td>".$osc_stochk."</td><td>".$stochk."</td> <td>Stochastic oscillator %K measures where the current closing price is relative to the range of the low/high range over a specific period. It ranges from 0 to 100.</td> </tr><tr><td>Stoch.D</td><td></td><td>".$stochd."</td> <td>Stochastic oscillator %D is the moving average of %K and helps to smooth out the %K values. It ranges from 0 to 100.</td> </tr> <!-- Add more rows for other indicators --> <!-- Add rows for each indicator --> <tr> <td>Stoch.K[1]</td><td></td><td>".$stochk1."</td> <td>Previous period's Stochastic oscillator %K value.</td> </tr> <tr> <td>Stoch.D[1]</td><td></td><td>".$stochd1."</td> <td>Previous period's Stochastic oscillator %D value.</td> </tr> <tr> <td>CCI20</td><td>".$osc_cci."</td><td>".$cci20."</td> <td>Commodity Channel Index (CCI) is an oscillator that measures the current price level relative to an average price level over a period of time. CCI values above +100 suggest overbought conditions, and values below -100 suggest oversold conditions.</td> </tr> <tr> <td>CCI20[1]</td><td></td><td>".$cci201."</td> <td>Previous period's Commodity Channel Index (CCI) value.</td> </tr> <tr> <td>ADX</td><td>".$osc_adx."</td><td>".$adx."</td> <td>Average Directional Index (ADX) is a trend strength indicator. It measures the strength of a trend and not its direction. ADX values above 25 typically indicate a strong trend.</td> </tr> <tr> <td>ADX+DI</td><td></td><td>".$adxdi."</td> <td>Positive Directional Indicator (+DI) is used alongside ADX to represent the bullish trend direction.</td> </tr> <tr> <td>ADX-DI</td><td></td><td>".$adx_di."</td> <td>Negative Directional Indicator (-DI) is used alongside ADX to represent the bearish trend direction.</td> </tr> <tr> <td>ADX+DI[1]</td><td></td><td>".$adxdi1."</td> <td>Previous period's Positive Directional Indicator (+DI) value.</td> </tr> <tr> <td>ADX-DI[1]</td><td></td><td>".$adx_di1."</td> <td>Previous period's Negative Directional Indicator (-DI) value.</td> </tr> <tr> <td>AO</td><td>".$osc_ao."</td><td>".$ao."</td> <td>Awesome Oscillator (AO) is a momentum indicator that shows market momentum in relation to a recent range of high and low prices.</td> </tr> <tr> <td>AO[1]</td><td></td><td>".$ao1."</td> <td>Previous period's Awesome Oscillator (AO) value.</td> </tr> <tr> <td>Mom</td><td>".$osc_mom."</td><td>".$mom."</td> <td>Momentum is an oscillator that measures the rate of change in price movements over a specific period.</td> </tr> <tr> <td>Mom[1]</td><td></td><td>".$mom1."</td> <td>Previous period's Momentum value.</td> </tr> <tr> <td>MACD.macd</td><td>".$osc_macd."</td><td>".$macdmacd."</td> <td>Moving Average Convergence Divergence (MACD) is a trend-following momentum indicator that shows the relationship between two moving averages of a security's price.</td> </tr> <tr> <td>MACD.signal</td><td></td><td>".$macdsignal."</td> <td>MACD signal line, which is a 9-day EMA of the MACD line. It helps to identify potential points of trend reversals.</td> </tr> <tr> <td>Rec.Stoch.RSI</td><td>".$osc_stochrsi."</td><td>".$recstochrsi."</td> <td>Recommendation based on the Stochastic RSI values. (Example: Buy, Sell, Hold)</td> </tr> <tr> <td>Stoch.RSI.K</td><td></td><td>".$stochrsik."</td> <td>Stochastic Relative Strength Index (Stoch RSI) measures the RSI relative to its high-low range over a specific period. It ranges from 0 to 100.</td> </tr> <tr> <td>Rec.WR</td><td></td><td>".$recwr."</td> <td>Recommendation based on Williams %R values. (Example: Buy, Sell, Hold)</td> </tr> <tr> <td>W.R</td><td>".$osc_wr."</td><td>".$wr."</td> <td>Williams %R is a momentum oscillator that measures overbought and oversold levels. It ranges from -100 to 0, with -20 being overbought and -80 being oversold.</td> </tr> <tr> <td>Rec.BBPower</td><td></td><td>".$recbbpower."</td> <td>Recommendation based on Bollinger Bands Power. (Example: Buy, Sell, Hold)</td> </tr> <tr> <td>BBPower</td><td>".$osc_bbp."</td><td>".$bbpower."</td> <td>Bollinger Bands Power measures the distance between the Bollinger Bands. It helps identify volatility conditions.</td> </tr> <tr> <td>Rec.UO</td><td></td><td>".$recuo."</td> <td>Recommendation based on Ultimate Oscillator values. (Example: Buy, Sell, Hold)</td> </tr> <tr> <td>UO</td><td>".$osc_uo."</td><td>".$uo."</td> <td>Ultimate Oscillator is a momentum oscillator that combines short-term, mid-term, and long-term price cycles to generate overbought and oversold signals.</td> </tr> <tr> <td>close</td><td></td><td>".$close."</td> <td>The closing price of the stock or asset.</td> </tr> <tr> <td>EMA5</td><td></td><td>".$ema5."</td> <td>Exponential Moving Average (EMA) with a period of 5.</td> </tr> <tr> <td>SMA5</td><td></td><td>".$sma5."</td> <td>Simple Moving Average (SMA) with a period of 5.</td> </tr> <tr> <td>EMA10</td><td>".$ma_ema10."</td><td>".$ema10."</td> <td>Exponential Moving Average (EMA) with a period of 10.</td> </tr> <tr> <td>SMA10</td><td>".$ma_sma10."</td><td>".$sma10."</td> <td>Simple Moving Average (SMA) with a period of 10.</td> </tr> <tr> <td>EMA20</td><td>".$ma_ema20."</td><td>".$ema20."</td> <td>Exponential Moving Average (EMA) with a period of 20.</td> </tr> <tr> <td>SMA20</td><td>".$ma_sma20."</td><td>".$sma20."</td> <td>Simple Moving Average (SMA) with a period of 20.</td> </tr> <tr> <td>EMA30</td><td>".$ma_ema30."</td><td>".$ema30."</td> <td>Exponential Moving Average (EMA) with a period of 30.</td> </tr> <tr> <td>SMA30</td><td>".$ma_sma30."</td><td>".$sma30."</td> <td>Simple Moving Average (SMA) with a period of 30.</td> </tr> <tr> <td>EMA50</td><td>".$ma_ema50."</td><td>".$ema50."</td> <td>Exponential Moving Average (EMA) with a period of 50.</td> </tr> <tr> <td>SMA50</td><td>".$ma_sma50."</td><td>".$sma50."</td> <td>Simple Moving Average (SMA) with a period of 50.</td> </tr> <tr> <td>EMA100</td><td>".$ma_ema100."</td><td>".$ema100."</td> <td>Exponential Moving Average (EMA) with a period of 100.</td> </tr> <tr> <td>SMA100</td><td>".$ma_sma100."</td><td>".$sma100."</td> <td>Simple Moving Average (SMA) with a period of 100.</td> </tr> <tr> <td>EMA200</td><td>".$ma_ema200."</td><td>".$ema200."</td> <td>Exponential Moving Average (EMA) with a period of 200.</td> </tr> <tr> <td>SMA200</td><td>".$ma_sma200."</td><td>".$sma200."</td> <td>Simple Moving Average (SMA) with a period of 200.</td> </tr> <tr> <td>Rec.Ichimoku</td><td>".$ma_ichimoku."</td><td>".$recichimoku."</td> <td>Recommendation based on Ichimoku Cloud values. (Example: Buy, Sell, Hold)</td> </tr> <tr> <td>Ichimoku.BLine</td><td>".$ma_ichimoku."</td><td>".$ichimokubline."</td> <td>Ichimoku Base Line (Kijun-sen) is one of the components of the Ichimoku Cloud indicator.</td> </tr> <tr> <td>Rec.VWMA</td><td>".$ma_vwma."</td><td>".$recvwma."</td> <td>Recommendation based on Volume Weighted Moving Average (VWMA). (Example: Buy, Sell, Hold)</td> </tr> <tr> <td>VWMA</td><td>".$ma_vwma."</td><td>".$vwma."</td> <td>Volume Weighted Moving Average (VWMA) calculates the average price weighted by volume over a specified period.</td> </tr> <tr> <td>Rec.HullMA9</td><td>".$ma_hullma."</td><td>".$rechullma9."</td> <td>Recommendation based on Hull Moving Average (Hull MA) values. (Example: Buy, Sell, Hold)</td> </tr> <tr> <td>HullMA9</td><td>".$ma_hullma9."</td><td>".$hullma9."</td> <td>Hull Moving Average (Hull MA) is a type of moving average that aims to reduce lag and improve smoothing.</td> </tr> <tr> <td>Pivot.M.Classic.S3</td><td></td><td>".$pivotmclassics3."</td> <td>Support 3 (S3) is a support level calculated using Classic Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Classic.S2</td><td></td><td>".$pivotmclassics2."</td> <td>Support 2 (S2) is a support level calculated using Classic Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Classic.S1</td><td></td><td>".$pivotmclassics1."</td> <td>Support 1 (S1) is a support level calculated using Classic Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Classic.Middle</td><td></td><td>".$pivotmclassicmiddle."</td> <td>Middle Pivot Point (PP) is the average of the high, low, and close prices from the previous period.</td> </tr> <tr> <td>Pivot.M.Classic.R1</td><td></td><td>".$pivotmclassicr1."</td> <td>Resistance 1 (R1) is a resistance level calculated using Classic Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Classic.R2</td><td></td><td>".$pivotmclassicr2."</td> <td>Resistance 2 (R2) is a resistance level calculated using Classic Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Classic.R3</td><td></td><td>".$pivotmclassicr3."</td> <td>Resistance 3 (R3) is a resistance level calculated using Classic Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Fibonacci.S3</td><td></td><td>".$pivotmfibonaccis3."</td> <td>Support 3 (S3) is a support level calculated using Fibonacci Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Fibonacci.S2</td><td></td><td>".$pivotmfibonaccis2."</td> <td>Support 2 (S2) is a support level calculated using Fibonacci Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Fibonacci.S1</td><td></td><td>".$pivotmfibonaccis1."</td> <td>Support 1 (S1) is a support level calculated using Fibonacci Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Fibonacci.Middle</td><td></td><td>".$pivotmfibonaccimiddle."</td> <td>Middle Pivot Point (PP) is the average of the high, low, and close prices from the previous period using Fibonacci Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Fibonacci.R1</td><td></td><td>".$pivotmfibonaccir1."</td> <td>Resistance 1 (R1) is a resistance level calculated using Fibonacci Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Fibonacci.R2</td><td></td><td>".$pivotmfibonaccir2."</td> <td>Resistance 2 (R2) is a resistance level calculated using Fibonacci Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Fibonacci.R3</td><td></td><td>".$pivotmfibonaccir3."</td> <td>Resistance 3 (R3) is a resistance level calculated using Fibonacci Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Camarilla.S3</td><td></td><td>".$pivotmcamarillas3."</td> <td>Support 3 (S3) is a support level calculated using Camarilla Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Camarilla.S2</td><td></td><td>".$pivotmcamarillas2."</td> <td>Support 2 (S2) is a support level calculated using Camarilla Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Camarilla.S1</td><td></td><td>".$pivotmcamarillas1."</td> <td>Support 1 (S1) is a support level calculated using Camarilla Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Camarilla.Middle</td><td></td><td>".$pivotmcamarillamiddle."</td> <td>Middle Pivot Point (PP) is the average of the high, low, and close prices from the previous period using Camarilla Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Camarilla.R1</td><td></td><td>".$pivotmcamarillar1."</td> <td>Resistance 1 (R1) is a resistance level calculated using Camarilla Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Camarilla.R2</td> <td></td><td>".$pivotmcamarillar2."</td> <td>Resistance 2 (R2) is a resistance level calculated using Camarilla Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Camarilla.R3</td><td></td><td>".$pivotmcamarillar3."</td> <td>Resistance 3 (R3) is a resistance level calculated using Camarilla Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Woodie.S3</td><td></td><td>".$pivotmwoodies3."</td> <td>Support 3 (S3) is a support level calculated using Woodie's Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Woodie.S2</td><td></td><td>".$pivotmwoodies2."</td> <td>Support 2 (S2) is a support level calculated using Woodie's Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Woodie.S1</td><td></td><td>".$pivotmwoodies1."</td> <td>Support 1 (S1) is a support level calculated using Woodie's Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Woodie.Middle</td><td></td><td>".$pivotmwoodiemiddle."</td> <td>Middle Pivot Point (PP) is the average of the high, low, and close prices from the previous period using Woodie's Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Woodie.R1</td><td></td><td>".$pivotmwoodier1."</td> <td>Resistance 1 (R1) is a resistance level calculated using Woodie's Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Woodie.R2</td><td></td><td>".$pivotmwoodier2."</td> <td>Resistance 2 (R2) is a resistance level calculated using Woodie's Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Woodie.R3</td><td></td><td>".$pivotmwoodier3."</td> <td>Resistance 3 (R3) is a resistance level calculated using Woodie's Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Demark.S1</td><td></td><td>".$pivotmdemarks1."</td> <td>Support 1 (S1) is a support level calculated using DeMark's Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Demark.Middle</td><td></td><td>".$pivotmdemarkmiddle."</td> <td>Middle Pivot Point (PP) is the average of the high, low, and close prices from the previous period using DeMark's Pivot Points methodology.</td> </tr> <tr> <td>Pivot.M.Demark.R1</td><td></td><td>".$pivotmdemarkr1."</td> <td>Resistance 1 (R1) is a resistance level calculated using DeMark's Pivot Points methodology.</td> </tr> <tr> <td>open</td><td></td><td>".$open."</td><td>The opening price of the stock or asset.</td> </tr> <tr> <td>P.SAR</td><td></td><td>".$psar."</td> <td>Parabolic Stop and Reverse (SAR) is a trend-following indicator used to set trailing stop-loss levels.</td> </tr> <tr> <td>BB.lower</td><td></td><td>".$bblower."</td> <td>Lower Bollinger Band (BB) is a volatility band that varies above and below a simple moving average based on the standard deviation.</td> </tr> <tr> <td>BB.upper</td><td></td><td>".$bbupper."</td> <td>Upper Bollinger Band (BB) is a volatility band that varies above and below a simple moving average based on the standard deviation.</td> </tr> <tr> <td>AO[2]</td><td></td><td>".$ao2."</td> <td>Awesome Oscillator (AO) value from two periods ago.</td> </tr> <tr> <td>volume</td><td></td><td>".$volume."</td> <td>The total number of shares or contracts traded during a given period.</td> </tr> <tr> <td>change</td><td></td><td>".$change."</td> <td>The price change or the difference between the current price and the previous period's price.</td> </tr> <tr> <td>low</td><td></td><td>".$low."</td> <td>The lowest price of the stock or asset during the given period.</td> </tr> <tr> <td>high</td><td></td><td>".$high."</td> <td>The highest price of the stock or asset during the given period.</td> </tr>";
            
        }
            
    }else{
           $data["indicator"] .= "No Stock Data Found";
    }

    $data = json_encode($data);

    // Send the data to the client as an SSE event
    echo "data: $data\n\n";
 
    // Flush the output buffer to ensure real-time updates
    
    ob_flush();
    flush();
    
    // Sleep for 1 second before sending the next update
    sleep(0.5);
}
