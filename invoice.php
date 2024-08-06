<?php
require_once 'fpdf184/fpdf.php';
require_once __DIR__ . '../includes/db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

global $link;
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    die('Order ID is required.');
}

// Fetch order details
$order_query = mysqli_query($link, "
    SELECT o.id AS order_id, o.order_date AS orderDate, o.total AS totalAmount, 
           CONCAT(u.username) AS customerName, u.email AS customerEmail, 
           o.shipping_name AS shippingName, o.shipping_address AS shippingAddress, 
           o.shipping_city AS shippingCity, o.shipping_state AS shippingState, 
           o.shipping_zip AS shippingZip, o.shipping_phone AS shippingPhone 
    FROM Orders o 
    JOIN Users u ON o.user_id = u.id 
    WHERE o.id = $order_id
");

if ($order_query && mysqli_num_rows($order_query) > 0) {
    $order = mysqli_fetch_assoc($order_query);

    // Fetch order items
    $items_query = mysqli_query($link, "
        SELECT p.name AS productName, oi.quantity AS quantity, oi.price AS unitPrice, 
               (oi.quantity * oi.price) AS totalPrice 
        FROM OrderItems oi 
        JOIN Products p ON oi.product_id = p.id 
        WHERE oi.order_id = $order_id
    ");

    if ($items_query && mysqli_num_rows($items_query) > 0) {
        $pdf = new FPDF();
        $pdf->AddPage();

        // Setting company logo and title
        $pdf->Image('./images/logo.png', 5, 5, 15);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Oven Store', 0, 1, 'C');
        $pdf->SetFont('Arial', 'I', 12);
        $pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');
        $pdf->Ln(10);

        // Print order and customer info
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Customer Name: ' . $order["customerName"], 0, 1);
        $pdf->Cell(0, 10, 'Customer Email: ' . $order["customerEmail"], 0, 1);
        $pdf->Cell(0, 10, 'Order Date: ' . date('d-m-Y', strtotime($order["orderDate"])), 0, 1);
        $pdf->Ln(10);

        // Print shipping details
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Shipping Name: ' . $order["shippingName"], 0, 1);
        $pdf->Cell(0, 10, 'Shipping Address: ' . $order["shippingAddress"], 0, 1);
        $pdf->Cell(0, 10, 'Shipping City: ' . $order["shippingCity"], 0, 1);
        $pdf->Cell(0, 10, 'Shipping State: ' . $order["shippingState"], 0, 1);
        $pdf->Cell(0, 10, 'Shipping Zip: ' . $order["shippingZip"], 0, 1);
        $pdf->Cell(0, 10, 'Shipping Phone: ' . $order["shippingPhone"], 0, 1);
        $pdf->Ln(10);

        // Setting the table header
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(173, 216, 230); // Light blue
        $pdf->Cell(90, 10, 'Product', 1, 0, 'C', true);
        $pdf->Cell(20, 10, 'Quantity', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Unit Price', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Total', 1, 1, 'C', true);

        $grand_total = 0;

        // Fetching items and displaying in a single table
        while ($item = mysqli_fetch_assoc($items_query)) {
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(90, 10, $item["productName"], 1);
            $pdf->Cell(20, 10, $item["quantity"], 1);
            $pdf->Cell(30, 10, number_format($item["unitPrice"], 2), 1);
            $pdf->Cell(30, 10, number_format($item["totalPrice"], 2), 1, 1);

            $grand_total += $item["totalPrice"];
        }

        // Print grand total amount
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(140, 10, 'Grand Total:', 1, 0, 'R');
        $pdf->Cell(30, 10, number_format($grand_total, 2), 1, 1, 'C');

        // Output the PDF
        $pdf->Output();
    } else {
        echo "No items found for this order.";
    }
} else {
    echo "Order not found.";
}

// Close connection
mysqli_close($link);
?>