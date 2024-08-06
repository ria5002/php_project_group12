<?php
require_once 'classes/Product.php';
session_start();

$products = Product::getAllProducts();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .card-body {
            display: flex;
            flex-direction: column;
        }

        .card-title,
        .card-text,
        .btn {
            margin-top: auto;
        }
    </style>
</head>

<body>
    <?php include ("navbar.php") ?>

    <div class="container mt-5">
        <h1 class="mb-4">Microwave Ovens</h1>
        <div class="form-group">
            <input type="text" id="search" class="form-control" placeholder="Search products...">
        </div>
        <div class="row" id="product-list">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4 product-item" data-name="<?php echo htmlspecialchars($product['name']); ?>"
                        data-description="<?php echo htmlspecialchars($product['description']); ?>">
                        <div class="card h-100">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top"
                                alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                                <p class="card-text">Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                                <a href="product.php?id=<?php echo htmlspecialchars($product['id']); ?>"
                                    class="btn btn-primary mt-auto">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products available.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#search').on('keyup', function () {
                var searchTerm = $(this).val().toLowerCase();
                $('.product-item').each(function () {
                    var name = $(this).data('name').toLowerCase();
                    var description = $(this).data('description').toLowerCase();
                    if (name.includes(searchTerm) || description.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
    <?php include ("footer.php") ?>

</body>

</html>