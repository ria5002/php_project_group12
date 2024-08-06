<?php
require_once 'classes/Order.php';
require_once 'classes/Product.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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

// Handle form submission for placing order
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error_message = '';

    if (empty($cart)) {
        $error_message = "Your cart is empty. Please add items to the cart before checking out.";
    } else {
        // Sanitize and validate input data
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
        $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
        $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
        $zip = filter_input(INPUT_POST, 'zip', FILTER_SANITIZE_STRING);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);

        // Validate phone number to contain only digits
        if (!preg_match('/^\d{10}$/', $phone)) {
            $error_message = "Please enter a valid 10-digit phone number.";
        }

        // Check if any required field is empty after sanitization
        if (!$name || !$address || !$city || !$state || !$zip || !$phone) {
            $error_message = "All fields are required.";
        }

        if (empty($error_message)) {
            $user_id = $_SESSION['user_id'];
            $shipping_details = [
                'name' => $name,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
                'phone' => $phone
            ];

            $order_id = Order::createOrder($user_id, $total, $shipping_details);
            if ($order_id) {
                foreach ($cart as $item) {
                    Order::addOrderItem($order_id, $item['product_id'], $item['quantity'], $item['price']);
                }
                $_SESSION['cart'] = []; // Clear the cart
                header("Location: confirmation.php?order_id=$order_id");
                exit();
            } else {
                $error_message = "Failed to create order.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function validateForm() {
            const phone = document.getElementById('phone').value;
            const phonePattern = /^\d{10}$/;
            if (!phonePattern.test(phone)) {
                alert("Please enter a valid 10-digit phone number.");
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h1>Checkout</h1>
        <?php if (isset($error_message) && $error_message): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="checkout.php" method="post" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" name="address" id="address" required>
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" class="form-control" name="city" id="city" required>
            </div>
            <div class="form-group">
                <label for="state">State:</label>
                <input type="text" class="form-control" name="state" id="state" required>
            </div>
            <div class="form-group">
                <label for="zip">Zip Code:</label>
                <input type="text" class="form-control" name="zip" id="zip" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" class="form-control" name="phone" id="phone" required>
            </div>

            <h2>Order Summary</h2>
            <div id="accordion">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
                                aria-expanded="true" aria-controls="collapseOne">
                                View Order Summary
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <div id="cart-items" class="cart">
                                <?php foreach ($cart as $item): ?>
                                    <div class="card mb-3" data-product-id="<?php echo $item['product_id']; ?>">
                                        <div class="row no-gutters">
                                            <div class="col-md-4">
                                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>"
                                                    class="card-img" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?>
                                                    </h5>
                                                    <p class="card-text">
                                                        <?php echo htmlspecialchars($item['description']); ?>
                                                    </p>
                                                    <p class="card-text">Quantity:
                                                        <?php echo htmlspecialchars($item['quantity']); ?>
                                                    </p>
                                                    <p class="card-text">Price:
                                                        $<?php echo htmlspecialchars($item['price']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <h2>Total: $<span id="total-amount"><?php echo number_format($total, 2); ?></span></h2>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Place Order</button>
        </form>
    </div>

    <?php include ("footer.php") ?>
</body>

</html>