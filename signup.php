<?php
session_start();
include("db.php");

if(isset($_SESSION["username"]) && strcmp($_SESSION["username"], "") != 0){
    $username = $_SESSION["username"];
    
    $sql = "SELECT Type from login where username like '%".$username."%' limit 1";
    $result = $con1->query($sql);
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $type = $row["Type"];
            header("Location: ".$type);
        }
    }
    session_write_close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Premium User Signup</title>
  <!-- Add Bootstrap CSS link here -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Add your custom CSS link here (if any) -->
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <img src="/daytradeinsight-logo.png" alt="DayTradeInsight Logo" style="height: 50px; padding-left:10px; padding-right:10px;">
    <a class="navbar-brand" href="#">Premium Signup</a>
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
              <h2 class="card-title text-center mb-4">Premium User Signup</h2>
              <form>
                <div class="form-group">
                  <label for="name">Full name</label>
                  <input type="text" class="form-control" id="name" placeholder="Enter name">
                </div>
                <div class="form-group">
                  <label for="email">Email address</label>
                  <input type="email" class="form-control" id="email" placeholder="Enter email">
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" id="password" placeholder="Password">
                </div>
                
                <div class="form-group">
                  <label for="mobile">Mobile</label>
                  <input type="mobile" class="form-control" id="mobile" placeholder="Enter mobile">
                </div>
                
                <button type="button" class="btn btn-primary btn-block" id="signup_btn">Signup</button>
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
        $("#signup_btn").click(function(){
            var name = $("#name").val();
            var username = $("#email").val();
            var password = $("#password").val();
            var mobile = $("#mobile").val();
            
            $.post("set_login.php", {name: name, username: username, password: password, mobile:mobile}, function(response){
                if(response !== "False"){
                    $("#responseMessage").text("Signup Success.");
                    $("#responseModal").modal("show");
                    window.location.href = response;
                }else{
                    $("#responseMessage").text("Login failed. Please try again.");
                    $("#responseModal").modal("show");
                }
            });
        });
    });
</script>
</html>
