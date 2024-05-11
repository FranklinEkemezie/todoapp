<?php
/**-------------------------------------
 * HEADER
 * -------------------------------------
 */
?>
<header class="border-bottom">
  <div class="container-fluid py-2 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-stretch">
      <?php if($title === "My Dashboard"): // Show Hamburger menu icon if at dashboard; else show Back arrow ?>
      <button class="toggle-sidebar-btn btn py-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar-content"><i class="fs-5 fa fa-bars"></i></button>
      <?php else: ?>
      <button onclick="window.history.back()" class="btn" ><i class="fa fa-arrow-left fs-5"></i></button>
      <?php endif; ?>

      <h1 class="fs-4 fw-bold d-inline-block ms-2 py-1">ToDoApp - <?php insert($title); ?></h1>
    </div>

    <div class="d-flex align-items-center">
      <!-- Hide in small screen / Show in larger screen -->
      <div class="d-none d-md-block">
        <button class="btn btn-success me-1" data-bs-toggle="modal" data-bs-target="#add-new-task-static-backdrop"><i class="fa fa-plus me-1"></i> Add New Task</button>
        <a href="/logout" class="btn btn-danger">Logout <i class="fa fa-sign-out ms-1"></i></a>

        <button title="Notifications" class="btn position-relative border rounded-circle mx-1"
        data-bs-container="body" title="Notification" data-bs-toggle="popover" data-bs-placement="top"
        data-bs-content="<?php insert($no_of_tasks_in_progress) ?> task<?php insert($no_of_tasks_in_progress > 1 ? "s" : ""); ?> pending">
          <i class="fa fa-bell"></i>

          <!-- Show if there are up to one notification -->
          <?php if ($no_of_tasks_in_progress > 0): ?>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?php insert($no_of_tasks_in_progress); ?></span>
          <span class="visually-hidden">tasks in progress</span>
          <?php endif; ?>
        </button>

      </div>

      <!-- Show in small screen  -->
      <div>
        <div class="dropdown" id="profile-dropdown">
          <button class="btn dropdown-toggle text-muted" type="button" id="profileDropdownBtn" data-bs-toggle="dropdown" data-bs-auto-close="outside">
            <i class="fa fa-user user-icon"></i>
          </button>

          <ul class="dropdown-menu dropdown-menu-md-end dropdown-menu-end shadow border-0">
            <div class="px-3 py-1 row align-items-center">
              <div class="col-4">
                <img src="/user/profile/photo" alt="User Profile" width="40px" height="40px" class="rounded-circle" data-todoapp-update="profile-img" />
                <!-- <i class="fa fa-user fa-2x"></i> -->
              </div>
              <div class="col-8 text-end lh-0">
                Hi, <br>
                <b data-todoapp-update="username"><?php insertc([$user, 'getUsername']); ?></b>
                <span data-todoapp-update="email" class="email-address d-inline-block w-100 overflow-ellipsis"><i class="fa fa-envelope me-1"></i><?php insertc([$user, 'getEmail']) ; ?> </span>
              </div>
            </div>


            <hr class="dropdown-divider">

            <!-- Task Related options -->
            <li><a href="#add-new-task-static-backdrop" class="dropdown-item" data-bs-toggle="modal"><i class="fa fa-plus"></i> Add Task</a></li>

            <li><hr class="dropdown-divider"></li>
            <!-- User Related Options -->
            <li><a href="" class="dropdown-item"><i class="fa fa-user-pen fa-pull"></i> Update Profile</a></li>
            <li><a href="" class="dropdown-item">
              <i class="fa fa-bell"></i>
              Notifications
              
              <?php if($no_of_tasks_in_progress): // Show if there is more than one pending task ?>
              <sup class="badge rounded-pill bg-danger small"><?php insert($no_of_tasks_in_progress); ?></sup>
              <?php endif; ?>
            </a></li>
            <li><a href="" class="dropdown-item"><i class="fa fa-gear"></i> Settings</a> </li>
            <!-- Dropdown divider -->
            <li><hr class="dropdown-divider"></li>
            <li><a href="/logout" class="dropdown-item"><i class="fa fa-sign-out"></i> Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</header>


