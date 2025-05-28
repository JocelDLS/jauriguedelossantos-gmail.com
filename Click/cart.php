<?php
    include 'server.php';

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id']; 
    } else {
        header('location: Login.php'); 
    }

    if (isset($_POST['update_cart'])) {
        $cart_id = $_POST['cart_id'];
        $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);

        $qty = $_POST['qty'];
        $qty = filter_var($qty, FILTER_SANITIZE_STRING);

        $update_qty = $conn->prepare("UPDATE `cart` SET qty = ? WHERE cart_id = ?");
        $update_qty->execute([$qty, $cart_id]);

        $success_msg[] = 'Cart quantity updated successfully';
    }

    if (isset($_POST['delete_item'])) {
        $cart_id = $_POST['cart_id'];
        $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);

        $verify_delete = $conn->prepare("SELECT * FROM `cart` WHERE cart_id = ?");
        $verify_delete->execute([$cart_id]);

        if($verify_delete->rowCount() > 0) {
            $delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE cart_id = ?");
            $delete_cart_id->execute([$cart_id]);
            $success_msg[] = 'cart item delete successfully';
        } else {
            $warning_msg[] = 'cart Item already deleted';
        }
    }

    if (isset($_POST['empty_cart'])) {
        $verify_empty_item = $conn->prepare("SELECT * FROM `cart` WHERE users_id = ?");
        $verify_empty_item->execute([$user_id]);
    
        if ($verify_empty_item->rowCount() > 0) {
            $delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE users_id = ?");
            $delete_cart_id->execute([$user_id]);
    
            $success_msg[] = 'Your cart has been emptied successfully.';
        } else {
            $warning_msg[] = 'Your cart is already empty.';
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
    <?php include 'user_header.php';?>
    <div class="products">
    <div class="heading">
            <h1>My Cart</h1>
            <img src="image/seperator.png">
        </div>
        <div class="box-container">
            <?php
                $grand_total = 0;

                $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE users_id = ?");
                $select_cart->execute([$user_id]);

                if ($select_cart->rowCount() > 0){
                    while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){

                        $select_products = $conn->prepare("SELECT * FROM `products` WHERE products_id = ?");
                        $select_products->execute([$fetch_cart['products_id']]);

                        if ($select_products->rowCount() > 0){
                            $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                            
            ?>
            <form action="" method="post" class="box <?php if($fetch_products['stock'] === 0){echo "disabled";}?>">
                <input type="hidden" name="cart_id" value="<?= $fetch_cart['cart_id']; ?>">
                <img src="uploaded_files/<?= htmlspecialchars($fetch_products['image']); ?>" class="image">
                
                <?php if ($fetch_products['stock'] > 9) { ?>
                    <span class="stock" style="color: green;">In stock</span>
                <?php } elseif ($fetch_products['stock'] == 0) { ?>
                    <span class="stock" style="color: red;">Out of stock</span>
                <?php } else { ?>
                    <span class="stock" style="color: red;">Hurry! Only <?= $fetch_products['stock']; ?> left</span>
                <?php } ?>
                
                <div class="content">
                    <h3 class="name"><?= $fetch_products['name']; ?></h3>
                    <div class="flex-btn">
                        <p class="price">Price <?= $fetch_products['price']; ?></p>
                        <input type="number" name="qty" required min="1" value="<?= $fetch_cart['qty']; ?>" max="99" maxlength="2" class="box qty">
                        <button type="submit" name="update_cart" class="ri-edit-box-line fa-edit box" title="Update quantity"></button>
                    </div>
                    <div class="flex-btn">
                        <p class="sub-total">Sub total: <span><?= $sub_total = ($fetch_cart['qty'] * $fetch_products['price']); ?></span></p>
                        <button type="submit" name="delete_item" class="btn" onclick="return confirm('Remove from cart?');">Delete</button>
                    </div>
                </div>
            </form>
            <?php
                        $grand_total += $sub_total; 
                    } else {
                        echo '<p class="empty">No products found!</p>';
                    }
                }
            } else {
                echo '<p class="empty">No products added yet!</p>';
            }
            ?>
        </div>
        <?php if($grand_total != 0) { ?>
            <div class="cart-total">
                <p>Total amount payable: <span> <?= $grand_total; ?></span></p>
                <div class="button">
                    <form action="" method="post">
                        <button type="submit" name="empty_cart" class="btn" onclick="return confirm('Are you sure you want to empty your cart?');">Empty Cart</button>
                    </form>
                    <a href="checkout.php" class="btn">Proceed to Checkout</a>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php include 'footer.php';?>
    <script src="js/user_scripts.js"></script>
    <?php include ''; ?>
</body>
</html>
