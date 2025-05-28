<?php
    include '../components/connect.php';

    if (isset($_COOKIE['seller_id'])) {
        $seller_id = $_COOKIE['seller_id'];
    } else {
        $seller_id = '';
        header('location: Login.php');
        exit;
    }

    if (isset($_POST['update_order'])) {
        $order_id = $_POST['order_id'];
        $update_payment = $_POST['update_payment'];

        if ($update_payment === 'order delivered') {
            $update_order = $conn->prepare("UPDATE `orders` SET payment_status = ?, status = ? WHERE order_id = ?");
            $update_order->execute([$update_payment, 'delivered', $order_id]);
        } else {
            $update_order = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE order_id = ?");
            $update_order->execute([$update_payment, $order_id]);
        }

        $success_msg[] = 'Payment status updated!';
    }

    if (isset($_POST['delete_order'])) {
        $order_id = $_POST['order_id'];

        $delete_order = $conn->prepare("DELETE FROM `orders` WHERE order_id = ?");
        $delete_order->execute([$order_id]);

        $success_msg[] = 'Order deleted!';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Click n' cart</title>
    <link rel="stylesheet" href="../admin/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" />
</head>
<body>
    <div class="main-container">
        <?php include '../admin/Admin_header.php'; ?>
        <section class="order-container">
            <div class="heading">
                <h1>Total Orders Placed</h1>
                <img src="../image/seperator.png" alt="separator">
            </div>
            <div class="box-container">
                <?php
                    $select_order = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ?");
                    $select_order->execute([$seller_id]);
                    if ($select_order->rowCount() > 0) {
                        while ($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)) {
                            $address_id = $fetch_order['address_id'];
                            $select_address = $conn->prepare("SELECT * FROM `addresses` WHERE address_id = ?");
                            $select_address->execute([$address_id]);
                            $fetch_address = $select_address->fetch(PDO::FETCH_ASSOC);
                ?>
                <div class="box">
                    <div class="status" style="color: <?= $fetch_order['status'] == 'in progress' ? 'limegreen' : 'red'; ?>;">
                        <?= htmlspecialchars($fetch_order['status']); ?>
                    </div>
                    <div class="details">
                        <p>User Name: <span><?= htmlspecialchars($fetch_order['name']); ?></span></p>
                        <p>User ID: <span><?= htmlspecialchars($fetch_order['user_id']); ?></span></p>
                        <p>Placed On: <span><?= isset($fetch_order['date_created']) ? htmlspecialchars($fetch_order['date_created']) : 'N/A'; ?></span></p>
                        <p>User Number: <span><?= htmlspecialchars($fetch_order['number']); ?></span></p>
                        <p>User Email: <span><?= htmlspecialchars($fetch_order['email']); ?></span></p>
                        <p>Total Price: <span><?= isset($fetch_order['total_amount']) ? htmlspecialchars($fetch_order['total_amount']) : 'N/A'; ?></span></p>
                        <p>Payment Method: <span><?= htmlspecialchars($fetch_order['method']); ?></span></p>
                        <p>User Address: <span>
                            <?= htmlspecialchars($fetch_address['house_no']); ?>, 
                            <?= htmlspecialchars($fetch_address['street']); ?>, 
                            <?= htmlspecialchars($fetch_address['city']); ?>, 
                            <?= htmlspecialchars($fetch_address['province']); ?>, 
                            <?= htmlspecialchars($fetch_address['zip_code']); ?>
                        </span></p>
                        <div class="products">
                            <strong>Ordered Products:</strong><br>
                            <?php
                                $select_items = $conn->prepare("SELECT oi.*, p.name FROM `order_items` oi JOIN `products` p ON oi.product_id = p.products_id WHERE oi.order_id = ? AND p.seller_id = ?");
                                $select_items->execute([$fetch_order['order_id'], $seller_id]);
                                while ($fetch_item = $select_items->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <div class="product-item" style="margin: 5px 0;">
                                <strong>Product Name:</strong> <?= htmlspecialchars($fetch_item['name']); ?><br>
                                <strong>Quantity:</strong> <?= htmlspecialchars($fetch_item['qty']); ?><br>
                                <strong>Price:</strong> <?= htmlspecialchars($fetch_item['price']); ?><br>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <form action="" method="post">
                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($fetch_order['order_id']); ?>">
                        <select name="update_payment" class="box" style="width: 90%;">
                            <option disabled selected><?= htmlspecialchars($fetch_order['payment_status']); ?></option>
                            <option value="pending">Pending</option>
                            <option value="order delivered">Order Delivered</option>
                        </select>
                        <div class="flex_btn">
                            <input type="submit" name="update_order" value="Update Payment" class="btn">
                            <input type="submit" name="delete_order" value="Delete Order" class="btn" onclick="return confirm('Are you sure you want to delete this order?')">
                        </div>
                    </form>
                </div>
                <?php
                        }
                    } else {
                        echo '<div class="empty"><p>No orders placed yet!</p></div>';
                    }
                ?>
            </div>
        </section>  
    </div>

    <script src="../admin/admin_scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <?php include '../components/alert.php'; ?>
</body>
</html>
