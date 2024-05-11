<section id="profile" class="settings-tab-content m-3">
  <h2 class="fs-3 fw-bold">User Profile</h2>
  
  <!-- Profile banner -->
  <div class="profile-banner rounded-1 text-white d-flex align-items-center shadow-sm p-3">
    <div class="profile-pix-container me-1">
      <img src="/user/profile/photo" alt="Profile Pix" width="64px" height="64px" class="rounded-circle" data-todoapp-update="profile-img">
    </div>
    <div class="user-details ms-1">
      <span class="fs-5 fw-bold" data-todoapp-update="username"><?php insertc([$user, 'getUsername']); ?></span> <br>
      <span class="small text-light"><?php insertc([$user, 'getEmail']); ?></span>
    </div>
  </div>

  <hr>

  <!-- User Info -->
  <div class="user-info my-3 mb-4">
    <h2 class="fs-4">User Info</h2>

    <ul class="list-group list-group-flush">
      <!-- Firstname -->
      <li class="list-group-item">
        <label for="profile-detail-firstname" class="fw-bold">Firstname</label>
        <input id="profile-detail-firstname" name="firstname" type="text" value="<?php insertc([$user, 'getFirstname']) ?>" class="border-0 bg-transparent" data-todoapp-update="firstname" contenteditable="false" readonly disabled>
        <button class="btn btn-sm float-end edit-profile-btn" onclick="showEditProfile(this);"><i class="fa fa-pen-to-square"></i></button>
      </li>

      <!-- Lastname -->
      <li class="list-group-item">
        <label for="profile-detail-lastname" class="fw-bold">Lastname</label>
        <input id="profile-detail-lastname" name="lastname" type ="text" value="<?php insertc([$user, 'getLastname']); ?>" class="border-0 bg-transparent" data-todoapp-update="lastname" contenteditable="false" readonly disabled>
        <button class="btn btn-sm float-end edit-profile-btn" onclick="showEditProfile(this);"><i class="fa fa-pen-to-square"></i></button>
      </li>

      <!-- Date of birth -->
      <li class="list-group-item">
        <label for="profile-detail-dob" class="fw-bold">Date of Birth</label>
        <input id="profile-detail-dob" name="dob" type="date" value="<?php insertdate($user_details['dob']); ?>" class="border-0 bg-transparent" data-todoapp-update="dob" contenteditable="false" readonly disabled/>
        <button class="btn btn-sm float-end edit-profile-btn" onclick="showEditProfile(this);"><i class="fa fa-pen-to-square"></i></button>
      </li>

      <!-- Account created -->
      <li class="list-group-item">
        <label class="fw-bold">Joined on</label>
        <input type="date" name="" id="" value="<?php insertdate($user_details['reg-date']); ?>" class="border-0 bg-transparent" contenteditable="false" readonly disabled/>
      </li>

    </ul>

    <hr>
  </div>

  <!-- Profile Picture -->
  <div class="user-pfp my-3">
    <h2 class="fs-4">Edit Your Profile Picture</h2>

    <div class="row">
      <div class="col-md-5">
        <!-- Choose from gallery -->
        <h6 class="fs-5 my-2">Choose from your photos</h6>

        <div class="pfp-container my-2">
          <picture onclick="displayChangeProfilePicture('change-pfp-form');" class="d-inline my-2">
            <img src="./../../assets/vendor/fontawesome/svgs/solid/user.svg" alt="User Profile Picture" width="120px" height="120px" />
            
            <br>
            <button type="button" class="btn btn-sm btn-primary my-2">Update Profile Picture</button>
          </picture>

          <form action="javascript:void(null)" enctype="multipart/form-data" class="d-none" id="change-pfp-form" name="change-pfp-form" onsubmit="changeProfilePicture(this);">
            <input type="file" name="pfp-photo" id="pfp-photo" accept="image/*"> <br>
            <button type="submit" class="btn btn-sm btn-primary my-2">Update Profile Picture</button>
          </form>
        </div>
      </div>

      <!-- Choose from avatar -->
      <div class="col-md-7">
        <h6 class="fs-5 my-2">Choose an avatar</h6>

        <div class="my-2">
          <div class="avatar-display-grid">
            <!-- Avatar 1 -->
            <div class="grid-1">
              <button class="btn d-block w-100" onclick="changeProfilePicture(null, 'avatar_1');">
                <img src="./../../assets/imgs/avatars/pfp_basketball.png" alt="Avatar 1" width="64px" height="64px" class="img-fluid rounded-circle" />
              </button>
            </div>
            <!-- Avatar 2 -->
            <div class="grid-2">
              <button class="btn d-block w-100" onclick="changeProfilePicture(null, 'avatar_2');">
                <img src="./../../assets/imgs/avatars/pfp_bicycle.png" alt="Avatar 2" width="64px" height="64px" class="img-fluid rounded-circle" />
              </button>
            </div>
            <!-- Avatar 3 -->
            <div class="grid-3">
              <button class="btn d-block w-100" onclick="changeProfilePicture(null, 'avatar_3');">
                <img src="./../../assets/imgs/avatars/pfp_bird.png" alt="Avatar 3" width="64px" height="64px" class="img-fluid rounded-circle" />
              </button>
            </div>
            <!-- Avatar 4 -->
            <div class="grid-1">
              <button class="btn d-block w-100" onclick="changeProfilePicture(null, 'avatar_4');">
                <img src="./../../assets/imgs/avatars/pfp_icewater.png" alt="Avatar 4" width="64px" height="64px" class="img-fluid rounded-circle" />
              </button>
            </div>
            <!-- Avatar 5 -->
            <div class="grid-2">
              <button class="btn d-block w-100" onclick="changeProfilePicture(null, 'avatar_5');">
                <img src="./../../assets/imgs/avatars/pfp_panda.png" alt="Avatar 5" width="64px" height="64px" class="img-fluid rounded-circle" />
              </button>
            </div>
            <!-- Avatar 6 -->
            <div class="grid-3">
              <button class="btn d-block w-100" onclick="changeProfilePicture(null, 'avatar_6');">
                <img src="./../../assets/imgs/avatars/pfp_sunglasses.png" alt="Avatar 6" width="64px" height="64px" class="img-fluid rounded-circle" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>
