<?php
session_start();
include("db.php");
if(isset($_GET["id"]) && strcmp($_GET["id"], "") != 0){
    $id = $_GET["id"];
    $sql = "SELECT * from brokers where ID=".$id;
    $result = $con1->query($sql);
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $id = $row["ID"];
            $name = $row["BrokerName"];
            $desc = $row["BrokerDesc"];
            $image = $row["BrokerImage"];
            $regulatory = $row["Regulatory"];
            $platforms = $row["TradingPlatforms"];
            $recommendation = $row["AccountRecommendation"];
            $investment = $row["AccountInvestment"];
            $deposit = $row["MinimumDeposit"];
            $fees = $row["Fees"];
            $leverage = $row["Leverage"];
            $support = $row["CustomerSupport"];
            $education = $row["Education"];
            $withdrawals = $row["Withdrawals"];
        }
    }else{
        
    }
}else{
    header("Location: brokers.php");
}
session_write_close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Market Advisor Profile | <?php echo $name; ?></title>
    <!-- Add Bootstrap CSS link here -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add your custom CSS link here -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a class="navbar-brand" href="profile.php?id=<?php echo $id; ?>"><?php echo $name; ?> | Advisors</a>
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

   <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $name; ?> | Advisor Profile</title>
    <!-- Add Bootstrap CSS link here -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add your custom CSS link here -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
    <!-- Broker Details Section -->
    <section class="broker-details py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <img width="170" height="100" src="<?php echo $image; ?>" alt="external-broker-investing-flaticons-flat-flat-icons"/>
                    <h2><?php echo $name; ?></h2>
                    <p>
                        <?php echo $desc; ?>
                    </p>
                    <!-- Add call-to-action buttons here -->
                    <a class="btn btn-primary btn-lg" href="https://rzp.io/l/3XQXcZv">Subscribe</a>
                    
                </div>
            </div>
        </div>
    </section>
    
    <!-- More Cards Section -->
<section class="more-cards py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Education &amp; Experience</h5>
                        <p class="card-text"><?php echo $education; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Regulatory</h5>
                        <p class="card-text"><?php echo $regulatory; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Trading Instruments</h5>
                        <p class="card-text"><?php echo $platforms; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Fees and Commissions</h5>
                        <p class="card-text"><?php echo $fees; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Customer Support</h5>
                        <p class="card-text"><?php echo $support; ?></p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>


<section class="account-types py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Plan Types</h2>
        <div class="row">
            <?php
            if(strcmp($recommendation, "True") == 0){
               echo '<div class="col-md-6 mb-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Recommendation</h4>
                        <p class="card-text">
                            Are you looking to navigate the complex world of trading and investments with confidence? Subscribe to our trading investment advisory services and embark on a journey towards financial success.
                        </p>
                        <a class="btn btn-primary" href="https://rzp.io/l/3XQXcZv">Subscribe</a>
                    </div>
                </div>
            </div>'; 
            }
            
            ?>
        </div>
    </div>
</section>

    <!-- User Reviews Section -->
    <section class="user-reviews py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">User Reviews</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">
                                <p>
                                    "Great broker with excellent customer support. The trading platform is user-friendly, and the educational resources are very helpful for beginners like me."
                                </p>
                                <footer class="blockquote-footer">Kunal Bansal</footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">
                                <p>
                                    "I've been trading with <?php echo $name; ?> for years, and they never disappoint. Fast withdrawals and competitive spreads make it a reliable choice for my trading needs."
                                </p>
                                <footer class="blockquote-footer">Himanshu Sharma</footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Disclaimer Section -->
    <section class="disclaimer py-4">
        <div class="container">
            <p class="text-center">
                Disclaimer: Trading in the financial markets involves substantial risk and may not be suitable for all investors. Past performance is not indicative of future results. Please carefully consider your financial situation and risk tolerance before trading. <?php echo $name; ?> does not provide financial advice and is not responsible for any trading decisions made by users based on the information provided on this profile page.
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
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
</body>
</html>
