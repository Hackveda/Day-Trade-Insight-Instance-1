<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Broker Profile</title>
  <!-- Add Bootstrap CSS link here -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <img src="/daytradeinsight-logo.png" alt="DayTradeInsight Logo" style="height: 50px; padding-left:10px; padding-right:10px;">
    <a class="navbar-brand" href="#">Advisor Dashboard</a>
    <!-- Add your navigation links here -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="/manage">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
    </ul>
  </nav>
  
  <div class="container mt-5">
    <h2 class="mb-4">Update Broker Profile</h2>
    <form>
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
      </div>
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
      </div>
      <div class="form-group">
        <label for="phone">Phone Number</label>
        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
      </div>
      <div class="form-group">
        <label for="company">Company Name</label>
        <input type="text" class="form-control" id="company" name="company" placeholder="Enter your company name" required>
      </div>
      <div class="form-group">
        <label for="address">Address</label>
        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter your address"></textarea>
      </div>
      <div class="form-group">
        <label for="about">About Me</label>
        <textarea class="form-control" id="about" name="about" rows="5" placeholder="Tell us about yourself"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
  </div>

  <!-- Add Bootstrap JS and jQuery scripts here -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
