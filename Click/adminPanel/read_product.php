<?php
    include '../components/connect.php';

    if (isset($_COOKIE['seller_id'])) {
        $seller_id = $_COOKIE['seller_id'];
    } else {
        $seller_id = '';
        header('location: Login.php');
    }

    $get_id = $_GET['post_id'];

    if(isset($_POST['delete'])){
        $p_id = $_POST['products_id'];
        $p_id = filter_var($p_id, FILTER_SANITIZE_STRING);
    
        $delete_image = $conn->prepare("DELETE FROM `products` WHERE products_id = ? AND seller_id = ?");
        $delete_image->execute([$p_id, $seller_id]);
        
        $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
        if($fetch_delete_image[''] != ''){
            unlink('../uploaded_files/'.$fetch_delete_image['image']);
        }

        $delete_project = $conn->prepare("DELETE FROM `products` WHERE products_id = ? AND seller_id = ?");
        $delete_project->execute([$p_id, $seller_id]);
        header("location: view_products.php");

        
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
        <section class="read-post">
            <div class="heading">
                <h1>products details</h1>
                <img src="../image/seperator.png">
            </div>
            <div class="box-container">
            <?php
                    $select_product = $conn->prepare("SELECT * FROM `products` WHERE products_id = ? AND seller_id = ?");
                    $select_product->execute([$get_id, $seller_id]);
                    if($select_product->rowCount() > 0){
                        while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
                ?>
                <form action="" method="post" class="box">
                    <input type="hidden" name="products_id" value="<?= $fetch_product['products_id']; ?>"> 
                    <div class="status" style="color: <?= ($fetch_product['status'] == 'active') ? 'limegreen' : 'coral'; ?>">
                        <?= $fetch_product['status']; ?>
                    </div>
                    <?php if($fetch_product['image'] != ''){?>
                        <img src="../uploaded_files/<?= $fetch_product['image']?>" class="image">
                    <?php } ?>
                    <div class="price">₱<?= $fetch_product['price'];?>/-</div>
                    <div class="title"><?= $fetch_product['name'];?></div>
                    <div class="content"><?= $fetch_product['product_detail'];?></div>
                    <div class="flex-btn">
                        <a href="edit_product.php?id=<?= $fetch_product['products_id'];?>" class="btn">edit</a>
                        <button type="submit" name="delete" class="btn" onclick="return confirm('Delete this product?');">delete</button>
                        <a href="view_products.php?post_id=<?=$fetch_product['products_id'];?>" class="btn">go back</a>
                    </div>
                </form>
                <?php
                        }
                    } else {
                        echo '
                        <div class="empty">
                            <p>
                                No products added yet!<br>
                                <a href="add_products.php" class="btn" style="margin-top: 1.5rem;">Add Products</a>
                            </p>
                        </div>
                        ';
                    }
                ?>

        </section>
    </div>
    

    <script src="../admin/admin_scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <?php include '../components/alert.php';?>
</body>
</html>
