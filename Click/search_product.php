<?php
    include 'server.php';

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id']; 
    } else {
        $user_id = 'location: Login.php';
    }

    include 'add_wishlist.php';
    include 'add_cart.php';

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
    <div class="banner">
        <div class="detail">
            <h1>search products</h1>
            <p>Looking for something specific? Use the search bar below to quickly find your favorite products from our wide selection!</p>
            <span><a href="home.php">home</a><i class="ri-arrow-right-line"></i>search products</span>
        </div>
    </div>
    <div class="products">
            <div class="heading">
                <h1>search result</h1>
                <img src="image/seperator.png">
            </div>
            <div class="box-container">
            <?php
                if (isset($_POST['search_product']) || isset($_POST['search_product_btn'])) {

                    $search_products = $_POST['search_product'];
                    $select_products = $conn->prepare("SELECT * FROM `products` WHERE (name LIKE ? OR category LIKE ?) AND status = ?");
                    $select_products->execute(["%$search_products%", "%$search_products%", 'active']);

                    if ($select_products->rowCount() > 0) {
                        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                            $product_id = $fetch_products['products_id'];
                            
            ?>

                <form action="" method="post" class="box <?php if ($fetch_products['stock'] == 0) { echo 'disabled'; } ?>">
                        <img src="uploaded_files/<?= htmlspecialchars($fetch_products['image']); ?>" class="image">
                        <?php if ($fetch_products['stock'] > 9){?>
                            <span class="stock" style="color: green;">In stock</span>
                        <?php }elseif ($fetch_products['stock'] == 0){?>
                            <span class="stock" style="color: red;">out of stock</span>
                        <?php }else{?>
                            <span class="stock" style="color: red;">Hurrym only <?= $fetch_products['stock'];?>left</span>
                        <?php }?>
                        <div class="content">
                            <div class="button">
                                <div><h3 class="name"><?= htmlspecialchars($fetch_products['name']); ?></h3></div>
                                <div>
                                <button type="submit" name="add_to_cart"><i class="ri-shopping-cart-line"></i></button>
                                <button type="submit" name="add_to_wishlist"><i class="ri-heart-line"></i></button>
                                    <a href="view_page.php?pid=<?= $fetch_products['products_id']; ?>">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </div>
                            </div>
                            <p class="price">price:<?= $fetch_products['price']; ?>.00</p>
                            <input type="hidden" name="product_id" value="<?= $fetch_products['products_id']; ?>">
                            <div class="flex-btn">
                                <a href="checkout.php?getid=<?= $fetch_products['products_id']; ?>" class="btn">buy now</a>
                                <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty box">
                            </div>
                        </div>

                    </form>
                <?php
                            }
                        }else {
                            echo '<p class="empty">No products added yet!</p>';
                        }
                    }else {
                        echo '<p class="empty">please search something</p>';
                    }
                
                ?>
            </div>
        </div>
    <?php include 'footer.php';?>
    <script src="js/user_scripts.js"></script>
    <?php include 'components/alert.php';?>
</body>
</html>
