<?php
session_start();

if(isset($_SESSION["username"]) && isset($_GET["symbol"])){
    $symbol = $_GET["symbol"];
    $_SESSION["symbol"] = $symbol;
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
  <link rel="stylesheet" href="/premium/styles.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Premium Dashboard</a>
    <!-- Add your navigation links here -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" type="button" id="btn_home">Home</a>
      </li>
      <li class="nav-item">
        <a type="button" class="nav-link" id="btn_logout">Logout</a>
      </li>
    </ul>
  </nav>

  <!-- Search Stocks Section -->
  <!--<section class="search-stocks-section py-5">
    <div class="container">
      <h2 class="text-center mb-4">Search Stocks</h2>
      
      <p class="text-center">Enter the stock symbol or name to search for stocks.</p>
      <form class="form-inline justify-content-center">
        <input type="text" class="form-control mr-2" placeholder="Stock Symbol/Name">
        <button type="submit" class="btn btn-primary">Search</button>
      </form>
    </div>
  </section>-->

  <!-- Features Section -->
  <section class="features-section py-5">
    <div class="container">
      <h2 class="text-center mb-4"><?php echo "Analyst Recommendation of " . $symbol; ?></h2>
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
              <!--<th>Analysis</th>-->
              <th>Hit</th>
            </tr>
          </thead>
          <tbody id="sse_price1">

          </tbody>
        </table>
      </div>
    </div>
  </section>
  
  <!-- Summary Section -->
  <section class="features-section py-5">
    <div class="container">
      <h2 class="text-center mb-4"><?php echo "Technical Summary of " . $symbol; ?></h2>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="thead-dark">
            <tr>
              <th>Technical</th>    
              <th>Recommendation</th>
              <th>Buy</th>
              <th>Sell</th>
              <th>Neutral</th>
            </tr>
          </thead>
          <tbody id="sse_summary">
              
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- Advanced Technical Analysis Section -->
<section class="advanced-analysis-section py-5">
  <div class="container">
    <h2 class="text-center mb-4"><?php echo "Advanced Technical Analysis of " . $symbol; ?></h2>
    <!-- Placeholder content for advanced technical analysis -->
    <div class="text-center">
      <p>Display advanced technical analysis charts, indicators, and trends here.</p>
      <!--<img src="placeholder_chart.png" alt="Technical Analysis Chart" class="img-fluid">-->
    </div>
    <!-- Indicator Table -->
    <div class="table-responsive mt-5">
      <table class="table table-bordered table-hover">
        <thead class="thead-dark">
          <tr>
            <th>Indicator Name</th>
            <th>Recommendation</th>
            <th>Value</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody id="indicatorBody">
          <!-- Add rows for each indicator -->
          <tr>
            <td>Recommend.Other</td>
            <td><!-- Add value here --></td>
            <td>Recommendation based on other technical indicators. (Example: Buy, Sell, Hold)</td>
          </tr>
          <tr>
            <td>Recommend.All</td>
            <td><!-- Add value here --></td>
            <td>Recommendation based on all available technical indicators. (Example: Buy, Sell, Hold)</td>
          </tr>
          <tr>
            <td>Recommend.MA</td>
            <td><!-- Add value here --></td>
            <td>Recommendation based on Moving Averages. (Example: Buy, Sell, Hold)</td>
          </tr>
          <tr>
            <td>RSI</td>
            <td><!-- Add value here --></td>
            <td>Relative Strength Index (RSI) is a momentum oscillator that measures the speed and change of price movements. It ranges from 0 to 100. Values above 70 indicate overbought conditions, and values below 30 indicate oversold conditions.</td>
          </tr>
          <tr>
            <td>RSI[1]</td>
            <td><!-- Add value here --></td>
            <td>Previous period's Relative Strength Index (RSI) value.</td>
          </tr>
          <tr>
            <td>Stoch.K</td>
            <td><!-- Add value here --></td>
            <td>Stochastic oscillator %K measures where the current closing price is relative to the range of the low/high range over a specific period. It ranges from 0 to 100.</td>
          </tr>
          <tr>
            <td>Stoch.D</td>
            <td><!-- Add value here --></td>
            <td>Stochastic oscillator %D is the moving average of %K and helps to smooth out the %K values. It ranges from 0 to 100.</td>
          </tr>
          <!-- Add more rows for other indicators -->
          <!-- Add rows for each indicator -->
          <tr>
            <td>Stoch.K[1]</td>
            <td><!-- Add value here --></td>
            <td>Previous period's Stochastic oscillator %K value.</td>
          </tr>
          <tr>
            <td>Stoch.D[1]</td>
            <td><!-- Add value here --></td>
            <td>Previous period's Stochastic oscillator %D value.</td>
          </tr>
          <tr>
            <td>CCI20</td>
            <td><!-- Add value here --></td>
            <td>Commodity Channel Index (CCI) is an oscillator that measures the current price level relative to an average price level over a period of time. CCI values above +100 suggest overbought conditions, and values below -100 suggest oversold conditions.</td>
          </tr>
          <tr>
            <td>CCI20[1]</td>
            <td><!-- Add value here --></td>
            <td>Previous period's Commodity Channel Index (CCI) value.</td>
          </tr>
          <tr>
            <td>ADX</td>
            <td><!-- Add value here --></td>
            <td>Average Directional Index (ADX) is a trend strength indicator. It measures the strength of a trend and not its direction. ADX values above 25 typically indicate a strong trend.</td>
          </tr>
          <tr>
            <td>ADX+DI</td>
            <td><!-- Add value here --></td>
            <td>Positive Directional Indicator (+DI) is used alongside ADX to represent the bullish trend direction.</td>
          </tr>
          <tr>
            <td>ADX-DI</td>
            <td><!-- Add value here --></td>
            <td>Negative Directional Indicator (-DI) is used alongside ADX to represent the bearish trend direction.</td>
          </tr>
          <tr>
            <td>ADX+DI[1]</td>
            <td><!-- Add value here --></td>
            <td>Previous period's Positive Directional Indicator (+DI) value.</td>
          </tr>
          <tr>
            <td>ADX-DI[1]</td>
            <td><!-- Add value here --></td>
            <td>Previous period's Negative Directional Indicator (-DI) value.</td>
          </tr>
          <tr>
            <td>AO</td>
            <td><!-- Add value here --></td>
            <td>Awesome Oscillator (AO) is a momentum indicator that shows market momentum in relation to a recent range of high and low prices.</td>
          </tr>
          <tr>
            <td>AO[1]</td>
            <td><!-- Add value here --></td>
            <td>Previous period's Awesome Oscillator (AO) value.</td>
          </tr>
          <tr>
            <td>Mom</td>
            <td><!-- Add value here --></td>
            <td>Momentum is an oscillator that measures the rate of change in price movements over a specific period.</td>
          </tr>
          <tr>
            <td>Mom[1]</td>
            <td><!-- Add value here --></td>
            <td>Previous period's Momentum value.</td>
          </tr>
          <tr>
            <td>MACD.macd</td>
            <td><!-- Add value here --></td>
            <td>Moving Average Convergence Divergence (MACD) is a trend-following momentum indicator that shows the relationship between two moving averages of a security's price.</td>
          </tr>
          <tr>
            <td>MACD.signal</td>
            <td><!-- Add value here --></td>
            <td>MACD signal line, which is a 9-day EMA of the MACD line. It helps to identify potential points of trend reversals.</td>
          </tr>
          <tr>
            <td>Rec.Stoch.RSI</td>
            <td><!-- Add value here --></td>
            <td>Recommendation based on the Stochastic RSI values. (Example: Buy, Sell, Hold)</td>
          </tr>
          <tr>
            <td>Stoch.RSI.K</td>
            <td><!-- Add value here --></td>
            <td>Stochastic Relative Strength Index (Stoch RSI) measures the RSI relative to its high-low range over a specific period. It ranges from 0 to 100.</td>
          </tr>
          <tr>
            <td>Rec.WR</td>
            <td><!-- Add value here --></td>
            <td>Recommendation based on Williams %R values. (Example: Buy, Sell, Hold)</td>
          </tr>
          <tr>
            <td>W.R</td>
            <td><!-- Add value here --></td>
            <td>Williams %R is a momentum oscillator that measures overbought and oversold levels. It ranges from -100 to 0, with -20 being overbought and -80 being oversold.</td>
          </tr>
          <tr>
            <td>Rec.BBPower</td>
            <td><!-- Add value here --></td>
            <td>Recommendation based on Bollinger Bands Power. (Example: Buy, Sell, Hold)</td>
          </tr>
          <tr>
            <td>BBPower</td>
            <td><!-- Add value here --></td>
            <td>Bollinger Bands Power measures the distance between the Bollinger Bands. It helps identify volatility conditions.</td>
          </tr>
          <tr>
            <td>Rec.UO</td>
            <td><!-- Add value here --></td>
            <td>Recommendation based on Ultimate Oscillator values. (Example: Buy, Sell, Hold)</td>
          </tr>
          <tr>
            <td>UO</td>
            <td><!-- Add value here --></td>
            <td>Ultimate Oscillator is a momentum oscillator that combines short-term, mid-term, and long-term price cycles to generate overbought and oversold signals.</td>
          </tr>
          <tr>
            <td>close</td>
            <td><!-- Add value here --></td>
            <td>The closing price of the stock or asset.</td>
          </tr>
          <tr>
            <td>EMA5</td>
            <td><!-- Add value here --></td>
            <td>Exponential Moving Average (EMA) with a period of 5.</td>
          </tr>
          <tr>
            <td>SMA5</td>
            <td><!-- Add value here --></td>
            <td>Simple Moving Average (SMA) with a period of 5.</td>
          </tr>
          <tr>
            <td>EMA10</td>
            <td><!-- Add value here --></td>
            <td>Exponential Moving Average (EMA) with a period of 10.</td>
          </tr>
          <tr>
            <td>SMA10</td>
            <td><!-- Add value here --></td>
            <td>Simple Moving Average (SMA) with a period of 10.</td>
          </tr>
          <tr>
            <td>EMA20</td>
            <td><!-- Add value here --></td>
            <td>Exponential Moving Average (EMA) with a period of 20.</td>
          </tr>
          <tr>
            <td>SMA20</td>
            <td><!-- Add value here --></td>
            <td>Simple Moving Average (SMA) with a period of 20.</td>
          </tr>
          <tr>
            <td>EMA30</td>
            <td><!-- Add value here --></td>
            <td>Exponential Moving Average (EMA) with a period of 30.</td>
          </tr>
          <tr>
            <td>SMA30</td>
            <td><!-- Add value here --></td>
            <td>Simple Moving Average (SMA) with a period of 30.</td>
          </tr>
          <tr>
            <td>EMA50</td>
            <td><!-- Add value here --></td>
            <td>Exponential Moving Average (EMA) with a period of 50.</td>
          </tr>
          <tr>
            <td>SMA50</td>
            <td><!-- Add value here --></td>
            <td>Simple Moving Average (SMA) with a period of 50.</td>
          </tr>
          <tr>
            <td>EMA100</td>
            <td><!-- Add value here --></td>
            <td>Exponential Moving Average (EMA) with a period of 100.</td>
          </tr>
          <tr>
            <td>SMA100</td>
            <td><!-- Add value here --></td>
            <td>Simple Moving Average (SMA) with a period of 100.</td>
          </tr>
          <tr>
            <td>EMA200</td>
            <td><!-- Add value here --></td>
            <td>Exponential Moving Average (EMA) with a period of 200.</td>
          </tr>
          <tr>
            <td>SMA200</td>
            <td><!-- Add value here --></td>
            <td>Simple Moving Average (SMA) with a period of 200.</td>
          </tr>
          <tr>
            <td>Rec.Ichimoku</td>
            <td><!-- Add value here --></td>
            <td>Recommendation based on Ichimoku Cloud values. (Example: Buy, Sell, Hold)</td>
          </tr>
          <tr>
            <td>Ichimoku.BLine</td>
            <td><!-- Add value here --></td>
            <td>Ichimoku Base Line (Kijun-sen) is one of the components of the Ichimoku Cloud indicator.</td>
          </tr>
          <tr>
            <td>Rec.VWMA</td>
            <td><!-- Add value here --></td>
            <td>Recommendation based on Volume Weighted Moving Average (VWMA). (Example: Buy, Sell, Hold)</td>
          </tr>
          <tr>
            <td>VWMA</td>
            <td><!-- Add value here --></td>
            <td>Volume Weighted Moving Average (VWMA) calculates the average price weighted by volume over a specified period.</td>
          </tr>
          <tr>
            <td>Rec.HullMA9</td>
            <td><!-- Add value here --></td>
            <td>Recommendation based on Hull Moving Average (Hull MA) values. (Example: Buy, Sell, Hold)</td>
          </tr>
          <tr>
            <td>HullMA9</td>
            <td><!-- Add value here --></td>
            <td>Hull Moving Average (Hull MA) is a type of moving average that aims to reduce lag and improve smoothing.</td>
          </tr>
          <tr>
            <td>Pivot.M.Classic.S3</td>
            <td><!-- Add value here --></td>
            <td>Support 3 (S3) is a support level calculated using Classic Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Classic.S2</td>
            <td><!-- Add value here --></td>
            <td>Support 2 (S2) is a support level calculated using Classic Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Classic.S1</td>
            <td><!-- Add value here --></td>
            <td>Support 1 (S1) is a support level calculated using Classic Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Classic.Middle</td>
            <td><!-- Add value here --></td>
            <td>Middle Pivot Point (PP) is the average of the high, low, and close prices from the previous period.</td>
          </tr>
          <tr>
            <td>Pivot.M.Classic.R1</td>
            <td><!-- Add value here --></td>
            <td>Resistance 1 (R1) is a resistance level calculated using Classic Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Classic.R2</td>
            <td><!-- Add value here --></td>
            <td>Resistance 2 (R2) is a resistance level calculated using Classic Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Classic.R3</td>
            <td><!-- Add value here --></td>
            <td>Resistance 3 (R3) is a resistance level calculated using Classic Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Fibonacci.S3</td>
            <td><!-- Add value here --></td>
            <td>Support 3 (S3) is a support level calculated using Fibonacci Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Fibonacci.S2</td>
            <td><!-- Add value here --></td>
            <td>Support 2 (S2) is a support level calculated using Fibonacci Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Fibonacci.S1</td>
            <td><!-- Add value here --></td>
            <td>Support 1 (S1) is a support level calculated using Fibonacci Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Fibonacci.Middle</td>
            <td><!-- Add value here --></td>
            <td>Middle Pivot Point (PP) is the average of the high, low, and close prices from the previous period using Fibonacci Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Fibonacci.R1</td>
            <td><!-- Add value here --></td>
            <td>Resistance 1 (R1) is a resistance level calculated using Fibonacci Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Fibonacci.R2</td>
            <td><!-- Add value here --></td>
            <td>Resistance 2 (R2) is a resistance level calculated using Fibonacci Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Fibonacci.R3</td>
            <td><!-- Add value here --></td>
            <td>Resistance 3 (R3) is a resistance level calculated using Fibonacci Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Camarilla.S3</td>
            <td><!-- Add value here --></td>
            <td>Support 3 (S3) is a support level calculated using Camarilla Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Camarilla.S2</td>
            <td><!-- Add value here --></td>
            <td>Support 2 (S2) is a support level calculated using Camarilla Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Camarilla.S1</td>
            <td><!-- Add value here --></td>
            <td>Support 1 (S1) is a support level calculated using Camarilla Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Camarilla.Middle</td>
            <td><!-- Add value here --></td>
            <td>Middle Pivot Point (PP) is the average of the high, low, and close prices from the previous period using Camarilla Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Camarilla.R1</td>
            <td><!-- Add value here --></td>
            <td>Resistance 1 (R1) is a resistance level calculated using Camarilla Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Camarilla.R2</td>
            <td><!-- Add value here --></td>
            <td>Resistance 2 (R2) is a resistance level calculated using Camarilla Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Camarilla.R3</td>
            <td><!-- Add value here --></td>
            <td>Resistance 3 (R3) is a resistance level calculated using Camarilla Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Woodie.S3</td>
            <td><!-- Add value here --></td>
            <td>Support 3 (S3) is a support level calculated using Woodie's Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Woodie.S2</td>
            <td><!-- Add value here --></td>
            <td>Support 2 (S2) is a support level calculated using Woodie's Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Woodie.S1</td>
            <td><!-- Add value here --></td>
            <td>Support 1 (S1) is a support level calculated using Woodie's Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Woodie.Middle</td>
            <td><!-- Add value here --></td>
            <td>Middle Pivot Point (PP) is the average of the high, low, and close prices from the previous period using Woodie's Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Woodie.R1</td>
            <td><!-- Add value here --></td>
            <td>Resistance 1 (R1) is a resistance level calculated using Woodie's Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Woodie.R2</td>
            <td><!-- Add value here --></td>
            <td>Resistance 2 (R2) is a resistance level calculated using Woodie's Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Woodie.R3</td>
            <td><!-- Add value here --></td>
            <td>Resistance 3 (R3) is a resistance level calculated using Woodie's Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Demark.S1</td>
            <td><!-- Add value here --></td>
            <td>Support 1 (S1) is a support level calculated using DeMark's Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Demark.Middle</td>
            <td><!-- Add value here --></td>
            <td>Middle Pivot Point (PP) is the average of the high, low, and close prices from the previous period using DeMark's Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>Pivot.M.Demark.R1</td>
            <td><!-- Add value here --></td>
            <td>Resistance 1 (R1) is a resistance level calculated using DeMark's Pivot Points methodology.</td>
          </tr>
          <tr>
            <td>open</td>
            <td><!-- Add value here --></td>
            <td>The opening price of the stock or asset.</td>
          </tr>
          <tr>
            <td>P.SAR</td>
            <td><!-- Add value here --></td>
            <td>Parabolic Stop and Reverse (SAR) is a trend-following indicator used to set trailing stop-loss levels.</td>
          </tr>
          <tr>
            <td>BB.lower</td>
            <td><!-- Add value here --></td>
            <td>Lower Bollinger Band (BB) is a volatility band that varies above and below a simple moving average based on the standard deviation.</td>
          </tr>
          <tr>
            <td>BB.upper</td>
            <td><!-- Add value here --></td>
            <td>Upper Bollinger Band (BB) is a volatility band that varies above and below a simple moving average based on the standard deviation.</td>
          </tr>
          <tr>
            <td>AO[2]</td>
            <td><!-- Add value here --></td>
            <td>Awesome Oscillator (AO) value from two periods ago.</td>
          </tr>
          <tr>
            <td>volume</td>
            <td><!-- Add value here --></td>
            <td>The total number of shares or contracts traded during a given period.</td>
          </tr>
          <tr>
            <td>change</td>
            <td><!-- Add value here --></td>
            <td>The price change or the difference between the current price and the previous period's price.</td>
          </tr>
          <tr>
            <td>low</td>
            <td><!-- Add value here --></td>
            <td>The lowest price of the stock or asset during the given period.</td>
          </tr>
          <tr>
            <td>high</td>
            <td><!-- Add value here --></td>
            <td>The highest price of the stock or asset during the given period.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

  <!-- Real Time Market News Section -->
  <!--<section class="real-time-news-section bg-light py-5">
    <div class="container">
      <h2 class="text-center mb-4">Real Time Market News</h2>
      
      <div class="row">
        <div class="col-md-6">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title">Market News 1</h5>
              <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut suscipit, lorem nec commodo aliquet.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title">Market News 2</h5>
              <p class="card-text">Praesent et lectus ac nulla malesuada pellentesque. Fusce convallis odio id finibus commodo.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section> -->

  <!-- Add Bootstrap JS and jQuery scripts here -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
  <script>
    
    $(document).ready(function() {
        
        function setCellTextColor() {
        $('td').each(function() {
          var text = $(this).text().trim();
          if (text === 'SELL' || text === 'STRONG_SELL') {
            $(this).css('color', '#df514c');
          } else if (text === 'BUY' || text === 'STRONG_BUY') {
            $(this).css('color', '#4caf50');
          }
        });
      }
        
        const eventSource2 = new EventSource('sse_price_symbol.php');

        // Event handler for receiving SSE data from the server
        eventSource2.onmessage = (event) => {
            
            const data = JSON.parse(event.data);

            // Access the values using keys
            var price_data = data.price;
            var indicator_data = data.indicator;
            var summary_data = data.summary;
            
            var dataDiv = document.getElementById('sse_price1');
            dataDiv.innerHTML = price_data;
            
            var dataDiv1 = document.getElementById('indicatorBody');
            dataDiv1.innerHTML = indicator_data;
            
            var dataDiv2 = document.getElementById('sse_summary');
            dataDiv2.innerHTML = summary_data;
            
            setCellTextColor();
            
        };
        
      $("#btn_home").click(function () {
          
        window.location.href = "/premium-user/index.php";
      });
        
      $("#btn_logout").click(function(){
          
        
        window.location.href = "/logout.php"; 
      });
      
    });
    
</script>

<script type="text/javascript">
  window._mfq = window._mfq || [];
  (function() {
    var mf = document.createElement("script");
    mf.type = "text/javascript"; mf.defer = true;
    mf.src = "//cdn.mouseflow.com/projects/d09b3e74-b799-46c7-b56b-2cd4b0c94b69.js";
    document.getElementsByTagName("head")[0].appendChild(mf);
  })();
</script>

</body>
</html>
