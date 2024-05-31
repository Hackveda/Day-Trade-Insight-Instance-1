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
                   
                    
                <table id="data-table" class="display">
                        <th colspan="37">
                            <b>Details Table of '.$symbol.'</b>
                </th>
                    '.$detailtable.'
                    </table>
                    
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
            
            
            $data["plots"] .= $table_data;    
            
            $data["plots"] .=  '</div>';

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

  function handleSSEEvent(eventData) {
    // Update the table data based on the SSE event data
    // For example, you might update the table content here

    // Call the function to apply colors
    var table = document.querySelector('.dataframe');
    var maxColumnIndices1 = [1, 3, 5, 7, 9, 11]; // Change as needed
    var colors1 = ['green', 'mediumseagreen', 'lightgreen']; // Change as needed
    applyColorsToTable(table, maxColumnIndices1, colors1);

    var maxColumnIndices2 = [2, 4, 6, 8, 10, 12]; // Change as needed
    var colors2 = ['red', 'orangered', 'lightcoral']; // Change as needed
    applyColorsToTable(table, maxColumnIndices2, colors2);
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