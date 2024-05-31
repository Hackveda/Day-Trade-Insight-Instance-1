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
    <title>Status Management | <?php echo $broker_name; ?></title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom CSS styles */
        /* Add your custom styles here */
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Status Management</a>
    <!-- Add your navigation links here -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="/manage">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="status.php">Status</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
    </ul>
  </nav>
    <section class="status-submission py-5">
    <div class="container">
      <h2 class="text-center mb-4">Add Status</h2>
      <form>
        <div class="mb-3">
          <label for="statusInput" class="form-label">Status Text</label>
          <textarea class="form-control" id="statusInput" rows="3" required></textarea>
        </div>
        <button type="button" class="btn btn-primary" id="btn_submit">Submit</button>
      </form>
    </div>
  </section>
  
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
        $(document).ready(function(){
            $("#btn_submit").click(function(){
                var status = $("#statusInput").val();
                $.post("add_status.php", {status:status}, function(response){
                    $("#responseMessage").html(response);
                    $("#responseModal").modal("show");
                });
            });
            
            
        });
    </script>
</html>
