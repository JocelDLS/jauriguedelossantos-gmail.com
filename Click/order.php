<?php
include 'server.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    header('Location: Login.php');
    exit;
}

$select_orders = $conn->prepare("
    SELECT o.order_id, o.date_created, o.status, o.total_amount, 
           oi.product_id, oi.qty, p.name, p.image, p.price 
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.products_id
    WHERE o.user_id = ? 
    ORDER BY o.date_created DESC
");
$select_orders->execute([$user_id]);
$orders = $select_orders->fetchAll(PDO::FETCH_ASSOC);

function formatPrice($price) {
    return "₱" . number_format($price, 2);
}

function getStatusColor($status) {
    switch ($status) {
        case 'delivered':
            return 'green';
        case 'canceled':
            return 'red';
        default:
            return 'orange';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Click n' Cart</title>
    <link rel="stylesheet" href="assets/user_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" />
</head>
<body>
<?php include 'user_header.php'; ?>

<div class="banner">
    <div class="detail">
        <h1>My Orders</h1>
        <p>Welcome to your orders page! Here you can find all the orders you have placed so far. You can review your order status,<br> payment details, and track the progress of your purchases.</p>
        <p>If you have any questions or need assistance with your orders, feel free to contact customer support.</p>
        <span><a href="home.php">Home</a><i class="ri-arrow-right-line"></i>My Orders</span>
    </div>
</div>

<div class="orders">
    <div class="heading">
        <h1>My Orders</h1>
        <img src="image/seperator.png" alt="Separator">
    </div>

    <div class="box-container">
        <?php
        if (count($orders) > 0) {
            $grouped_orders = [];
            foreach ($orders as $order) {
                $grouped_orders[$order['order_id']][] = $order;
            }

            foreach ($grouped_orders as $order_id => $order_items) {
                $order = $order_items[0];
                $order_status_color = getStatusColor($order['status']);
                ?>
                <div class="box" <?php if ($order['status'] == 'canceled') echo 'style="border:2px solid red;"'; ?>>
                    <a href="view_order.php?get_id=<?= $order['order_id']; ?>">
                        <img src="uploaded_files/<?= $order_items[0]['image'] ?? 'default.jpg'; ?>" class="image" alt="Product Image">
                        <p class="date"><i class="ri-calendar-line"></i><?= date('F j, Y', strtotime($order['date_created'])); ?></p>
                        <div class="content">
                            <div class="row">
                                <h3 class="name"><?= $order_items[0]['name']; ?> + <?= count($order_items) - 1; ?> more</h3>
                                <p class="price">Total: <?= formatPrice($order['total_amount']); ?></p>
                                <p class="status" style="color: <?= $order_status_color; ?>;">
                                    <?= ucfirst($order['status']); ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                <?php
            }
        } else {
            echo '<p class="empty">No orders placed yet!</p>';
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="js/user_scripts.js"></script>
<?php include 'components/alert.php'; ?>
</body>
</html>
