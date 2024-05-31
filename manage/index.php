<?php
session_start();
include("../db.php");

if(isset($_SESSION["broker"]) && strcmp($_SESSION["broker"], "") != 0){
    $broker = $_SESSION["broker"];
    $broker_name = $_SESSION["broker_name"];
    //session_write_close();
}else{
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Recommendation Management | <?php echo $broker_name; ?></title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom CSS styles */
        /* Add your custom styles here */
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <img src="/daytradeinsight-logo.png" alt="DayTradeInsight Logo" style="height: 50px; padding-left:10px; padding-right:10px;">
    <a class="navbar-brand" href="#">Advisor Dashboard</a>
    <!-- Add your navigation links here -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="/manage">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
    </ul>
  </nav>
    <div class="container mt-5">
        <h1 class="mb-4">Welcome, <?php echo $broker_name; ?></h1>

        <!-- Add Recommendation Panel -->
        <div class="card mb-4">
            <div class="card-header">
                Add New Recommendation
            </div>
            <div class="card-body">
                <!-- Add Recommendation Form -->
                <form id="addForm">
                    <!-- Form fields for Stock Symbol, Price, Target, Signal, Recommendation, Option Target, Target View -->
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="stockSymbol">Stock Symbol</label>
                            <input type="text" class="form-control" id="stockSymbol" name="stockSymbol" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="target">Target</label>
                            <input type="number" class="form-control" id="target" name="target" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="signal">Signal</label>
                            <select class="form-control" id="signal" name="signal" required>
                                <option value="Buy">Buy</option>
                                <option value="Sell">Sell</option>
                                <option value="Hold">Hold</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="signal">Strike</label>
                            <input type="number" class="form-control" id="strike" name="strike" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="recommendation">Recommendation</label>
                            <input type="text" class="form-control" id="recommendation" name="recommendation" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="entryPrice">Entry Price</label>
                            <input type="number" class="form-control" id="entryPrice" name="entryPrice" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="optionTarget">Option Target</label>
                            <input type="number" class="form-control" id="optionTarget" name="optionTarget" required>
                        </div>
                        
                        <div class="form-group col-md-4">
                            <label for="fundNeeded">Fund Needed</label>
                            <input type="number" class="form-control" id="fundNeeded" name="fundNeeded" required>
                        </div>
                        
                        <div class="form-group col-md-4">
                            <label for="optionTarget">Target View</label>
                            <select class="form-control" id="targetView" name="targetView" required>
                                <option value="1-2 Days">1-2 Days</option>
                                <option value="2-3 Days">2-3 Days</option>
                                <option value="3-5 Days">3-5 Days</option>
                                <option value="Expiry Day">Expiry Day</option>
                                <option value="1-2 Months">1-2 Months</option>
                                <option value="6 Months">6 Months</option>
                                <option value="1 Year">1 Year</option>
                                <option value="5 Year">5 Year</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="optionTarget">Lot Size</label>
                            <input type="number" class="form-control" id="lotSize" name="lotSize" required>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary mt-3" id="btn_add_recommend">Add Recommendation</button>
                </form>
            </div>
        </div>

        <!-- Stock Recommendations List -->
        <div class="card">
            <div class="card-header">
                Stock Recommendations
            </div>
            <div class="card-body">
                <!-- Table to display stock recommendations -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <!-- Table headers for Stock Symbol, Price, Target, Signal, Recommendation, Option Target, Target View, Edit, Delete -->
                            <th>Stock Symbol</th>
                            <th>Price</th>
                            <th>Target</th>
                            <th>Signal</th>
                            <th>Option</th>
                            <th>Option Price</th>
                            <th>Entry Price</th>
                            <th>Option Target</th>
                            <th>Target View</th>
                            <th>Analysis</th>
                            <th>Hit</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Table rows will be populated dynamically using JavaScript -->
                        <tr>
                            <td>Stock Symbol Data</td>
                            <td>Price Data</td>
                            <td>Target Data</td>
                            <td>Signal Data</td>
                            <td>Recommendation Data</td>
                            <td>Option Target Data</td>
                            <td>Target View Data</td>
                            <td>
                                <button class="btn btn-primary btn-sm edit-btn">Edit</button>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-btn">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Target Hits List -->
        <div class="card">
            <div class="card-header">
                Target Hits (<a id="hitcount"></a>)
            </div>
            <div class="card-body">
                <!-- Table to display stock recommendations -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <!-- Table headers for Stock Symbol, Price, Target, Signal, Recommendation, Option Target, Target View, Edit, Delete -->
                            <th>Stock Symbol</th>
                            <th>Price</th>
                            <th>Target</th>
                            <th>Signal</th>
                            <th>Option</th>
                            <th>Option Price</th>
                            <th>Option Target</th>
                            <th>Target View</th>
                            <th>Analysis</th>
                            <th>Hit</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody1">
                        <!-- Table rows will be populated dynamically using JavaScript -->
                        <tr>
                            <td>Stock Symbol Data</td>
                            <td>Price Data</td>
                            <td>Target Data</td>
                            <td>Signal Data</td>
                            <td>Recommendation Data</td>
                            <td>Option Target Data</td>
                            <td>Target View Data</td>
                            <td>
                                <button class="btn btn-primary btn-sm edit-btn">Edit</button>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-btn">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
    
     <!-- Edit Recommendation Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="modalContent">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Recommendation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Edit Recommendation Form -->
                    <!-- Use the same form structure as the "Add Recommendation" section -->
                    <div class="card mb-4">
            <div class="card-header">
                Edit Recommendation
            </div>
            <div class="card-body">
                <!-- Add Recommendation Form -->
                <form id="editForm">
                    <!-- Form fields for Stock Symbol, Price, Target, Signal, Recommendation, Option Target, Target View -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="stockSymbol">Stock Symbol</label>
                            <input type="text" class="form-control" id="stockSymbol" name="stockSymbol" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="target">Target</label>
                            <input type="number" class="form-control" id="target" name="target" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="signal">Signal</label>
                            <select class="form-control" id="signal" name="signal" required>
                                <option value="Buy">Buy</option>
                                <option value="Sell">Sell</option>
                                <option value="Hold">Hold</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="target">Strike</label>
                            <input type="number" class="form-control" id="strike" name="strike" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="recommendation">Recommendation</label>
                            <input type="text" class="form-control" id="recommendation" name="recommendation" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="optionTarget">Entry Price</label>
                            <input type="number" class="form-control" id="entryPrice" name="entryPrice" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="optionTarget">Option Target</label>
                            <input type="number" class="form-control" id="optionTarget" name="optionTarget" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="fundNeeded">Fund Needed</label>
                            <input type="number" class="form-control" id="fundNeeded" name="fundNeeded" required>
                        </div>
                    </div>
                </form>
            </div>
        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btn_save_changes">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal -->
        <div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="responseModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="responseModalLabel">Response Message</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Response message will be shown here -->
                        <p id="responseMessage">This is the response message.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
</body>
<!-- Link Bootstrap and jQuery JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // JavaScript code to handle form submission and dynamic table population
        $(document).ready(function () {
                
                $("#tableBody").on("click", ".edit-btn", function () {
                    var id = $(this).attr("id");
                    var symbol = $(this).attr("data-symbol");
                    var recommend = $(this).attr("data-recommend");
                    
                    $("#editForm").html("Fetching data for "+symbol);
                    $("#editModal").modal("show");
                        
                    $.post("get_stock_data.php", {symbol:symbol, recommend:recommend}, function(response){
                        
                        $("#editForm").html(response);
                        $("#editModal").modal("show");
                        
                    });
                });
                
                
                $("#tableBody").on("click", ".delete-btn", function () {
                    var symbol = $(this).attr("data-symbol");
                    var recommend = $(this).attr('data-recommend');
                    
                    $.post("delete_stock_data.php", {symbol:symbol, recommend:recommend}, function(response){
                        $("#responseMessage").text(response); // Update the content of the modal with the response message
                        $("#responseModal").modal("show");
                    });
                });
        });
    </script>

<script>
    
    $(document).ready(function() {
        const eventSource = new EventSource('../sse_price.php');
        
                // Event handler for receiving SSE data from the server
                eventSource.onmessage = (event) => {
                    
                    var data = JSON.parse(event.data);
                    
                    const dataDiv = document.getElementById('tableBody');
                    dataDiv.innerHTML = data.manage2;
                    
                    const dataDiv1 = document.getElementById('tableBody1');
                    dataDiv1.innerHTML = data.manage3;
                    
                    const dataDiv2 = document.getElementById('hitcount');
                    dataDiv2.innerHTML = data.hitcount;
                    
                };
        
        
        const eventSource1 = new EventSource('../sse_kite.php');

        // Event handler for receiving SSE data from the server
        eventSource1.onmessage = (event) => {
            //const dataDiv = document.getElementById('tableBody');
            //dataDiv.innerHTML = event.data;
        };
    
    });
    
</script>

<script>
    $(document).ready(function(){
        $("#btn_add_recommend").click(function(){
            
            $("#responseMessage").text("Adding Recommendation ..."); // Update the content of the modal with the response message
            $("#responseModal").modal("show");
            
            var stockSymbol = $("#stockSymbol").val();
            var target = $("#target").val();
            var signal = $("#signal").val();
            var strike = $("#strike").val();
            var recommendation = $("#recommendation").val();
            var entryPrice = $("#entryPrice").val();
            var optionTarget = $("#optionTarget").val();
            var targetView = $("#targetView").val();
            var fundNeeded = $("#fundNeeded").val();
            var lotSize = $("#lotSize").val();
            
            $.post("add_stock_data.php", {stockSymbol:stockSymbol, target:target, signal : signal, recommendation:recommendation, entryPrice:entryPrice, optionTarget:optionTarget, fundNeeded:fundNeeded, targetView:targetView, strike:strike, lotSize:lotSize},
            function(response){
                $("#responseMessage").text(response); // Update the content of the modal with the response message
                $("#responseModal").modal("show");
            });
        });
        
        // Save Changes 
        
        $(document).on("click", "#btn_save_changes", function () {
            var symbol = $("#edit_stockSymbol").val();
            var target = $("#edit_target").val();
            var signal = $("#edit_signal").val();
            var strike = $("#edit_strike").val();
            var recommend = $("#edit_recommendation").val();
            var entry_price = $("#edit_entryPrice").val();
            var option_target = $("#edit_optionTarget").val();
            var fundNeeded = $("#edit_fundNeeded").val();
            var view = $("#edit_targetView").val();
            
            $.post("update_stock_data.php", 
            {symbol:symbol, target:target, signal:signal, recommend:recommend, entry_price:entry_price, option_target:option_target, fundNeeded:fundNeeded, view:view, strike:strike}, function(response){
                $("#responseMessage").text(response); // Update the content of the modal with the response message
                $("#responseModal").modal("show");
            });
             
        });
        
    });
    
</script>
<script>
  $(document).ready(function() {
      $("#recommendation").click(function(){
      // Read input values
      var tradingSymbol = $("#stockSymbol").val().toUpperCase();
      
      // Get today's date
      var today = new Date();

      // Extract the day of the month (1 to 31)
      var todaysDay= today.getDate();
      
      var currentYear = today.getFullYear();
    
      // Get the last two digits of the year (short year)
      var shortYear = currentYear % 100;
      
      // Array of short month names
      var shortMonthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

      // Get the index of the month in the array (0 to 11)
      var monthIndex = today.getMonth();

      // Get the short month name from the array based on the index
      var expiryMonth = shortMonthNames[monthIndex];
      
      var strike = $("#strike").val();
      
      var signal = $("#signal").val().toUpperCase();
      
      if(signal == "BUY"){
          signal = "CE";
      }else if(signal == "SELL"){
          signal = "PE";
      }

      // Concatenate the parts to form the Option Trading Symbol
      var optionTradingSymbol = tradingSymbol + shortYear + expiryMonth + strike + signal;
      
      optionTradingSymbol = optionTradingSymbol.toUpperCase();

      // Display the generated Option Trading Symbol
      $("#recommendation").val(optionTradingSymbol);    
      });
      
  });
</script>
</html>
