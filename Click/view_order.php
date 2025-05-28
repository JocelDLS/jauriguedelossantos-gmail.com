<?php
    include 'server.php';

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    } else {
        header('location: Login.php');
        exit();
    }

    if (isset($_GET['get_id'])) {
        $get_id = $_GET['get_id'];
    } else {
        header('location:order.php');
        exit();
    }

    if (isset($_POST['cancel'])) { 
        $update_order = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?"); 
        $update_order->execute(['canceled', $get_id]); 
        header('location:order.php'); 
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
        <h1>order details</h1>
        <p>Here you can find a list of all the orders you've placed. Each order shows the product name, date, status, and price.</p>
        <p>You can click on any order to view more details, track its status, or review your past purchases.</p>
        <p>If you have any issues or questions about your order, our customer support is here to help.</p>
        <span><a href="home.php">home</a><i class="ri-arrow-right-line"></i>order details</span>
    </div>
</div>
<div class="order-detail">
    <div class="heading">
        <h1>my order detail</h1>
        <img src="image/seperator.png">
    </div>
    <div class="box-container">
        <?php
        $grand_total = 0;
        $select_order = $conn->prepare("SELECT * FROM `orders` WHERE order_id = ? LIMIT 1");
        $select_order->execute([$get_id]);

        if ($select_order->rowCount() > 0) {
            $fetch_order = $select_order->fetch(PDO::FETCH_ASSOC);

            $select_address = $conn->prepare("SELECT * FROM `addresses` WHERE address_id = ? LIMIT 1");
            $select_address->execute([$fetch_order['address_id']]);
            $fetch_address = $select_address->fetch(PDO::FETCH_ASSOC);
            $full_address = $fetch_address ?
                'House No. ' . $fetch_address['house_no'] . ', ' .
                $fetch_address['street'] . ', ' .
                $fetch_address['city'] . ', ' .
                $fetch_address['province'] . ', ' .
                $fetch_address['zip_code'] :
                'Address not found';

            $select_items = $conn->prepare("SELECT * FROM `order_items` WHERE order_id = ?");
            $select_items->execute([$fetch_order['order_id']]);

            while ($item = $select_items->fetch(PDO::FETCH_ASSOC)) {
                $select_product = $conn->prepare("SELECT * FROM `products` WHERE products_id = ? LIMIT 1");
                $select_product->execute([$item['product_id']]);

                if ($select_product->rowCount() > 0) {
                    $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);

                    $sub_total = $item['price'] * $item['qty'];
                    $grand_total += $sub_total;
                    ?>
                    <div class="box">
                        <div class="col">
                            <p class="title"><i class="ri-calendar-line"></i><?= $fetch_order['date_created']; ?></p>
                            <img src="uploaded_files/<?= $fetch_product['image']; ?>" class="image">
                            <p class="price">₱<?= $fetch_product['price']; ?></p>
                            <h3 class="name"><?= $fetch_product['name']; ?></h3>
                            <p class="grand-total">total amount payable <span>₱<?= number_format($grand_total, 2); ?></span></p>
                        </div>
                        <div class="col">
                            <p class="title">billing address</p>
                            <p class="user"><i class="ri-user-3-line"></i><?= $fetch_order['name']; ?></p>
                            <p class="user"><i class="ri-phone-line"></i><?= $fetch_order['number']; ?></p>
                            <p class="user"><i class="ri-mail-line"></i><?= $fetch_order['email']; ?></p>
                            <p class="user"><i class="ri-map-pin-line"></i><?= $full_address; ?></p>
                            <p class="status" style="color:
                                <?php
                                if ($fetch_order['status'] == 'delivered') echo 'green';
                                elseif ($fetch_order['status'] == 'canceled') echo 'red';
                                else echo 'orange';
                                ?>">
                                <?= $fetch_order['status']; ?>
                            </p>
                            <?php if ($fetch_order['status'] == 'canceled' || $fetch_order['status'] == 'delivered') { ?>
                                <a href="checkout.php?get_id=<?= $fetch_product['products_id']; ?>" class="btn">order again</a>
                            <?php } else { ?>
                                <form action="" method="post">
                                    <button type="submit" name="cancel" class="btn" onclick="return confirm('Do you want to cancel this product?');">cancel</button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                }
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
