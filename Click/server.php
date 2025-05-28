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

if(isset($_POST['register'])){
    $user_id = unique_id();
    $username = $_POST['names'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $checkEmail->execute(['email' => $email]);
    
    if ($checkEmail->rowCount() > 0){
        echo "Email Address Already Exists!";
    } else {
        $insertQuery = $conn->prepare("INSERT INTO users(user_id, name, phone_number, email, password) VALUES (:user_id, :name, :phone, :email, :password)");
        $insertQuery->execute([
            'user_id' => $user_id,
            'name' => $username,
            'phone' => $phone,
            'email' => $email,
            'password' => $password
        ]);
        
        if ($insertQuery) {
            header("Location: Login.php");
        } else {
            echo "Error: Unable to register user.";
        }
    }
}

$passwordError = "";
$emailError = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = md5(trim($_POST['password'])); // md5 is not secure, use only if needed for legacy

    $checkUser = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $checkUser->execute(['email' => $email]);

    if ($checkUser->rowCount() > 0) {
        $row = $checkUser->fetch(PDO::FETCH_ASSOC);
        if ($row['password'] === $password) {
            setcookie('user_id', $row['user_id'], time() + 60*60*24*30, '/');
            header("Location: home.php");
            exit(); // Prevent further execution
        } else {
            $passwordError = "Wrong password!";
        }
    } else {
        $emailError = "Email not found!";
    }
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
