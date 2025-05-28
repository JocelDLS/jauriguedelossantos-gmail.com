<?php
    include '../components/connect.php';

    if (isset($_COOKIE['seller_id'])) {
        $seller_id = $_COOKIE['seller_id'];
    } else {
        $seller_id = '';
        header('location: Login.php');
    }

    if(isset($_POST['delete'])){
        $p_id = $_POST['products_id']; // ✅ Now matches the input name
        $p_id = filter_var($p_id, FILTER_SANITIZE_STRING);
    
        $delete_project = $conn->prepare("DELETE FROM `products` WHERE products_id = ? AND seller_id = ?");
        $delete_project->execute([$p_id, $seller_id]);
    
        $success_msg[] = 'Product deleted successfully';
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
        <section class="show-post">
            <div class="heading">
                <h1>your products</h1>
                <img src="../image/seperator.png">
            </div>
            <div class="box-container">
                <?php
                    $select_products = $conn-> prepare("SELECT * FROM `products` WHERE seller_id = ?");
                    $select_products->execute([$seller_id]);
                    if($select_products->rowCount() > 0){
                        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {

                ?>
                <form action="" method="post" class="box">
                    <input type="hidden" name="products_id" value="<?= $fetch_products['products_id']; ?>"> 
                    <?php if($fetch_products['image'] != '') { ?>
                        <img src="../uploaded_files/<?=$fetch_products['image'];?>" class="image">
                    <?php }?>
                    <div class="status" style="color: <?php if($fetch_products['status'] == 'active') { echo 'limegreen'; } else { echo 'coral'; } ?>">
                        <?= htmlspecialchars($fetch_products['status']); ?>
                    </div> 
                    <div class="price">₱<?= $fetch_products['price'];?>/-</div>
                    <div class="content"> 
                        <div class="title"><?=$fetch_products['name'];?></div>
                        <div class="flex-btn">
                            <a href="edit_product.php?id=<?=$fetch_products['products_id'];?>" class="btn">edit</a>
                            <button type="submit" name="delete" class="btn" onclick="return confirm('Delete this product?');">delete</button>
                            <a href="read_product.php?post_id=<?=$fetch_products['products_id'];?>" class="btn">read</a>
                        </div>
                    </div>
                </form>
                <?php
                        }
                    }else{
                        echo '
                            <div class="empty">
                                <p>
                                    No products added yet!<br> 
                                    <a href="add_products.php" class="btn" style="margin-top: 1.5rem; line-hight:2;">Add Products</a>
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
