<?php
    include '../components/connect.php';

    if (isset($_COOKIE['seller_id'])) {
        $seller_id = $_COOKIE['seller_id'];
    } else {
        header('location: Login.php');
        exit;
    }

    if (isset($_POST['submit'])) {
        // Fetch the current seller details
        $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE seller_id = ? LIMIT 1");
        $select_seller->execute([$seller_id]);
        $fetch_seller = $select_seller->fetch(PDO::FETCH_ASSOC);

        $prev_pass = $fetch_seller['password'];
        $prev_image = $fetch_seller['image'];

        // Sanitize input fields
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);

        // Update name
        if (!empty($name)) {
            $update_name = $conn->prepare("UPDATE `sellers` SET name = ? WHERE seller_id = ?");
            $update_name->execute([$name, $seller_id]);
            $success_msg[] = 'Username updated successfully';
        }

        // Update email
        if (!empty($email)) {
            $select_email = $conn->prepare("SELECT * FROM `sellers` WHERE email = ? AND seller_id != ?");
            $select_email->execute([$email, $seller_id]);

            if ($select_email->rowCount() > 0) {
                $warning_msg[] = 'Email already exists';
            } else {
                $update_email = $conn->prepare("UPDATE `sellers` SET email = ? WHERE seller_id = ?");
                $update_email->execute([$email, $seller_id]);
                $success_msg[] = 'Email updated successfully';
            }
        }

        // Handle image update
        if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
            $image = $_FILES['image']['name'];
            $image = filter_var($image, FILTER_SANITIZE_STRING);
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            $rename = uniqid() . '.' . $ext;
            $image_size = $_FILES['image']['size'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_folder = '../uploaded_files/' . $rename;

            if ($image_size > 2000000) {
                $warning_msg[] = 'Image size is too large';
            } else {
                $update_image = $conn->prepare("UPDATE `sellers` SET image = ? WHERE seller_id = ?");
                $update_image->execute([$rename, $seller_id]);
                move_uploaded_file($image_tmp_name, $image_folder);

                // Delete the old image if a new one is uploaded
                if ($prev_image != '' && $prev_image != $rename) {
                    unlink('../uploaded_files/' . $prev_image);
                }

                $success_msg[] = 'Image updated successfully';
            }
        }

        // Handle password update
        if (!empty($_POST['old_pass'])) {
            $old_pass = sha1($_POST['old_pass']);
            $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);

            $new_pass = sha1($_POST['new_pass']);
            $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);

            $cpass = sha1($_POST['cpass']);
            $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

            // Check if old password matches
            if ($old_pass != $prev_pass) {
                $warning_msg[] = 'Old password does not match';
            } elseif ($new_pass != $cpass) {
                $warning_msg[] = 'Passwords do not match';
            } else {
                if (!empty($new_pass)) {
                    $update_pass = $conn->prepare("UPDATE sellers SET password = ? WHERE seller_id = ?");
                    $update_pass->execute([$cpass, $seller_id]);
                    $success_msg[] = 'Password updated successfully';
                } else {
                    $warning_msg[] = 'Please enter a new password';
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Click n' cart - update profile page</title>
    <link rel="stylesheet" href="../admin/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" />
</head>
<body>
    <div class="main-container">
        <?php include '../admin/Admin_header.php';?>
        <section class="form-container">
            <div class="heading">
                <h1>update profile details</h1>
                <img src="../image/seperator.png">
            </div>
            <form action="" method="post" enctype="multipart/form-data" class="register">
                <div class="img-box">
                    <img src="..//uploaded_files/<?= $fetch_profile['image'];?>">
                </div>
                <div class="flex">
                    <div class="col">
                        <div class="input-field">
                            <p>update profile </p>
                            <input type="file" name="image" accept="image/*" class="box">
                        </div>
                        <div class="input-field">
                            <p>your name</p>
                            <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p>your email </p>
                            <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p>old password </p>
                            <input type="password" name="old_pass" placeholder="enter your old password" class="box">
                        </div>
                        <div class="input-field">
                            <p>new password </p>
                            <input type="password" name="new_pass" placeholder="enter your new password" class="box">
                        </div>
                        <div class="input-field">
                            <p>confirm password </p>
                            <input type="password" name="cpass" placeholder="confirm your password" class="box">
                        </div>
                    </div>
                </div>
                <input type="submit" name="submit" value="update profile" class="btn">
            </form>
        </section>
    </div>


    <script src="../admin/admin_scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <?php include '../components/alert.php';?>
</body>
</html>
