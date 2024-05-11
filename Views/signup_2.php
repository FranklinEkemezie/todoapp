<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup | ToDoApp</title>

  <link rel="shortcut icon" href="./../assets/imgs/icons8-to-do-96(-xxxhdpi).png" type="image/x-icon">

  <link rel="stylesheet" href="../assets/css/signup.css">

  <script src="../assets/js/functions.js" defer></script>
  <script src="../assets/js/forms.js" defer></script>
  <script src="../assets/js/signup_2.js" defer></script>
</head>
<body>
  <main>
    <section id="heading">
      <div class="title">
        <h1>Create an <br> Account</h1>
        <p>
          <span class="username"><?php echo $username; ?></span>,
          Enter your password
        </p>
      </div>

      <div>
        <i class="fa fa-4x fa-user-plus"></i>
      </div>
    </section>

    <section id="form-section">
      <!-- Error message -->
      <div class="error-msg fa-fade <?php echo !isset($form_error_msg) ? 'hidden' : '' ?>">
        <p class="msg">
          <!-- Error message goes here -->
          <?php echo $form_error_msg; ?>
        </p>
        <span class="close-btn"><i class="fa fa-close fa-pull-right"></i></span>
      </div>

      <form id="signup-form" action="/signup" method="POST">
        <div class="input-fields">
          <!-- Password -->
          <div class="form-field">
            <span class="icon"><i class="fa fa-lock"></i></span>
            <input type="password" name="password" id="password" value="<?php echo $password; ?>" placeholder="" autocomplete="on">
            <label for="password">Password</label>
            <span class="info"><?php echo $password_error_msg; ?></span>
          </div>

          <!-- Confirm password -->
          <div class="form-field">
            <span class="icon"><i class="fa fa-lock-open"></i></span>
            <input type="password" name="confirm-password" id="confirm-password" value="<?php echo $confirm_password; ?>" placeholder="">
            <label for="confirm-password">Confirm password</label>
            <span class="info"><?php  echo $confirm_password_error_msg; ?></span>
          </div>

          <!--  -->
          <div>
            <button id="toggle-password"><i class="fa fa-eye-slash toggle-password-icon"></i></button>
            <label for="toggle-password">Show Password</label>
          </div>
        </div>

        <div class="submit-field">
          <p>Already have an account? <a href="/login" class="link">Login</a></p>
          <div class="submit-btn-container">
            <button type="submit">Next &nbsp; <i class="fa fa-arrow-right"></i></button>
          </div>
        </div>
      </form>
    </section>
  </main>
</body>
</html>