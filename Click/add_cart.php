<?php
    if (isset($_POST['add_to_cart'])) {
        if ($user_id != '') {

            $id = unique_id();
            $product_id = $_POST['product_id'];

            if (isset($_POST['qty']) && filter_var($_POST['qty'], FILTER_VALIDATE_INT) !== false && $_POST['qty'] > 0) {
                $qty = $_POST['qty'];
            } else {
                $qty = 1;
            }

            $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE users_id = ? AND products_id = ?");
            $verify_cart->execute([$user_id, $product_id]);

            $max_cart_item = $conn->prepare("SELECT * FROM `cart` WHERE users_id = ?");
            $max_cart_item->execute([$user_id]);    

            if ($verify_cart->rowCount() > 0) {
                $warning_msg[] = 'Product already exists in your cart';
            } else if ($max_cart_item->rowCount() > 20) {
                $warning_msg[] = 'Your cart is full';
            } else {
                $select_price = $conn->prepare("SELECT * FROM `products` WHERE products_id = ? LIMIT 1");
                $select_price->execute([$product_id]);
                $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

                $insert_cart = $conn->prepare("INSERT INTO `cart` (cart_id, users_id, products_id, price, qty) VALUES (?, ?, ?, ?, ?)");
                $insert_cart->execute([$id, $user_id, $product_id, $fetch_price['price'], $qty]);

                $success_msg[] = 'Product added to your cart successfully';
            }

        } else {
            $warning_msg[] = 'Please login first';
        }
    }
?>
