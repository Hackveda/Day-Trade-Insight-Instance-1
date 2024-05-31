<?php
session_start();
include("db.php");

if(isset($_SESSION["username"])){
    $username = $_SESSION["username"];
    //session_write_close();
    $symbol = $_GET["symbol"];
    
    // Insert the Symbol in Database
    $sql2 = "UPDATE login set AnalyseStock = '".$symbol."' where Username like '".$username."'";
    $con1->query($sql2);
}else{
    $username = "";
}

session_write_close();

date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
#$date = date('d M Y H:i:s');
$date = date('d M Y');
$date = "24 July 2023";

echo "<html><head><title>Analysing Stock ".$symbol."</title></head><body id='body'>";

// Handle OI Change Stocks
    $sql7 = "SELECT * from plots where symbol like '".$symbol."'";
    $result7 = $con1->query($sql7);
    if($result7->num_rows > 0){
        while($row = $result7->fetch_assoc()){
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
                            <p>'.$sentiment.'</p>
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
                    
                    <table class="table table-bordered table-hover">
                        <th colspan="37">
                            <b>Details Sentiment of '.$symbol.'</b>
                        </th>
                    <td colspan=4>'.$detailsentiment.'</td>
                    </table>
                    
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
            
            
            $data["plots"] .= '<div class="col-md-12 mb-4">
                                <h4 class="text-center">'.$symbol.'</h4>
                                <img src="data:image/png;base64,' . $base64_image . '" alt="'.$symbol.'" class="img-fluid">
                                <p class="text-center">Last Updated: '.$date.'</p>';
            
            $data["plots"] .= $table_data;    
            
            $data["plots"] .=  '</div>';
            
        }
    }else{
            $data["plots"] = '<div class="col-md-12 mb-4">
                                <h4 class="text-center">Analysis in Progress ...</h4>
                                </div>';
    }
    
    echo $data["plots"];
    
    echo "</body>
    
    
    <script>
    
    function applyColorsToTable(table, maxColumnIndices, colors) {
    var rows = table.querySelectorAll('tr');
    
    for (var j = 0; j < maxColumnIndices.length; j++) {
      var maxColumnIndex = maxColumnIndices[j];
      var cellValues = [];

      for (var i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
        var cell = rows[i].querySelectorAll('td')[maxColumnIndex];
        var cellValue = parseFloat(cell.textContent);
        cellValues.push({ value: cellValue, element: cell });
      }

      // Sort the cellValues in descending order
      cellValues.sort(function (a, b) {
        return b.value - a.value;
      });

      // Set the background color for the top three cells
      for (var i = 0; i < 3 && i < cellValues.length; i++) {
        cellValues[i].element.style.backgroundColor = colors[i];
      }
    }
  }
  
  function updateRupeeSymbol(table, columnIndices) {
    var rows = table.querySelectorAll('tr');

    for (var j = 0; j < columnIndices.length; j++) {
      var columnIndex = columnIndices[j];

      for (var i = 1; i < rows.length; i++) {
        var cell = rows[i].querySelectorAll('td')[columnIndex];
        var cellContent = cell.textContent;
        var numericValue = extractNumericValue(cellContent);
        if (!isNaN(numericValue)) {
          cell.textContent = '₨' + numericValue.toFixed(2); // Adjust formatting as needed
        }
      }
    }
  }
  
  function applyMultiColorsToTable(table, maxColumnIndices, colorsMax, colorsMin) {
    var rows = table.querySelectorAll('tr');

    for (var j = 0; j < maxColumnIndices.length; j++) {
      var maxColumnIndex = maxColumnIndices[j];
      var cellValues = [];

      for (var i = 1; i < rows.length; i++) {
        // Start from 1 to skip the header row
        var cell = rows[i].querySelectorAll('td')[maxColumnIndex];
        var cellValue = parseFloat(cell.textContent);
        cellValues.push({ value: cellValue, element: cell });
      }

      // Sort the cellValues in ascending order
      cellValues.sort(function (a, b) {
        return a.value - b.value;
      });

      // Set the background color for the top three minimum cells (green shades)
      for (var i = 0; i < 3 && i < cellValues.length; i++) {
        cellValues[i].element.style.backgroundColor = colorsMin[i];
      }

      // Sort the cellValues in descending order
      cellValues.sort(function (a, b) {
        return b.value - a.value;
      });

      // Set the background color for the top three maximum cells (red shades)
      for (var i = 0; i < 3 && i < cellValues.length; i++) {
        cellValues[i].element.style.backgroundColor = colorsMax[i];
      }
    }
  }

  function handleSSEEvent(eventData) {
    // Update the table data based on the SSE event data
    // For example, you might update the table content here

    // Call the function to apply colors
    var table = document.querySelector('.dataframe');
    var maxColumnIndices1 = [1, 3, 5, 7, 9]; // Change as needed
    var colors1 = ['green', 'mediumseagreen', 'lightgreen']; // Change as needed
    applyColorsToTable(table, maxColumnIndices1, colors1);

    var maxColumnIndices2 = [2, 4, 6, 8, 10]; // Change as needed
    var colors2 = ['red', 'orangered', 'lightcoral']; // Change as needed
    applyColorsToTable(table, maxColumnIndices2, colors2);
    
    var maxColumnIndices1 = [11, 15, 16, 17, 18, 19, 20, 22, 23, 24, 25, 26, 27]; //column 13
    var colors1Max = ['red', 'orangered', 'lightcoral']; // Change as needed for maximum values
    var colors1Min = ['green', 'mediumseagreen', 'lightgreen']; // Change as needed for minimum values
    applyMultiColorsToTable(table, maxColumnIndices1, colors1Max, colors1Min);
    
  }

  // Replace this with your actual SSE setup
  // Example: var sse = new EventSource('your_sse_url');
  // sse.onmessage = function(event) {
  //   var eventData = JSON.parse(event.data); // Parse the SSE event data if it's JSON
  //   handleSSEEvent(eventData);
  // };
  
    
    const eventSource = new EventSource('../sse_price.php');

        // Event handler for receiving SSE data from the server
        eventSource.onmessage = (event) => {
            const data = JSON.parse(event.data);
            const dataDiv = document.getElementById('body');
            dataDiv.innerHTML = data.analyse;
            handleSSEEvent(data.analyse);
        };
        
    </script>
    </html>";

?>