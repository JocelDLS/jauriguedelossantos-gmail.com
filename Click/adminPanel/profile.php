<?php
    include '../components/connect.php';

    if (isset($_COOKIE['seller_id'])) {
        $seller_id = $_COOKIE['seller_id'];
    } else {
        $seller_id = '';
        header('location: Login.php');
    }

    $select_products = $conn->prepare("SELECT * FROM `products` WHERE seller_id = ?");
    $select_products->execute([$seller_id]);
    $total_products = $select_products->rowCount();

    $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ?");
    $select_orders->execute([$seller_id]);
    $total_orders = $select_orders->rowCount();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Click n' cart</title>
    <link rel="stylesheet" href="../admin/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" />
</head>
<body>
    <div class="main-container">
        <?php include '../admin/Admin_header.php';?>
        <section class="seller-profile">
            <div class="heading">
                <h1>profile details</h1>
                <img src="../image/seperator.png">
            </div>
            <div class="details">
                <div class="seller">
                    <img src="../uploaded_files/<?= $fetch_profile['image']; ?>">
                    <h3 class="name"><?= $fetch_profile['name'];?></h3>
                    <span>seller</span>
                    <a href="update.php" class="btn">update profile</a>
                </div>
                <div class="flex">
                    <div class="box">
                        <span><?= $total_products; ?></span>
                        <p>total products</p>
                        <a href="view_products.php" class="btn">view products</a>
                    </div>
                    <div class="box">
                        <span><?= $total_orders; ?></span>
                        <p>total orders placed</p>
                        <a href="admin_orders.php" class="btn">view order</a>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <script src="../admin/admin_scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <?php include '../components/alert.php';?>
</body>
</html>
