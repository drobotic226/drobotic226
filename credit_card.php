<?php
session_start();

// Example products (same array as before)
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

// Cart check
$cart = $_SESSION['cart'] ?? [];
if(empty($cart)){
    die("Your cart is empty. Please add products first.");
}

// Calculate total
$totalAmount = 0;
foreach($cart as $item){
    $id = $item['id'];
    $quantity = intval($item['quantity']);
    if(isset($products[$id])){
        $unitPrice = floatval(preg_replace('/[^\d.]/','',$products[$id]['price']));
        $totalAmount += $unitPrice * $quantity;
    }
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $card_number = $_POST['card_number'] ?? '';
    $card_name   = $_POST['card_name'] ?? '';
    $expiry      = $_POST['expiry'] ?? '';
    $cvv         = $_POST['cvv'] ?? '';

    // Simple validation (you can extend it)
    if($card_number && $card_name && $expiry && $cvv){
        // Save order in session
        $orderId = uniqid("ORD_");
        $_SESSION['orders'][$orderId] = [
            'items' => $cart,
            'payment_method' => "Credit Card",
            'timestamp' => date("Y-m-d H:i:s")
        ];

        // Clear cart
        unset($_SESSION['cart']);

        // Redirect to confirmation page
        header("Location: confirmation.php?order_id=".$orderId);
        exit;
    } else {
        $error = "Please fill all card details.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>DROBOTIC</title>
<style>
body { font-family: Arial, sans-serif; margin:0; background:#f7f7f7; padding:20px; }
.container { max-width:500px; margin:auto; background:#fff; padding:20px; border-radius:8px; }
input { width:100%; padding:10px; margin:8px 0; }
button { padding:12px; background:green; border:none; color:#fff; cursor:pointer; border-radius:5px; width:100%; }
.error { color:red; }
</style>
</head>
<body>
<div class="container">
    <h2>Pay with Credit Card</h2>
    <p>Total to Pay: <b>₹<?= number_format($totalAmount,2) ?></b></p>

    <?php if(!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="card_number" placeholder="Card Number" required>
        <input type="text" name="card_name" placeholder="Name on Card" required>
        <input type="text" name="expiry" placeholder="MM/YY" required>
        <input type="text" name="cvv" placeholder="CVV" required>
        <button type="submit">Confirm & Pay</button>
    </form>
</div>
</body>
</html>
