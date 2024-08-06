<?php
require_once 'classes/Product.php';
session_start();

// Use session for storing the cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];
$total = 0;

// Fetch product details for each item in the cart
foreach ($cart as &$item) {
    $product = Product::getProductById($item['product_id']);
    if ($product) {
        $item['name'] = $product['name'];
        $item['image_url'] = $product['image_url'];
        $item['description'] = $product['description'];
        $item['price'] = $product['price']; // Ensure price is updated correctly
    }
    $total += $item['quantity'] * $item['price'];
}
unset($item); // Break the reference with the last element

// Handle remove item from cart
if (isset($_POST['remove'])) {
    $removeId = (int) $_POST['remove'];
    foreach ($cart as $key => $item) {
        if ((int) $item['product_id'] === $removeId) {
            unset($cart[$key]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($cart); // Reindex array and update session
    header("Location: cart.php"); // Redirect to avoid form resubmission
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .cart-container {
            margin-top: 50px;
        }

        .card-img {
            max-height: 200px;
            object-fit: cover;
        }

        .total-container {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php include ("navbar.php"); ?>

    <div class="container cart-container">
        <h1>Your Cart</h1>
        <div id="cart-items" class="cart">
            <?php foreach ($cart as $item): ?>
                <div class="card mb-3" data-product-id="<?php echo $item['product_id']; ?>">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" class="card-img"
                                alt="<?php echo htmlspecialchars($item['name']); ?>">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($item['description']); ?></p>
                                <p class="card-text">Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                                <p class="card-text">Price: $<?php echo htmlspecialchars($item['price']); ?></p>
                                <form method="post" action="cart.php">
                                    <input type="hidden" name="remove" value="<?php echo $item['product_id']; ?>">
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="total-container">
            <h2>Total: $<span id="total-amount"><?php echo number_format($total, 2); ?></span></h2>
            <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
        </div>
    </div>
    <?php include ("footer.php"); ?>
    <script>
        // Log the session cart in the console for debugging
        const sessionCart = <?php echo json_encode($_SESSION['cart']); ?>;
        console.log('Session Cart:', sessionCart);
    </script>
</body>

</html>