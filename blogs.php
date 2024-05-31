<?php
session_start();
include("db.php");

if(isset($_GET["q"]) && !empty($_GET["q"])){
    $title = $_GET["q"];
    
    $sql1 = "SELECT Title, Description, SeoImage from blog_meta where Title like '%".$title."%'";
    $con1->set_charset('utf8mb4');
    $result1 = $con1->query($sql1);
    
    if($result1->num_rows > 0){
        while($row = $result1->fetch_assoc()){
            $title1 = $row["Title"];
            $description = $row["Description"];
            $seoimage = $row["SeoImage"];
        }
    }
    
    echo '<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Primary Meta Tags -->
    <title>'.$title1.' | Day Trade Insight</title>
    <meta name="title" content="'.$title1.'" />
    <meta name="description" content="'.$description.'" />
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.daytradeinsight.com/blogs.php?q='.$title1.'" />
    <meta property="og:title" content="'.$title1.'" />
    <meta property="og:description" content="'.$description.'" />
    <meta property="og:image" content="'.$seoimage.'" />
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="https://www.daytradeinsight.com/blogs.php?q='.$title1.'" />
    <meta property="twitter:title" content="'.$title1.'" />
    <meta property="twitter:description" content="'.$title1.'" />
    <meta property="twitter:image" content="'.$seoimage.'" />

<!-- Meta Tags Generated with https://metatags.io -->

    <!-- Add Bootstrap CSS link here -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Add DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <!-- Add your custom CSS link here -->
  <link rel="stylesheet" href="styles.css">

    <!-- Include Materialize CSS -->
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <!-- Include Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        /* Custom styles go here */
        body {
            background-color: #f0f0f0;
            font-family: \'Roboto\', sans-serif;
        }

        .container {
            padding: 20px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Day Trade Insight</a>
    <!-- Add your navigation links here -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="/">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="login.php">Login</a>
      </li>
    </ul>
  </nav>
  
  <header class="hero-section text-center py-1">
    <div class="container">
      <h1 class="display-4">'.$title1.'</h1>
      <p class="lead">'.$description.'</p>
      <!-- Add call-to-action buttons here -->
      <a class="btn btn-primary btn-lg" href="signup.php">Get Started Free</a>
      
    </div>
  </header>
  
    <!-- Main Section -->
    <main class="container">
    <div class="row">
            <div class="col s12">
                <h2>'.$title2.'</h2>
                <ul class="collapsible">
    ';
    
    $sql = "SELECT Title, Question, Answer from questions where Title like '%".$title."%'";
    $result = $con1->query($sql);
    
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $title2 = $row["Title"];
            $question = $row["Question"];
            $answer = $row["Answer"];
            
            echo '
                    <li>
                        <div class="collapsible-header"><i class="material-icons">arrow_drop_down</i>'.$question.'</div>
                        <div class="collapsible-body">
                            <h3 style="word-wrap: break-word;">'.$answer.'</h3>
                            <!--<p>'.$answer.'</p>-->
                        </div>
                    </li>';
        }
    }
    
    echo '</ul>
            </div>
        </div></main>
        <!-- Include Materialize JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <script>
        // Initialize Materialize components
        document.addEventListener(\'DOMContentLoaded\', function () {
            // Collapsible
            var collapsibles = document.querySelectorAll(\'.collapsible\');
            M.Collapsible.init(collapsibles);

            // Dropdown
            var dropdowns = document.querySelectorAll(\'.dropdown-trigger\');
            M.Dropdown.init(dropdowns, { hover: true });

            // Tooltips
            var tooltips = document.querySelectorAll(\'.tooltipped\');
            M.Tooltip.init(tooltips);

            // Other Materialize components can be initialized here
        });
    </script>
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src=\'https://embed.tawk.to/58e08d0bf7bbaa72709c3b48/default\';
    s1.charset=\'UTF-8\';
    s1.setAttribute(\'crossorigin\',\'*\');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    </bod></html>
    
        ';
}else{
    // Do Nothing
}



?>