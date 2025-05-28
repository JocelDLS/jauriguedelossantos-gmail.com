<?php
    include '../components/connect.php';

    if (isset($_COOKIE['seller_id'])) {
        $seller_id = $_COOKIE['seller_id'];
    } else {
        $seller_id = '';
        header('location: Login.php');
    }

    if(isset($_POST['delete_msg'])){

        $delete_id = $_POST['delete_id'];
        $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

        $verify_delete = $conn->prepare("SELECT * FROM `message` WHERE msg_id = ?");
        $verify_delete->execute([$delete_id]);

        if($verify_delete->rowCount() > 0){
            $delete_msg = $conn->prepare("DELETE FROM `message` WHERE msg_id = ?");
            $delete_msg->execute([$delete_id]);

            $success_msg[] = 'message deleted successfully';
        }else{
            $warning_msg[] = 'meessage already deleted';
        }
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
        <section class="user-container">
            <div class="heading">
                <h1>registerd users</h1>
                <img src="../image/seperator.png">
            </div>
            <div class="box-container">
                <?php
                    $select_user = $conn->prepare("SELECT * FROM `users`");
                    $select_user->execute();
                    if($select_user->rowCount() > 0){
                        while ($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)){
                            $user_id = $fetch_user['user_id'];
                ?>
                <div class="box">
                    <img src="../uploaded_files/<?= $fetch_user['profile_picture'];?>">
                    <p>user id : <span><?= $user_id;?></span></p>
                    <p>user name : <span><?= $fetch_user['name'];?></span></p>
                    <p>user email : <span><?= $fetch_user['email'];?></span></p>
                </div>
                <?php
                        }
                    }else{
                        echo '
                            <div class="empty">
                                <p>
                                    No user account yet!<br> 
                                </p>
                            </div>
                        ';
                    }
                ?>
            </div>
        </section>
    </div>


    <script src="../admin/admin_scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <?php include '../components/alert.php';?>
</body>
</html>
