<?php
session_start();
include("../db.php");
if(isset($_SESSION["username"])){
    $username = $_SESSION["username"];
    $sql = "SELECT Name from login where Username like '".$username."'";
    $result = $con1->query($sql);
    $name = "";
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $name = $row["Name"];
        }
    }else{
        
    }
}else{
    header("Location: /login.php");
}
session_write_close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Wallet</title>
  <!-- Add Bootstrap CSS link here -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Add your custom CSS link here -->
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="#"><?php echo $name; ?> | Wallet</a>
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

  <?php
  
  function formatAmountToINR($amount) {
    // Using number_format to format the amount with two decimal places and comma as thousand separator
    return '₹' . number_format($amount, 2, '.', ',');
    }
  
  $sql1 = "SELECT * from subscribe where username like '".$username."'";
  $result1 = $con1->query($sql1);
  if($result1->num_rows > 0){
      while($row = $result1->fetch_assoc()){
          $advisor = $row["advisor"];
          $plantype = $row["plantype"];
          $amount = $row["amount"];
          
          $sql2 = "SELECT * from brokers where Username like '".$advisor."'";
          $result2 = $con1->query($sql2);
          if($result2->num_rows > 0){
              while($row = $result2->fetch_assoc()){
                  $advisor_name = $row["BrokerName"];
              }
          }else{
              
          }
          
          $sql3 = "SELECT * from positions where Username like '".$advisor."'";
          $result3 = $con1->query($sql3);
          if($result3->num_rows > 0){
              while($row = $result3->fetch_assoc()){
                  $pl = $row["PL"];
                  $invested = $row["Invested"];
              }
          }else{
              
          }
          
          // Get Funds
          $funds = 0;
          
          $sql4 = "SELECT * from funds where Username like '".$advisor."'";
          $result4 = $con1->query($sql4);
          if($result4->num_rows > 0){
              while($row = $result4->fetch_assoc()){
                  $funds = $row["Funds"];
              }
          }else{
              
          }
          
          $total_investment = $funds + $invested;
          
          // Calculated PL
          $user_pl = ($amount / $total_investment) * $pl;
          $user_pl = round($user_pl / (1 + (18 / 100)));
          
          $user_pl = formatAmountToINR($user_pl);
          $amount = formatAmountToINR($amount);
          $pl = formatAmountToINR($pl);
          $funds = formatAmountToINR($funds);
          $invested = formatAmountToINR($invested);
          
          echo '<section class="wallet-info py-5">
    <div class="container">
      <h2 class="text-center mb-4">PL Statement from '.$advisor_name.'</h2>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Investment</h5>
              <p class="card-text">'.$amount.'</p>
            </div>
            <a href="https://rzp.io/l/3XQXcZv" class="btn btn-primary">Invest Now</a>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">P&amp;L</h5>
              <p class="card-text">'.$user_pl.'</p>
            </div>
            <a href="/premium/orders.php" class="btn btn-primary">Advisor Positions</a>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Withdrawable Balance</h5>
              <p class="card-text">Rs 0.0</p>
            </div>
            <a href="#" class="btn btn-primary">Withdraw Money</a>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Advisor P&L</h5>
              <p class="card-text">'.$pl.'</p>
            </div>
            <a href="/premium/orders.php" class="btn btn-primary">Advisor Positions</a>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Advisor Funds</h5>
              <p class="card-text">'.$funds.'</p>
            </div>
            <a href="/premium/orders.php" class="btn btn-primary">Advisor Positions</a>
          </div>
          
        </div>
        <div class="col-md-4 mb-4">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Advisor Invested</h5>
              <p class="card-text">'.$invested.'</p>
            </div>
            <a href="/premium/orders.php" class="btn btn-primary">Advisor Positions</a>
          </div>
        </div>
        
      </div>
    </div>
  </section>';
          
      }
  }else{
      
  }
  ?>
  
  <section class="withdrawals-history py-5">
    <div class="container">
      <h2 class="text-center mb-4">Withdrawals History</h2>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="thead-dark">
            <tr>
              <th>Date</th>
              <th>Amount</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>2023-07-10</td>
              <td>Rs 10,000</td>
              <td>Completed</td>
            </tr>
            <tr>
              <td>2023-07-05</td>
              <td>Rs 5,000</td>
              <td>Completed</td>
            </tr>
            <!-- Add more rows with actual withdrawal records here -->
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- Add Bootstrap JS and jQuery scripts here -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
