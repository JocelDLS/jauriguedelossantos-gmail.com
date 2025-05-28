<?php   
    include 'server.php';

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id']; 
    } else {
        $user_id = '';
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

    <div class="slider-container">
        <div class="slider">
            <div class="slideBox active">
                <div class="textBox">
                    <h1>Save Time <br> the convenience of efficient, <br> time-saving online grocery shopping.</h1>
                    <a href="menu.php" class="btn">shop now</a>
                </div>
                <div class="imgBox">
                    <img src="image/home-bg.jpg" alt="">
                </div>
            </div>
            <div class="slideBox ">
                <div class="textBox">
                    <h1>Save Money<br> Benefit from competitive pricing and <br> exclusive digital promotions.</h1>
                    <a href="menu.php" class="btn">shop now</a>
                </div>
                <div class="imgBox">
                    <img src="image/bg.jpg">
                </div>
            </div>
            <div class="slideBox ">
                <div class="textBox">
                    <h1> Shop Online <br> Enjoy a seamless shopping experience <br> with reliable home delivery.</h1>
                    <a href="menu.php" class="btn">shop now</a>
                </div>
                <div class="imgBox">
                    <img src="image/bg.jpg">
                </div>
            </div>
        </div>
        <ul class="controls">
            <li onclick="nextSlide();" class="next"><i class="ri-arrow-right-line"></i></li>
            <li onclick="prevSlide();" class="prev"><i class="ri-arrow-left-line"></i></li>
        </ul>
    </div>

    <div class="service">
        <div class="box-container">
            <div class="box">
                <div class="icon">
                    <div class="icon-box">
                        <img src="image/services.png" class="img1">
                        <img src="image/services (1).png" class="img2">
                    </div>
                </div>
                <div class="detail">
                    <h4>Delivery</h4>
                    <span>100% Secure</span>
                </div>
            </div>
            <div class="box">
                <div class="icon">
                    <div class="icon-box">
                        <img src="image/services (2).png" class="img1">
                        <img src="image/services (3).png" class="img2">
                    </div>
                </div>
                <div class="detail">
                    <h4>Returns</h4>
                    <span>24/7 Free Returns</span>
                </div>
            </div>
            <div class="box">
                <div class="icon">
                    <div class="icon-box">
                        <img src="image/services (5).png" class="img1">
                        <img src="image/services (6).png" class="img2">
                    </div>
                </div>
                <div class="detail">
                    <h4>payment</h4>
                    <span>100% secure</span>
                </div>
            </div>
        </div>
    </div>
    <div class="categories">
    <div class="heading">
        <h1>categories features</h1>
        <img src="image/seperator.png">
    </div>
    <div class="box-container">
        <div class="box">
            <img src="image/toiletries.jpg">
            <a href="menu.php" class="btn">Toiletries</a>
        </div>
            <div class="box">
                <img src="image/food.jpg">
                <a href="menu.php" class="btn">Farm Product</a>
            </div>
            <div class="box">
                <img src="image/ketchupjpg.jpg">
                <a href="menu.php" class="btn">Kitchen Stocks</a>
            </div>
            <div class="box">
                <img src="image/cookie.jpg">
                <a href="menu.php" class="btn">Snacks and Biscuits</a>
            </div>
            <div class="box">
                <div class="img">
                    <img src="image/softdrinks.jpg">
                </div>
                <a href="menu.php" class="btn">Drinks and Beverages</a>
            </div>
        </div>
    </div>
    <img src="image/Screenshot 2025-04-30 215152.png" class="menu-banner">

    <div class="products">
    <div class="heading">
        <h1>our latest products</h1>
        <img src="image/seperator.png">
    </div>
        <div class="box-container">
            <?php
                $select_products = $conn->prepare("SELECT * FROM `products` WHERE status = ? LIMIT 3");
                $select_products->execute(['active']);

                if ($select_products->rowCount() > 0) {
                    while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <form action="" method="post" class="box <?php if ($fetch_products['stock'] == 0) { echo 'disabled'; } ?>">
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
                            <div><h3 class="name"><?= htmlspecialchars($fetch_products['name']); ?></h3></div>
                            <div>
                                <button type="submit" name="add_to_cart"><i class="ri-shopping-cart-line"></i></button>
                                <button type="submit" name="add_to_wishlist"><i class="ri-heart-line"></i></button>
                                <a href="view_page.php?pid=<?= $fetch_products['products_id']; ?>">
                                    <i class="ri-eye-line"></i>
                                </a>
                            </div>
                        </div>
                        <p class="price">price:<?= $fetch_products['price']; ?></p>
                        <input type="hidden" name="product_id" value="<?= $fetch_products['products_id']; ?>">
                        <div class="flex-btn">
                            <a href="checkout.php?getid=<?= $fetch_products['products_id']; ?>" class="btn">buy now</a>
                            <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty box">
                        </div>
                    </div>

                </form>
            <?php
                    }
                } else {
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
