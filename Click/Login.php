<?php
    session_start();
    include("server.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login/Register</title>
  <link rel="stylesheet" href="assets/Login.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" />
</head>
<body>
  <svg class="login__blob" viewBox="0 0 700 840" xmlns="http://www.w3.org/2000/svg">
      <image class="login__img" href="image/cart.jpg"/>
    </g>
</svg>      
  <div class="login container grid" id="loginAccessRegister">
    <!-- LOGIN -->
    <div class="login__access">
      <h1 class="login__title">Log in to your account.</h1>
      <div class="login__area">
        <form action="Login.php" method="post" id="loginForm" class="login__form">
          <div class="login__content grid">
          <div class="login__box">
            <input type="email" name="email" required placeholder=" " 
                  class="login__input <?php if (!empty($emailError)) echo 'error'; ?>" 
                  value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            <label class="login__label">Email</label>
            <i class="ri-mail-fill login__icon"></i>
          </div>
            <?php if (!empty($emailError)): ?>
              <small class="error-text"><?php echo $emailError; ?></small>
            <?php endif; ?>
            <div class="login__box">
              <input type="password" name="password" required placeholder=" " 
              class="login__input <?php if (!empty($passwordError)) echo 'error'; ?>" id="password">
              <label class="login__label">Password</label>
              <i class="ri-eye-off-fill login__icon login__password" id="loginPassword"></i>
            </div>
            <?php if (!empty($passwordError)): ?>
              <small class="error-text"><?php echo $passwordError; ?></small>
            <?php endif; ?>
          </div>

          <a href="#" class="login__forgot">Forgot your password?</a>
          <button type="submit" name="login" class="login__button">Login</button>
        </form>
        <div class="login__social">
          <p class="login__social-title">Or login with</p>

          <div class="login__social-links">
            <a href="#" class="login__social-link">
              <img src="image/icon-google.svg" alt="image" class="login__social-img">
            </a>

            <a href="#" class="login__social-link">
              <img src="image/icon-facebook.svg" alt="image" class="login__social-img">
            </a>

            <a href="#" class="login__social-link">
                <img src="image/icon-apple.svg" alt="image" class="login__social-img">
            </a>
          </div>
      </div>
        <p class="login__switch">Don't have an account? <button id="loginButtonRegister">Create Account</button></p>
      </div>
    </div>

    <!-- REGISTER -->
    <div class="login__register">
      <h1 class="login__title">Create new account.</h1>
      <div class="login__area">
        <form action="Login.php" method="post" id="registerForm" class="login__form">
          <div class="login__content grid">
            <div class="login__group grid">
              <div class="login__box">
                <input type="text" name="names" required placeholder=" " class="login__input">
                <label class="login__label">Username</label>
                <i class="ri-id-card-fill login__icon"></i>
              </div>
              <div class="login__box">
                <input type="text" name="phone" required placeholder=" " class="login__input">
                <label class="login__label">Phone Number</label>
                <i class="ri-phone-fill login__icon"></i>
              </div>
            </div>

            <div class="login__box">
              <input type="email" name="email" required placeholder=" " class="login__input">
              <label class="login__label">Email</label>
              <i class="ri-mail-fill login__icon"></i>
            </div>

            <div class="login__box">
              <input type="password" name="password" required placeholder=" " class="login__input" id="passwordCreate">
              <label class="login__label">Password</label>
              <i class="ri-eye-off-fill login__icon login__password" id="loginPasswordCreate"></i>
            </div>
          </div>
          <button type="submit" name="register" class="login__button">Create account</button>
        </form>

        <p class="login__switch">Already have an account? <button id="loginButtonAccess">Log In</button></p>
      </div>
    </div>
  </div>

  <script src="js/Login.js"></script>
  <?php include 'components/alert.php';?>
</body>
</html>
