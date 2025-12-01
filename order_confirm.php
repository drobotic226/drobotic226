<?php
session_start();

// Example products (same as before, so order items can be shown)
$products = [
    ["name"=>"Class M15","price"=>"₹150","image"=>"images/312489_nz9ucg.png","company"=>"Eureka Forbes"], 
    ["name"=>"Euroclean Glide","price"=>"₹120","image"=>"images/312756_h1yc9m.png","company"=>"Eureka Forbes"],
    ["name"=>"Decker Vacuum","price"=>"₹180","image"=>"images/312488_bvgyqz.png","company"=>"Black & Decker"],
    ["name"=>"Karcher 2","price"=>"₹200","image"=>"images/312489_nz9ucg.png","company"=>"Karcher"],
    ["name"=>"Vacuum X","price"=>"₹160","image"=>"images/312756_h1yc9m.png","company"=>"Black & Decker"],
    ["name"=>"Cleaner Pro","price"=>"₹140","image"=>"images/312756_h1yc9m.png","company"=>"Karcher"],
    ["name"=>"SuperVac 3000","price"=>"₹250","image"=>"images/thumbnail_AUTOBIN_01_dc5df37216.jpg","company"=>"Eureka Forbes"],
    ["name"=>"DustBuster Pro","price"=>"₹300","image"=>"images/thumbnail_EFL_Auto_Bin_Banner_1_6a2dec8338_1_346bd9dd2c.jpg","company"=>"Black & Decker"],
];

// Get order ID from query
$orderId = $_GET['order_id'] ?? null;

if(!$orderId || !isset($_SESSION['orders'][$orderId])){
    die("Invalid order. Please place an order first.");
}

// Get order details
$order = $_SESSION['orders'][$orderId];
$items = $order['items'];
$timestamp = $order['timestamp'];
$payment = $order['payment_method'];

// Calculate total again
$totalAmount = 0;
foreach($items as $item){
    $id = $item['id'];
    $quantity = intval($item['quantity']);
    if(isset($products[$id])){
        $unitPrice = floatval(preg_replace('/[^\d.]/','',$products[$id]['price']));
        $totalAmount += $unitPrice * $quantity;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Order Confirmation</title>
<style>
body { font-family: Arial, sans-serif; margin:0; background:#f7f7f7; padding:20px; }
.container { max-width:700px; margin:auto; background:#fff; padding:20px; border-radius:8px; text-align:center; }
h2 { color:green; }
table { width:100%; border-collapse: collapse; margin-top:20px; }
table, th, td { border:1px solid #ddd; }
th, td { padding:10px; text-align:center; }
.total { font-weight:bold; font-size:18px; color:#333; }
</style>
</head>
<body>
<div class="container">
    <h2>✅ Order Confirmed!</h2>
    <p>Thank you for your purchase. Your order has been successfully placed.</p>
    <p><b>Order ID:</b> <?= htmlspecialchars($orderId) ?></p>
    <p><b>Order Date:</b> <?= htmlspecialchars($timestamp) ?></p>
    <p><b>Payment Method:</b> <?= htmlspecialchars($payment) ?></p>

    <h3>Order Summary</h3>
    <table>
        <tr>
            <th>Product</th>
            <th>Company</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        <?php foreach($items as $item): 
            $id = $item['id'];
            $quantity = intval($item['quantity']);
            ?>
            <tr>
                <td><?= htmlspecialchars($products[$id]['name']) ?></td>
                <td><?= htmlspecialchars($products[$id]['company']) ?></td>
                <td><?= $quantity ?></td>
                <td><?= $products[$id]['price'] ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3" class="total">Total</td>
            <td class="total">₹<?= number_format($totalAmount,2) ?></td>
        </tr>
    </table>
</div>
</body>
</html>
