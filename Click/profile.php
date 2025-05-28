<?php   
include 'server.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id']; 
} else {
    header('Location: Login.php');
    exit;
}

$select = $conn->prepare("
    SELECT o.*, oi.*
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    WHERE o.user_id = ?
    ORDER BY o.date_created DESC
");
$select->execute([$user_id]);
$results = $select->fetchAll(PDO::FETCH_ASSOC);

$order_ids = [];
foreach ($results as $row) {
    $order_ids[$row['order_id']] = true;
}
$total_orders = count($order_ids);

$select_message = $conn->prepare("SELECT * FROM `message` WHERE users_id = ?");
$select_message->execute([$user_id]);
$total_message = $select_message->rowCount();
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
    <section class="profile">
        <div class="heading">
            <h1>profile detail</h1>
            <img src="image/seperator.png">
        </div>
        <div class="details">
            <div class="user">
                <img src="uploaded_files/<?= htmlspecialchars($fetch_profile['profile_picture']); ?>">
                <h3><?= $fetch_profile['name'];?></h3>
                <p>user</p>
                <a href="update.php" class="btn">update profile</a>
            </div>
            <div class="box-container">
                <div class="box">
                    <div class="flex">
                        <i class="ri-folder-reduce-fill"></i>
                        <h3><?= $total_orders;?></h3>
                    </div>
                    <a href="order.php" class="btn">view orders</a>
                </div>
                <div class="box">
                    <div class="flex">
                        <i class="ri-message-fill"></i>
                        <h3><?= $total_message;?></h3>
                    </div>
                    <a href="message.php" class="btn">view message</a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php';?>
    <script src="js/user_scripts.js"></script>
    <?php include 'components/alert.php';?>
</body>
</html>
