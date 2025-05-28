<?php
    include 'server.php';

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id']; 
    } else {
        header('location: Login.php');
        exit;
    }

    if (isset($_POST['place_order'])) {
        $address_id = unique_id();
        $name = $_POST['name'];
        $number = $_POST['number'];
        $email = $_POST['email'];
        $method = $_POST['method'];
        $address_type = $_POST['address_type'];
        $house_no = $_POST['house_no'];
        $street = $_POST['street'];
        $city = $_POST['city'];
        $province = $_POST['province'];
        $zip_code = $_POST['zip_code'];

        $insert_address = $conn->prepare("INSERT INTO addresses (address_id, user_id, address_type, house_no, street, city, province, zip_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_address->execute([$address_id, $user_id, $address_type, $house_no, $street, $city, $province, $zip_code]);

        if (isset($_GET['get_id'])) {
            $get_id = $_GET['get_id'];
            $select_product = $conn->prepare("SELECT * FROM products WHERE products_id = ?");
            $select_product->execute([$get_id]);
            $product = $select_product->fetch(PDO::FETCH_ASSOC);

            $order_id = unique_id();
            $seller_id = $product['seller_id'];
            $price = $product['price'];
            $qty = 1;

            $insert_order = $conn->prepare("INSERT INTO orders (order_id, user_id, seller_id, name, number, email, method, address_id, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_order->execute([$order_id, $user_id, $seller_id, $name, $number, $email, $method, $address_id, $price]);

            $insert_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, price, qty) VALUES (?, ?, ?, ?)");
            $insert_item->execute([$order_id, $get_id, $price, $qty]);

            $update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE products_id = ? AND stock >= ?");
            $update_stock->execute([$qty, $get_id, $qty]);
        } else {
            $select_cart = $conn->prepare("SELECT * FROM cart WHERE users_id = ?");
            $select_cart->execute([$user_id]);

            if ($select_cart->rowCount() > 0) {
                $items_by_seller = [];

                foreach ($select_cart as $cart_item) {
                    $product_id = $cart_item['products_id'];
                    $qty = $cart_item['qty'];

                    $select_product = $conn->prepare("SELECT * FROM products WHERE products_id = ?");
                    $select_product->execute([$product_id]);
                    $product = $select_product->fetch(PDO::FETCH_ASSOC);

                    $seller_id = $product['seller_id'];
                    $price = $product['price'];

                    $items_by_seller[$seller_id][] = [
                        'product_id' => $product_id,
                        'price' => $price,
                        'qty' => $qty
                    ];
                }

                foreach ($items_by_seller as $seller_id => $items) {
                    $order_id = unique_id();
                    $total = 0;

                    foreach ($items as $item) {
                        $total += $item['price'] * $item['qty'];
                    }

                    $insert_order = $conn->prepare("INSERT INTO orders (order_id, user_id, seller_id, name, number, email, method, address_id, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $insert_order->execute([$order_id, $user_id, $seller_id, $name, $number, $email, $method, $address_id, $total]);

                    foreach ($items as $item) {
                        $insert_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, price, qty) VALUES (?, ?, ?, ?)");
                        $insert_item->execute([$order_id, $item['product_id'], $item['price'], $item['qty']]);

                        $update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE products_id = ? AND stock >= ?");
                        $update_stock->execute([$item['qty'], $item['product_id'], $item['qty']]);
                    }
                }

                $clear_cart = $conn->prepare("DELETE FROM cart WHERE users_id = ?");
                $clear_cart->execute([$user_id]);
            }
        }

        echo "<script>alert('Order placed successfully!'); window.location.href = 'home.php';</script>";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Click n' Cart - Checkout Page</title>
    <link rel="stylesheet" href="assets/user_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" />
</head>
<body>
    <?php include 'user_header.php'; ?>

    <div class="banner">
        <div class="detail">
            <h1>checkout</h1>
            <p>Thank you for shopping with Click n' Cart! <br>
            Please review your order below. Make sure all details are correct before placing your order.</p>
            <span><a href="home.php">home</a><i class="ri-arrow-right-line"></i>checkout</span>
        </div>
    </div>

    <div class="checkout">
        <div class="heading">
            <h1>checkout summary</h1>
            <img src="image/seperator.png">
        </div>

        <div class="row">
            <form action="" method="post" class="register">
                <h3>Billing Details</h3>
                <div class="flex">
                    <div class="box">
                        <div class="input-field">
                            <p>Your Name <span>*</span></p>
                            <input type="text" name="name" required maxlength="50" value="<?= htmlspecialchars($fetch_profile['name']); ?>" class="input">
                        </div>
                        <div class="input-field">
                            <p>Your Number <span>*</span></p>
                            <input type="text" name="number" required maxlength="10" value="<?= htmlspecialchars($fetch_profile['phone_number']); ?>" class="input">
                        </div>
                        <div class="input-field">
                            <p>Your Email <span>*</span></p>
                            <input type="email" name="email" required maxlength="50" value="<?= htmlspecialchars($fetch_profile['email']); ?>" class="input">
                        </div>
                        <div class="input-field">
                            <p>Payment Method <span>*</span></p>
                            <select name="method" class="input" required>
                                <option value="cash on delivery">Cash on Delivery</option>
                                <option value="credit or debit card">Credit or Debit Card</option>
                                <option value="Gcash">Gcash</option>
                                <option value="PayMaya">PayMaya</option>
                            </select>
                        </div>
                        <div class="input-field">
                            <p>Address Type <span>*</span></p>
                            <select name="address_type" class="input" required>
                                <option value="Home">Home</option>
                                <option value="Office">Office</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="box">
                        <div class="input-field">
                            <p>House / Building No. <span>*</span></p>
                            <input type="text" name="house_no" required maxlength="20" placeholder="e.g. 27-B" class="input">
                        </div>
                        <div class="input-field">
                            <p>Street / Barangay <span>*</span></p>
                            <input type="text" name="street" required maxlength="50" placeholder="e.g. Mabini St., Brgy. Malinis" class="input">
                        </div>
                        <div class="input-field">
                            <p>City / Municipality <span>*</span></p>
                            <input type="text" name="city" required maxlength="30" placeholder="e.g. Quezon City" class="input">
                        </div>
                        <div class="input-field">
                            <p>Province / Region <span>*</span></p>
                            <input type="text" name="province" required maxlength="30" placeholder="e.g. NCR" class="input">
                        </div>
                        <div class="input-field">
                            <p>ZIP / Postal Code <span>*</span></p>
                            <input type="text" name="zip_code" required maxlength="10" placeholder="e.g. 1101" class="input">
                        </div>
                    </div>
                </div>
                <input type="submit" value="Confirm Purchase" name="place_order" class="btn">
            </form>

            <div class="summary">
                <h3>my bag</h3>
                <div class="box-container">
                    <?php
                        $grand_total = 0;
                        if (isset($_GET['get_id'])) {
                            $select_get = $conn->prepare("SELECT * FROM `products` WHERE products_id = ?");
                            $select_get->execute([$_GET['get_id']]);
                            
                            while($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)){
                                $sub_total = $fetch_get['price'];
                                $grand_total += $sub_total;
                            
                    ?>
                    <div class="flex">
                        <img src="uploaded_files/<?= $fetch_get['image'];?>" class="image">
                        <div>
                            <h3 class="name"><?= $fetch_get['name'];?></h3>
                            <p class="price"><?= $fetch_get['price'];?></p>
                        </div>
                    </div>
                    <?php
                            }
                        } else {
                            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE users_id = ?");
                            $select_cart->execute([$user_id]);

                            if ($select_cart->rowCount() > 0) {
                                while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                                    $select_products = $conn->prepare("SELECT * FROM `products` WHERE products_id = ?");
                                    $select_products->execute([$fetch_cart['products_id']]);
                                    $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                                    $sub_total = ($fetch_cart['qty'] * $fetch_products['price']);
                                    $grand_total += $sub_total;
                                
                    ?>
                    <div class="flex">
                        <img src="uploaded_files/<?= $fetch_products['image'];?>" class="image">
                        <div>
                            <h3 class="name"><?= $fetch_products['name'];?></h3>
                            <p class="price"><?= $fetch_products['price'];?> x <?= $fetch_cart['qty'];?></p>
                        </div>
                    </div>
                    <?php
                                }
                            } else {
                                echo '<p class="empty">your cart is empty</p>';
                            }
                        }
                    ?>
                </div>
                <div class="grand-total">
                    <span>total amount payable:</span>
                    <p><?= $grand_total;?></p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="js/user_scripts.js"></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>
