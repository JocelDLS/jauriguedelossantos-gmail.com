<?php
    include '../components/connect.php';

    if (isset($_COOKIE['seller_id'])) {
        $seller_id = $_COOKIE['seller_id'];
    } else {
        $seller_id = '';
        header('location: Login.php');
        exit;
    }

    if (isset($_POST['publish']) || isset($_POST['draft'])) {
        $id = unique_id();

        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $price = $_POST['price'];
        $price = filter_var($price, FILTER_SANITIZE_STRING);

        $description = $_POST['description'];
        $description = filter_var($description, FILTER_SANITIZE_STRING);

        $stock = $_POST['stocks'];
        $stock = filter_var($stock, FILTER_SANITIZE_STRING);

        $status = isset($_POST['publish']) ? 'active' : 'deactive';

        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_files/'.$image;

        $category = $_POST['category'];
        $category = filter_var($category, FILTER_SANITIZE_STRING);

        $select_image = $conn->prepare("SELECT * FROM `products` WHERE image = ? AND seller_id = ?");
        $select_image->execute([$image, $seller_id]);

        if (!empty($image)) {
            if ($select_image->rowCount() > 0) {
                $warning_msg[] = 'Image name already exists. Please rename your image.';
            } elseif ($image_size > 2000000) {
                $warning_msg[] = 'Image size is too large.';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
            }
        } else {
            $image = '';
        }

        if ($select_image->rowCount() > 0 && $image != '') {
            $warning_msg[] = 'Please rename your image.';
        } else {
            $insert_product = $conn->prepare("INSERT INTO `products` (products_id, seller_id, name, price, image, category, stock, product_detail, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_product->execute([$id, $seller_id, $name, $price, $image, $category, $stock, $description, $status]);
            $success_msg[] = 'Product ' . ($status === 'active' ? 'added' : 'saved as draft') . ' successfully.';
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
    <div class="main-container">
        <?php include '../admin/Admin_header.php';?>
        <section class="post-editor">
            <div class="heading">
                <h1>add products</h1>
                <img src="../image/seperator.png">
            </div>
            <div class="form-container">
                <form action="" method="post" enctype="multipart/form-data" class="register">
                    <div class="input-field">
                        <p>product name <span>*</span></p>
                        <input type="text" name="name" maxlength="100" placeholder="add product name" required class="box">
                    </div>
                    <div class="input-field">
                        <p>product category <span>*</span></p>
                        <input type="text" name="category" maxlength="100" placeholder="add product category" required class="box">
                    </div>
                    <div class="input-field">
                        <p>product price <span>*</span></p>
                        <input type="number" name="price" maxlength="100" placeholder="add product price" required class="box">
                    </div>
                    <div class="input-field">
                        <p>product detail <span>*</span></p>
                        <textarea name="description" required maxlength="1000" placeholder="add product detail" class="box"></textarea>
                    </div>
                    <div class="input-field">
                        <p>product stock <span>*</span></p>
                        <input type="number" name="stocks" maxlength="10" min="0" max="9999999999" placeholder="add product stocks" required class="box">
                    </div>
                    <div class="input-field">
                        <p>product image <span>*</span></p>
                        <input type="file" name="image" accept="image/*" required class="box">
                    </div>
                    <div class="flex-btn">
                        <input type="submit" name="publish" value="add product" class="btn">
                        <input type="submit" name="draft" value="save as draft" class="btn">
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script src="../admin/admin_scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <?php include '../components/alert.php';?>
</body>
</html>
