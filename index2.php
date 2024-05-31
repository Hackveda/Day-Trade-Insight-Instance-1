<?php
session_start();
include("db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Intraday Stocks | Day Trade Insight</title>
  <!-- Add Bootstrap CSS link here -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Add your custom CSS link here -->
  <link rel="stylesheet" href="styles.css">
  <style>
      .video-item {
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .video-embed {
      width: 100%;
      height: 0;
      padding-bottom: 56.25%;
      position: relative;
    }

    .video-embed iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border: none;
    }

    .video-details {
      padding: 10px;
    }

    .video-title {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .video-presenter {
      font-size: 14px;
      color: #555;
      margin-bottom: 10px;
    }

    .video-date {
      font-size: 14px;
      color: #555;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Day Trade Insight</a>
    <!-- Add your navigation links here -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="/">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/manage">Advisor</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="login.php">Login</a>
      </li>
    </ul>
  </nav>

  <!-- Hero Section -->
  <header class="hero-section text-center py-5">
    <div class="container">
      <h1 class="display-4">Real-Time Intraday Stocks</h1>
      <p class="lead">Get live updates and analysis of stocks for today's trading session.</p>
      <!-- Add call-to-action buttons here -->
      <a class="btn btn-primary btn-lg" href="https://rzp.io/l/3XQXcZv">Upgrade to Premium</a>
      
    </div>
  </header>
  
  
  
  <section class="slider-section py-5">
    <div class="container">
        
        <?php
        
        // Get Status Updates
    
    $sql7 = "SELECT * from promotions ORDER BY ID DESC";
    $con1->set_charset('utf8mb4');
    $result7 = $con1->query($sql7);
    $count = $result7->num_rows;
    if($result7->num_rows > 0){
        $status_counter = 0;
        
        echo '<div id="status-slider" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">';
        
        while($row = $result7->fetch_assoc()){
            
            if($status_counter == 0){
                echo '<li data-target="#status-slider" data-slide-to="'.$status_counter.'" class="active"></li>';
            }else{
                echo '<li data-target="#status-slider" data-slide-to="'.$status_counter.'"></li>';
            }
            
            $status_counter = $status_counter + 1;
            
        }
        
        echo '</ol><div class="carousel-inner">';
        
        $sql8 = "SELECT * from promotions ORDER BY ID DESC";
        $con1->set_charset('utf8mb4');
        $result8 = $con1->query($sql8);
        
         $status_counter = 0;
         
        while($row = $result8->fetch_assoc()){
            
            $status = $row["Status"];
            
            if($status_counter == 0){
                echo '<div class="carousel-item active"><div>
              <p>'.$status.'</p></div></div>';
            }else{
                echo '<div class="carousel-item"><div>
              <p>'.$status.'</p></div></div>';
            }
              
               $status_counter =  $status_counter + 1;
            
        }
        
        echo '</div>
        <!--<a class="carousel-control-prev" href="#status-slider" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#status-slider" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>-->
      </div>';
        
    }else{
        
    }
        
        ?>
      
    </div>
  </section>

  <!-- Features Section -->
  <section class="features-section py-5">
    <div class="container">
      <h2 class="text-center mb-4">Intraday Stocks for Today</h2>
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
              <th>Analysis</th>
              <th>Hit</th>
              <th>Advisor</th>
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

 <!-- Main Content Section -->
  <section class="main-content">
    <div class="container">
      <h1 class="text-center mb-4">Day Trade Insight Video Tutorials</h1>

      <!-- Video Presentations Grid -->
      <div id="video_data" class="row">
          
          <?php
          $sql1 = "SELECT * FROM `videos` ORDER BY ID DESC limit 10";
          $result1 = $con1->query($sql1);
          if($result1->num_rows > 0){
              while($row = $result1->fetch_assoc()){
                  $ppt_link = $row["Url"];
                  $ppt_title = ucwords($row["Title"]);
                  
                  $url = $ppt_link;
                    
                    // Extract video ID from the YouTube URL
                    $videoId = substr($url, strrpos($url, '/') + 1);
                    
                    // Extract time value from the YouTube URL
                    $timeStart = null;
                    $timePosition = strpos($url, '?t=');
                    if ($timePosition !== false) {
                        $timeStart = substr($url, $timePosition + 3);
                    } else {
                        $timePosition = strpos($url, '&t=');
                        if ($timePosition !== false) {
                            $timeStart = substr($url, $timePosition + 3);
                        }
                    }
                    
                    // Create modified URL with embed format and start time parameter
                    $modifiedUrl = 'https://www.youtube.com/embed/' . $videoId;
                    if ($timeStart !== null) {
                        $modifiedUrl .= '?start=' . $timeStart;
                    }
                    
                    // Output the modified URL
                    $ppt_link = $modifiedUrl;
                  
                  echo '<div class="col-lg-6 col-md-6 col-sm-12 mb-4">
          <div class="video-item">
            <div class="video-embed">
              <iframe src="'.$ppt_link.'" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="video-details">
              <h2 class="video-title">'.$ppt_title.'</h2>
            </div>
          </div>
        </div>';
              }
          }else{
              echo '<div class="col-lg-6 col-md-6 col-sm-12 mb-4">
          <div class="video-item">
            <div class="video-details">
              <h2 class="video-title">Nop Video Found</h2>
            </div>
          </div>
        </div>';
          }
          ?>
          
        
        <!-- Add more video items here -->
      </div>
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
              <th>Target</th>
              <th>Signal</th>
              <th>Option</th>
              <th>Option Target</th>
              <th>Hit</th>
              <th>Advisor</th>
            </tr>
          </thead>
          <tbody id="sse_price1">

          </tbody>
        </table>
      </div>
    </div>
  </section>
  
  <section class="brokers py-5">
    <div class="container">
        <h2 class="text-center mb-4">Advisors on Day Trade Insight</h2>
        <div class="row">
            
            <?php
            $sql2 = "SELECT * from brokers ORDER BY ID DESC";
            $result2 = $con1->query($sql2);
            if($result2->num_rows > 0){
                while($row = $result2->fetch_assoc()){
                    $id = $row["ID"];
                    $name = $row["BrokerName"];
                    $desc = $row["BrokerDesc"];
                    $image = $row["BrokerImage"];
                    
                    if(strcmp($image, "") == 0){
                        $image = 'https://img.icons8.com/external-flaticons-flat-flat-icons/64/external-broker-investing-flaticons-flat-flat-icons.png';
                    }
                    
                    echo '<div class="col-md-4 mb-4">
                <div class="card">

                    <div class="card-body">
                        <img width="64" height="64" src="'.$image.'" alt="external-broker-investing-flaticons-flat-flat-icons"/>
                        <h5 class="card-title">'.$name.'</h5>
                        <p class="card-text">
                            '.$desc.'
                        </p>
                        <a class="btn btn-primary" href="profile.php?id='.$id.'">View Profile</a>
                    </div>
                </div>
            </div>';
                }
            }else{
                echo '<div class="col-md-4 mb-4">
                <div class="card">

                    <div class="card-body">
                        <img width="64" height="64" src="https://img.icons8.com/external-flaticons-flat-flat-icons/64/external-broker-investing-flaticons-flat-flat-icons.png" alt="external-broker-investing-flaticons-flat-flat-icons"/>
                        <h5 class="card-title">XYZ Stock Brokers</h5>
                        <p class="card-text">
                            XYZ Stock Brokers is a well-established brokerage firm known for its user-friendly platform and excellent customer support. They offer a wide range of trading instruments and account types suitable for both beginners and experienced traders.
                        </p>
                        <a class="btn btn-primary" href="profile.php">View Profile</a>
                    </div>
                </div>
            </div>';
            }
            
            ?>
        </div>
    </div>
</section>

<!-- Investor P&L Calculator Section -->
  <section class="investor-pl-calculator py-5">
    <div class="container">
      <h2 class="text-center mb-4">Investor Fixed Deposit Calculator</h2>
      <div class="row">
        <div class="col-md-12 mb-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Calculate Your Investment</h5>
              <form id="investment-form">
                <div class="form-group">
                  <label for="investmentAmount">Initial Investment Amount</label>
                  <input type="number" class="form-control" id="investmentAmount" placeholder="Enter amount (INR)">
                </div>
                <button type="button" class="btn btn-primary" id="calculateInvestment">Calculate</button>
              </form>
              <div id="investmentResults" class="mt-3"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <section class="premium-signup-section bg-light py-5">
    <div class="container">
      <h2 class="text-center mb-4">Returns Comparisons</h2>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <!-- Table header -->
          <thead class="thead-dark">
            <tr>
              <th>Investment Options</th>
              <th>Average Returns</th>
            </tr>
          </thead>
          <!-- Table body -->
          <tbody>
            <tr>
              <td>Day Trade Insight</td>
              <td><span class="text-muted">21%</span></td>
            </tr>
            <tr>
              <td>Private Equity</td>
              <td><span class="text-muted">15-20% or higher</span></td>
            </tr>
            <tr>
              <td>Venture Capital</td>
              <td><span class="text-muted">Highly Variable, 100% or higher</span></td>
            </tr>
            <tr>
                <tr>
              <td>Hedge Funds</td>
              <td><span class="text-muted">15% or higher</span></td>
            </tr>
            <tr>
              <td>Equity (Stocks)</td>
              <td><span class="text-muted">12-15%</span></td>
            </tr>
              <tr>
              <td>Mutual Funds (Equity)</td>
              <td><span class="text-muted">6-8%</span></td>
              
            </tr>
            <tr>
              <td>Mutual Funds (Debt)</td>
              <td><span class="text-muted">6-8%</span></td>
              
            </tr>
            <tr>
              <td>Public Provident Fund (PPF)</td>
              <td><span class="text-muted">Around 7.1%</span></td>
            </tr>
            
            <tr>
              <td>National Savings Certificate (NSC)</td>
              <td><span class="text-muted">Around 6.8%</span></td>
              
            </tr>
            <tr>
              <td>Bank Fixed Deposits</td>
              <td><span class="text-muted">3-6%</span></td>
            </tr>
            <tr>
              <td>Real Estate</td>
              <td><span class="text-muted">8-10%</span></td>
            </tr>
            <tr>
              <td>Government Bonds</td>
              <td><span class="text-muted">6-7%</span></td>
            </tr>
            <tr>
              <td>Corporate Bonds</td>
              <td><span class="text-muted">7-9%</span></td>
            </tr>
            <tr>
              <td>Real Estate Investment Trusts (REITs)</td>
              <td><span class="text-muted">7-9%</span></td>
            </tr>
          </tbody>
        </table>
      </div>
      <p class="text-center mt-4">
        <!-- Add a call-to-action button for premium signup -->
        <a class="btn btn-primary btn-lg" href="https://rzp.io/l/3XQXcZv">Upgrade to Premium</a>
      </p>
    </div>
  </section>
  
  <!-- Premium Signup Section -->
  <section class="premium-signup-section bg-light py-5">
    <div class="container">
      <h2 class="text-center mb-4">Upgrade to Premium for Extra Benefits</h2>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <!-- Table header -->
          <thead class="thead-dark">
            <tr>
              <th>Features</th>
              <th>Free Plan</th>
              <th>Premium Plan</th>
            </tr>
          </thead>
          <!-- Table body -->
          <tbody>
              <tr>
              <td>Stock Recommendations</td>
              <td><span class="text-muted">1-2</span></td>
              <td>Unlimited</td>
            </tr>
              <tr>
              <td>Advanced Technical Analysis</td>
              <td><span class="text-muted">✗</span></td>
              <td>✓</td>
            </tr>
            <tr>
              <td>Technical Indicators</td>
              <td><span class="text-muted">✗</span></td>
              <td>✓</td>
            </tr>
            <tr>
              <td>Historical Data</td>
              <td><span class="text-muted">✗</span></td>
              <td>✓</td>
            </tr>
            
            <tr>
              <td>Stock Watchlists</td>
              <td><span class="text-muted">✗</span></td>
              <td>✓</td>
            </tr>
            <tr>
              <td>User Alerts</td>
              <td><span class="text-muted">✗</span></td>
              <td>✓</td>
            </tr>
            <tr>
              <td>Real-Time Market News</td>
              <td><span class="text-muted">✗</span></td>
              <td>✓</td>
            </tr>
            <tr>
              <td>Email Support</td>
              <td><span class="text-muted">✗</span></td>
              <td>✓</td>
            </tr>
          </tbody>
        </table>
      </div>
      <p class="text-center mt-4">
        <!-- Add a call-to-action button for premium signup -->
        <a class="btn btn-primary btn-lg" href="https://rzp.io/l/3XQXcZv">Upgrade to Premium</a>
      </p>
    </div>
  </section>
  
  <!-- About Section (You can add more sections as needed) -->
  <section class="about py-5 bg-light">
    <div class="container">
      <h2 class="text-center mb-4">About Day Trade Insight</h2>
      <p>
        Day Trade Insight is a platform dedicated to providing day traders with valuable insights and recommendations to optimize their trading strategies. Our team of expert analysts leverages cutting-edge technology and data-driven analysis to identify potential opportunities in the stock market.
      </p>
      <p>
        Whether you are a seasoned day trader or a beginner looking to enhance your trading skills, Day Trade Insight offers a range of tools and resources to help you make informed trading decisions.
      </p>
    </div>
  </section>

  <!-- Disclaimer Section -->
    <section class="disclaimer py-4">
        <div class="container">
            <p class="text-center">
                Disclaimer: Trading in the financial markets involves substantial risk and may not be suitable for all investors. Past performance is not indicative of future results. Please carefully consider your financial situation and risk tolerance before trading. Day Trade Insight does not provide financial advice and is not responsible for any trading decisions made by users based on the information provided on this website.
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-section bg-dark text-center text-light py-3">
        <div class="container">
            <p>&copy; 2023 Day Trade Insight. All rights reserved.</p>
        </div>
    </footer>

  <!-- Add Bootstrap JS and jQuery scripts here -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 
</body>

<script>
    
    $(document).ready(function() {
        
        const eventSource = new EventSource('sse_price.php');

        // Event handler for receiving SSE data from the server
        eventSource.onmessage = (event) => {
            
            const data = JSON.parse(event.data);
            
            const dataDiv = document.getElementById('sse_price');
            dataDiv.innerHTML = data.free;
            
            const dataDiv1 = document.getElementById('sse_price1');
            dataDiv1.innerHTML = data.free1;
            
            const dataDiv2 = document.getElementById('hitcount');
            dataDiv2.innerHTML = data.hitcount;
            
            const dataDiv3 = document.getElementById('plot');
            dataDiv3.innerHTML = data.plots;
            
        };

    const eventSource1 = new EventSource('sse_kite.php');

        // Event handler for receiving SSE data from the server
        eventSource1.onmessage = (event1) => {
            //const dataDiv = document.getElementById('sse_price');
            //dataDiv.innerHTML = event.data;
        };
        

      // Bind the beforeunload event to close the SSE connection
      $(window).on('beforeunload', function() {
        if (eventSource) {
        eventSource.close(); // Close the SSE connection if it exists
      }
      
      if (eventSource1) {
        eventSource1.close(); // Close the SSE connection if it exists
      }
      });
    });
    
</script>

<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/58e08d0bf7bbaa72709c3b48/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>

<!-- ... (Remaining code remains unchanged) ... -->

<script>
  // Investment Calculator
  document.getElementById('calculateInvestment').addEventListener('click', function () {
    const investmentAmount = parseFloat(document.getElementById('investmentAmount').value);
    if (isNaN(investmentAmount)) {
      alert('Please enter a valid amount');
      return;
    }

    const gstDeduction = investmentAmount * 0.18;
    const afterGst = investmentAmount - gstDeduction;
    const platformFee = afterGst * 0.10;
    const tradableAmount = afterGst - platformFee;
    const roi = 0.10;
    const withdrawableAmount = (afterGst - platformFee) * roi;
    const totalprofit = (withdrawableAmount * 18) - investmentAmount;
    const totalreturns = investmentAmount + totalprofit;

    /*
    To Be Calculated and Included
    <p><strong>Goods &amp; Services Tax (18%):</strong> - Rs ${gstDeduction.toFixed(2)}</p>
      <p><strong>Amount After GST:</strong> Rs ${afterGst.toFixed(2)}</p>
      <p><strong>Advisor Charges (10%):</strong> - Rs ${platformFee.toFixed(2)}</p>
      <p><strong>Trading Amount:</strong> Rs ${tradableAmount.toFixed(2)}</p>
    */
    
    const results = `
      <p><strong>Initial Investment:</strong> Rs ${investmentAmount.toFixed(2)}</p>
      <p><strong>Withdrawable Amount (Monthly):</strong> Rs ${withdrawableAmount.toFixed(2)}</p>
      <p><strong>Total Profit Earnings:</strong> Rs ${totalprofit.toFixed(2)}</p>
      <p><strong>Total Returns:</strong> Rs ${totalreturns.toFixed(2)} (Investment Amount + Profit)</p>
      <p><strong>Investment Duration:</strong> 1 Year 6 Months</p>
    `;

    document.getElementById('investmentResults').innerHTML = results;
  });
</script>

</html>
