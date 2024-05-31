<?php
session_start();
include("../db.php");
if(isset($_SESSION["username"])){
    // Do nothing
}else{
    header("Location: ../index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Premium User Dashboard</title>
  <!-- Add Bootstrap CSS link here -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Add your custom CSS link here -->
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <img src="/daytradeinsight-logo.png" alt="DayTradeInsight Logo" style="height: 50px; padding-left:10px; padding-right:10px;">
    <a class="navbar-brand" href="#">Premium Dashboard</a>
    <!-- Add your navigation links here -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="/premium">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="orders.php">Positions</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/brokers.php">Advisors</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="wallet.php">Wallet</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/logout.php">Logout</a>
      </li>
    </ul>
  </nav>

  <!-- Search Stocks Section -->
  <!--<section class="search-stocks-section py-5">
    <div class="container">
      <h2 class="text-center mb-4">Search Stocks</h2>
     
      <p class="text-center">Enter the stock symbol or name to search for stocks.</p>
      <form class="form-inline justify-content-center">
        <input type="text" class="form-control mr-2" placeholder="Stock Symbol">
        <button type="submit" class="btn btn-primary">Search</button>
      </form>
    </div>
  </section> -->

  <!-- Features Section -->
  <section class="features-section py-5">
    <div class="container">
      <h2 class="text-center mb-4">Intraday Stocks for Today</h2>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="thead-dark">
            <tr>
                <th>Date</th>
              <th>Stock Symbol</th>
              <th>Price</th>
              <th>Target</th>
              <th>Signal</th>
              <th>Option</th>
              <th>Option Price</th>
              <th>Entry Price</th>
              <th>Option Target</th>
              <th>Target View</th>
              <th>Buy</th>
              <th>Sell</th>
            </tr>
          </thead>
          <tbody id="sse_price">

          </tbody>
        </table>
      </div>
    </div>
  </section>
  
  <!-- Stock Chart Images Section -->
<section class="stock-chart-images py-5">
  <div class="container">
    <h2 class="text-center mb-4">Open Interest Change Screener</h2>
    <div class="row" id="plot">
      
      <!-- Add more images here -->
    </div>
  </div>
</section>

<!-- News Table -->
<section class="features-section py-5">
    <div class="container">
      <h2 class="text-center mb-4">Latest Stock News</h2>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="thead-dark">
            <tr>
                <th>Date</th>
                <th>Symbol</th>
              <th>News</th>
            </tr>
          </thead>
          <tbody id="sse_news">

          </tbody>
        </table>
      </div>
      <p class="text-center mt-4">
        <a href="/search-news.php"><button type="button" class="btn btn-primary">Search Stock News</button></a>
      </p>
    </div>
    
  </section>
  
  <section class="features-section py-5">
    <div class="container">
      <h2 class="text-center mb-4">Target Hits (<a id="hitcount"></a>)</h2>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="thead-dark">
            <tr>
              <th>Stock Symbol</th>
              <th>Price</th>
              <th>Target</th>
              <th>Signal</th>
              <th>Option</th>
              <th>Option Price</th>
              <th>Option Target</th>
              <th>Target View</th>
              <th>Hit</th>
              <th>Buy</th>
              <th>Sell</th>
              <th>Advisor</th>
            </tr>
          </thead>
          <tbody id="sse_price1">

          </tbody>
        </table>
      </div>
    </div>
  </section>
  
  <!-- Edit Recommendation Modal -->
    <div class="modal fade" id="buyModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="modalContent">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Buy</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Edit Recommendation Form -->
                    <!-- Use the same form structure as the "Add Recommendation" section -->
                    <div class="card mb-4">
            <div class="card-header">
                Buy Stock
            </div>
            <div class="card-body">
                <!-- Add Recommendation Form -->
                <form id="buyForm">
                    <!-- Form fields for Stock Symbol, Price, Target, Signal, Recommendation, Option Target, Target View -->
                    
                </form>
            </div>
        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-buy" id="btn_buy">Buy</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="sellModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="modalContent">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Sell</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Edit Recommendation Form -->
                    <!-- Use the same form structure as the "Add Recommendation" section -->
                    <div class="card mb-4">
            <div class="card-header">
                Sell Stock
            </div>
            <div class="card-body">
                <!-- Add Recommendation Form -->
                <form id="sellForm">
                    
                </form>
            </div>
        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-sell" id="btn_sell">Sell</button>
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

  <!-- Add Bootstrap JS and jQuery scripts here -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<script>
        
</script>

<script>
        
</script>

<script>
    
    $(document).ready(function() {
        
    const eventSource = new EventSource('../sse_price.php');

        // Event handler for receiving SSE data from the server
        eventSource.onmessage = (event) => {
            const data = JSON.parse(event.data);
            
            const dataDiv = document.getElementById('sse_price');
            dataDiv.innerHTML = data.premium;
            
            const dataDiv1 = document.getElementById('sse_price1');
            dataDiv1.innerHTML = data.premium1;
            
            const dataDiv2 = document.getElementById('hitcount');
            dataDiv2.innerHTML = data.hitcount;
            
            const dataDiv3 = document.getElementById('plot');
            dataDiv3.innerHTML = data.plots;
            
            const dataDiv4 = document.getElementById('sse_news');
            dataDiv4.innerHTML = data.tweets;
        };

    const eventSource1 = new EventSource('../sse_kite.php');

        // Event handler for receiving SSE data from the server
        eventSource1.onmessage = (event) => {
            //const dataDiv = document.getElementById('sse_price');
            //dataDiv.innerHTML = event.data;
        };
    
    $(document).on("click", ".btn", function () {
        var symbol = $(this).attr("data-symbol");
        var action = $(this).attr("data-action");
        
        if(action == "analysis"){
        window.location.href = "/analysis/?symbol="+symbol;
        //window.open("/analysis/?symbol="+symbol);
        }else if(action == "Buy"){
            
            var id = $(this).attr("id");
                    var symbol = $(this).attr("data-symbol");
                    var recommend = $(this).attr("data-recommend");
                    
                    $("#buyForm").html("Fetching data for "+symbol);
                    $("#buyModal").modal("show");
                        
                    $.post("get_stock_data.php", {symbol:symbol, recommend:recommend}, function(response){
                        
                        $("#buyForm").html(response);
                        $("#buyModal").modal("show");
                        
                    });
            
        }else if(action == "Sell"){
            var id = $(this).attr("id");
                    var symbol = $(this).attr("data-symbol");
                    var recommend = $(this).attr("data-recommend");
                    
                    $("#sellForm").html("Fetching data for "+symbol);
                    $("#sellModal").modal("show");
                        
                    $.post("get_order_data.php", {symbol:symbol, recommend:recommend}, function(response){
                        
                        $("#sellForm").html(response);
                        $("#sellModal").modal("show");
                        
                    });
        }
    });
    
    $(document).on('change', '.qty', function(){
        
       var qty = $(this).val(); 
       var price = $("#price").val();
       
       var margin = formatPriceWithCommas(qty * price);
       $("#margin").html(margin);
       
    });
    
    $(document).on('change', '.op-qty', function(){
        
       var qty = $(this).val(); 
       var lotsize = $("#lotSize").val();
       var qty = qty * lotsize;
       var price = $("#op_price").val();
       
       var margin = formatPriceWithCommas(qty * price);
       $("#op_margin").html(margin);
       
    });
    
    function formatPriceWithCommas(price) {
      // Convert the number to a string to add commas
      var parts = price.toString().split(".");
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      return parts.join(".");
    }

    
    $("#btn_buy").click(function(){
       var qty = $("#qty").val(); 
       var op_qty = $("#op_qty").val();
       
       if(qty != "" && qty > 0){
           var symbol = $("#stockSymbol").val();
           var price = $("#price").val();
           var qty = $("#qty").val();
           var margin = price * qty;
           
           $.post("buy_order.php", {symbol:symbol, price:price, qty:qty, margin:margin}, function(response){
               
               $("#responseMessage").html(response);
               $("#responseModal").modal('show');
               
           });
           
       }
       
       if(op_qty != "" && op_qty > 0){
           var symbol = $("#recommend").val();
           var price = $("#op_price").val();
           var qty = $("#op_qty").val();
           var lotsize = $("#lotSize").val();
           var margin = price * qty;
           
           $.post("buy_op_order.php", {symbol:symbol, price:price, qty:qty, margin:margin, lotsize:lotsize}, function(response){
               
               $("#responseMessage").html(response);
               $("#responseModal").modal('show');
               
           });
       }
       
    });
    
    $("#btn_sell").click(function(){
       var qty = $("#qty").val(); 
       var op_qty = $("#op_qty").val();
       
       if(qty != "" && qty > 0){
           var symbol = $("#stockSymbol").val();
           var price = $("#price").val();
           var qty = $("#qty").val();
           var margin = price * qty;
           
           $.post("sell_order.php", {symbol:symbol, price:price, qty:qty, margin:margin}, function(response){
               
               $("#responseMessage").html(response);
               $("#responseModal").modal('show');
               
           });
           
       }
       
       if(op_qty != "" && op_qty > 0){
           var symbol = $("#recommend").val();
           var price = $("#op_price").val();
           var qty = $("#op_qty").val();
           var lotsize = $("#lotSize").val();
           qty = qty * lotsize;
           var margin = price * qty;
           
           $.post("sell_op_order.php", {symbol:symbol, price:price, qty:qty, margin:margin, lotsize:lotsize}, function(response){
               
               $("#responseMessage").html(response);
               $("#responseModal").modal('show');
               
           });
       }
       
    });
    
    });
</script>

</html>
