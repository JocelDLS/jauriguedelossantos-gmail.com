<?php
    include '../components/connect.php';

    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_EMAIL); // better filter
    
        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    
        $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE email = ? AND password = ?");
        $select_seller->execute([$email, $pass]);
        $row = $select_seller->fetch(PDO::FETCH_ASSOC);
    
        if ($select_seller->rowCount() > 0) {
            setcookie('seller_id', $row['seller_id'], time() + 60*60*24*30, '/');
            header('Location: dashboard.php');
            exit();
        } else {
            $warning_msg[] = 'Incorrect email or password';
        }
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Click</title>
    <link rel="stylesheet" href="../admin/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" />
</head>
<body>

    <div class="form-container">

        <form action="" method="post" enctype="multipart/form-data" class="register">
            <h3>Login Now</h3>
            
                <div class="input-field">
                    <p>Email <span>*</span></p>
                    <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
                </div>
                
            
            
                <div class="input-field">
                    <p>Your Password <span>*</span></p>
                    <input type="password" name="pass" placeholder="Enter your password" maxlength="50" required class="box">
                </div>
            
            <input type="submit" name="submit" value="Login" class="btn">
        </form>
    </div>

    

    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <?php include '../components/alert.php';?>
</body>
</html>
