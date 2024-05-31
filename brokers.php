<?php
session_start();
include("db.php");
session_write_close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Day Trade Insight - Stock Recommendations</title>
  <!-- Add Bootstrap CSS link here -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Add your custom CSS link here -->
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <img src="/daytradeinsight-logo.png" alt="DayTradeInsight Logo" style="height: 50px; padding-left:10px; padding-right:10px;">
    <a class="navbar-brand" href="#">Financial Advisors</a>
    <!-- Add your navigation links here -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="/premium">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/premium/orders.php">Positions</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="brokers.php">Advisors</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/logout.php">Logout</a>
      </li>
    </ul>
  </nav>

  <!-- Hero Section -->
  <header class="hero-section text-center py-5">
    <div class="container">
      <h1 class="display-4">Financial Advisors</h1>
      <p class="lead">Get expert stock recommendations for your day trading strategies and invest with top financial investment advisors</p>
    </div>
  </header>

  <section class="brokers py-5">
    <div class="container">
        <h2 class="text-center mb-4">Advisors on Day Trade Insight</h2>
        <div class="row">
            
            <?php
            $sql = "SELECT * from brokers ORDER BY ID DESC";
            $result = $con1->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
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

  <!-- Add Bootstrap JS and jQuery scripts here -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
