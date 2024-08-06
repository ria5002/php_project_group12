<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
    body,
    html {
      height: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
    }

    .content {
      flex: 1;
    }

    .footer {
      padding: 20px 0;
      background-color: #f1f1f1;
      text-align: center;
    }

    .hero-section {
      background: url('images/banner.jpg') no-repeat center center;
      background-size: cover;
      height: 50vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .hero-section h1 {
      font-size: 3.5rem;
    }

    .product-highlights {
      padding: 50px 0;
      text-align: center;
    }

    .product-highlights h2 {
      margin-bottom: 30px;
    }

    .product-card {
      margin: 20px 0;
      height: 100%;
    }

    .product-card img {
      height: 200px;
      object-fit: contain;
      padding: 10px;
    }

    .product-card .card-body {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
  </style>
</head>

<body>
  <?php include 'navbar.php'; ?>

  <div class="content">
    <div class="hero-section">
      <h1>Welcome to Microwave Store</h1>
    </div>

    <div class="container product-highlights">
      <h2>Featured Products</h2>
      <div class="row">
        <div class="col-md-4">
          <div class="card product-card">
            <img src="images/1.webp" class="card-img-top" alt="Panasonic NN-SN936B">
            <div class="card-body">
              <h5 class="card-title">Panasonic NN-SN936B</h5>
              <p class="card-text">High-capacity microwave with inverter technology for even cooking and a sleek design.
              </p>
              <a href="product.php?id=1" class="btn btn-primary">View Details</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card product-card">
            <img src="images/4.webp" class="card-img-top" alt="LG NeoChef LMC1575ST">
            <div class="card-body">
              <h5 class="card-title">LG NeoChef LMC1575ST</h5>
              <p class="card-text">Smart inverter technology with a sleek design, providing precise cooking and even
                heating.</p>
              <a href="product.php?id=4" class="btn btn-primary">View Details</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card product-card">
            <img src="images/5.webp" class="card-img-top" alt="Breville BMO850BSS1BUC1">
            <div class="card-body">
              <h5 class="card-title">Breville BMO850BSS1BUC1</h5>
              <p class="card-text">The Quick Touch microwave features smart settings that adjust cooking time and power
                for optimal results.</p>
              <a href="product.php?id=5" class="btn btn-primary">View Details</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="footer">
    <p>&copy; <?php echo date("Y"); ?> Microwave Store. All rights reserved.</p>
  </footer>

</body>

</html>