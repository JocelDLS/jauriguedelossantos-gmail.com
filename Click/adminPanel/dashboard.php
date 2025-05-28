<?php
    include '../components/connect.php';

    if (isset($_COOKIE['seller_id'])) {
        $seller_id = $_COOKIE['seller_id'];
    } else {
        $seller_id = '';
        header('location: Login.php');
    }

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
        <section class="dashboard">
            <div class="heading">
                <h1>dashboard</h1>
                <img src="../image/seperator.png">
            </div>
            <div class="box-container">
                <div class="box">
                    <?php
                        $select_messege = $conn-> prepare("SELECT * FROM `message`");
                        $select_messege-> execute();
                        $number_of_msg = $select_messege->rowCount();
                    ?>
                    <h3><?=$number_of_msg; ?></h3>
                    <p>unread message</p>
                    <a href="admin_message.php" class="btn">see message</a>
                </div>
                <div class="box">
                    <?php
                        $select_products = $conn-> prepare("SELECT * FROM `products` WHERE seller_id = ?");
                        $select_products-> execute([$seller_id]);
                        $number_of_products = $select_products->rowCount();
                    ?>
                    <h3><?=$number_of_products; ?></h3>
                    <p>product added</p>
                    <a href="add_products.php" class="btn">your products</a>
                </div>
                <div class="box">
                    <?php
                        $select_active_products = $conn-> prepare("SELECT * FROM `products` WHERE seller_id = ? AND status = ?");
                        $select_active_products-> execute([$seller_id, 'active' ]);
                        $number_of_active_products = $select_active_products->rowCount();
                    ?>
                    <h3><?=$number_of_active_products; ?></h3>
                    <p>total active products</p>
                    <a href="view_products.php" class="btn">active products</a>
                </div>
                <div class="box">
                    <?php
                        $select_deactive_products = $conn-> prepare("SELECT * FROM `products` WHERE seller_id = ? AND status = ?");
                        $select_deactive_products-> execute([$seller_id, 'deactive' ]);
                        $number_of_deactive_products = $select_deactive_products->rowCount();
                    ?>
                    <h3><?=$number_of_deactive_products; ?></h3>
                    <p>total deactive products</p>
                    <a href="view_products.php" class="btn">deactive products</a>
                </div>
                <div class="box">
                    <?php
                        $select_order = $conn-> prepare("SELECT * FROM `orders` WHERE seller_id =?");
                        $select_order-> execute([$seller_id]);
                        $number_of_order = $select_order->rowCount();
                    ?>
                    <h3><?=$number_of_order; ?></h3>
                    <p>total orders place</p>
                    <a href="admin_orders.php" class="btn">total orders</a>
                </div>
                <div class="box">
                    <?php
                        $select_confirm_order = $conn-> prepare("SELECT * FROM `orders` WHERE seller_id = ? AND status = ?");
                        $select_confirm_order-> execute([$seller_id, 'in progress']);
                        $number_of_confirm_order = $select_confirm_order->rowCount();
                    ?>
                    <h3><?=$number_of_confirm_order; ?></h3>
                    <p>total confirm orders</p>
                    <a href="admin_orders.php" class="btn">confirm orders</a>
                </div>
                <div class="box">
                    <?php
                        $select_canceled_order = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ? AND status = ?");
                        $select_canceled_order->execute([$seller_id, 'canceled']);
                        $number_of_canceled_order = $select_canceled_order->rowCount();
                    ?>
                    <h3><?=$number_of_canceled_order; ?></h3>
                    <p>total canceled orders</p>
                    <a href="admin_orders.php" class="btn">canceled orders</a>
                </div>
                <div class="box">
                    <?php
                        $select_user = $conn-> prepare("SELECT * FROM `users`");
                        $select_user-> execute();
                        $number_of_user = $select_user->rowCount();
                    ?>
                    <h3><?=$number_of_user; ?></h3> 
                    <p>user account</p>
                    <a href="user_accounts.php" class="btn">see users</a>
                </div>
            </div>
        </section>
    </div>


    <script src="../admin/admin_scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <?php include '../components/alert.php';?>
</body>
</html>
