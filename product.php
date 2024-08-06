<?php
require_once 'classes/Product.php';
session_start();

$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    die('Product ID is required.');
}

$product = Product::getProductById($product_id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quantity = (int) $_POST['quantity'];
    $found = false;

    // Use session for storing the cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Iterate over the cart to find the product and update quantity if found
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }

    // If the product was not found in the cart, add it as a new item
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $product['price'],
            'name' => $product['name'],
            'image_url' => $product['image_url'],
            'description' => $product['description']
        ];
    }

    // Redirect to cart page
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include ("navbar.php") ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="img-fluid"
                    alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="col-md-6">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                <form method="post">
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>

    <?php include ("footer.php") ?>
</body>

</html>