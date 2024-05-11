<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | ToDoApp</title>

  <link rel="shortcut icon" href="./../assets/imgs/icons8-to-do-96(-xxxhdpi).png" type="image/x-icon">

  <link rel="stylesheet" href="../assets/css/login.css">

  <script src="../assets/js/functions.js" defer></script>
  <script src="../assets/js/forms.js" defer></script>
  <script src="../assets/js/login.js" defer></script>

</head>
<body>
  <main>
    <section id="heading">
      <div class="title">
        <h1>Login</h1>
        <p>Welcome Back</p>
      </div>

      <div>
        <i class="fa fa-user-lock fa-4x"></i>
      </div>
    </section>

    <section id="form-section">
      <!-- Error message -->
      <div class="error-msg fa-fade <?php echo !isset($form_error_msg) ? 'hidden' : '' ?>">
        <p class="msg">
          <!-- Error message goes here -->
          <?php insert($form_error_msg); ?>
        </p>
        <span class="close-btn"><i class="fa fa-close fa-pull-right"></i></span>
      </div>
      <form id="login-form" action="/login" method="POST">
        <div class="input-fields">
          <!-- Email Address -->
          <div class="form-field">
            <span class="icon"><i class="fa fa-user-alt"></i></span>
            <input type="email" name="email" id="email" value="<?php insert($email); ?>" placeholder="">
            <label for="email">Email Address</label>
            <span class="info"></span>
          </div>

          <!-- Password -->
          <div class="form-field">
            <span class="icon"><i class="fa fa-lock"></i></span>
            <input type="password" name="password" id="password" placeholder="" autocomplete="on">
            <label for="password">Password</label>
            <span class="info"></span>
          </div>
        </div>

        <!-- Forgot password -->
        <div class="extras">
          <p>
            <input type="checkbox" name="remember-me" id="remember-me">
            <label for="remember-me">Remember me</label>
          </p>
          <p class="forgot-password"><a href="" class="link">Forgot password?</a></p>
        </div>


        <!-- Submit -->
        <div class="submit-field">
          <p>Don't have an account? <a href="/signup" class="link">Sign up</a></p>
          <button type="submit">Login &nbsp; <i class="fa fa-sign-in"></i></button>
        </div>
      </form>
    </section>
  </main>
</body>
</html>