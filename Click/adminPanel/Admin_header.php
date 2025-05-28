<header>
    <div class="logo">
        <img src="../image/logo.png" width="150">
    </div>
    <div class="right">
        <div id="user-btn"><i class="ri-user-fill"></i></div>
        <div class="toggle-btn"><i class="ri-menu-line"></i></div>
    </div>
    <div class="profile-detail">
        <?php
            $select_profile = $conn->prepare("SELECT * FROM sellers WHERE seller_id = ?");
            $select_profile->execute([$seller_id]);

            if ($select_profile-> rowCount() > 0){
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="profile">
            <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" class="logo-img" width="100">
            <p><?= $fetch_profile['name']; ?></p>
            <div class="flex-btn">
                <a href="profile.php" class="btn">Profile</a>
                <a href="../components/admin_logout.php" onclick="return confirm('logout form this website?')" class="btn">logout</a>
            </div>
        </div>
        <?php } ?>
    </div>
</header>


<div class="sidebar-container"> 
    <div class="sidebar">
        <?php
            $select_profile = $conn->prepare("SELECT * FROM sellers WHERE seller_id = ?");
            $select_profile->execute([$seller_id]);

            if ($select_profile-> rowCount() > 0){
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="profile">
            <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" class="logo-img" width="100">
            <p><?= $fetch_profile['name']; ?></p>
        </div>
        <?php } ?>
        <h5>menu</h5>
        <div class="navbar">
            <ul>
                <li><a href="dashboard.php"><i class="ri-home-smile-fill"></i> Dashboard</a></li>
                <li><a href="add_products.php"><i class="ri-shopping-bag-4-fill"></i> Add Product</a></li>
                <li><a href="view_products.php"><i class="ri-list-view"></i> view Product</a></li>
                <li><a href="user_accounts.php"><i class="ri-user-fill"></i> accouts</a></li>
                <li><a href="../components/admin_logout.php" onclick="return confirm('logout form this website?')"><i class="ri-logout-box-line"></i> Log Out</a></li>
            </ul>
        </div>
    </div>
</div>



