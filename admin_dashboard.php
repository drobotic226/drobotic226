<?php 
include("db.php");

// Dynamic values from database (example)
$total_users = 1560;
$total_orders = 1140;
$total_products = 7560;
$revenue = 24300;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="bg-gray-900 text-white">

<div class="flex">

    <!-- Sidebar -->
    <aside class="w-64 h-screen bg-gray-800 p-6 space-y-6">
        <h2 class="text-2xl font-bold mb-8">Dashboard</h2>

        <nav class="space-y-4">
             <!-- LOGO SECTION -->
   <div class="flex items-center space-x-3 mb-10">
    <img src="images/WhatsApp Image 2025-09-10 at 11.42.02 AM.jpeg" 
         alt="Logo" 
         class="w-20 h-20 rounded-lg">
</div>

      
      
			<a href="users.php" class="flex items-center hover:text-gray-300">Users</a>
            <a href="add_product.php" class="flex items-center hover:text-gray-300">Products</a>
            <a href="#" class="flex items-center hover:text-gray-300">Orders</a>
            <a href="#" class="flex items-center hover:text-gray-300">Analytics</a>
        </nav>

        <div class="absolute bottom-10">
            <a href="#" class="hover:text-gray-300">Settings</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">

        <!-- Top Stats -->
        <div class="grid grid-cols-4 gap-6 mb-6">

            <div class="bg-gray-800 p-6 rounded-xl shadow-lg">
                <p class="text-gray-400">Total Users</p>
                <h2 class="text-3xl font-bold"><?php echo $total_users ?></h2>
            </div>

            <div class="bg-gray-800 p-6 rounded-xl shadow-lg">
                <p class="text-gray-400">Total Orders</p>
                <h2 class="text-3xl font-bold"><?php echo $total_orders ?></h2>
            </div>

            <div class="bg-gray-800 p-6 rounded-xl shadow-lg">
                <p class="text-gray-400">Total Products</p>
                <h2 class="text-3xl font-bold"><?php echo $total_products ?></h2>
            </div>

            <div class="bg-gray-800 p-6 rounded-xl shadow-lg">
                <p class="text-gray-400">Revenue</p>
                <h2 class="text-3xl font-bold">$<?php echo $revenue ?></h2>
            </div>

        </div>

      

       

                  <?php 
include("db.php");

// Example orders array
$orders = [
    ["name" => "John Doe", "product" => "Laptop", "date" => "01 Jan 2024", "status" => "Delivered", "tracking" => "Delivered at 10:00 AM, 01 Jan 2024"],
    ["name" => "Alicia Smith", "product" => "Smartphone", "date" => "24 Dec 2023", "status" => "Pending", "tracking" => "Order is being processed"],
    ["name" => "Michael Lee", "product" => "Headphones", "date" => "18 Dec 2023", "status" => "Shipped", "tracking" => "Shipped via UPS, tracking #123456"]
];

$customerNumber = 1;
$year = date("Y");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dashboard Orders</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white">

<!-- Recent Orders -->
<div class="grid grid-cols-1 gap-6 mt-6 p-6">
    <div class="bg-gray-800 p-6 rounded-xl shadow-lg">
        <h3 class="text-xl font-semibold mb-4">Customer Orders</h3>

        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-700">
                <tr>
                    <th class="py-2 px-3 text-gray-300 border">Customer ID</th>
                    <th class="py-2 px-3 text-gray-300 border">Customer Name</th>
                    <th class="py-2 px-3 text-gray-300 border">Product Purchased</th>
                    <th class="py-2 px-3 text-gray-300 border">Purchase Date</th>
                    <th class="py-2 px-3 text-gray-300 border">Delivery Status</th>
                    <th class="py-2 px-3 text-gray-300 border">Send Message</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($orders as $order): ?>
                    <?php
                        $customerID = sprintf("DRB-CUST-%s-%04d", $year, $customerNumber++);
                    ?>
                    <tr class="border-b border-gray-700">
                        <td class="py-3 px-3"><?php echo $customerID; ?></td>
                        <td class="py-3 px-3"><?php echo $order['name']; ?></td>
                        <td class="py-3 px-3"><?php echo $order['product']; ?></td>
                        <td class="py-3 px-3"><?php echo $order['date']; ?></td>

                        <td class="py-3 px-3">
                            <?php
                                $status = $order['status'];
                                $color = "bg-gray-600";
                                if($status == "Delivered") $color = "bg-green-600";
                                elseif($status == "Pending") $color = "bg-yellow-600";
                                elseif($status == "Shipped") $color = "bg-blue-600";
                                $tracking = $order['tracking'];
                                $statusID = "status-" . $customerNumber;
                            ?>

                            <button onclick="toggleTracking('<?php echo $statusID; ?>')" class="<?php echo $color; ?> px-3 py-1 rounded-full text-sm">
                                <?php echo $status; ?>
                            </button>

                            <div id="<?php echo $statusID; ?>" class="mt-2 p-2 bg-gray-700 rounded hidden text-sm">
                                <?php echo $tracking; ?>
                            </div>
                        </td>

                        <!-- MESSAGE BUTTON -->
                        <td class="py-3 px-3">
                            <button 
                                onclick="openMsgBox('<?php echo $customerID; ?>', '<?php echo $order['name']; ?>')" 
                                class="bg-purple-600 hover:bg-purple-700 px-3 py-1 rounded text-sm">
                                Message
                            </button>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<!-- MESSAGE POPUP BOX -->
<div id="messageBox" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-gray-800 p-6 rounded-xl w-96 shadow-xl">
        <h2 class="text-xl font-bold mb-4">Send Message</h2>

        <p class="text-gray-300 mb-2">To: <span id="msgCustomer"></span></p>
        <p class="text-gray-300 mb-3 text-sm">Customer ID: <span id="msgCustomerID"></span></p>

        <textarea id="messageText" class="w-full p-2 rounded bg-gray-700 text-white" rows="7"></textarea>

        <div class="flex justify-end space-x-3 mt-4">
            <button onclick="closeMsgBox()" class="px-3 py-1 bg-gray-600 rounded">Cancel</button>
            <button onclick="sendMessage()" class="px-3 py-1 bg-green-600 rounded">Send</button>
        </div>
    </div>
</div>

<script>
function toggleTracking(id) {
    document.getElementById(id).classList.toggle('hidden');
}

// OPEN POPUP BOX + FILL DEFAULT MESSAGE
function openMsgBox(customerID, name) {

    const defaultMessage = 
`DISPATCHED: Your Pilgrim Order ${customerID} is on its way. 
To track real-time, click here:

https://clpt.io/PILGRM

/MTAzMTQxNDMyNg==

Love, Pilgrim`;

    document.getElementById("msgCustomer").innerText = name;
    document.getElementById("msgCustomerID").innerText = customerID;

    document.getElementById("messageText").value = defaultMessage;

    document.getElementById("messageBox").classList.remove("hidden");
}

// CLOSE POPUP
function closeMsgBox() {
    document.getElementById("messageBox").classList.add("hidden");
}

// SEND MESSAGE (DEMO)
function sendMessage() {
    const msg = document.getElementById("messageText").value;
    const id = document.getElementById("msgCustomerID").innerText;

    alert("Message sent to " + id + ":\n\n" + msg);

    document.getElementById("messageBox").classList.add("hidden");
}
</script>

</body>
</html>

            </div>
        </div>

    </main>

</div>

<!-- Charts JS Code -->
<script>
const salesCtx = document.getElementById('salesChart');
new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun'],
        datasets: [{
            label: 'Sales',
            data: [200, 300, 250, 350, 400, 450],
            borderWidth: 3
        }]
    }
});

const productCtx = document.getElementById('productChart');
new Chart(productCtx, {
    type: 'bar',
    data: {
        labels: ['Product A','B','C','D','E'],
        datasets: [{
            label: 'Sales',
            data: [500, 1500, 1200, 900, 700],
            borderWidth: 1
        }]
    }
});
</script>

</body>
</html>
