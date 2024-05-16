<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Settings | ToDoApp</title>

  <link rel="shortcut icon" href="./../../assets/imgs/icons8-to-do-96(-xxxhdpi).png" type="image/x-icon">

  <link rel="stylesheet" href="./../../assets/css/header.css">
  <link rel="stylesheet" href="./../../assets/css/create_task_modal.css">
  <link rel="stylesheet" href="./../../assets/css/settings.css">

  <!-- Bootstrap JavaScript -->
  <script src="./../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js" defer></script>

  <!-- Custom script -->
  <script src="./../../assets/js/functions.js" defer></script>
  <script src="./../../assets/js/utils.js" defer></script>

  <script src="./../../assets/js/settings.js" defer></script>

  <!-- Initiate Bootstrap components -->
  <script src="./../../assets/js/bs_init.js" defer></script>
</head>
<body>
  <!-- Header -->
  <?php includePageHeader("Settings", [
    "user" => $user,
    "no_of_tasks_in_progress" => $no_of_tasks_in_progress
  ]) ?>

  <!-- Main -->
  <main>
    <!-- --------------------------------
    SIDE-BAR CONTENT
    -------------------------------- -->
    <div id="sidebar-content" class="border-0 border-end pt-3">
      <!-- Greeting -->
      <div class="container-fluid px-3 my-2">
        <span class="fs-5 text-muted"><?php insertc("greet"); ?>,</span> <br>
        <span data-todoapp-update="username" class="fs-4 fw-bold"><?php insertc([$user, 'getUsername']); ?></span>
      </div>

      <div class="text-center px-3 my-2 my-3">
        <a href="dashboard" class="btn btn-sm rounded-pill px-3 shadow-sm btn-primary d-inline-block mx-auto"><i class="fa fa-arrow-left me-1"></i> Go to Dashboard</a>
      </div>

      <!-- Search Settings -->
      <div class="container-fluid px-3 my-2">
        <form action="javascript:void()">
          <div class="input-group input-group">
            <label for="settings-search-input" class="input-group-text bg-transparent"><i class="fa fa-search "></i></label>
            <input type="search" name="settings-search-input" id="settings-search-input" class="form-control" placeholder="Search settings"/>
          </div>
        </form>
      </div>

      <!-- List items -->
      <div class="settings-options container-fluid px-0 my-4">
        <div class="list-group list-group-flush">
          <a href="?tab=profile" class="list-group-item list-group-item-action"><i class="fa fa-user me-2"></i> Profile</a>
          <a href="?tab=manage_account" class="list-group-item list-group-item-action"><i class="fa fa-gear me-2"></i> Manage Account</a>
          <a href="?tab=privacy" class="list-group-item list-group-item-action"><i class="fa fa-user-shield me-2"></i> Privacy and Security</a>
          <a href="?tab=teams" class="list-group-item list-group-item-action"><i class="fa fa-users me-2"></i> Teams</a>
          <a href="" class="list-group-item list-group-item-action"><i class="fa fa-bell me-2"></i> Notifications</a>
          <a href="?tab=sync" class="list-group-item list-group-item-action"><i class="fa fa-cloud me-2"></i> Sync and Backup</a>
          <a href="" class="list-group-item list-group-item-action"><i class="fa fa-wheelchair me-2"></i> Accessiblity</a>
          <a href="" class="list-group-item list-group-item-action"><i class="fa fa-circle-question me-2"></i> Help and Support</a>
          <a href="" class="list-group-item list-group-item-action"><i class="fa fa-credit-card me-2"></i> Subscription and Billing</a>
          <a href="" class="list-group-item list-group-item-action"><i class="fa fa-gears me-2"></i> Advanced Settings</a>
          <a href="?tab=about_us" class="list-group-item list-group-item-action"><i class="fa fa-list me-2"></i> About ToDoApp</a>
        </div>
      </div>
    </div>

    <!-- -------------------------
      MAIN CONTENT
    ----------------------------->
    <div id="main-content">
      <!-- Create Task Modal -->
      <?php require_once __DIR__ . "/../../src/includes/create_task_modal.php" ?>

      <!-- Setting Tab content -->
      <div id="settings-tab-content-container" class="position-relative">
        <!-- ---------------------------------------
          /********************************************
            * SETTING TAB CONTENT GOES HERE
            ****************************************** */
        ------------------------------------------- -->
      </div>
    </div>
  </main>
</body>
</html>