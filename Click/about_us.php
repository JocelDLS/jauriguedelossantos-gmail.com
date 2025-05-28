<?php
    include 'server.php';

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id']; 
    } else {
        $user_id = 'location: Login.php';
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
    <div class="banner">
        <div class="detail">
            <h1>About us</h1>
            <p>Welcome to Click n' Cart, your trusted online grocery destination! We’re here to make your shopping easier, 
                <br> faster, and more convenient  all from the comfort of your home.
                <br><br> At Click n' Cart, we believe grocery shopping should be simple. No more long lines, heavy bags, or last-minute
                <br> store runs. With just a few clicks, you can explore a wide range of fresh produce, daily essentials, 
                <br> household items, and more  all at competitive prices and delivered right to your door.</p>
            <span><a href="home.php">home</a><i class="ri-arrow-right-line"></i>about us</span>
        </div>
    </div>

    <?php include 'footer.php';?>
    <script src="js/user_scripts.js"></script>
    <?php include 'components/alert.php';?>
</body>
</html>
