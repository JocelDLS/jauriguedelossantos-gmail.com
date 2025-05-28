<?php
    include '../components/connect.php';

    if (isset($_POST['submit'])){
        $id = unique_id();
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);

        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $max_size = 2 * 1024 * 1024; // 2MB

        $image = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];

        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_ext)) {
            $warning_msg[] = 'Unsupported image type!';
        } elseif ($image_size > $max_size) {
            $warning_msg[] = 'Image size is too large!';
        } else {
            $rename = unique_id().'.'.$ext;
            $image_folder = '../uploaded_files/'.$rename;

            // Ensure folder exists
            if (!is_dir('../uploaded_files')) {
                mkdir('../uploaded_files', 0777, true);
            }

            if (move_uploaded_file($image_tmp_name, $image_folder)) {
                // Proceed with DB insert (you probably already handle this)
            } else {
                $warning_msg[] = 'Failed to upload image.';
            }
        }

        $select_seller = $conn -> prepare("SELECT * FROM `sellers` WHERE email = ?");
        $select_seller-> execute([$email]);

        if ($select_seller-> rowCount() > 0){
            $warning_msg[] = 'email already exist!';
        }else{
            $insert_seller = $conn -> prepare("INSERT INTO `sellers` (seller_id, name, email, password, image) VALUES (?, ?, ?, ?, ?)");
            $insert_seller-> execute([$id, $name, $email, $pass, $rename]);
            move_uploaded_file($image_tmp_name, $image_folder);
            $success_msg[] = 'new seller registered! please loging now';
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Click</title>
    <link rel="stylesheet" href="admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" />
</head>
<body>

    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data" class="register">
            <h3>Register Now</h3>
            
            <div class="flex">
                <div class="col">
                <div class="input-field">
                    <p>Your Name <span>*</span></p>
                    <input type="text" name="name" placeholder="Enter your name" maxlength="50" required class="box">
                </div>
                <div class="input-field">
                    <p>Email <span>*</span></p>
                    <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
                </div>
            </div>

            <div class="col">
                <div class="input-field">
                    <p>Your Password <span>*</span></p>
                    <input type="password" name="pass" placeholder="Enter your password" maxlength="50" required class="box">
                </div>
                <div class="input-field">
                    <p>Confirm Your Password <span>*</span></p>
                    <input type="password" name="cpass" placeholder="Confirm your password" maxlength="50" required class="box">
                </div>
            </div>
            </div>
            <div class="input-field">
                <p>Your Profile <span>*</span></p>
                <input type="file" name="image" accept="image/*" required class="box">
            </div>
            <input type="submit" name="submit" value="Register now" class="btn">
        </form>
    </div>

    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</body>
</html>
