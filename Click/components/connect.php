<?php
$host = "localhost";
$db = "click_n_cart";
$user = "root";
$pass = "";



try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}


function unique_id() {
    $chars = '0123456789';
    $charLength = strlen($chars);
    $randomString = '';
    mt_srand((int)(microtime(true) * 1000000));
    for ($i = 0; $i < 20; $i++) {
        $randomString .= $chars[mt_rand(0, $charLength - 1)];
    }
    return $randomString;
}
?>
