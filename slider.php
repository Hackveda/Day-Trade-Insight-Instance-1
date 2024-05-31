<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Status Updates Slider</title>
  <!-- Add Bootstrap CSS link here -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Add your custom CSS link here -->
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <!-- Slider Section -->
  <section class="slider-section py-5">
    <div class="container">
      <h2 class="text-center mb-4">Status Updates and Images</h2>
      <div id="status-slider" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#status-slider" data-slide-to="0" class="active"></li>
          <li data-target="#status-slider" data-slide-to="1"></li>
          <li data-target="#status-slider" data-slide-to="2"></li>
          <!-- Add more indicators if needed -->
        </ol>
        <div class="carousel-inner">
          <!-- Slide 1 -->
          <div class="carousel-item active">
            <div>
              <h5>Status Text 1 😊</h5>
              <p>Write your status text here...</p>
            </div>
          </div>
          <!-- Slide 2 -->
          <div class="carousel-item">
            <div>
              <h5>Status Text 2 😄</h5>
              <p>Write your status text here...</p>
            </div>
          </div>
          <!-- Slide 3 -->
          <div class="carousel-item">
            <div>
              <h5>Status Text 3 😍</h5>
              <p>Write your status text here...</p>
            </div>
          </div>
          <!-- Add more slides with different status texts and smileys as needed -->
        </div>
        <a class="carousel-control-prev" href="#status-slider" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#status-slider" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </div>
  </section>

  <!-- ...Rest of the sections... -->

  <!-- Add Bootstrap JS and jQuery scripts here -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
