<?php
    include 'server.php';

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id']; 
    } else {
        $user_id = '';
    }

    if (isset($_POST['send_message'])){
        if ($user_id != ''){

            $id = unique_id();
            $name = $_POST['name'];
            $name = filter_var($name, FILTER_SANITIZE_STRING);

            $email = $_POST['email'];
            $email = filter_var($email, FILTER_SANITIZE_STRING);

            $subject = $_POST['subject'];
            $subject = filter_var($subject, FILTER_SANITIZE_STRING);
            
            $message = $_POST['message'];
            $message = filter_var($message, FILTER_SANITIZE_STRING);  
            $verify_message = $conn->prepare("SELECT * FROM `message` WHERE users_id = ? AND name = ? AND email = ? AND subject = ? AND message = ?");
            $verify_message->execute([$user_id, $name, $email, $subject, $message]);

            if($verify_message->rowCount() > 0 ){
                $warning_message[] = 'Message already exists';
            } else {
                $insert_message = $conn->prepare("INSERT INTO `message` (msg_id, users_id, name, email, subject, message) VALUES (?,?,?,?,?,?)");
                $insert_message->execute([$id, $user_id, $name, $email, $subject, $message]);  
            }
        } else {
            $warning_message[] = 'Please login first';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Click n' Cart - Contact Us Page</title>
    <link rel="stylesheet" href="assets/user_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" />
</head>
<body>
    <?php include 'user_header.php';?>
    <div class="banner">
        <div class="detail">
            <h1>Contact Us</h1>
            <p>We’re here to help! If you have any questions or need assistance, feel free to reach out through the contact <br> form, email, or phone. Our team is ready to assist you and ensure you have the best experience with Click n' Cart.</p>
            <span><a href="home.php">Home</a><i class="ri-arrow-right-line"></i>Contact Us</span>
        </div>
    </div>
    <div class="services">
        <div class="heading">
            <h1>Our Services</h1>
            <p>Click n' Cart offers a hassle-free online grocery shopping experience with fast delivery, secure payments, and excellent customer support.</p>
            <img src="image/seperator.png">
        </div>
        <div class="box-container">
            <div class="box">
                <img src="image/fast-delivery.png">
                <div>
                    <h1>Fast & Reliable Delivery</h1>
                    <p>We ensure fast delivery to your doorstep so that you can enjoy your groceries without any delays. Your order will arrive fresh and on time, every time!</p>
                </div>
            </div>
            <div class="box">
                <img src="image/return.png">
                <div>
                    <h1>Easy Returns</h1>
                    <p>If you're not satisfied with a product, our hassle-free returns process makes it easy to return items and get your money back or an exchange.</p>
                </div>
            </div>
            <div class="box">
                <img src="image/secure-payment.png">
                <div>
                    <h1>Secure Payment Options</h1>
                    <p>We offer secure payment methods for a safe and easy checkout experience when purchasing your groceries.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="form-container">
        <div class="heading">
            <h1>Drop Us a Line</h1>
            <p>If you have any questions or feedback, feel free to reach out to us! We're here to assist you and make your experience even better.</p>
            <img src="image/seperator.png">
        </div>
        <form action="" method="post" class="register">
            <div class="input-field">
                <label>Name <sup>*</sup></label>
                <input type="text" name="name" required placeholder="Enter your name" class="box">
            </div>
            <div class="input-field">
                <label>Email <sup>*</sup></label>
                <input type="email" name="email" required placeholder="Enter your email" class="box">
            </div>
            <div class="input-field">
                <label>Subject <sup>*</sup></label>
                <input type="text" name="subject" required placeholder="Reason.." class="box">
            </div>
            <div class="input-field">
                <label>Comment <sup>*</sup></label>
                <textarea name="message" cols="30" rows="10" required placeholder="Your message..." class="box"></textarea>
            </div>
            <button type="submit" name="send_message" class="btn">Send Message</button>
        </form>
    </div>

    <?php include 'footer.php';?>
    <script src="js/user_scripts.js"></script>
    <?php include 'components/alert.php';?>
</body>
</html>
