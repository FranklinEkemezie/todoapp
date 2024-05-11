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
  <script src="../assets/js/signup.js" defer></script>
</head>
<body>
  <main>
    <section id="heading">
      <div class="title">
        <h1>Create an <br> Account</h1>
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
          <!-- Username -->
          <div class="form-field">
            <span class="icon"><i class="fa fa-user"></i></span>
            <input type="username" name="username" id="username" value="<?php echo $username; ?>" placeholder="">
            <label for="username">Username</label>
            <span class="info"><?php echo $username_error_msg; ?></span>
          </div>

          <!-- Email Address -->
            <div class="form-field">
            <span class="icon"><i class="fa fa-envelope"></i></span>
            <input type="email" name="email" id="email" value="<?php echo $email; ?>" placeholder="">
            <label for="email">Email Address</label>
            <span class="info"><?php echo $email_error_msg; ?></span>
          </div>

        </div>

        <!-- Go to the next frame -->
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