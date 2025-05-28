<?php
    include 'server.php';

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id']; 
    } else {
        $user_id = '';
    }

    $pid = $_GET['pid'];

    include 'add_wishlist.php';
    include 'add_cart.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Click n' Cart - view page</title>
    <link rel="stylesheet" href="assets/user_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" />
</head>
<body>
    <?php include 'user_header.php';?>
    <section class="view_page">
    <div class="heading">
            <h1>products details</h1>
            <img src="image/seperator.png">
        </div>
        <?php
            if(isset($_GET['pid'])){
                $pid = $_GET['pid'];
                $select_products = $conn->prepare("SELECT * FROM `products` WHERE products_id = ?");
                $select_products->execute([$pid]);
                if($select_products->rowCount() > 0 ){
                    while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
                        
                    
        ?>
        <form action="" method="post" class="box">
            <div class="img-box">
                <img src="uploaded_files/<?= htmlspecialchars($fetch_products['image']); ?>"">
            </div>
            <div class="detail">
                    <?php if ($fetch_products['stock'] > 9){?>
                        <span class="stock" style="color: green;">In stock</span>
                    <?php }elseif ($fetch_products['stock'] == 0){?>
                        <span class="stock" style="color: red;">out of stock</span>
                    <?php }else{?>
                        <span class="stock" style="color: red;">Hurrym only <?= $fetch_products['stock'];?>left</span>
                    <?php }?>
                <p class="price"><?= htmlspecialchars($fetch_products['price']); ?></p>
                <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
                <p class="products-detail"><?= htmlspecialchars($fetch_products['product_detail']); ?></p>
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($fetch_products['products_id']); ?>">
                <div class="button">
                    <button type="submit" name="add_to_wishlist" class="btn">add to wishlist <i class="ri-heart-line"></i></button>
                    <input type="hidden" name="qty" value="1" min="0" class="quantity">
                    <button type="submit" name="add_to_cart" class="btn">add to cart <i class="ri-shopping-cart-line"></i></button>
                </div>
            </div>
        </form>

        <?php
                    }
                }
            }
        ?>
    </section>
    <div class="products">
        <div class="heading">
            <h1>Similar Products</h1>
            <?php
            $get_category = $conn->prepare("SELECT category FROM `products` WHERE products_id = ?");
            $get_category->execute([$pid]);
            $cat_data = $get_category->fetch(PDO::FETCH_ASSOC);
            $category = $cat_data ? $cat_data['category'] : '';
            ?>
            <p>You might also like these items from the same category.</p>
            <img src="image/seperator.png"> 

        </div>

        <?php
        if ($category) {
            $get_similar = $conn->prepare("SELECT * FROM `products` WHERE category = ? AND products_id != ? AND status = 'active' LIMIT 6");
            $get_similar->execute([$category, $pid]);

            if ($get_similar->rowCount() > 0) {
                echo '<div class="box-container">';
                while ($similar = $get_similar->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <form action="" method="post" class="box <?= $similar['stock'] == 0 ? 'disabled' : ''; ?>">
            <img src="uploaded_files/<?= htmlspecialchars($similar['image']); ?>" class="image">
            <?php if ($similar['stock'] > 9) { ?>
                <span class="stock" style="color: green;">In stock</span>
            <?php } elseif ($similar['stock'] == 0) { ?>
                <span class="stock" style="color: red;">Out of stock</span>
            <?php } else { ?>
                <span class="stock" style="color: red;">Hurry, only <?= $similar['stock']; ?> left</span>
            <?php } ?>
            <div class="content">
                <div class="button">
                    <div><h3 class="name"><?= htmlspecialchars($similar['name']); ?></h3></div>
                    <div>
                        <button type="submit" name="add_to_cart"><i class="ri-shopping-cart-line"></i></button>
                        <button type="submit" name="add_to_wishlist"><i class="ri-heart-line"></i></button>
                        <a href="view_page.php?pid=<?= $similar['products_id']; ?>"><i class="ri-eye-line"></i></a>
                    </div>
                </div>
                <p class="price">Price: <?= $similar['price']; ?></p>
                <input type="hidden" name="product_id" value="<?= $similar['products_id']; ?>">
                <div class="flex-btn">
                    <a href="checkout.php?getid=<?= $similar['products_id']; ?>" class="btn">Buy Now</a>
                    <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty box">
                </div>
            </div>
        </form>
        <?php
                }
                echo '</div>';
            } else {
                echo '<p class="empty">No similar products found.</p>';
            }
        }
        ?>
    </div>

    <?php include 'footer.php';?>
    <script src="js/user_scripts.js"></script>
    <?php include 'components/alert.php';?>
</body>
</html>
