<?php
session_start();
include("db.php");

if(isset($_SESSION["broker"]) && strcmp($_SESSION["broker"], "") != 0){
    $broker = $_SESSION["broker"];
    $broker_name = $_SESSION["broker_name"];
    header("Location: /manage");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Broker Login</title>
  <!-- Add Bootstrap CSS link here -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Add your custom CSS link here (if any) -->
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <img src="/daytradeinsight-logo.png" alt="DayTradeInsight Logo" style="height: 50px; padding-left:10px; padding-right:10px;">
    <a class="navbar-brand" href="#">Advisor Login</a>
    <!-- Add your navigation links here -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="/">Home</a>
      </li>
    </ul>
  </nav>
  <!-- Login Section -->
  <section class="login-section py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h2 class="card-title text-center mb-4">Advisor Login</h2>
              <form>
                <div class="form-group">
                  <label for="email">Email address</label>
                  <input type="email" class="form-control" id="email" placeholder="Enter email">
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" id="password" placeholder="Password">
                </div>
                <button type="button" class="btn btn-primary btn-block" id="login_btn">Login</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Response Modal -->
  <div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="signupModalLabel">Response</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Add your sign up form here -->
          <p id="responseMessage"></p>
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
    $(document).ready(function(){
        $("#login_btn").click(function(){
            var username = $("#email").val();
            var password = $("#password").val();
            
            $.post("get_login.php", {username: username, password: password}, function(response){
                if(response == "True"){
                    $("#responseMessage").text("Login Success.");
                    $("#responseModal").modal("show");
                    window.location.href = "/manage";
                }else{
                    $("#responseMessage").text("Login failed. Please try again.");
                    $("#responseModal").modal("show");
                }
            });
        });
    });
</script>
</html>
