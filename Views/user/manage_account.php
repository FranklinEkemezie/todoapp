<section id="manage-account" class="settings-tab-content m-3">
  <div class="container-fluid">
    <h2 class="fs-3 fw-bold">Manage Account</h2>
    <p class="text-muted fs-6">Change passwords, email and so on. Manage your accounts here!</p>

    <hr>
  </div>

  <!-- Manage Passwords -->
  <div class="container-fluid manage-passwords my-2">
    <h2 class="fs-4 pe-1 my-3"><i class="fa fa-key"></i> Your Password</h2>

    <div class="container-fluid my-2">
      <button class="btn btn-secondary" onclick="showEditPassword(this);">Change Password</button>
    </div>

    <hr>
  </div>

  <!-- Manage Username -->
  <div class="container-fluid manage-username my-2">
    <h2 class="fs-4 pe-1"><i class="fa fa-user"></i> Your Username</h2>

    <div class="container-fluid d-flex align-items-center justify-content-between">
      <label for="profile-detail-username" class="fw-bold d-none" hidden>Username</label>
      <input id="profile-detail-username" name="username" type ="text" value="<?php insertc([$user, 'getUsername']); ?>" class="border-0 bg-transparent fs-5 form-control" data-todoapp-update="username" contenteditable="false" readonly disabled>
      <button class="btn btn-sm float-end edit-profile-btn" onclick="showEditProfile(this);"><i class="fa fa-pen-to-square"></i></button>
    </div>

    <hr>
  </div>

  <!-- Manage Email -->
  <div class="container-fluid manage-email my-2">
    <h2 class="fs-4 pe-1"><i class="fa fa-envelope"></i> Your Email</h2>

    <div class="container-fluid d-flex align-items-center justify-content-between">
      <label for="profile-detail-email" class="fw-bold d-none" hidden>Email</label>
      <input id="profile-detail-email" name="email" type ="text" value="<?php insertc([$user, 'getEmail']); ?>" class="border-0 bg-transparent fs-5 form-control" data-todoapp-update="email" contenteditable="false" readonly disabled>
      <button class="btn btn-sm float-end edit-profile-btn" onclick="showEditProfile(this);"><i class="fa fa-pen-to-square"></i></button>
    </div>

    <hr>
  </div>

  <!--  -->
  <div class="container-fluid bg-light my-2 py-2 d-flex align-items-center justify-content-between border position-sticky bottom-0">
    <!-- Activate account -->
    <button class="btn btn-secondary">Deactivate Account</button>

    <!-- Delete account -->
    <button class="btn btn-danger">Delete Account</button>
  </div>


</section>