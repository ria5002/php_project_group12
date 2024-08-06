<?php
session_start();
$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <h1>Order Confirmation</h1>
        <p>Your order has been placed successfully!</p>
        <div class="mt-4">
            <a href="invoice.php?order_id=<?php echo $order_id; ?>" class="btn btn-primary">Download Invoice</a>
            <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
        </div>
    </div>
</body>

</html>