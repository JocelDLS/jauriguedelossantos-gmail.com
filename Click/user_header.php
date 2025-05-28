<header class="header">
    <section class="flex">
        <a href="home.php" class="logo">
            <img src="image/logo.png" width="150px">
        </a>
        <nav class="navbar">
            <a href="home.php">home</a>
            <a href="about_us.php">about us</a>
            <a href="menu.php">shop</a>
            <a href="order.php">order</a>
            <a href="contact.php">contact us</a>
        </nav>
        <form action="search_product.php" method="post" class="search-form">
            <input type="text" name="search_product" placeholder="search product..." required maxlength="100">
            <button type="submit" name="search_product_btn" id="search_product_btn">
                <i class="ri-search-line"></i>
            </button>
        </form>

        <div class="icon">
            <span class="ri-play-list-add-line" id="menu-btn"></span>
            <span class="ri-search-line" id="search-btn"></span>
            
            <?php
                $count_wishlist_item = $conn->prepare("SELECT * FROM `wishlist` WHERE users_id = ? ");
                $count_wishlist_item->execute([$user_id]);
                $total_wishlist_items = $count_wishlist_item->rowCount();
            ?>
            <a href="wishlist.php"><i class="ri-heart-line"><sup><?= $total_wishlist_items;?></sup></i></a>
            <?php
                $count_cart_item = $conn->prepare("SELECT * FROM `cart` WHERE users_id = ? ");
                $count_cart_item->execute([$user_id]);
                $total_cart_items = $count_cart_item->rowCount();
            ?>
            <a href="cart.php"><i class="ri-shopping-cart-line"><sup><?= $total_cart_items;?></sup></i></a>
            <span class="ri-user-fill" id="user-btn"></span>
        </div>
        <div class="profile-detail">
            <?php
                $select_profile = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
                $select_profile->execute([$user_id]);

                if ($select_profile-> rowCount() > 0){
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <img src="uploaded_files/<?= htmlspecialchars($fetch_profile['profile_picture']); ?>" alt="Profile Image">
            <h3 style="margin-bottom: 1rem;"><?= htmlspecialchars($fetch_profile['name']); ?></h3>
            <div class="flex-btn">
                <a href="profile.php" class="btn">view profile</a>
                <a href="components/user_logout.php" onclick="return confirm('Logout from this website');" class="btn">logout</a>
            </div>
            <?php } else { ?>
                <h3 style="margin-bottom: 1rem;">Please login</h3>
                <div class="flex-btn">
                    <a href="login.php" class="btn">login</a>
                </div>
            <?php } ?>
        </div>
    </section>
</header>
