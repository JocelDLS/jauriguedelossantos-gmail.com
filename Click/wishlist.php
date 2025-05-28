<?php
    include 'server.php';

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id']; 
    } else {
        $user_id = 'location: Login.php';
    }

    include 'add_cart.php';

    if (isset($_POST['delete_item'])) {
        
        $wishlist_id = $_POST['wishlist_id'];
        $wishlist_id = filter_var($wishlist_id, FILTER_SANITIZE_STRING);

        $verify_delete = $conn->prepare("SELECT * FROM `wishlist` WHERE wish_id = ?");
        $verify_delete->execute([$wishlist_id]);

        if($verify_delete->rowCount() > 0 ){
            $delete_wishlist_id = $conn->prepare("DELETE FROM `wishlist` WHERE wish_id = ?");
            $delete_wishlist_id->execute([$wishlist_id]);
            $success_msg[] = 'item removed from wishlist';
        }else{
            $warning_msg[] = 'item already removed';
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
            <h1>my wishlist</h1>
            <img src="image/seperator.png">
        </div>
        <div class="box-container">
            <?php
                $grand_total = 0;

                $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE users_id = ?");
                $select_wishlist->execute([$user_id]);

                if ($select_wishlist->rowCount() > 0){
                    while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){

                        $select_products = $conn->prepare("SELECT * FROM `products` WHERE products_id = ?");
                        $select_products->execute([$fetch_wishlist['products_id']]);

                        if ($select_products->rowCount() > 0){
                            $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                            
            ?>
            <form action="" method="post" class="box <?php if($fetch_products['stock'] === 0){echo "disabled";}?>">
                <input type="hidden" name="wishlist_id" value="<?= $fetch_wishlist['wish_id'];?>">
                <img src="uploaded_files/<?= htmlspecialchars($fetch_products['image']); ?>" class="image">
                        <?php if ($fetch_products['stock'] > 9){?>
                            <span class="stock" style="color: green;">In stock</span>
                        <?php }elseif ($fetch_products['stock'] == 0){?>
                            <span class="stock" style="color: red;">out of stock</span>
                        <?php }else{?>
                            <span class="stock" style="color: red;">Hurrym only <?= $fetch_products['stock'];?></span>
                        <?php }?>
                <div class="content">
                    <div class="button">
                        <div><h3><?= $fetch_products['name']; ?></h3></div>
                        <div>
                            <button type="submit" name="add_to_cart"><i class="ri-shopping-cart-line"></i></button>
                            <a href="view_page.php?pid=<?= $fetch_products['products_id'];?>"><i class="ri-eye-line"></i></a>
                            <button type="submit" name="delete_item" onclick="return confirm('remove from wishlist');"><i class="ri-close-line"></i></button>
                        </div>
                    </div>
                    <input type="hidden" name="product_id" value="<?= $fetch_products['products_id']?>">
                    <div class="flex">
                        <p class="price">price  <?= $fetch_products['price'];?>.00</p>
                    </div>
                    <div class="flex">
                        <input type="hidden" name="qty" required min = "1" value="1" max="99" maxlength="2" class="qty">
                        <a href="checkout.php?get_id=<?= $fetch_products['products_id']; ?>" class="btn">Buy Now</a>    
                    </div>
                </div>
            </form>
            <?php
                        $grand_total += $fetch_wishlist['price'];
                        }
                    }
                }else {
                    echo '<p class="empty">No products added yet!</p>';
                }
            ?>
        </div>
    </div>

    <?php include 'footer.php';?>
    <script src="js/user_scripts.js"></script>
    <?php include 'components/alert.php';?>
</body>
</html>
